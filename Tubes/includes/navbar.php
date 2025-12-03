<nav class="navbar">
    <a href="/TUBES/index.php" class="nav-logo">
        <span>WEB Donasi</span>
    </a>

    <ul class="nav-menu">
        
        <li><a href="/TUBES/index.php">Beranda</a></li>
        
        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
            
            <li><a href="/TUBES/dashboard.php">Dashboard</a></li>

            <?php if ($_SESSION['role'] == 'donatur'): ?>
                <li><a href="/TUBES/items/my_items.php">Donasi Saya</a></li>
            <?php endif; ?>

            <?php if ($_SESSION['role'] == 'admin'): ?>
                <li><a href="/TUBES/items/index.php">Kelola Barang</a></li>
                <li><a href="/TUBES/recipients/index.php">Data Penerima</a></li>
                <li><a href="/TUBES/transactions/index.php">Penyaluran</a></li>
            <?php endif; ?>

            <li>
                <a href="/TUBES/auth/logout.php" class="btn logout" onclick="confirmLogout(event)">
                    Logout
                </a>
            </li>

        <?php else: ?>
            
            <li>
                <a href="/TUBES/auth/login.php" class="btn login">
                    Login / Daftar
                </a>
            </li>

        <?php endif; ?>

    </ul>
</nav>


<div style="height: 80px;"></div>