<?php
/**
 * SETUP DATABASE OTOMATIS
 * Jalankan file ini sekali saja di browser: localhost/TUBES/config/setup_database.php
 */

$host = "localhost";
$user = "root";
$pass = "";

// 1. Koneksi ke MySQL (Tanpa memilih database dulu)
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("Gagal koneksi ke MySQL: " . mysqli_connect_error());
}

echo "<h3>🚀 Memulai Proses Setup Database...</h3><hr>";

// 2. Buat Database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS db_donasi";
if (mysqli_query($conn, $sql)) {
    echo "✅ Database 'db_donasi' siap.<br>";
} else {
    echo "❌ Gagal membuat database: " . mysqli_error($conn) . "<br>";
}

// 3. Pilih Database
mysqli_select_db($conn, "db_donasi");

// 4. Buat Tabel Users
$tabel_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'donatur') DEFAULT 'donatur',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $tabel_users)) {
    echo "✅ Tabel 'users' siap.<br>";
} else {
    echo "❌ Gagal membuat tabel users: " . mysqli_error($conn) . "<br>";
}

// 5. Buat Tabel Items
$tabel_items = "CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    status ENUM('tersedia', 'disalurkan') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $tabel_items)) {
    echo "✅ Tabel 'items' siap.<br>";
} else {
    echo "❌ Gagal membuat tabel items: " . mysqli_error($conn) . "<br>";
}

// 6. Buat Tabel Recipients
$tabel_recipients = "CREATE TABLE IF NOT EXISTS recipients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_penerima VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    kebutuhan_utama VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $tabel_recipients)) {
    echo "✅ Tabel 'recipients' siap.<br>";
} else {
    echo "❌ Gagal membuat tabel recipients: " . mysqli_error($conn) . "<br>";
}

// 7. Buat Tabel Transactions
$tabel_transactions = "CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    recipient_id INT NOT NULL,
    tanggal_salur DATE NOT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES recipients(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $tabel_transactions)) {
    echo "✅ Tabel 'transactions' siap.<br>";
} else {
    echo "❌ Gagal membuat tabel transactions: " . mysqli_error($conn) . "<br>";
}

// 8. Isi Akun Admin Default (Jika tabel users masih kosong)
$cek_user = mysqli_query($conn, "SELECT * FROM users LIMIT 1");
if (mysqli_num_rows($cek_user) == 0) {
    // Password default: admin123
    $password_admin = password_hash("admin123", PASSWORD_DEFAULT);
    $password_donatur = password_hash("donatur123", PASSWORD_DEFAULT);
    
    $sql_admin = "INSERT INTO users (nama_lengkap, username, password, role) VALUES 
    ('Administrator', 'admin', '$password_admin', 'admin'),
    ('Donatur Budi', 'budi', '$password_donatur', 'donatur')";

    if (mysqli_query($conn, $sql_admin)) {
        echo "✅ Akun Admin Default berhasil dibuat (User: admin, Pass: admin123).<br>";
        echo "✅ Akun Donatur Default berhasil dibuat (User: budi, Pass: donatur123).<br>";
    }
} else {
    echo "ℹ️ Data user sudah ada, skip pembuatan akun default.<br>";
}

echo "<hr><h3>🎉 SELESAI! Database siap digunakan.</h3>";
echo "<a href='../index.php'>Kembali ke Halaman Utama</a>";
?>