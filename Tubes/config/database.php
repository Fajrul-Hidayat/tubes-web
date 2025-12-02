<?php
/**
 * File Konfigurasi Database
 * Digunakan untuk menghubungkan aplikasi PHP dengan MySQL
 */

// Konfigurasi Kredensial Database
// Sesuaikan dengan settingan XAMPP/Laragon Anda
$host = "localhost";   // Server database (biasanya localhost)
$user = "root";        // Username default XAMPP adalah 'root'
$pass = "";            // Password default XAMPP biasanya kosong
$db   = "db_donasi";   // Nama database yang akan kita gunakan

// Melakukan koneksi ke MySQL
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil?
if (!$conn) {
    // Jika gagal, hentikan proses dan tampilkan pesan error
    die("❌ Koneksi Database Gagal: " . mysqli_connect_error());
}

// Set timezone ke Waktu Indonesia Barat (WIB) agar pencatatan waktu transaksi akurat
date_default_timezone_set('Asia/Jakarta');
?>