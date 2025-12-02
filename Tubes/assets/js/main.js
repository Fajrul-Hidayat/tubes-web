/**
 * ECO DONASI — MAIN JAVASCRIPT
 * Menangani interaksi UI, animasi alert, validasi, dan preview gambar.
 */

document.addEventListener("DOMContentLoaded", function () {
  // ---------------------------------------------------------
  // 1. AUTO HIDE ALERTS
  // Menghilangkan pesan sukses/error secara otomatis setelah 3 detik
  // ---------------------------------------------------------
  const alerts = document.querySelectorAll(".alert-success, .alert-error");

  alerts.forEach(function (alert) {
    // Tambahkan transisi CSS agar halus
    alert.style.transition = "opacity 0.5s ease, transform 0.5s ease";

    setTimeout(function () {
      // Mulai memudar
      alert.style.opacity = "0";
      alert.style.transform = "translateY(-10px)";

      // Hapus elemen dari DOM setelah animasi selesai
      setTimeout(function () {
        alert.remove();
      }, 500);
    }, 3500); // Waktu tunggu 3.5 detik
  });

  // ---------------------------------------------------------
  // 2. KONFIRMASI HAPUS (DELETE CONFIRMATION)
  // Mencegah penghapusan data yang tidak disengaja
  // ---------------------------------------------------------
  const deleteButtons = document.querySelectorAll(
    ".btn-delete, .delete-confirm"
  );

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      const konfirmasi = confirm(
        "⚠️ PERINGATAN:\n\nApakah Anda yakin ingin menghapus data ini?\nTindakan ini tidak dapat dibatalkan."
      );

      if (!konfirmasi) {
        e.preventDefault(); // Batalkan aksi jika user klik Cancel
      }
    });
  });

  // ---------------------------------------------------------
  // 3. IMAGE PREVIEW (Untuk Form Upload Barang)
  // Menampilkan gambar yang dipilih sebelum di-upload
  // ---------------------------------------------------------
  const uploadInput = document.getElementById("imageUpload");
  const previewContainer = document.getElementById("imagePreview");

  if (uploadInput && previewContainer) {
    uploadInput.addEventListener("change", function () {
      const file = this.files[0];

      if (file) {
        const reader = new FileReader();

        reader.addEventListener("load", function () {
          previewContainer.src = this.result;
          previewContainer.style.display = "block";
          previewContainer.style.border = "2px solid #00a896"; // Border tosca saat ada gambar
        });

        reader.readAsDataURL(file);
      } else {
        previewContainer.style.display = "none";
      }
    });
  }

  // ---------------------------------------------------------
  // 4. ACTIVE NAVBAR STATE
  // Menandai menu yang sedang aktif berdasarkan URL
  // ---------------------------------------------------------
  const currentLocation = location.href;
  const menuItem = document.querySelectorAll(".nav-menu a");
  const menuLength = menuItem.length;

  for (let i = 0; i < menuLength; i++) {
    if (menuItem[i].href === currentLocation) {
      menuItem[i].classList.add("active");
      // Tambahkan style inline atau class khusus di CSS untuk .active
      menuItem[i].style.fontWeight = "700";
      menuItem[i].style.borderBottom = "2px solid white";
    }
  }
});

// ---------------------------------------------------------
// 5. FUNGSI GLOBAL KONFIRMASI (Opsional untuk dipanggil via onclick)
// ---------------------------------------------------------
function confirmLogout(e) {
  if (!confirm("Apakah Anda yakin ingin keluar dari sistem?")) {
    e.preventDefault();
  }
}
