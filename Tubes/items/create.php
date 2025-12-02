<?php
// 1. Panggil komponen utama
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

// 2. Cek Login (Hanya user login yang boleh akses)
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

// ---------------------------------------------------------
// LOGIKA PENYIMPANAN DATA (BACKEND)
// ---------------------------------------------------------
if (isset($_POST['simpan'])) {
    
    // Ambil data dari form
    $user_id = $_SESSION['user_id']; // Siapa yang upload?
    $nama_barang = htmlspecialchars($_POST['nama_barang']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $status = 'tersedia';

    // LOGIKA UPLOAD GAMBAR
    $foto = $_FILES['gambar']['name'];
    $tmp_foto = $_FILES['gambar']['tmp_name'];
    
    // Rename nama foto agar unik (mencegah nama file bentrok)
    // Contoh: baju.jpg menjadi 1709283_baju.jpg
    $foto_baru = time() . '_' . $foto;
    $path = "../assets/uploads/" . $foto_baru;

    // Cek apakah user memilih gambar?
    if (!empty($foto)) {
        // Pindahkan file ke folder uploads
        if (move_uploaded_file($tmp_foto, $path)) {
            
            // Masukkan ke Database
            $stmt = $conn->prepare("INSERT INTO items (user_id, nama_barang, kategori, deskripsi, gambar, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $user_id, $nama_barang, $kategori, $deskripsi, $foto_baru, $status);
            
            if ($stmt->execute()) {
                // Berhasil
                echo "<script>
                        alert('Barang berhasil didonasikan! Terima kasih.');
                        window.location='my_items.php'; 
                      </script>";
            } else {
                $error = "Gagal menyimpan ke database!";
            }

        } else {
            $error = "Gagal upload gambar! Periksa permission folder.";
        }
    } else {
        $error = "Wajib menyertakan foto barang.";
    }
}
?>

<!-- --------------------------------------------------------- -->
<!-- TAMPILAN FORM (FRONTEND) -->
<!-- --------------------------------------------------------- -->

<div class="container">
    <div class="card" style="max-width: 800px; margin: 40px auto;">
        
        <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <h3>➕ Donasi Barang Baru</h3>
            <p style="color: #666; font-size: 14px;">Isi detail barang dengan jelas agar penerima memahaminya.</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <!-- PENTING: enctype="multipart/form-data" wajib ada untuk upload file -->
        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="grid">
                <!-- Kolom Kiri: Input Teks -->
                <div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" required placeholder="Contoh: Kemeja Batik Lengan Panjang">
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Pakaian">Pakaian</option>
                            <option value="Buku">Buku & Alat Tulis</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Peralatan Rumah">Peralatan Rumah</option>
                            <option value="Mainan">Mainan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi & Kondisi</label>
                        <textarea name="deskripsi" rows="5" required placeholder="Jelaskan kondisi barang, ukuran, minus, dll..."></textarea>
                    </div>
                </div>

                <!-- Kolom Kanan: Upload Foto -->
                <div>
                    <div class="form-group">
                        <label>Foto Barang</label>
                        <div style="background: #f8f9fa; padding: 20px; text-align: center; border: 2px dashed #ddd; border-radius: 8px;">
                            
                            <!-- Tempat Preview Gambar (Script JS di Footer akan menangani ini) -->
                            <img id="imagePreview" src="" style="width: 100%; max-height: 200px; object-fit: cover; display: none; margin-bottom: 15px; border-radius: 6px;">
                            
                            <input type="file" name="gambar" id="imageUpload" accept="image/*" required>
                            <p style="font-size: 12px; color: #999; margin-top: 5px;">Format: JPG, PNG, JPEG (Max 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; text-align: right;">
                <a href="../dashboard.php" class="btn btn-warning" style="margin-right: 10px;">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan Barang</button>
            </div>

        </form>
    </div>
</div>

<?php include "../includes/footer.php"; ?>