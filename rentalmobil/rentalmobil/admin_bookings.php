<?php
require 'config.php'; // Menghubungkan ke konfigurasi database dan session

// ===============================
// Hanya admin yang bisa akses halaman ini
// ===============================
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Jika bukan admin, langsung diarahkan ke login
    header("Location: login.php");
    exit;
}

// ===============================
// ADMIN ACTION
// ===============================

// 1. Setujui booking
if (isset($_GET['approve'])) {
    $id = (int) $_GET['approve']; // Ambil ID booking dari URL dan konversi ke integer

    // Ambil no_plat mobil dari booking
    $stmt = $pdo->prepare("SELECT no_plat FROM bookings WHERE id = ?");
    $stmt->execute([$id]);
    $booking = $stmt->fetch();

    if ($booking) {
        $no_plat = $booking['no_plat'];

        // Update status booking menjadi approved
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'approved' WHERE id = ?");
        $stmt->execute([$id]);

        // Update status mobil menjadi booked
        $stmt = $pdo->prepare("UPDATE cars SET status = 'booked' WHERE no_plat = ?");
        $stmt->execute([$no_plat]);
    }

    // Setelah aksi selesai, redirect kembali ke halaman admin bookings
    header("Location: admin_bookings.php");
    exit;
}

// 2. Tolak booking
if (isset($_GET['reject'])) {
    $id = (int) $_GET['reject']; // Ambil ID booking dari URL

    // Ambil no_plat mobil dari booking
    $stmt = $pdo->prepare("SELECT no_plat FROM bookings WHERE id = ?");
    $stmt->execute([$id]);
    $booking = $stmt->fetch();

    if ($booking) {
        $no_plat = $booking['no_plat'];

        // Update status booking menjadi rejected
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$id]);

        // Kembalikan status mobil menjadi available
        $stmt = $pdo->prepare("UPDATE cars SET status = 'available' WHERE no_plat = ?");
        $stmt->execute([$no_plat]);
    }

    header("Location: admin_bookings.php");
    exit;
}

// 3. Hapus booking
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete']; // Ambil ID booking dari URL

    // Hapus data booking
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: admin_bookings.php");
    exit;
}

// ===============================
// Ambil semua data booking untuk ditampilkan
// ===============================
$stmt = $pdo->query("
    SELECT 
        b.*, 
        u.username, u.email, u.phone, u.address, u.ktp,
        c.merk, c.tipe, c.gambar
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN cars c ON b.no_plat = c.no_plat
    ORDER BY b.id DESC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC); // Simpan hasil query dalam array
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Booking - Admin</title>
    <style>
        /* ====== Style Umum ====== */
        body {
            font-family: Arial, sans-serif;
            margin: 25px;
            background: #f8f8f8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #eee;
        }

        img {
            max-width: 90px;
            border-radius: 5px;
        }

        h1 {
            color: #333;
        }

        /* Warna status berbeda */
        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-approved {
            color: green;
            font-weight: bold;
        }

        .status-rejected {
            color: red;
            font-weight: bold;
        }

        /* Tombol aksi */
        a.button {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            margin: 2px;
            display: inline-block;
        }

        .approve {
            background: green;
            color: white;
        }

        .reject {
            background: red;
            color: white;
        }

        .delete {
            background: gray;
            color: white;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .back:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

    <h1>📋 Kelola Booking Pengguna</h1>

    <table>
        <tr>
            <th>Nama Pengguna</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>KTP</th>
            <th>Mobil</th>
            <th>Gambar Mobil</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Kembali</th> <!-- Kolom baru -->
            <th>Lama Sewa</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Aksi</th>
            <th>Hapus</th>
        </tr>

        <?php if (count($bookings) > 0): ?>
            <?php foreach ($bookings as $b): ?>
                <tr>
                    <!-- Informasi pengguna -->
                    <td><?= htmlspecialchars($b['username']) ?></td>
                    <td><?= htmlspecialchars($b['email']) ?></td>
                    <td><?= htmlspecialchars($b['phone']) ?></td>
                    <td><?= htmlspecialchars($b['address']) ?></td>

                    <!-- Gambar KTP -->
                    <td>
                        <?php if ($b['ktp']): ?>
                            <img src="uploads/ktp/<?= htmlspecialchars($b['ktp']) ?>" alt="KTP">
                        <?php else: ?>
                            Tidak ada
                        <?php endif; ?>
                    </td>

                    <!-- Informasi mobil -->
                    <td><?= htmlspecialchars($b['merk'] . ' ' . $b['tipe']) ?></td>
                    <td>
                        <?php if ($b['gambar']): ?>
                            <img src="uploads/<?= htmlspecialchars($b['gambar']) ?>" alt="Mobil">
                        <?php endif; ?>
                    </td>

                    <!-- Tanggal mulai booking -->
                    <td><?= htmlspecialchars($b['tanggal_mulai']) ?></td>

                    <!-- Tanggal kembali otomatis -->
                    <td>
                        <?php
                        $tanggal_kembali = date('Y-m-d', strtotime($b['tanggal_mulai'] . ' + ' . $b['lama_hari'] . ' days'));
                        echo $tanggal_kembali;
                        ?>
                    </td>

                    <!-- Lama sewa -->
                    <td><?= htmlspecialchars($b['lama_hari']) ?> hari</td>

                    <!-- Total harga -->
                    <td>Rp <?= number_format($b['total_harga'], 0, ',', '.') ?></td>

                    <!-- Status booking -->
                    <td class="status-<?= htmlspecialchars($b['status']) ?>">
                        <?= ucfirst($b['status']) ?>
                    </td>

                    <!-- Aksi setujui/tolak -->
                    <td>
                        <?php if ($b['status'] === 'pending'): ?>
                            <a href="?approve=<?= $b['id'] ?>" class="button approve">Setujui</a>
                            <a href="?reject=<?= $b['id'] ?>" class="button reject">Tolak</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>

                    <!-- Hapus booking -->
                    <td>
                        <a href="?delete=<?= $b['id'] ?>" class="button delete" onclick="return confirm('Yakin ingin menghapus booking ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Jika tidak ada data booking -->
            <tr>
                <td colspan="14">Belum ada data booking.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Tombol kembali ke dashboard admin -->
    <a href="index_admin.php" class="back">⬅ Kembali ke Dashboard Admin</a>

</body>

</html>