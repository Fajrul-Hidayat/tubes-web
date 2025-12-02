<?php 
include "../includes/header.php"; 
include "../includes/navbar.php"; 
require "../config/database.php";

// ADMIN ONLY
include "../auth/auth_check_admin.php";
?>

<div class="content">

    <h1 class="page-title">Dashboard Admin</h1>

    <div class="dashboard-grid">

        <!-- TOTAL DONATUR -->
        <div class="card dashboard-card">
            <h3>Total Donatur</h3>
            <?php 
            $donatur = mysqli_fetch_assoc(mysqli_query($conn, 
                "SELECT COUNT(*) AS total FROM users WHERE role='donatur'"
            ));
            ?>
            <p class="dashboard-number"><?= $donatur['total'] ?></p>
        </div>

        <!-- TOTAL PENERIMA -->
        <div class="card dashboard-card">
            <h3>Total Penerima</h3>
            <?php 
            $rec = mysqli_fetch_assoc(mysqli_query($conn, 
                "SELECT COUNT(*) AS total FROM recipients"
            ));
            ?>
            <p class="dashboard-number"><?= $rec['total'] ?></p>
        </div>

        <!-- TOTAL BARANG TERSEDIA -->
        <div class="card dashboard-card">
            <h3>Barang Tersedia</h3>
            <?php 
            $items_t = mysqli_fetch_assoc(mysqli_query($conn, 
                "SELECT COUNT(*) AS total FROM items WHERE status='tersedia'"
            ));
            ?>
            <p class="dashboard-number"><?= $items_t['total'] ?></p>
        </div>

        <!-- TOTAL BARANG DISALURKAN -->
        <div class="card dashboard-card">
            <h3>Barang Disalurkan</h3>
            <?php 
            $items_d = mysqli_fetch_assoc(mysqli_query($conn, 
                "SELECT COUNT(*) AS total FROM items WHERE status='disalurkan'"
            ));
            ?>
            <p class="dashboard-number"><?= $items_d['total'] ?></p>
        </div>

        <!-- TOTAL TRANSAKSI -->
        <div class="card dashboard-card">
            <h3>Total Transaksi Penyaluran</h3>
            <?php 
            $trans = mysqli_fetch_assoc(mysqli_query($conn, 
                "SELECT COUNT(*) AS total FROM transactions"
            ));
            ?>
            <p class="dashboard-number"><?= $trans['total'] ?></p>
        </div>

    </div>

</div>

<?php include "../includes/footer.php"; ?>
