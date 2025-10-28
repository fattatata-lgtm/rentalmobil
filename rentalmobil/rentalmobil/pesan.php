<?php
require 'config.php'; // koneksi database

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// =======================
// NOTIF MOBIL BELUM SERVICE >1 BULAN
// =======================
$notif_service = [];
$stmt = $pdo->query("SELECT merk, tipe, no_plat, last_service FROM cars");
$cars = $stmt->fetchAll();

$today = new DateTime();
foreach ($cars as $car) {
    if (!$car['last_service']) {
        $notif_service[] = "{$car['merk']} {$car['tipe']} {$car['no_plat']} (Belum Pernah Service)";
    } else {
        $lastService = new DateTime($car['last_service']);
        $diff = $today->diff($lastService)->days;
        if ($diff > 30) {
            $notif_service[] = "{$car['merk']} {$car['tipe']} {$car['no_plat']} (Terakhir Service {$car['last_service']})";
        }
    }
}

// =======================
// NOTIF MOBIL BELUM BAYAR PAJAK
// =======================
$notif_pajak = [];
$stmt = $pdo->query("SELECT merk, tipe, no_plat, pajak_status FROM cars");
$pajak_cars = $stmt->fetchAll();

foreach ($pajak_cars as $car) {
    if (!$car['pajak_status']) {
        $notif_pajak[] = "{$car['merk']} {$car['tipe']} {$car['no_plat']} (Belum pernah bayar pajak)";
        continue;
    }
    $lastPajak = new DateTime($car['pajak_status']);
    $diffYears = $today->diff($lastPajak)->y;

    if ($diffYears >= 1 && $diffYears < 5) {
        $notif_pajak[] = "{$car['merk']} {$car['tipe']} {$car['no_plat']} (Sudah lebih dari 1 tahun, pajak tahunan)";
    }
    if ($diffYears >= 5) {
        $notif_pajak[] = "{$car['merk']} {$car['tipe']} {$car['no_plat']} (Sudah lebih dari 5 tahun, pajak 5 tahunan + ganti plat + cek fisik)";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Notifikasi Mobil</title>
<style>
body { font-family:sans-serif; background:#f4f6f9; padding:20px; }
.container { max-width:800px; margin:30px auto; }
.notif { padding:15px 20px; margin-bottom:15px; border-radius:8px; }
.notif-service { background:#fee2e2; border-left:5px solid #ef4444; }
.notif-pajak { background:#fef3c7; border-left:5px solid #f59e0b; }
.notif-success { background:#d1fae5; border-left:5px solid #10b981; }
.back-btn { display:block; width:max-content; margin:20px auto; padding:8px 16px; background:#3b82f6; color:#fff; border-radius:8px; font-weight:600; text-align:center; text-decoration:none; }
.back-btn:hover { background:#1e40af; }
h2 { margin-top:40px; color:#1e293b; }
</style>
</head>
<body>

<div class="container">

<h2>Mobil Belum di Service </h2>
<?php if(count($notif_service) > 0): ?>
    <?php foreach($notif_service as $mobil): ?>
        <div class="notif notif-service">Mobil <strong><?= htmlspecialchars($mobil) ?></strong> perlu di-service.</div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="notif notif-success">Semua mobil sudah di-service ✅</div>
<?php endif; ?>

<h2>Mobil Belum Bayar Pajak</h2>
<?php if(count($notif_pajak) > 0): ?>
    <?php foreach($notif_pajak as $mobil): ?>
        <div class="notif notif-pajak">Mobil <strong><?= htmlspecialchars($mobil) ?></strong></div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="notif notif-success">Semua mobil sudah bayar pajak ✅</div>
<?php endif; ?>

</div>

<a href="index_admin.php" class="back-btn">⬅ Kembali ke Dashboard Admin</a>

</body>
</html>
