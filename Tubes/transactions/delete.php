<?php
session_start();
require "../config/database.php";

// Cek Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. AMBIL ITEM ID DULU SEBELUM MENGHAPUS TRANSAKSI
    // Kita perlu tahu barang apa yang terlibat dalam transaksi ini
    $check = mysqli_query($conn, "SELECT item_id FROM transactions WHERE id = '$id'");
    $data = mysqli_fetch_assoc($check);

    if ($data) {
        $item_id = $data['item_id'];

        // 2. KEMBALIKAN STATUS BARANG JADI 'TERSEDIA'
        // Rollback status
        mysqli_query($conn, "UPDATE items SET status = 'tersedia' WHERE id = '$item_id'");

        // 3. BARU HAPUS TRANSAKSINYA
        $delete = mysqli_query($conn, "DELETE FROM transactions WHERE id = '$id'");

        if ($delete) {
            header("Location: index.php?msg=Transaksi dibatalkan. Barang kembali tersedia.");
        } else {
            echo "Gagal menghapus data.";
        }
    } else {
        header("Location: index.php");
    }
}
?>