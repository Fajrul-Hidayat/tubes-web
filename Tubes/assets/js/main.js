document.addEventListener("DOMContentLoaded", function () {
  const alerts = document.querySelectorAll(".alert-success, .alert-error");

  alerts.forEach(function (alert) {
    alert.style.transition = "opacity 0.5s ease, transform 0.5s ease";

    setTimeout(function () {
      alert.style.opacity = "0";
      alert.style.transform = "translateY(-10px)";

      setTimeout(function () {
        alert.remove();
      }, 500);
    }, 3500);
  });

  const deleteButtons = document.querySelectorAll(
    ".btn-delete, .delete-confirm"
  );

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      const konfirmasi = confirm(
        "PERINGATAN:\n\nApakah Anda yakin ingin menghapus data ini?\nTindakan ini tidak dapat dibatalkan."
      );

      if (!konfirmasi) {
        e.preventDefault();
      }
    });
  });

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
          previewContainer.style.border = "2px solid #00a896";
        });

        reader.readAsDataURL(file);
      } else {
        previewContainer.style.display = "none";
      }
    });
  }

  const currentLocation = location.href;
  const menuItem = document.querySelectorAll(".nav-menu a");
  const menuLength = menuItem.length;

  for (let i = 0; i < menuLength; i++) {
    if (menuItem[i].href === currentLocation) {
      menuItem[i].classList.add("active");
      menuItem[i].style.fontWeight = "700";
      menuItem[i].style.borderBottom = "2px solid white";
    }
  }
});

function confirmLogout(e) {
  if (!confirm("Apakah Anda yakin ingin keluar dari sistem?")) {
    e.preventDefault();
  }
}
