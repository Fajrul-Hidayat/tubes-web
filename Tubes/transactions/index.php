<?php
// 1. Panggil Komponen Utama
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

// 2. Keamanan: Cek Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// 3. Query Kompleks (JOIN 3 Tabel)
// Kita perlu mengambil data dari:
// - transactions (Tanggal, Keterangan)
// - items (Nama Barang)
// - recipients (Nama Penerima)
$query = "SELECT transactions.*, items.nama_barang, recipients.nama_penerima 
          FROM transactions 
          JOIN items ON transactions.item_id = items.id 
          JOIN recipients ON transactions.recipient_id = recipients.id 
          ORDER BY transactions.tanggal_salur DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container">
    
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; margin-bottom: 20px;">
        <div>
            <h2>🤝 Riwayat Penyaluran</h2>
            <p style="color: #666;">Daftar barang yang telah berhasil disalurkan ke penerima.</p>
        </div>
        <a href="create.php" class="btn btn-primary">
            + Catat Penyaluran Baru
        </a>
    </div>

    <!-- Alert Sukses -->
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert-success" style="margin-bottom: 20px;">
            <?= htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <!-- Tabel Data -->
    <div class="card">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="25%">Barang</th>
                        <th width="25%">Penerima Manfaat</th>
                        <th width="20%">Keterangan</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                
                                <!-- Tanggal Format Indonesia -->
                                <td><?= date('d M Y', strtotime($row['tanggal_salur'])); ?></td>
                                
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_barang']); ?></strong>
                                </td>
                                
                                <td>
                                    <span style="color: #00a896; font-weight: 500;">
                                        <?= htmlspecialchars($row['nama_penerima']); ?>
                                    </span>
                                </td>
                                
                                <td><?= htmlspecialchars($row['keterangan']); ?></td>

                                <td>
                                    <!-- Tombol Hapus (Batalkan Transaksi) -->
                                    <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger delete-confirm" 
                                       style="padding: 5px 10px; font-size: 12px;" title="Batalkan Penyaluran">
                                       Batal
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                                Belum ada penyaluran barang. <br>
                                <a href="create.php" style="font-weight: bold; color: #00a896;">Mulai Salurkan Sekarang</a>
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>