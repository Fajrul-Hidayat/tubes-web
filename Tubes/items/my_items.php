<?php
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM items WHERE user_id = '$user_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; margin-bottom: 20px;">
        <div>
            <h2>📦 Donasi Saya</h2>
            <p style="color: #666;">Kelola barang yang Anda sumbangkan di sini.</p>
        </div>
        <a href="create.php" class="btn btn-primary">
            + Tambah Donasi
        </a>
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
                        <th width="25%">Nama Barang</th>
                        <th width="15%">Kategori</th>
                        <th width="15%">Tanggal Upload</th>
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
                                             alt="Foto Barang" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #eee;">
                                    <?php else: ?>
                                        <span style="font-size: 12px; color: #ccc;">No Image</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($row['nama_barang']); ?></strong>
                                    <br>
                                    <small style="color: #888;">
                                        <?= substr(htmlspecialchars($row['deskripsi']), 0, 50); ?>...
                                    </small>
                                </td>

                                <td><?= $row['kategori']; ?></td>
                                
                                <td><?= date('d M Y', strtotime($row['created_at'])); ?></td>

                                <td>
                                    <?php if ($row['status'] == 'tersedia'): ?>
                                        <span style="background: #e6fffa; color: #00a896; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            Tersedia
                                        </span>
                                    <?php else: ?>
                                        <span style="background: #ebf8ff; color: #3182ce; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            Disalurkan
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($row['status'] == 'tersedia'): ?>
                                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                                        
                                        <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger delete-confirm" style="padding: 5px 10px; font-size: 12px;">Hapus</a>
                                    <?php else: ?>
                                        <span style="font-size: 12px; color: #aaa; font-style: italic;">
                                            <i class="icon-lock"></i> Terkunci
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                                <img src="https://img.icons8.com/ios/50/dddddd/open-box.png" style="margin-bottom: 10px; opacity: 0.5;"><br>
                                Belum ada barang yang didonasikan.<br>
                                <a href="create.php" style="color: #00a896; font-weight: 600;">Mulai Donasi Sekarang</a>
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>