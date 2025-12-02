<?php
include "../includes/header.php";
include "../includes/navbar.php";
require "../config/database.php";

// 1. Cek Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// 2. Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = $_GET['id'];

// 3. Ambil Data Lama
$stmt = $conn->prepare("SELECT * FROM recipients WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

// Jika data tidak ada
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// 4. Proses Update
if (isset($_POST['update'])) {
    $nama = htmlspecialchars($_POST['nama_penerima']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $hp = htmlspecialchars($_POST['no_hp']);
    $kebutuhan = htmlspecialchars($_POST['kebutuhan_utama']);

    $updateQ = $conn->prepare("UPDATE recipients SET nama_penerima=?, alamat=?, no_hp=?, kebutuhan_utama=? WHERE id=?");
    $updateQ->bind_param("ssssi", $nama, $alamat, $hp, $kebutuhan, $id);

    if ($updateQ->execute()) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location='index.php?msg=Sukses update data';
              </script>";
    } else {
        $error = "Gagal update data!";
    }
}
?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 40px auto;">
        
        <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <h3>✏️ Edit Data Penerima</h3>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            
            <div class="form-group">
                <label>Nama Penerima</label>
                <input type="text" name="nama_penerima" required value="<?= htmlspecialchars($data['nama_penerima']); ?>">
            </div>

            <div class="form-group">
                <label>Nomor HP</label>
                <input type="text" name="no_hp" required value="<?= htmlspecialchars($data['no_hp']); ?>">
            </div>

            <div class="form-group">
                <label>Kebutuhan Utama</label>
                <input type="text" name="kebutuhan_utama" required value="<?= htmlspecialchars($data['kebutuhan_utama']); ?>">
            </div>

            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" rows="4" required><?= htmlspecialchars($data['alamat']); ?></textarea>
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <a href="index.php" class="btn btn-warning">Batal</a>
                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>

<?php include "../includes/footer.php"; ?>