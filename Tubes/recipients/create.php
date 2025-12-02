<?php
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

// Cek Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// Proses Simpan Data
if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama_penerima']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $hp = htmlspecialchars($_POST['no_hp']);
    $kebutuhan = htmlspecialchars($_POST['kebutuhan_utama']);

    $stmt = $conn->prepare("INSERT INTO recipients (nama_penerima, alamat, no_hp, kebutuhan_utama) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $alamat, $hp, $kebutuhan);

    if ($stmt->execute()) {
        echo "<script>
                alert('Data Penerima berhasil ditambahkan!');
                window.location='index.php?msg=Sukses tambah data';
              </script>";
    } else {
        $error = "Gagal menyimpan data!";
    }
}
?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 40px auto;">
        
        <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <h3>➕ Tambah Data Penerima</h3>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            
            <div class="form-group">
                <label>Nama Penerima (Yayasan/Individu)</label>
                <input type="text" name="nama_penerima" required placeholder="Contoh: Panti Asuhan Kasih Bunda">
            </div>

            <div class="form-group">
                <label>Nomor HP / Telepon</label>
                <input type="text" name="no_hp" required placeholder="0812xxxx">
            </div>

            <div class="form-group">
                <label>Kebutuhan Utama</label>
                <input type="text" name="kebutuhan_utama" required placeholder="Contoh: Pakaian Layak, Buku, Sembako">
                <p style="font-size: 12px; color: #888;">Apa yang paling mereka butuhkan saat ini?</p>
            </div>

            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" rows="4" required placeholder="Jalan, RT/RW, Kelurahan..."></textarea>
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <a href="index.php" class="btn btn-warning">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
            </div>

        </form>
    </div>
</div>

<?php include "../includes/footer.php"; ?>