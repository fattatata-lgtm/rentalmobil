<?php
require 'config.php';

// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data riwayat booking user
try {
    $stmt = $pdo->prepare("
        SELECT b.id, b.no_plat, b.tanggal_mulai, b.lama_hari, b.total_harga, 
               b.status, b.created_at, c.merk, c.tipe, c.gambar
        FROM bookings b
        JOIN cars c ON b.no_plat = c.no_plat
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error mengambil data riwayat: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking - Rental Mobil</title>
    <link rel="stylesheet" href="style3.css">
    <style>
        header {
            background-color: #222;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header .logo {
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
            color: #fff;
        }

        nav {
            display: flex;
            gap: 25px;
        }

        nav .nav-link {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 5px;
            transition: background 0.3s, color 0.3s;
        }

        nav .nav-link:hover {
            background-color: #fff;
            color: #222;
        }

        nav .nav-link.contact {
            border: 1px solid #fff;
        }

        nav .nav-link.contact:hover {
            background-color: #25D366;
            /* warna hijau WA */
            color: #fff;
            border-color: #25D366;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px 20px;
            }

            nav {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }
        }




        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
            padding: 0 20px 40px;
            margin-top: 40px;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .button {
            display: inline-block;
            padding: 8px 15px;
            margin-top: 10px;
            background-color: #007bff;
            /* biru */
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #0056b3;
            /* biru lebih gelap saat hover */
        }

        .disabled {
            background-color: #6c757d;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <header>
        <a href="index_user.php" class="logo">🚘 RentalMobilCAFA</a>
        <nav>
            <a href="index_user.php" class="nav-link">Daftar Mobil</a>
            <a href="riwayat.php" class="nav-link">Detail Booking</a>
            <a href="tentangg1.php" class="nav-link active">Tentang Kami</a>
            <a href="https://wa.me/6285855273945" target="_blank" class="nav-link contact">Hubungi Kami</a>
        </nav>
    </header>

    <div class="user-info">
        Halo, <?= htmlspecialchars($_SESSION['username']) ?> |
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <!-- Riwayat Utama -->
    <h2 style="margin-left:15px;">Detail Booking Anda</h2>

    <div class="grid">
        <?php if (count($riwayat) > 0): ?>
            <?php foreach ($riwayat as $r): ?>
                <div class="card">
                    <?php if (!empty($r['gambar'])): ?>
                        <img src="uploads/<?= htmlspecialchars($r['gambar']) ?>" alt="<?= htmlspecialchars($r['merk']) ?>">
                    <?php else: ?>
                        <img src="uploads/no-image.png" alt="No Image">
                    <?php endif; ?>

                    <div class="card-body">
                        <h3><?= htmlspecialchars($r['merk']) ?> <?= htmlspecialchars($r['tipe']) ?></h3>
                        <p><b>No Plat:</b> <?= htmlspecialchars($r['no_plat']) ?></p>
                        <p><b>Tanggal Mulai:</b> <?= htmlspecialchars($r['tanggal_mulai']) ?></p>
                        <p><b>Lama Sewa:</b> <?= htmlspecialchars($r['lama_hari']) ?> hari</p>
                        <p><b>Total Harga:</b> Rp<?= number_format($r['total_harga'], 0, ',', '.') ?></p>

                        <a href="detail_riwayat.php?id=<?= $r['id'] ?>" class="button">🔍 Lihat Detail</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; width:100%;">Belum ada riwayat booking.</p>
        <?php endif; ?>
    </div>

    <!-- ====== Footer ====== -->
    <footer style="background-color:#222; color:#fff; text-align:center; padding:20px 10px; margin-top:30px;">
        <p>&copy; <?= date('Y') ?> RentalMobilCAFA. Semua hak dilindungi.</p>
        <p>
            <a href="index_user.php" style="color:#fff; text-decoration:none; margin:0 10px;">Beranda</a> |
            <a href="riwayat.php" style="color:#fff; text-decoration:none; margin:0 10px;">Riwayat Booking</a> |
            <a href="tentangg1.php" style="color:#fff; text-decoration:none; margin:0 10px;">Tentang Kami</a>
        </p>
        <p>Hubungi kami:
            <a href="https://wa.me/6285855273945" target="_blank" style="color:#25D366; text-decoration:none;">WhatsApp</a>
        </p>
    </footer>

</body>

</html>