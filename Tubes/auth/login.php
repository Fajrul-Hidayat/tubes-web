<?php 
session_start();
require "../config/database.php";

// Jika user sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: ../dashboard.php");
    exit;
}

// Proses Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data user berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah username ditemukan
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verifikasi Password
        if (password_verify($password, $row['password'])) {
            // Set Session
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            $_SESSION['role'] = $row['role']; // Penting untuk membedakan Admin/Donatur

            // Redirect ke Dashboard
            header("Location: ../dashboard.php");
            exit;
        }
    }

    $error = "Username atau Password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TUBES</title>
    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

<div class="login-container">
    <h2 class="login-title">Masuk TUBES</h2>

    <!-- Notifikasi Error -->
    <?php if(isset($error)): ?>
        <div class="alert-error">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Notifikasi Sukses Register -->
    <?php if(isset($_GET['success'])): ?>
        <div class="alert-success" style="background:#d4edda; color:#155724; padding:10px; border-radius:6px; margin-bottom:15px; text-align:center;">
            Registrasi berhasil! Silakan login.
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Masukkan username Anda..." autocomplete="off">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Masukkan password...">
        </div>

        <button type="submit" name="login" class="btn btn-primary login-btn">Login Sekarang</button>
    </form>

    <div class="login-footer-text">
        Belum punya akun? <a href="register.php">Daftar sebagai Donatur</a>
        <br>
        <a href="../index.php" style="font-size:12px; color:#999;">Kembali ke Beranda</a>
    </div>
</div>

</body>
</html>