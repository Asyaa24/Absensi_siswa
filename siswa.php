<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('https://source.unsplash.com/random/1920x1080'); /* Gambar acak dari Unsplash */
            background-size: cover;
            background-position: center;
        }
        body.dark {
            background-color: #1a202c;
            color: #f7fafc;
        }
        body.dark .bg-white {
            background-color: #2d3748;
            color: #f7fafc;
        }
        body.dark .text-gray-700 {
            color: #a0aec0;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body class="bg-gray-100">
<a href="login.php" class="logout-btn">
        ← Logout
</a>

<div class="container mx-auto py-10">
    <h1 class="text-3xl font-bold text-center mb-6 text-blue-500">Absensi Siswa</h1>
    <div class="flex justify-end">
        <button id="toggleDarkMode" class="bg-gray-500 text-white py-1 px-3 rounded-lg">Mode Gelap</button>
    </div>

    <div id="quote" class="text-center text-xl mt-6 font-semibold text-blue-500">
        <!-- Quote akan muncul di sini -->
    </div>

    <form action="add_attendance.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="nama" class="block text-gray-700 font-bold">Nama Siswa:</label>
            <input type="text" id="nama" name="nama" required 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div class="mb-4">
            <label for="kelas" class="block text-gray-700 font-bold">Kelas:</label>
            <select id="kelas" name="kelas" required 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="X">X</option>
                <option value="XI">XI</option>
                <option value="XII">XII</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="jurusan" class="block text-gray-700 font-bold">Jurusan:</label>
            <select id="jurusan" name="jurusan" required 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="RPL">Rekayasa Perangkat Lunak (RPL)</option>
                <option value="BR">Bisnis Retail (BR)</option>
                <option value="MP">Manajemen Perkantoran (MP)</option>
                <option value="AKL">Akuntansi dan Keuangan Lembaga (AKL)</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="status" class="block text-gray-700 font-bold">Status Kehadiran:</label>
            <select id="status" name="status" required 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
            </select>
        </div>
        <button type="submit" 
            class="w-full bg-blue-500 text-white font-bold py-2 rounded-lg hover:bg-blue-600 transition">
            Tambahkan Absensi
        </button>
    </form>

    <script>
        const quotes = [
            "Selamat datang, semangat hari ini!",
            "Jangan menyerah, setiap hari adalah kesempatan baru!",
            "Keberhasilan dimulai dengan langkah pertama!",
            "Belajar itu perjalanan, bukan tujuan."
        ];
        document.getElementById('quote').textContent = quotes[Math.floor(Math.random() * quotes.length)];
    </script>

    <script>
        const status = document.getElementById('status');
        const statusEmoji = document.createElement('span');
        const statusContainer = document.querySelector('.mb-4');
        
        status.addEventListener('change', function() {
            let emoji = '';
            if (status.value === 'Hadir') {
                emoji = '😊';
            } else if (status.value === 'Izin') {
                emoji = '🙋‍♂️';
            } else if (status.value === 'Sakit') {
                emoji = '🤒';
            }
            statusContainer.appendChild(statusEmoji);
            statusEmoji.textContent = emoji;
        });
    </script>

    <script>
        const toggleButton = document.getElementById('toggleDarkMode');
        const body = document.body;

        toggleButton.addEventListener('click', () => {
            body.classList.toggle('dark');
            toggleButton.textContent = body.classList.contains('dark') ? 'Mode Terang' : 'Mode Gelap';
        });
    </script>
</div>
</body>
</html>
