<?php
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

// Cek Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// -----------------------------------------------------------
// AMBIL DATA UNTUK DROPDOWN
// -----------------------------------------------------------

// 1. Ambil Barang yang STATUSNYA 'TERSEDIA' saja
// Barang yang sudah disalurkan TIDAK BOLEH muncul lagi
$qItems = mysqli_query($conn, "SELECT id, nama_barang, kategori FROM items WHERE status = 'tersedia' ORDER BY nama_barang ASC");

// 2. Ambil Data Penerima
$qRecipients = mysqli_query($conn, "SELECT id, nama_penerima, alamat FROM recipients ORDER BY nama_penerima ASC");


// -----------------------------------------------------------
// PROSES PENYIMPANAN TRANSAKSI
// -----------------------------------------------------------
if (isset($_POST['simpan'])) {
    $item_id = $_POST['item_id'];
    $recipient_id = $_POST['recipient_id'];
    $tanggal = $_POST['tanggal_salur'];
    $keterangan = htmlspecialchars($_POST['keterangan']);

    // Validasi Sederhana
    if (empty($item_id) || empty($recipient_id) || empty($tanggal)) {
        $error = "Mohon lengkapi semua data!";
    } else {
        // Mulai Transaksi Database (Agar aman, pakai Transaction Commit/Rollback opsional, tapi native biasa juga oke)
        
        // A. Simpan ke Tabel Transactions
        $stmt = $conn->prepare("INSERT INTO transactions (item_id, recipient_id, tanggal_salur, keterangan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $item_id, $recipient_id, $tanggal, $keterangan);
        
        if ($stmt->execute()) {
            
            // B. UPDATE STATUS BARANG JADI 'DISALURKAN'
            // Ini langkah krusial agar barang tidak bisa dipilih lagi
            $updateItem = mysqli_query($conn, "UPDATE items SET status = 'disalurkan' WHERE id = '$item_id'");

            if ($updateItem) {
                echo "<script>
                        alert('Penyaluran Berhasil Dicatat!');
                        window.location='index.php?msg=Sukses mencatat transaksi';
                      </script>";
            } else {
                $error = "Gagal mengupdate status barang!";
            }
        } else {
            $error = "Gagal menyimpan transaksi!";
        }
    }
}
?>

<div class="container">
    <div class="card" style="max-width: 700px; margin: 40px auto;">
        
        <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <h3>🤝 Catat Penyaluran Barang</h3>
            <p style="color: #888;">Pastikan barang fisik benar-benar akan diserahkan.</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($qItems) == 0): ?>
            <!-- Jika tidak ada barang tersedia -->
            <div style="text-align: center; padding: 20px; background: #fff3cd; color: #856404; border-radius: 8px;">
                ⚠️ <strong>Stok Kosong!</strong><br>
                Tidak ada barang berstatus 'Tersedia' untuk disalurkan.<br>
                <a href="../items/index.php" style="text-decoration: underline;">Cek Data Barang</a>
            </div>
        <?php else: ?>

            <form action="" method="POST">
                
                <div class="form-group">
                    <label>Pilih Barang (Hanya yang Tersedia)</label>
                    <select name="item_id" required style="font-size: 16px;">
                        <option value="">-- Pilih Barang --</option>
                        <?php while($item = mysqli_fetch_assoc($qItems)): ?>
                            <option value="<?= $item['id']; ?>">
                                <?= htmlspecialchars($item['nama_barang']); ?> (<?= $item['kategori']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pilih Penerima Manfaat</label>
                    <select name="recipient_id" required style="font-size: 16px;">
                        <option value="">-- Pilih Penerima --</option>
                        <?php while($rcp = mysqli_fetch_assoc($qRecipients)): ?>
                            <option value="<?= $rcp['id']; ?>">
                                <?= htmlspecialchars($rcp['nama_penerima']); ?> - <?= substr($rcp['alamat'], 0, 30); ?>...
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <small style="display: block; margin-top: 5px;">
                        Penerima belum ada? <a href="../recipients/create.php" target="_blank">Tambah Baru</a>
                    </small>
                </div>

                <div class="form-group">
                    <label>Tanggal Penyaluran</label>
                    <input type="date" name="tanggal_salur" required value="<?= date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label>Keterangan / Berita Acara (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Contoh: Diserahkan langsung oleh tim lapangan..."></textarea>
                </div>

                <div style="text-align: right; margin-top: 25px;">
                    <a href="index.php" class="btn btn-warning">Batal</a>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan Transaksi</button>
                </div>

            </form>

        <?php endif; ?>

    </div>
</div>

<?php include "../includes/footer.php"; ?>