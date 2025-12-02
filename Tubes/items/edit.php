<?php
// 1. Panggil Komponen Utama
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

// 2. Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 3. Ambil ID Barang dari URL
if (!isset($_GET['id'])) {
    header("Location: my_items.php");
    exit;
}

$id_barang = $_GET['id'];
$user_id   = $_SESSION['user_id'];
$role      = $_SESSION['role']; // Ambil role user saat ini

// ---------------------------------------------------------
// 4. QUERY DATA LAMA & VALIDASI AKSES (PERBAIKAN UTAMA)
// ---------------------------------------------------------

if ($role === 'admin') {
    // ADMIN: Boleh akses semua barang berdasarkan ID saja
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->bind_param("i", $id_barang);
} else {
    // DONATUR: Wajib cek kepemilikan (user_id)
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id_barang, $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Jika data tidak ditemukan (ID salah atau Donatur mencoba akses punya orang lain)
if (!$data) {
    echo "<script>
            alert('Akses Ditolak! Data tidak ditemukan atau bukan milik Anda.'); 
            window.location='my_items.php';
          </script>";
    exit;
}

// Validasi Tambahan: Barang yang sudah disalurkan TIDAK BOLEH diedit lagi
// Kecuali Admin mungkin butuh override, tapi amannya kita kunci dulu.
if ($data['status'] == 'disalurkan') {
    echo "<script>
            alert('Barang yang sudah disalurkan tidak dapat diedit demi arsip data!'); 
            window.location='my_items.php';
          </script>";
    exit;
}

// ---------------------------------------------------------
// 5. PROSES UPDATE DATA
// ---------------------------------------------------------
if (isset($_POST['update'])) {
    
    $nama = htmlspecialchars($_POST['nama_barang']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);

    // Logika Gambar
    $foto_lama = $data['gambar'];
    $foto_baru_name = $_FILES['gambar']['name'];
    $foto_baru_tmp  = $_FILES['gambar']['tmp_name'];

    // Skenario 1: User Upload Foto Baru
    if (!empty($foto_baru_name)) {
        
        $nama_file_unik = time() . '_' . $foto_baru_name;
        $path_upload = "../assets/uploads/" . $nama_file_unik;

        // Upload file baru
        if (move_uploaded_file($foto_baru_tmp, $path_upload)) {
            
            // Hapus Foto Lama (Jika ada fisik filenya)
            if (file_exists("../assets/uploads/" . $foto_lama) && $foto_lama != "") {
                unlink("../assets/uploads/" . $foto_lama); 
            }

            // Update Database (Ganti nama foto)
            $updateQ = $conn->prepare("UPDATE items SET nama_barang=?, kategori=?, deskripsi=?, gambar=? WHERE id=?");
            $updateQ->bind_param("ssssi", $nama, $kategori, $deskripsi, $nama_file_unik, $id_barang);
        }
    } 
    // Skenario 2: User Tidak Ganti Foto
    else {
        // Update Database (Tanpa ganti foto)
        $updateQ = $conn->prepare("UPDATE items SET nama_barang=?, kategori=?, deskripsi=? WHERE id=?");
        $updateQ->bind_param("sssi", $nama, $kategori, $deskripsi, $id_barang);
    }

    // Eksekusi Update
    if ($updateQ->execute()) {
        // Redirect cerdas: Admin balik ke index, Donatur balik ke my_items
        $redirectPage = ($role === 'admin') ? 'index.php' : 'my_items.php';
        
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location='$redirectPage?msg=Sukses update data';
              </script>";
    } else {
        $error = "Gagal mengupdate data!";
    }
}
?>

<!-- --------------------------------------------------------- -->
<!-- TAMPILAN FORM EDIT -->
<!-- --------------------------------------------------------- -->

<div class="container">
    <div class="card" style="max-width: 800px; margin: 40px auto;">
        
        <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <h3>✏️ Edit Barang Donasi</h3>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="grid">
                <!-- Kolom Kiri -->
                <div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" required value="<?= $data['nama_barang']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori" required>
                            <option value="<?= $data['kategori']; ?>"><?= $data['kategori']; ?> (Saat Ini)</option>
                            <option value="">-- Ubah Kategori --</option>
                            <option value="Pakaian">Pakaian</option>
                            <option value="Buku">Buku & Alat Tulis</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Peralatan Rumah">Peralatan Rumah</option>
                            <option value="Mainan">Mainan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" rows="5" required><?= $data['deskripsi']; ?></textarea>
                    </div>
                </div>

                <!-- Kolom Kanan (Gambar) -->
                <div>
                    <div class="form-group">
                        <label>Foto Barang</label>
                        <div style="background: #f8f9fa; padding: 20px; text-align: center; border: 2px dashed #ddd; border-radius: 8px;">
                            
                            <!-- Foto Lama -->
                            <p style="font-size: 12px; color: #666; margin-bottom: 5px;">Foto Saat Ini:</p>
                            <?php if(!empty($data['gambar'])): ?>
                                <img src="../assets/uploads/<?= $data['gambar']; ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; margin-bottom: 15px;">
                            <?php else: ?>
                                <p style="color: #999; font-size: 12px;">(Tidak ada foto)</p>
                            <?php endif; ?>

                            <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">

                            <!-- Preview Foto Baru -->
                            <img id="imagePreview" src="" style="width: 100%; max-height: 200px; object-fit: cover; display: none; margin-bottom: 15px; border-radius: 6px;">

                            <label style="font-size:14px; display:block; margin-bottom:5px;">Ganti Foto (Opsional):</label>
                            <input type="file" name="gambar" id="imageUpload" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; text-align: right;">
                <!-- Tombol Batal Cerdas: Balik ke halaman yang sesuai role -->
                <?php $backLink = ($role === 'admin') ? 'index.php' : 'my_items.php'; ?>
                
                <a href="<?= $backLink; ?>" class="btn btn-warning" style="margin-right: 10px;">Batal</a>
                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>

<?php include "../includes/footer.php"; ?>