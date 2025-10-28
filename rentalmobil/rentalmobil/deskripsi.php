<?php
require 'config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil no_plat dari URL
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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($car['merk']) ?> - Deskripsi Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fb;
            font-family: 'Poppins', sans-serif;
        }

        /* Buat kartu agak ke bawah */
        .content-wrapper {
            margin-top: 100px;
            margin-bottom: 60px;
        }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        /* Gambar tidak crop, proporsional */
        .card img {
            width: 100%;
            height: auto; /* tinggi mengikuti proporsi asli */
            display: block;
            max-height: 400px; /* opsional, supaya tidak terlalu tinggi */
            object-fit: contain; /* tampil utuh */
        }

        .card-body {
            padding: 20px;
        }

        .car-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #222;
        }

        .price {
            color: #007BFF;
            font-weight: 700;
            font-size: 1rem;
        }

        .car-detail p {
            margin: 4px 0;
            font-size: 0.9rem;
            color: #555;
        }

        .btn-primary {
            background-color: #007BFF;
            border: none;
            padding: 9px 16px;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-gray {
            background-color: #adb5bd;
            color: #fff;
            font-size: 0.9rem;
        }

        .btn-back {
            background-color: #f1f3f5;
            color: #333;
            font-size: 0.9rem;
        }

        .btn-back:hover {
            background-color: #e9ecef;
        }

        footer {
            text-align: center;
            padding: 15px;
            color: #777;
            font-size: 0.85rem;
            background-color: #f8f9fa;
            border-top: 1px solid #e2e6ea;
        }
    </style>
</head>

<body>
    <div class="container content-wrapper">
        <div class="card mx-auto" style="max-width: 600px;">
            <img src="uploads/<?= !empty($car['gambar']) ? htmlspecialchars($car['gambar']) : 'no-image.png' ?>"
                alt="<?= htmlspecialchars($car['merk']) ?>">

            <div class="card-body">
                <h2 class="car-title mb-1"><?= htmlspecialchars($car['merk']) ?> - <?= htmlspecialchars($car['tipe']) ?></h2>
                <p class="price mb-2">Rp <?= number_format($car['harga_per_hari'], 0, ',', '.') ?>/hari</p>

                <div class="car-detail mb-3">
                    <p><strong>No. Plat:</strong> <?= htmlspecialchars($car['no_plat']) ?></p>
                    <p><strong>Tahun:</strong> <?= htmlspecialchars($car['tahun']) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($car['status']) ?></p>
                    <p><strong>Deskripsi:</strong> <?= htmlspecialchars($car['deskripsi'] ?? '-') ?></p>
                </div>

                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <?php if ($car['status'] === 'available' || $car['status'] === 'tersedia'): ?>
                        <a href="sewa.php?no_plat=<?= urlencode($car['no_plat']) ?>" class="btn btn-primary">
                            🚗 Pesan
                        </a>
                    <?php else: ?>
                        <button class="btn btn-gray" disabled>❌ Tidak Tersedia</button>
                    <?php endif; ?>

                    <a href="index_user.php" class="btn btn-back">⬅️ Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
