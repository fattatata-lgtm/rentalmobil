<?php
require 'config.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil semua mobil dari database
$stmt = $pdo->query("SELECT * FROM cars");
$cars = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Rental Mobil</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* ===== Global ===== */
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background-color: #f4f6f9;
            color: #333;
        }

        a {
            text-decoration: none;
        }

        /* ===== Header ===== */
        header {
            background-color: #1e293b;
            color: #fff;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .logout-btn {
            background-color: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background-color: #b91c1c;
        }

        /* ===== Admin Info ===== */
        .admin-info {
            text-align: right;
            padding: 15px 40px;
            font-size: 15px;
            color: #555;
        }

        /* ===== Dashboard Menu ===== */
        .dashboard-menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }

        .menu-card {
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            width: 180px;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .menu-card h3 {
            margin: 10px 0 0 0;
            font-size: 18px;
            color: #1e293b;
        }

        .menu-card p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #64748b;
        }

        /* ===== Cars Table ===== */
        .table-container {
            width: 95%;
            max-width: 1200px;
            margin: 30px auto;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 14px 16px;
            text-align: center;
        }

        th {
            background-color: #3b82f6;
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        .status-available {
            background-color: #10b981;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-booked {
            background-color: #f59e0b;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        h2 {
            text-align: center;
            margin-top: 40px;
            color: #1e293b;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header>
        <h1>🚘 Dashboard Admin</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <!-- ADMIN INFO -->
    <div class="admin-info">
        Halo, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> (Admin)
    </div>

    <!-- DASHBOARD MENU -->
    <div class="dashboard-menu">
        <a href="admin_cars.php" class="menu-card">
            <img src="https://img.icons8.com/ios-filled/50/3b82f6/car.png"/>
            <h3>Kelola Mobil</h3>
            <p>Tambah, edit, hapus mobil</p>
        </a>
        <a href="pesan.php" class="menu-card">
            <img src="https://img.icons8.com/ios-filled/50/3b82f6/chat.png"/>
            <h3>Notif</h3>
            <p>Lihat notif bila ada mobil yg belum diservice dan belum bayar pajak</p>
        </a>
        <a href="admin_bookings.php" class="menu-card">
            <img src="https://img.icons8.com/ios-filled/50/3b82f6/booking.png"/>
            <h3>Cek Booking</h3>
            <p>Lihat semua booking</p>
        </a>
    </div>

    <!-- DAFTAR MOBIL -->
    <h2>Daftar Mobil</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Merk</th>
                    <th>Tipe</th>
                    <th>Tahun</th>
                    <th>Harga / Hari</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?= htmlspecialchars($car['merk']) ?></td>
                        <td><?= htmlspecialchars($car['tipe']) ?></td>
                        <td><?= htmlspecialchars($car['tahun']) ?></td>
                        <td>Rp <?= number_format($car['harga_per_hari'], 0, ',', '.') ?></td>
                        <td>
                            <?php if($car['status'] === 'Tersedia'): ?>
                                <span class="status-available"><?= htmlspecialchars($car['status']) ?></span>
                            <?php else: ?>
                                <span class="status-booked"><?= htmlspecialchars($car['status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
