<?php
require 'config.php';

// Cek login user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Ambil ID booking
if (!isset($_GET['id'])) {
    die("ID booking tidak ditemukan.");
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil data booking
try {
    $stmt = $pdo->prepare("
        SELECT b.*, c.merk, c.tipe, c.gambar, u.username 
        FROM bookings b
        JOIN cars c ON b.no_plat = c.no_plat
        JOIN users u ON b.user_id = u.id
        WHERE b.id = ? AND b.user_id = ?
    ");
    $stmt->execute([$id, $user_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$data) die("Data tidak ditemukan.");
} catch (PDOException $e) {
    die("Error DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nota Booking - Rental Mobil</title>

    <!-- Tambahkan library html2pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 25px;
        }

        .nota {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }

        .nota h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .nota hr {
            margin: 10px 0 15px;
            border: none;
            border-top: 2px solid #eee;
        }

        .nota table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .nota td {
            padding: 6px 0;
            vertical-align: top;
        }

        .nota .center {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn:hover {
            background: #0056b3;
        }

        .back {
            background: #6c757d;
        }

        .back:hover {
            background: #5a6268;
        }

        @media print {

            .btn-container,
            header {
                display: none;
            }

            body {
                background: white;
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>🚘 Rental Mobil</h1>
    </header>

    <div class="nota" id="nota">
        <h2>Nota Penyewaan Mobil</h2>
        <hr>
        <table>
            <tr>
                <td><b>Nama Penyewa</b></td>
                <td>: <?= htmlspecialchars($data['username']) ?></td>
            </tr>
            <tr>
                <td><b>Mobil</b></td>
                <td>: <?= htmlspecialchars($data['merk'] . ' ' . $data['tipe']) ?></td>
            </tr>
            <tr>
                <td><b>No. Plat</b></td>
                <td>: <?= htmlspecialchars($data['no_plat']) ?></td>
            </tr>
            <tr>
                <td><b>Tanggal Mulai</b></td>
                <td>: <?= htmlspecialchars($data['tanggal_mulai']) ?></td>
            </tr>
            <tr>
                <td><b>Tanggal Kembali</b></td>
                <td>: <?= htmlspecialchars((new DateTime($data['tanggal_mulai']))->modify("+" . $data['lama_hari'] . " days")->format('Y-m-d')) ?></td>

            </tr>
            <tr>
                <td><b>Lama Sewa</b></td>
                <td>: <?= htmlspecialchars($data['lama_hari']) ?> hari</td>
            </tr>
            <tr>
                <td><b>Total Harga</b></td>
                <td>: Rp<?= number_format($data['total_harga'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td><b>Tanggal Transaksi</b></td>
                <td>: <?= htmlspecialchars($data['created_at']) ?></td>
            </tr>

        </table>
        <hr>
        <p class="center">Terima kasih telah menggunakan layanan kami!</p>
    </div>

    <div class="btn-container">
        <a href="riwayat.php" class="btn back">⬅ Kembali</a>
        <a href="#" class="btn" id="download">⬇ Download PDF</a>
    </div>

    <script>
        document.getElementById("download").addEventListener("click", function() {
            const element = document.getElementById("nota");
            const opt = {
                margin: 10,
                filename: 'Nota_Rental_<?= $data['id'] ?>.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };
            html2pdf().set(opt).from(element).save();
        });
    </script>

</body>

</html>