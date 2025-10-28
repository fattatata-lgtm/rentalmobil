<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tentang Kami - RentalMobilKu</title>
    <link rel="stylesheet" href="style3.css">
    <style>
        /* NAVBAAARRRR */
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


        /* ====== Konten Tentang Kami ====== */
        .container {
            width: 100%;
            max-width: none;
            padding: 60px 80px;
            background: #f8f8f8;
            box-sizing: border-box;
        }

        .container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 26px;
            color: #222;
        }

        .container h3 {
            margin-top: 30px;
            color: #111;
        }

        .container p,
        .container ul {
            font-size: 16px;
            line-height: 1.8;
            max-width: 100%;
            margin-bottom: 15px;
        }

        .container ul {
            padding-left: 25px;
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            .user-info {
                text-align: left;
                padding: 10px 20px;
            }
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

    <!-- ====== Konten Utama ====== -->
    <div class="container">
        <h2>Tentang Kami</h2>

        <p><strong>RentalMobilCAfA</strong> adalah penyedia layanan rental mobil terpercaya di Indonesia.
            Kami menawarkan pengalaman sewa kendaraan yang aman, nyaman, dan mudah.
            Dengan armada mobil berkualitas yang selalu dalam kondisi terbaik, kami siap
            menemani perjalanan Anda kapan pun dan di mana pun.</p>

        <h3>Visi</h3>
        <p>Menjadi perusahaan rental mobil terbaik di Indonesia yang mengutamakan
            pelayanan, kepercayaan, dan kepuasan pelanggan.</p>

        <h3>Misi</h3>
        <ul>
            <li>Menyediakan kendaraan dengan kondisi prima dan selalu siap pakai.</li>
            <li>Memberikan pelayanan cepat, ramah, dan profesional.</li>
            <li>Menawarkan harga kompetitif dengan transparansi biaya.</li>
            <li>Membangun hubungan jangka panjang dengan pelanggan.</li>
        </ul>

        <h3>Komitmen Kami</h3>
        <p>Kami berkomitmen untuk memberikan kenyamanan dan keamanan bagi setiap pelanggan.
            Dengan dukungan tim profesional dan sistem pemesanan online yang mudah,
            <strong>RentalMobilCAFA</strong> menjadi solusi terbaik untuk kebutuhan transportasi Anda.
        </p>
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