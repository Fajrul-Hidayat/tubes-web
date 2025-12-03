<?php 
include "includes/header.php";
include "includes/navbar.php";
require "config/database.php";

if (!isset($_SESSION['login'])) {
    echo "<script>
            alert('Akses ditolak! Silakan login terlebih dahulu.');
            window.location='auth/login.php';
          </script>";
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$nama_lengkap = $_SESSION['nama_lengkap'];


if ($role == 'admin') {
    $qDonatur = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='donatur'");
    $dDonatur = mysqli_fetch_assoc($qDonatur);

    $qItems = mysqli_query($conn, "SELECT COUNT(*) as total FROM items");
    $dItems = mysqli_fetch_assoc($qItems);

    $qTrans = mysqli_query($conn, "SELECT COUNT(*) as total FROM transactions");
    $dTrans = mysqli_fetch_assoc($qTrans);

} else {
    $qMyItems = mysqli_query($conn, "SELECT COUNT(*) as total FROM items WHERE user_id = '$user_id'");
    $dMyItems = mysqli_fetch_assoc($qMyItems);

    $qMyDistributed = mysqli_query($conn, "SELECT COUNT(*) as total FROM items WHERE user_id = '$user_id' AND status='disalurkan'");
    $dMyDistributed = mysqli_fetch_assoc($qMyDistributed);
    
    $tersedia = $dMyItems['total'] - $dMyDistributed['total'];
}
?>


<div class="container">
    
    <div style="margin-top: 30px; margin-bottom: 30px;">
        <h2 style="margin-bottom: 5px;">Selamat Datang, <?= htmlspecialchars($nama_lengkap); ?>! 👋</h2>
        <p style="color: #666;">
            Anda login sebagai <span style="font-weight: bold; color: #00a896; text-transform: capitalize;"><?= $role; ?></span>.
            Berikut adalah ringkasan aktivitas di TUBES.
        </p>
    </div>

    <div class="grid">

        <?php if ($role == 'admin'): ?>
            
            <div class="card">
                <h3>👥 Donatur Terdaftar</h3>
                <h1 style="font-size: 40px; color: #05668d; margin: 10px 0;"><?= $dDonatur['total']; ?></h1>
                <p>Orang baik yang telah bergabung.</p>
            </div>

            <div class="card">
                <h3>📦 Total Barang Masuk</h3>
                <h1 style="font-size: 40px; color: #00a896; margin: 10px 0;"><?= $dItems['total']; ?></h1>
                <p>Barang bekas layak pakai terkumpul.</p>
                <a href="items/index.php" class="btn btn-primary" style="margin-top: 10px;">Lihat Data</a>
            </div>

            <div class="card">
                <h3>🤝 Transaksi Penyaluran</h3>
                <h1 style="font-size: 40px; color: #e63946; margin: 10px 0;"><?= $dTrans['total']; ?></h1>
                <p>Bantuan yang telah diserahkan.</p>
                <a href="transactions/index.php" class="btn btn-primary" style="margin-top: 10px;">Kelola Penyaluran</a>
            </div>

        <?php else: ?>

            <div class="card">
                <h3>💖 Total Donasi Saya</h3>
                <h1 style="font-size: 40px; color: #00a896; margin: 10px 0;"><?= $dMyItems['total']; ?></h1>
                <p>Barang yang telah Anda sumbangkan.</p>
            </div>

            <div class="card">
                <h3>⏳ Status Barang</h3>
                <p><strong><?= $tersedia; ?></strong> Menunggu Penyaluran</p>
                <p><strong><?= $dMyDistributed['total']; ?></strong> Sudah Diterima Warga</p>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
                <p style="font-size: 13px; color: #888;">Terima kasih atas kepedulian Anda.</p>
            </div>

            <div class="card" style="background-color: #f0fdfa; border: 1px solid #ccfbf1;">
                <h3>✨ Mulai Berbagi</h3>
                <p>Punya barang bekas layak pakai?</p>
                <p>Jangan dibuang, salurkan kepada yang membutuhkan sekarang juga.</p>
                <br>
                <a href="items/create.php" class="btn btn-primary">
                    + Donasi Barang Baru
                </a>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php 
include "includes/footer.php"; 
?>