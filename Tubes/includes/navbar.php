<nav class="navbar">
    <!-- LOGO (KIRI) -->
    <a href="/TUBES/index.php" class="nav-logo">
        <!-- Ikon Daun (Emoji) agar sesuai tema Eco -->
        <span>🌱 TUBES</span>
    </a>

    <!-- MENU UTAMA (KANAN) -->
    <ul class="nav-menu">
        
        <!-- 1. Menu yang SELALU muncul (Public) -->
        <li><a href="/TUBES/index.php">Beranda</a></li>
        
        <!-- CATATAN: Link 'Katalog' sudah dihapus dari sini karena foldernya dihapus -->

        <!-- 2. Logika PHP: Cek apakah user sudah login? -->
        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
            
            <!-- A. Menu Dasar (Semua User Login) -->
            <li><a href="/TUBES/dashboard.php">Dashboard</a></li>

            <!-- B. Menu Khusus DONATUR -->
            <?php if ($_SESSION['role'] == 'donatur'): ?>
                <li><a href="/TUBES/items/my_items.php">Donasi Saya</a></li>
            <?php endif; ?>

            <!-- C. Menu Khusus ADMIN -->
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <li><a href="/TUBES/items/index.php">Kelola Barang</a></li>
                <li><a href="/TUBES/recipients/index.php">Data Penerima</a></li>
                <li><a href="/TUBES/transactions/index.php">Penyaluran</a></li>
            <?php endif; ?>

            <!-- D. Tombol Logout -->
            <li>
                <a href="/TUBES/auth/logout.php" class="btn logout" onclick="confirmLogout(event)">
                    Logout
                </a>
            </li>

        <?php else: ?>
            
            <!-- 3. Jika BELUM Login (Tamu) -->
            <li>
                <a href="/TUBES/auth/login.php" class="btn login">
                    Login / Daftar
                </a>
            </li>

        <?php endif; ?>

    </ul>
</nav>

<!-- 
    SPACER DIV (PENTING)
    Mendorong konten ke bawah agar tidak tertutup navbar yang posisinya fixed.
-->
<div style="height: 80px;"></div>