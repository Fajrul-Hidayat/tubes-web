<!-- 
      SPACER FOOTER
      Memberi sedikit jarak agar konten tidak mepet dengan footer
    -->
    <div style="margin-top: 50px;"></div>

    <!-- FOOTER START -->
    <footer class="footer">
        <div class="container">
            <!-- Copyright Dinamis (Tahun otomatis update) -->
            <p>
                &copy; <?= date('Y'); ?> <strong>TUBES</strong>. 
                Platform Berbagi Barang Bekas Layak Pakai.
            </p>
            
            <p style="font-size: 13px; margin-top: 10px; color: #aaa;">
                Dibuat untuk Tugas Besar Pemrograman Web <br>
                <em>"Berbagi bukan tentang seberapa besar dan seberapa berharganya hal yang kau beri, 
                namun seberapa tulus dan ikhlasnya apa yang ingin kau beri."</em>
            </p>
        </div>
    </footer>
    <!-- FOOTER END -->

    <!-- ======================================================= -->
    <!-- MAIN JAVASCRIPT -->
    <!-- 
       File ini menangani:
       1. Menghilangkan alert error/sukses secara otomatis (3 detik)
       2. Konfirmasi pop-up saat tombol hapus ditekan
       3. Preview gambar saat upload
    -->
    <!-- ======================================================= -->
    <script src="/TUBES/assets/js/main.js"></script>

</body>
</html>
