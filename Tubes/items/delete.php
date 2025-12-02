<?php
session_start();
require "../config/database.php";

// 1. Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 2. Cek Parameter ID di URL
if (!isset($_GET['id'])) {
    header("Location: my_items.php");
    exit;
}

$id_barang = $_GET['id'];
$user_id   = $_SESSION['user_id'];
$role      = $_SESSION['role']; // Ambil role user

// ---------------------------------------------------------
// 3. AMBIL DATA BARANG DULU & VALIDASI AKSES
// ---------------------------------------------------------

if ($role === 'admin') {
    // ADMIN: Boleh akses semua barang (Cek ID saja)
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->bind_param("i", $id_barang);
} else {
    // DONATUR: Wajib cek kepemilikan (Cek ID & User ID)
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id_barang, $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Jika data tidak ditemukan (ID salah atau Donatur mencoba hapus punya orang lain)
if (!$data) {
    echo "<script>
            alert('Akses Ditolak! Data tidak ditemukan atau bukan milik Anda.');
            window.location='my_items.php';
          </script>";
    exit;
}

// 4. Validasi Status
// Barang yang sudah disalurkan sebaiknya tidak dihapus agar riwayat transaksi tetap ada
// Kecuali Admin yang mungkin butuh menghapusnya karena kesalahan input
if ($data['status'] == 'disalurkan' && $role !== 'admin') {
    echo "<script>
            alert('Gagal! Barang yang sudah disalurkan tidak boleh dihapus.');
            window.location='my_items.php';
          </script>";
    exit;
}

// 5. PROSES HAPUS GAMBAR FISIK
$foto = $data['gambar'];
$path_foto = "../assets/uploads/" . $foto;

// Cek apakah filenya ada di folder? Jika ada, hapus.
if (!empty($foto) && file_exists($path_foto)) {
    unlink($path_foto); // Fungsi PHP untuk menghapus file fisik
}

// 6. PROSES HAPUS DATA DI DATABASE
$deleteQuery = $conn->prepare("DELETE FROM items WHERE id = ?");
$deleteQuery->bind_param("i", $id_barang);

if ($deleteQuery->execute()) {
    // Redirect Cerdas: Admin ke index, Donatur ke my_items
    $redirectPage = ($role === 'admin') ? 'index.php' : 'my_items.php';

    header("Location: $redirectPage?msg=Barang berhasil dihapus.");
    exit;
} else {
    echo "<script>
            alert('Terjadi kesalahan sistem saat menghapus database.');
            window.location='my_items.php';
          </script>";
}
?>