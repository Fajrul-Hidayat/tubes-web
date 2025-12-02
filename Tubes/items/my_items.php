<?php
// 1. Panggil Komponen Utama
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

// 2. Keamanan: Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 3. Ambil data User ID yang sedang login
$user_id = $_SESSION['user_id'];

// 4. Query Data Barang Milik User Ini
// Menggunakan ORDER BY id DESC agar barang terbaru muncul paling atas
$query = "SELECT * FROM items WHERE user_id = '$user_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container">
    
    <!-- Header Halaman -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; margin-bottom: 20px;">
        <div>
            <h2>📦 Donasi Saya</h2>
            <p style="color: #666;">Kelola barang yang Anda sumbangkan di sini.</p>
        </div>
        <!-- Tombol Tambah Barang -->
        <a href="create.php" class="btn btn-primary">
            + Tambah Donasi
        </a>
    </div>

    <!-- Tampilkan Alert Sukses (Dari create.php atau delete.php) -->
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert-success" style="margin-bottom: 20px;">
            <?= htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <!-- Kartu Tabel -->
    <div class="card">
        <div style="overflow-x: auto;"> <!-- Agar responsif di HP -->
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
                                
                                <!-- Kolom Foto -->
                                <td>
                                    <?php if (!empty($row['gambar'])): ?>
                                        <img src="../assets/uploads/<?= $row['gambar']; ?>" 
                                             alt="Foto Barang" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #eee;">
                                    <?php else: ?>
                                        <span style="font-size: 12px; color: #ccc;">No Image</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Kolom Nama & Deskripsi Pendek -->
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_barang']); ?></strong>
                                    <br>
                                    <small style="color: #888;">
                                        <?= substr(htmlspecialchars($row['deskripsi']), 0, 50); ?>...
                                    </small>
                                </td>

                                <td><?= $row['kategori']; ?></td>
                                
                                <td><?= date('d M Y', strtotime($row['created_at'])); ?></td>

                                <!-- Kolom Status dengan Badge Warna -->
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

                                <!-- Kolom Aksi -->
                                <td>
                                    <?php if ($row['status'] == 'tersedia'): ?>
                                        <!-- Hanya barang 'tersedia' yang boleh diedit/hapus -->
                                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                                        
                                        <!-- Class 'delete-confirm' akan memicu alert JS -->
                                        <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger delete-confirm" style="padding: 5px 10px; font-size: 12px;">Hapus</a>
                                    <?php else: ?>
                                        <!-- Jika sudah disalurkan, kunci aksi -->
                                        <span style="font-size: 12px; color: #aaa; font-style: italic;">
                                            <i class="icon-lock"></i> Terkunci
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    
                    <?php else: ?>
                        <!-- Tampilan Jika Data Kosong -->
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