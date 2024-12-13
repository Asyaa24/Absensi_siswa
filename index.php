<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(120deg, #a1c4fd, #c2e9fb);
            font-family: 'Inter', sans-serif;
            color: #333;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(45deg, #ff6b6b, #ff4757);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            transition: background 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .logout-btn:hover {
            background: linear-gradient(45deg, #ff4757, #e84118);
        }
        .filter-btn {
            padding: 10px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: transform 0.2s, background-color 0.3s;
            text-decoration: none;
        }
        .filter-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .kelas-x { background-color: #4caf50; color: #fff; }
        .kelas-x:hover { background-color: #388e3c; }
        .kelas-xi { background-color: #2196f3; color: #fff; }
        .kelas-xi:hover { background-color: #1976d2; }
        .kelas-xii { background-color: #ff9800; color: #fff; }
        .kelas-xii:hover { background-color: #f57c00; }
        .table-container {
            overflow-x: auto;
        }
        table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

thead th {
    background-color: #1e90ff; /* Warna biru header */
    color: #ffffff;           /* Teks putih */
    padding: 10px;
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    border-bottom: 2px solid #ddd;
}

tbody td {
    padding: 8px;
    font-size: 14px;
    color: #333;
}

tbody tr:nth-child(odd) {
    background-color: #f9f9f9; /* Warna latar untuk baris ganjil */
}

tbody tr:hover {
    background-color: #f1f1f1; /* Warna latar saat baris di-hover */
}

    </style>
</head>
<body class="min-h-screen flex flex-col items-center">
    <a href="login.php" class="logout-btn">← Logout</a>

    <div class="container bg-white p-6 rounded-lg shadow-md mt-10 max-w-4xl w-full">
        <h1 class="text-2xl font-bold text-center text-gray-700 mb-6">Filter Data Absensi</h1>

        <!-- Filter Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <h2 class="font-semibold text-center text-gray-600 mb-2">Kelas X</h2>
                <div class="space-y-2">
                    <a href="?kelas=X&jurusan=RPL" class="filter-btn kelas-x block text-center">X RPL</a>
                    <a href="?kelas=X&jurusan=BR" class="filter-btn kelas-x block text-center">X BR</a>
                    <a href="?kelas=X&jurusan=MP" class="filter-btn kelas-x block text-center">X MP</a>
                    <a href="?kelas=X&jurusan=AKL" class="filter-btn kelas-x block text-center">X AKL</a>
                </div>
            </div>
            <div>
                <h2 class="font-semibold text-center text-gray-600 mb-2">Kelas XI</h2>
                <div class="space-y-2">
                    <a href="?kelas=XI&jurusan=RPL" class="filter-btn kelas-xi block text-center">XI RPL</a>
                    <a href="?kelas=XI&jurusan=BR" class="filter-btn kelas-xi block text-center">XI BR</a>
                    <a href="?kelas=XI&jurusan=MP" class="filter-btn kelas-xi block text-center">XI MP</a>
                    <a href="?kelas=XI&jurusan=AKL" class="filter-btn kelas-xi block text-center">XI AKL</a>
                </div>
            </div>
            <div>
                <h2 class="font-semibold text-center text-gray-600 mb-2">Kelas XII</h2>
                <div class="space-y-2">
                    <a href="?kelas=XII&jurusan=RPL" class="filter-btn kelas-xii block text-center">XII RPL</a>
                    <a href="?kelas=XII&jurusan=BR" class="filter-btn kelas-xii block text-center">XII BR</a>
                    <a href="?kelas=XII&jurusan=MP" class="filter-btn kelas-xii block text-center">XII MP</a>
                    <a href="?kelas=XII&jurusan=AKL" class="filter-btn kelas-xii block text-center">XII AKL</a>
                </div>
            </div>
        </div>
    </div>


        <?php
        // Database Connection
        $conn = new mysqli("localhost", "root", "", "absensi");
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Filter berdasarkan kelas dan jurusan
        $kelas = $_GET['kelas'] ?? null;
        $jurusan = $_GET['jurusan'] ?? null;
        if ($kelas && $jurusan) {
            $result = $conn->query("SELECT * FROM absen WHERE kelas = '$kelas' AND jurusan = '$jurusan'");
            $data = $conn->query("SELECT status, COUNT(*) as jumlah FROM absen WHERE kelas = '$kelas' AND jurusan = '$jurusan' GROUP BY status");

            // Data untuk grafik
            $statuses = [];
            $counts = [];
            $colors = [];
            while ($row = $data->fetch_assoc()) {
                $statuses[] = $row['status'];
                $counts[] = $row['jumlah'];

                // Mapping warna berdasarkan status
                if ($row['status'] === 'Hadir') {
                    $colors[] = '#2196f3';
                } elseif ($row['status'] === 'Izin') {
                    $colors[] = '#ffeb3b';
                } elseif ($row['status'] === 'Sakit') {
                    $colors[] = '#f44336';
                } else {
                    $colors[] = '#9e9e9e';
                }
            }
        } else {
            $result = null;
        }
        ?>

        <?php if ($kelas && $jurusan && $result && $result->num_rows > 0): ?>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4 text-center">Data Siswa: Kelas <?php echo htmlspecialchars($kelas); ?> - Jurusan <?php echo htmlspecialchars($jurusan); ?></h2>

            <!-- Tabel Data -->
            <table id="absensiTable" class="w-full bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
            <thead class="bg-blue-500 text-white">
                 <tr>
                     <th class="px-4 py-2 border text-left">No</th>
                     <th class="px-4 py-2 border text-left">Nama</th>
                     <th class="px-4 py-2 border text-left">Status</th>
                 </tr>
          </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td class='px-4 py-3 border-b text-gray-800'>{$no}</td>
                                <td class='px-4 py-3 border-b text-gray-800'>{$row['nama']}</td>
                                <td class='px-4 py-3 border-b text-gray-800'>{$row['status']}</td>
                              </tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>

            <!-- Grafik -->
            <canvas id="jurusanChart" style="max-width: 400px; max-height: 300px;"></canvas>

            <!-- Export PDF -->
            <div class="text-center mt-6">
                <button id="exportPDF" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition">
                    Export PDF
                </button>
            </div>

            <script>
                const ctx = document.getElementById('jurusanChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: <?php echo json_encode($statuses); ?>,
                        datasets: [{
                            data: <?php echo json_encode($counts); ?>,
                            backgroundColor: <?php echo json_encode($colors); ?>,
                        }]
                    },
                    options: {
                        responsive: true,
                    }
                });

                document.getElementById('exportPDF').addEventListener('click', function () {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF();

                    doc.setFontSize(16);
                    doc.text(`Laporan Absensi Siswa: Kelas <?php echo htmlspecialchars($kelas); ?> - Jurusan <?php echo htmlspecialchars($jurusan); ?>`, 14, 20);

                    doc.autoTable({
                        html: '#absensiTable',
                        startY: 30,
                        theme: 'grid',
                    });

                    doc.save(`absensi_<?php echo htmlspecialchars($kelas); ?>_<?php echo htmlspecialchars($jurusan); ?>.pdf`);
                });
            </script>
        <?php elseif ($kelas && $jurusan): ?>
            <p class="text-center text-gray-500">Tidak ada data untuk kelas dan jurusan ini.</p>
        <?php endif; ?>
    </div>
</body>
</html>
