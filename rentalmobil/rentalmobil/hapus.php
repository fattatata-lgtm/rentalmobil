<?php
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];

  // Cek apakah mobil masih digunakan di tabel bookings
  $check = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE car_id = ?");
  $check->execute([$id]);
  $count = $check->fetchColumn();

  if ($count > 0) {
    echo "<script>
                alert('❌ Mobil tidak dapat dihapus karena masih memiliki data booking!');
                window.location='admin_cars.php';
              </script>";
    exit;
  }

  // Kalau tidak ada booking, baru hapus
  $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
  $stmt->execute([$id]);

  echo "<script>
            alert('✅ Mobil berhasil dihapus!');
            window.location='admin_cars.php';
          </script>";
  exit;
}
