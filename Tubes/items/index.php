<?php
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
            alert('Akses Ditolak! Halaman ini khusus Administrator.');
            window.location='../dashboard.php';
          </script>";
    exit;
}

$whereClause = "";
$keyword = "";

if (isset($_GET['q'])) {
    $keyword = htmlspecialchars($_GET['q']);
    $whereClause = "WHERE items.nama_barang LIKE '%$keyword%' OR users.nama_lengkap LIKE '%$keyword%'";
}

$query = "SELECT items.*, users.nama_lengkap 
          FROM items 
          JOIN users ON items.user_id = users.id 
          $whereClause 
          ORDER BY items.id DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2>🗂️ Kelola Semua Barang</h2>
            <p style="color: #666;">Pantau stok barang masuk dari seluruh donatur.</p>
        </div>
        
        <form action="" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="q" placeholder="Cari barang / donatur..." value="<?= $keyword ?>" 
                   style="padding: 10px; border: 1px solid #ccc; border-radius: 6px; width: 250px;">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if($keyword): ?>
                <a href="index.php" class="btn btn-warning">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert-success" style="margin-bottom: 20px;">
            <?= htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Foto</th>
                        <th width="20%">Nama Barang</th>
                        <th width="15%">Donatur</th> 
                        <th width="10%">Kategori</th>
                        <th width="10%">Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                
                                <td>
                                    <?php if (!empty($row['gambar'])): ?>
                                        <img src="../assets/uploads/<?= $row['gambar']; ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                    <?php else: ?>
                                        <span style="font-size: 10px; color: #ccc;">No Pic</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($row['nama_barang']); ?></strong>
                                    <br>
                                    <small style="color: #888;">
                                        <?= date('d M Y', strtotime($row['created_at'])); ?>
                                    </small>
                                </td>

                                <td>
                                    <span style="color: #555; font-weight: 500;">
                                        <i class="icon-user"></i> <?= htmlspecialchars($row['nama_lengkap']); ?>
                                    </span>
                                </td>

                                <td><?= $row['kategori']; ?></td>

                                <td>
                                    <?php if ($row['status'] == 'tersedia'): ?>
                                        <span style="background: #e6fffa; color: #00a896; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                            Tersedia
                                        </span>
                                    <?php else: ?>
                                        <span style="background: #ebf8ff; color: #3182ce; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                            Disalurkan
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning" 
                                       style="padding: 5px 10px; font-size: 12px;" title="Edit Data">
                                       Edit
                                    </a>
                                    
                                    <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger delete-confirm" 
                                       style="padding: 5px 10px; font-size: 12px;" title="Hapus Permanen">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #999;">
                                Tidak ada data barang ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
