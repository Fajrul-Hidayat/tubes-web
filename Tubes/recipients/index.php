<?php
// 1. Panggil Komponen Utama
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

// 2. Keamanan: Cek Login & Role ADMIN
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
            alert('Akses Ditolak! Halaman ini khusus Administrator.');
            window.location='../dashboard.php';
          </script>";
    exit;
}

// 3. Query Data Penerima
// Mengurutkan dari yang terbaru (DESC)
$query = "SELECT * FROM recipients ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container">
    
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; margin-bottom: 20px;">
        <div>
            <h2>👥 Data Penerima Manfaat</h2>
            <p style="color: #666;">Kelola data panti asuhan, yayasan, atau individu yang membutuhkan.</p>
        </div>
        <a href="create.php" class="btn btn-primary">
            + Tambah Penerima
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
                        <th width="20%">Nama Penerima</th>
                        <th width="25%">Alamat</th>
                        <th width="15%">No. HP</th>
                        <th width="20%">Kebutuhan Utama</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong><?= htmlspecialchars($row['nama_penerima']); ?></strong></td>
                                <td><?= htmlspecialchars($row['alamat']); ?></td>
                                <td><?= htmlspecialchars($row['no_hp']); ?></td>
                                
                                <td>
                                    <span style="background: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <?= htmlspecialchars($row['kebutuhan_utama']); ?>
                                    </span>
                                </td>

                                <td>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                                    <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger delete-confirm" style="padding: 5px 10px; font-size: 12px;">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
                                Belum ada data penerima. Silakan tambah data baru.
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>