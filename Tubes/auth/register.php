<?php 
session_start();
require "../config/database.php";

if (isset($_SESSION['login'])) {
    header("Location: ../dashboard.php");
    exit;
}

if (isset($_POST['register'])) {
    
    $nama = htmlspecialchars(trim($_POST['nama_lengkap']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password_raw = $_POST['password'];
    $role = 'donatur'; 

    if (empty($nama) || empty($username) || empty($password_raw)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        $checkQuery = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $checkQuery->bind_param("s", $username);
        $checkQuery->execute();
        $checkQuery->store_result();

        if ($checkQuery->num_rows > 0) {
            $error = "Username '$username' sudah terpakai. Pilih yang lain.";
        } else {
            $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);
            $insertQuery = $conn->prepare("INSERT INTO users (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)");
            $insertQuery->bind_param("ssss", $nama, $username, $password_hash, $role);

            if ($insertQuery->execute()) {
                header("Location: login.php?success=1");
                exit;
            } else {
                $error = "Terjadi kesalahan sistem. Silakan coba lagi.";
            }
        }
        $checkQuery->close();
        if(isset($insertQuery)) $insertQuery->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - TUBES</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

    <div class="login-container">
        <h2 class="login-title">Gabung Kebaikan</h2>

        <?php if(isset($error)): ?>
            <div class="alert-error">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama_lengkap" required 
                       placeholder="Contoh: Budi Santoso" autocomplete="name">
            </div>

            <div class="form-group">
                <label for="user">Username</label>
                <input type="text" id="user" name="username" required 
                       placeholder="Buat username unik..." autocomplete="off">
            </div>

            <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" id="pass" name="password" required 
                       placeholder="Minimal 6 karakter..." minlength="6">
            </div>

            <button type="submit" name="register" class="btn btn-primary login-btn">
                Daftar Sekarang
            </button>
        </form>

        <div class="login-footer-text">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
            <br><br>
            <a href="../index.php" style="font-size: 12px; color: #999; text-decoration: none;">&larr; Kembali ke Beranda</a>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>

</body>
</html>