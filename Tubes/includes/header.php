<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUBES - Platform Berbagi Kebaikan</title>
    
    <!-- FONT GOOGLE: POPPINS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- STYLE UTAMA (PERBAIKAN PATH) -->
    <!-- Mengarah ke folder 'TUBES' sesuai nama folder di laptop Anda -->
    <link rel="stylesheet" href="/TUBES/assets/css/style.css">

    <!-- Ikon di Tab Browser (Opsional) -->
    <link rel="shortcut icon" href="/TUBES/assets/img/favicon.ico" type="image/x-icon">

    <?php 
    // Memulai sesi secara global di semua halaman yang memanggil header ini
    // Logika ini mencegah error "Session already started"
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    ?>
</head>
<body>
<!-- Body dibuka di sini, akan ditutup di footer.php -->