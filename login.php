<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "absensi");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
       

            // Redirect berdasarkan role
            if ($user['role'] === 'guru') {
                header('Location: index.php');
            } else if ($user['role'] === 'siswa') {
                header('Location: siswa.php');
            }
            exit;
        } else {
            $error = "Password salah.";
        }
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #d7e8f7, #f7d6e8);
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm">
        <h1 class="text-2xl font-bold text-center mb-6">Login</h1>
        <?php if (isset($error)): ?>
            <p class="text-red-500 text-sm mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200" 
                    required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200" 
                    required>
            </div>
            <button 
                type="submit" 
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                Login
            </button>
        </form>
    </div>
</body>
</html>
