<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: my_items.php");
    exit;
}

$id_barang = $_GET['id'];
$user_id   = $_SESSION['user_id'];
$role      = $_SESSION['role']; 
if ($role === 'admin') {
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->bind_param("i", $id_barang);
} else {
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id_barang, $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "<script>
            alert('Akses Ditolak! Data tidak ditemukan atau bukan milik Anda.');
            window.location='my_items.php';
          </script>";
    exit;
}

if ($data['status'] == 'disalurkan' && $role !== 'admin') {
    echo "<script>
            alert('Gagal! Barang yang sudah disalurkan tidak boleh dihapus.');
            window.location='my_items.php';
          </script>";
    exit;
}

$foto = $data['gambar'];
$path_foto = "../assets/uploads/" . $foto;

if (!empty($foto) && file_exists($path_foto)) {
    unlink($path_foto); 
}

$deleteQuery = $conn->prepare("DELETE FROM items WHERE id = ?");
$deleteQuery->bind_param("i", $id_barang);

if ($deleteQuery->execute()) {
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