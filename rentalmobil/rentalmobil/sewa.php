<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil no_plat mobil dari URL
if (!isset($_GET['no_plat'])) {
    echo "Mobil tidak ditemukan.";
    exit;
}
$no_plat = $_GET['no_plat'];

// Ambil data mobil
$stmt = $pdo->prepare("SELECT * FROM cars WHERE no_plat = ?");
$stmt->execute([$no_plat]);
$car = $stmt->fetch();

if (!$car) {
    echo "Data mobil tidak ada.";
    exit;
}

// Kalau form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id       = $_SESSION['user_id'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $lama_hari     = (int) $_POST['lama_hari'];

    // Hitung total harga
    $total_harga = $lama_hari * $car['harga_per_hari'];

    // Simpan ke tabel bookings (status pending)
    $stmt = $pdo->prepare("INSERT INTO bookings 
        (user_id, no_plat, tanggal_mulai, lama_hari, total_harga, status) 
        VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$user_id, $no_plat, $tanggal_mulai, $lama_hari, $total_harga]);

    echo "<script>
        alert('✅ Pesanan berhasil dikirim! Tunggu konfirmasi admin.');
        window.location='index_user.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Mobil</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: #f5f7fa;
        }

        .container {
            max-width: 480px;
            margin: 60px auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .header {
            background: #007BFF;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
        }

        .content {
            padding: 25px;
        }

        .content h3 {
            margin-bottom: 8px;
            color: #333;
        }

        .price {
            color: #007BFF;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.2s;
        }

        input:focus {
            border-color: #007BFF;
            outline: none;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.4);
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary {
            background: #007BFF;
            color: white;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-back {
            background: #e9ecef;
            color: #333;
            font-weight: 500;
            margin-top: 10px;
        }

        .btn-back:hover {
            background: #d6d8db;
        }

        .footer {
            text-align: center;
            padding: 10px;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">Form Penyewaan Mobil</div>
        <div class="content">
            <h3><?= htmlspecialchars($car['merk']) ?> - <?= htmlspecialchars($car['tipe']) ?></h3>
            <p class="price">Rp <?= number_format($car['harga_per_hari'], 0, ',', '.') ?> / hari</p>

            <form method="POST">
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" required>
                </div>

                <div class="form-group">
                    <label for="lama_hari">Lama Sewa (hari)</label>
                    <input type="number" id="lama_hari" name="lama_hari" min="1" required>
                </div>

                <button type="submit" class="btn btn-primary">✅ Konfirmasi Pesanan</button>
            </form>

            <!-- Tombol kembali ke deskripsi -->
            <a href="deskripsi.php?no_plat=<?= urlencode($car['no_plat']) ?>">
                <button class="btn btn-back">⬅️ Kembali ke Deskripsi</button>
            </a>
        </div>
    </div>

    
</body>

</html>
