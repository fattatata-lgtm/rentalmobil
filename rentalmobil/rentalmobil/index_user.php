<?php
require 'config.php';

// Cek login & role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil daftar mobil
$stmt = $pdo->query("SELECT * FROM cars");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil semua booking milik user
$stmt = $pdo->prepare("SELECT no_plat, status FROM bookings WHERE user_id = ?");
$stmt->execute([$user_id]);
$userBookings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // hasil: [no_plat => status]
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Mobil - User</title>
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

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
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
    <!-- ====== Header ====== -->
    <header>
        <a href="index_user.php" class="logo">🚘 RentalMobilCAFA</a>
        <nav>
            <a href="index_user.php" class="nav-link">Daftar Mobil</a>
            <a href="riwayat.php" class="nav-link">Detail Booking</a>
            <a href="tentangg1.php" class="nav-link active">Tentang Kami</a>
            <a href="https://wa.me/6285855273945" target="_blank" class="nav-link contact">Hubungi Kami</a>
        </nav>
    </header>

    <!-- ====== User Info ====== -->
    <div class="user-info">
        Halo, <?= htmlspecialchars($_SESSION['username']) ?> |
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <!-- ====== Daftar Mobil ====== -->
    <h2 style="margin-left:15px;">Daftar Mobil</h2>
    <div class="grid-container">
        <?php foreach ($cars as $car):
            $no_plat = $car['no_plat'];
            $statusMobil = strtolower(trim($car['status']));
            $statusBooking = $userBookings[$no_plat] ?? null;
        ?>
            <div class="card">
                <a href="deskripsi.php?no_plat=<?= urlencode($no_plat) ?>">
                    <?php if (!empty($car['gambar'])): ?>
                        <img src="uploads/<?= htmlspecialchars($car['gambar']) ?>" alt="<?= htmlspecialchars($car['merk']) ?>">
                    <?php else: ?>
                        <img src="uploads/no-image.png" alt="No Image">
                    <?php endif; ?>
                </a>
                <h3><?= htmlspecialchars($car['merk']) ?> - <?= htmlspecialchars($car['tipe']) ?></h3>
                <p><b>Tahun:</b> <?= htmlspecialchars($car['tahun']) ?></p>
                <p><b>Harga:</b> Rp <?= number_format($car['harga_per_hari'], 0, ',', '.') ?>/hari</p>

                <?php
                // Jika booking user masih pending
                if ($statusBooking === 'pending'): ?>
                    <p><b>Status:</b> <span style="color:orange;">🔶 Menunggu Persetujuan</span></p>
                    <button class="disabled" disabled>Pending</button>

                    <?php
                // Jika mobil sedang disewa oleh user
                elseif ($statusBooking === 'approved'):
                    if ($statusMobil === 'available' || $statusMobil === 'tersedia'): ?>
                        <p><b>Status:</b> <span style="color:green;">🟩 Tersedia </span></p>
                        <a href="deskripsi.php?no_plat=<?= urlencode($no_plat) ?>" class="button">🚗 Sewa Lagi</a>
                    <?php else: ?>
                        <p><b>Status:</b> <span style="color:green;">🟢 Disetujui silahkan hubungi kami</span></p>
                        <button class="disabled" disabled>Mobil Disewa</button>
                    <?php endif; ?>

                <?php
                // Jika booking ditolak
                elseif ($statusBooking === 'rejected'): ?>
                    <p><b>Status:</b> <span style="color:blue;">🟦 Ditolak</span></p>
                    <a href="deskripsi.php?no_plat=<?= urlencode($no_plat) ?>" class="button">🚗 Sewa Lagi</a>

                    <?php
                // Jika user belum pernah booking mobil ini
                else:
                    if ($statusMobil === 'available' || $statusMobil === 'tersedia'): ?>
                        <p><b>Status:</b> <span style="color:green;">🟩 Tersedia</span></p>
                        <a href="deskripsi.php?no_plat=<?= urlencode($no_plat) ?>" class="button">🚗 Sewa Sekarang</a>
                    <?php else: ?>
                        <p><b>Status:</b> <span style="color:red;">❌ Tidak Tersedia</span></p>
                        <button class="disabled" disabled>Sedang Disewa</button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
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
           <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20ingin%20bertanya%20tentang%20rental%20mobil." target="_blank">
  <button>Hubungi Kami</button>
</a>

        </p>
    </footer>


</body>

</html>