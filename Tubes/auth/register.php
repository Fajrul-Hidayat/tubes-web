<?php 
session_start();
require "../config/database.php";

// ------------------------------------------------------------------
// LOGIKA BACKEND
// ------------------------------------------------------------------

// 1. Cek Sesi: Jika user sudah login, lempar ke Dashboard
if (isset($_SESSION['login'])) {
    header("Location: ../dashboard.php");
    exit;
}

// 2. Proses Saat Tombol Register Ditekan
if (isset($_POST['register'])) {
    
    // Sanitasi Input (Membersihkan karakter berbahaya)
    $nama = htmlspecialchars(trim($_POST['nama_lengkap']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password_raw = $_POST['password'];
    $role = 'donatur'; // Default role untuk pendaftar umum

    // Validasi Sederhana
    if (empty($nama) || empty($username) || empty($password_raw)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        // Cek Ketersediaan Username (Prepared Statement)
        $checkQuery = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $checkQuery->bind_param("s", $username);
        $checkQuery->execute();
        $checkQuery->store_result();

        if ($checkQuery->num_rows > 0) {
            $error = "Username '$username' sudah terpakai. Pilih yang lain.";
        } else {
            // Enkripsi Password
            $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);

            // Simpan Data User Baru
            $insertQuery = $conn->prepare("INSERT INTO users (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)");
            $insertQuery->bind_param("ssss", $nama, $username, $password_hash, $role);

            if ($insertQuery->execute()) {
                // Redirect ke login dengan sinyal sukses
                header("Location: login.php?success=1");
                exit;
            } else {
                $error = "Terjadi kesalahan sistem. Silakan coba lagi.";
            }
        }
        // Tutup statement
        $checkQuery->close();
        if(isset($insertQuery)) $insertQuery->close();
    }
}
?>

<!-- ------------------------------------------------------------------ -->
<!-- TAMPILAN FRONTEND (HTML) -->
<!-- ------------------------------------------------------------------ -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - TUBES</title>
    
    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Terintegrasi -->
    <!-- style.css untuk reset dasar, login.css untuk layout kartu -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

    <div class="login-container">
        <!-- Judul -->
        <h2 class="login-title">Gabung Kebaikan</h2>

        <!-- Alert Error (Muncul jika variabel $error ada isinya) -->
        <?php if(isset($error)): ?>
            <div class="alert-error">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <!-- Form Registrasi -->
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

        <!-- Footer Kartu Login -->
        <div class="login-footer-text">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
            <br><br>
            <a href="../index.php" style="font-size: 12px; color: #999; text-decoration: none;">&larr; Kembali ke Beranda</a>
        </div>
    </div>

    <!-- JAVASCRIPT INTEGRATION -->
    <!-- Script ini wajib ada agar alert bisa hilang otomatis (Auto-hide) -->
    <script src="../assets/js/main.js"></script>

</body>
</html>