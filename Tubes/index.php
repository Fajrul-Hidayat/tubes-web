<?php
include "includes/header.php";
include "includes/navbar.php";
require "config/database.php";

$qItems = mysqli_query($conn, "SELECT COUNT(*) as total FROM items WHERE status = 'tersedia'");
$dItems = mysqli_fetch_assoc($qItems);

$qSalur = mysqli_query($conn, "SELECT COUNT(*) as total FROM items WHERE status = 'disalurkan'");
$dSalur = mysqli_fetch_assoc($qSalur);

$qDonatur = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'donatur'");
$dDonatur = mysqli_fetch_assoc($qDonatur);
?>

<div style="background: linear-gradient(135deg, #00a896 0%, #028090 100%); color: white; padding: 80px 20px; text-align: center; margin-bottom: 40px;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 15px; color: white;">Berbagi Barang, Berbagi Harapan </h1>
        <p style="color: #ffffff;font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9; max-width: 700px; margin-left: auto; margin-right: auto;">
            Platform donasi barang bekas layak pakai terpercaya. 
            Salurkan barang tak terpakai Anda kepada mereka yang membutuhkan secara transparan dan tepat sasaran.
        </p>
        
        <?php if (!isset($_SESSION['login'])): ?>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="auth/register.php" class="btn" style="background: #fff; color: #00a896; font-weight: bold; padding: 12px 30px; font-size: 16px;">
                    Mulai Donasi Sekarang
                </a>
            </div>
        <?php else: ?>
            <a href="dashboard.php" class="btn" style="background: #fff; color: #00a896; font-weight: bold; padding: 12px 30px; font-size: 16px;">
                Ke Dashboard Saya
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    
    <div class="grid" style="margin-bottom: 60px;">
        <div class="card" style="text-align: center; border-bottom: 5px solid #00a896;">
            <div style="font-size: 40px; margin-bottom: 10px;">📦</div>
            <h1 style="font-size: 40px; color: #00a896; margin: 0;"><?= $dItems['total']; ?></h1>
            <p style="color: #666; margin-top: 5px;">Barang Tersedia</p>
        </div>

        <div class="card" style="text-align: center; border-bottom: 5px solid #05668d;">
            <div style="font-size: 40px; margin-bottom: 10px;">🤝</div>
            <h1 style="font-size: 40px; color: #05668d; margin: 0;"><?= $dSalur['total']; ?></h1>
            <p style="color: #666; margin-top: 5px;">Telah Disalurkan</p>
        </div>

        <div class="card" style="text-align: center; border-bottom: 5px solid #ffb703;">
            <div style="font-size: 40px; margin-bottom: 10px;">👥</div>
            <h1 style="font-size: 40px; color: #ffb703; margin: 0;"><?= $dDonatur['total']; ?></h1>
            <p style="color: #666; margin-top: 5px;">Donatur Bergabung</p>
        </div>
    </div>

    <div style="margin-bottom: 60px;">
        <h2 style="text-align: center; margin-bottom: 40px; color: #05668d;">Mengapa TUBES?</h2>
        
        <div class="grid">
            <div style="padding: 20px;">
                <h3 style="color: #00a896;">1. Mudah & Cepat</h3>
                <p style="color: #666; line-height: 1.6;">
                    Cukup foto barang bekas layak pakai Anda, upload, dan tunggu admin memverifikasi untuk penyaluran.
                </p>
            </div>
            <div style="padding: 20px;">
                <h3 style="color: #00a896;">2. Transparan</h3>
                <p style="color: #666; line-height: 1.6;">
                    Pantau status barang Anda mulai dari "Tersedia" hingga "Disalurkan" kepada penerima manfaat yang terdata.
                </p>
            </div>
            <div style="padding: 20px;">
                <h3 style="color: #00a896;">3. Tepat Sasaran</h3>
                <p style="color: #666; line-height: 1.6;">
                    Kami bekerja sama dengan panti asuhan dan korban bencana untuk memastikan barang Anda sampai ke tangan yang tepat.
                </p>
            </div>
        </div>
    </div>

    <div class="card" style="background-color: #e0f2f1; text-align: center; padding: 40px; border: none;">
        <h2 style="margin-top: 0;">Siap Berbagi Kebaikan?</h2>
        <p style="margin-bottom: 20px; color: #555;">Satu barang kecil dari Anda bisa menjadi harta berharga bagi mereka.</p>
        
        <?php if (!isset($_SESSION['login'])): ?>
            <a href="auth/register.php" class="btn btn-primary" style="padding: 12px 25px; font-size: 16px;">
                Daftar Sebagai Donatur Sekarang
            </a>
        <?php else: ?>
            <a href="items/create.php" class="btn btn-primary" style="padding: 12px 25px; font-size: 16px;">
                Tambah Donasi Baru
            </a>
        <?php endif; ?>
    </div>

</div>

<?php include "includes/footer.php"; ?>