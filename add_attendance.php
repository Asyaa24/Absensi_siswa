<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses data absensi
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan']; // Ambil data jurusan dari form
    $status = $_POST['status'];

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "absensi");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Persiapan query dengan parameter
    $stmt = $conn->prepare("INSERT INTO absen (nama, kelas, jurusan, status, created_at) VALUES (?, ?, ?, ?, ?)");
    $created_at = date('Y-m-d H:i:s'); // Waktu saat ini
    $stmt->bind_param("sssss", $nama, $kelas, $jurusan, $status, $created_at);

    // Eksekusi query dan pengecekan hasil
    if ($stmt->execute()) {
        echo "<script>
                alert('Absensi berhasil ditambahkan!');
                window.location.href = 'siswa.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Tutup koneksi
    $stmt->close();
    $conn->close();
}
?>
