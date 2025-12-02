<?php
session_start();
require "../config/database.php";

// 1. Cek Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// 2. Proses Hapus
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Query Delete
    $stmt = $conn->prepare("DELETE FROM recipients WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?msg=Data penerima berhasil dihapus");
    } else {
        echo "<script>
                alert('Gagal menghapus data! Mungkin data sedang digunakan.'); 
                window.location='index.php';
              </script>";
    }
} else {
    header("Location: index.php");
}
?>