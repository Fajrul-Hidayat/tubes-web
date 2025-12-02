<?php
// 1. Panggil Komponen Utama
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

// 2. Keamanan: Cek Login & Cek Role ADMIN
// Jika bukan admin, tendang ke dashboard
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
            alert('Akses Ditolak! Halaman ini khusus Administrator.');
            window.location='../dashboard.php';
          </script>";
    exit;
}

// 3. Logika Pencarian (Search)
$whereClause = "";
$keyword = "";

if (isset($_GET['q'])) {
    $keyword = htmlspecialchars($_GET['q']);
    // Cari berdasarkan Nama Barang ATAU Nama Donatur
    $whereClause = "WHERE items.nama_barang LIKE '%$keyword%' OR users.nama_lengkap LIKE '%$keyword%'";
}

// 4. Query Data dengan JOIN
// Kita ambil data barang + nama lengkap donaturnya dari tabel users
$query = "SELECT items.*, users.nama_lengkap 
          FROM items 
          JOIN users ON items.user_id = users.id 
          $whereClause 
          ORDER BY items.id DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container">
    
    <!-- Header: Judul & Search Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2>🗂️ Kelola Semua Barang</h2>
            <p style="color: #666;">Pantau stok barang masuk dari seluruh donatur.</p>
        </div>
        
        <!-- Form Pencarian -->
        <form action="" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="q" placeholder="Cari barang / donatur..." value="<?= $keyword ?>" 
                   style="padding: 10px; border: 1px solid #ccc; border-radius: 6px; width: 250px;">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if($keyword): ?>
                <a href="index.php" class="btn btn-warning">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Alert Sukses (Jika ada aksi delete/edit) -->
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
                        <th width="10%">Foto</th>
                        <th width="20%">Nama Barang</th>
                        <th width="15%">Donatur</th> <!-- Kolom Baru -->
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
                                
                                <!-- Foto -->
                                <td>
                                    <?php if (!empty($row['gambar'])): ?>
                                        <img src="../assets/uploads/<?= $row['gambar']; ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                    <?php else: ?>
                                        <span style="font-size: 10px; color: #ccc;">No Pic</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Nama Barang -->
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_barang']); ?></strong>
                                    <br>
                                    <small style="color: #888;">
                                        <?= date('d M Y', strtotime($row['created_at'])); ?>
                                    </small>
                                </td>

                                <!-- Nama Donatur (Hasil Join) -->
                                <td>
                                    <span style="color: #555; font-weight: 500;">
                                        <i class="icon-user"></i> <?= htmlspecialchars($row['nama_lengkap']); ?>
                                    </span>
                                </td>

                                <td><?= $row['kategori']; ?></td>

                                <!-- Status -->
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

                                <!-- Aksi Admin -->
                                <td>
                                    <!-- Admin bisa mengedit barang orang lain untuk moderasi -->
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning" 
                                       style="padding: 5px 10px; font-size: 12px;" title="Edit Data">
                                       Edit
                                    </a>
                                    
                                    <!-- Admin bisa menghapus barang spam/tidak layak -->
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
```

### ⚠️ Perhatian Penting: Update `delete.php` & `edit.php`

Saat ini file `delete.php` dan `edit.php` yang kita buat sebelumnya memiliki fitur keamanan **"Hanya Pemilik yang Boleh Hapus/Edit"**.

Karena sekarang **Admin** juga butuh menghapus/mengedit barang orang lain (untuk moderasi), Anda perlu sedikit memodifikasi file `delete.php` dan `edit.php`.

**Buka `items/delete.php` dan ubah Query Pengecekannya:**

Cari baris ini di `delete.php`:
```php
$stmt = $conn->prepare("SELECT * FROM items WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id_barang, $user_id);
```

**Ubah menjadi logika ini (Agar Admin lolos pengecekan):**

```php
// Cek Role dulu
if ($_SESSION['role'] == 'admin') {
    // Kalau Admin, tidak perlu cek user_id, cukup cek ID barangnya saja
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->bind_param("i", $id_barang);
} else {
    // Kalau Donatur, WAJIB cek kepemilikan (user_id)
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id_barang, $user_id);
}