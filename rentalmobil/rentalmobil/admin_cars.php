<?php
require 'config.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = "";

// Fungsi Upload Gambar
function uploadImage($fileInputName, $oldFilename = null)
{
    if (empty($_FILES[$fileInputName]['name'])) {
        return $oldFilename;
    }

    $fileName = $_FILES[$fileInputName]['name'];
    $fileTmp  = $_FILES[$fileInputName]['tmp_name'];
    $error    = $_FILES[$fileInputName]['error'];

    if ($error !== UPLOAD_ERR_OK) {
        return $oldFilename;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        return false;
    }

    $targetDir = __DIR__ . '/uploads/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $newName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
    $targetPath = $targetDir . $newName;

    if (move_uploaded_file($fileTmp, $targetPath)) {
        if ($oldFilename && file_exists($targetDir . $oldFilename)) {
            unlink($targetDir . $oldFilename);
        }
        return $newName;
    }

    return $oldFilename;
}

// =====================
// TAMBAH MOBIL
// =====================
if (isset($_POST['add'])) {
    $no_plat = trim($_POST['no_plat']);
    $merk = trim($_POST['merk']);
    $tipe = trim($_POST['tipe']);
    $tahun = (int)$_POST['tahun'];
    $harga_per_hari = (float)$_POST['harga_per_hari'];
    $status = $_POST['status'] ?? 'available';
    $last_service = $_POST['last_service'] ?: null;
    $pajak_status = $_POST['pajak_status'];
    $deskripsi = $_POST['deskripsi'];
    $gambar = uploadImage('gambar');

    $stmt = $pdo->prepare("INSERT INTO cars 
        (no_plat, merk, tipe, tahun, harga_per_hari, status, last_service, pajak_status, deskripsi, gambar)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$no_plat, $merk, $tipe, $tahun, $harga_per_hari, $status, $last_service, $pajak_status, $deskripsi, $gambar]);
    header("Location: admin_cars.php?msg=add_ok");
    exit;
}

// =====================
// EDIT MOBIL
// =====================
if (isset($_POST['edit'])) {
    $no_plat = $_POST['no_plat'];
    $merk = $_POST['merk'];
    $tipe = $_POST['tipe'];
    $tahun = (int)$_POST['tahun'];
    $harga_per_hari = (float)$_POST['harga_per_hari'];
    $status = $_POST['status'];
    $last_service = $_POST['last_service'] ?: null;
    $pajak_status = $_POST['pajak_status'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $pdo->prepare("SELECT gambar FROM cars WHERE no_plat=?");
    $stmt->execute([$no_plat]);
    $oldCar = $stmt->fetch();
    $oldGambar = $oldCar ? $oldCar['gambar'] : null;

    $gambar = uploadImage('gambar', $oldGambar);

    $stmt = $pdo->prepare("UPDATE cars SET 
        merk=?, tipe=?, tahun=?, harga_per_hari=?, status=?, last_service=?, pajak_status=?, deskripsi=?, gambar=?
        WHERE no_plat=?");
    $stmt->execute([$merk, $tipe, $tahun, $harga_per_hari, $status, $last_service, $pajak_status, $deskripsi, $gambar, $no_plat]);
    header("Location: admin_cars.php?msg=edit_ok");
    exit;
}

// =====================
// HAPUS MOBIL
// =====================
if (isset($_POST['delete'])) {
    $no_plat = $_POST['no_plat'];

    $stmt = $pdo->prepare("SELECT gambar FROM cars WHERE no_plat=?");
    $stmt->execute([$no_plat]);
    $car = $stmt->fetch();
    if ($car && $car['gambar'] && file_exists(__DIR__ . '/uploads/' . $car['gambar'])) {
        unlink(__DIR__ . '/uploads/' . $car['gambar']);
    }

    $stmt = $pdo->prepare("DELETE FROM cars WHERE no_plat=?");
    $stmt->execute([$no_plat]);
    header("Location: admin_cars.php?msg=del_ok");
    exit;
}

// Ambil semua mobil
$stmt = $pdo->query("SELECT * FROM cars ORDER BY tahun DESC");
$cars = $stmt->fetchAll();

// Pesan sukses/error
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'add_ok') $message = "✅ Mobil berhasil ditambahkan.";
    if ($_GET['msg'] === 'edit_ok') $message = "✅ Data mobil berhasil diupdate.";
    if ($_GET['msg'] === 'del_ok') $message = "✅ Mobil berhasil dihapus.";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Mobil - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #1e293b;
        }

        .msg {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .error {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .card h2 {
            margin-top: 0;
            color: #1e293b;
        }

        .card form label {
            display: block;
            margin-top: 10px;
            font-weight: 600;
        }

        .card form input,
        .card form select,
        .card form textarea {
            width: 100%;
            padding: 8px;
            margin: 4px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .card form small {
            display: block;
            color: #6b7280;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .card form button {
            background-color: #3b82f6;
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
        }

        .card form button:hover {
            background-color: #1e40af;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        th,
        td {
            padding: 12px 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background-color: #3b82f6;
            color: #fff;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        .btn-save {
            background-color: #10b981;
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin-bottom: 4px;
        }

        .btn-del {
            background-color: #ef4444;
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-save:hover {
            background-color: #047857;
        }

        .btn-del:hover {
            background-color: #b91c1c;
        }

        img {
            max-width: 80px;
            border-radius: 6px;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            background-color: #3b82f6;
            color: #fff;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-back:hover {
            background-color: #1e40af;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Kelola Mobil</h1>

        <?php if ($message): ?>
            <div class="msg <?= strpos($message, '❌') !== false ? 'error' : 'success' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>Tambah Mobil</h2>
            <form method="post" enctype="multipart/form-data">
                <label>No. Plat:</label>
                <input type="text" name="no_plat" placeholder="No. Plat" required>

                <label>Merk:</label>
                <input type="text" name="merk" placeholder="Merk" required>

                <label>Tipe:</label>
                <input type="text" name="tipe" placeholder="Tipe" required>

                <label>Tahun:</label>
                <input type="number" name="tahun" placeholder="Tahun" required>

                <label>Harga per Hari:</label>
                <input type="number" name="harga_per_hari" placeholder="Harga per Hari" required>

                <label>Terakhir Service:</label>
                <input type="date" name="last_service" placeholder="Terakhir Service">

                <label>Terakhir Bayar Pajak:</label>
                <input type="date" name="pajak_status" placeholder="Terakhir Bayar Pajak">

                <label>Gambar:</label>
                <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp">

                <label>Deskripsi:</label>
                <textarea name="deskripsi" placeholder="Deskripsi"></textarea>

                <button type="submit" name="add">Tambah Mobil</button>
            </form>
        </div>

        <h2>Daftar Mobil</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No. Plat</th>
                        <th>Merk</th>
                        <th>Tipe</th>
                        <th>Tahun</th>
                        <th>Harga/Hari</th>
                        <th>Status</th>
                        <th>Pajak</th>
                        <th>Terakhir Service</th>
                        <th>Gambar</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                        <tr>
                            <form method="post" enctype="multipart/form-data">
                                <td><input type="text" name="no_plat" value="<?= htmlspecialchars($car['no_plat']) ?>" readonly></td>
                                <td><input type="text" name="merk" value="<?= htmlspecialchars($car['merk']) ?>"></td>
                                <td><input type="text" name="tipe" value="<?= htmlspecialchars($car['tipe']) ?>"></td>
                                <td><input type="number" name="tahun" value="<?= htmlspecialchars($car['tahun']) ?>"></td>
                                <td><input type="number" name="harga_per_hari" value="<?= htmlspecialchars($car['harga_per_hari']) ?>"></td>
                                <td>
                                    <select name="status">
                                        <option value="available" <?= $car['status'] == 'available' ? 'selected' : '' ?>>Tersedia</option>
                                        <option value="booked" <?= $car['status'] == 'booked' ? 'selected' : '' ?>>Disewa</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="date" name="pajak_status" value="<?= htmlspecialchars($car['pajak_status']) ?>">
                                </td>
                                <td>
                                    <input type="date" name="last_service" value="<?= htmlspecialchars($car['last_service']) ?>">
                                </td>
                                <td>
                                    <?php if ($car['gambar']): ?>
                                        <img src="uploads/<?= htmlspecialchars($car['gambar']) ?>" alt="">
                                    <?php endif; ?>
                                    <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp">
                                </td>
                                <td><textarea name="deskripsi"><?= htmlspecialchars($car['deskripsi']) ?></textarea></td>
                                <td>
                                    <button type="submit" name="edit" class="btn-save">Simpan</button>
                                    <button type="submit" name="delete" class="btn-del" onclick="return confirm('Hapus mobil ini?')">Hapus</button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a href="index_admin.php" class="btn-back">⬅ Kembali ke Dashboard Admin</a>
    </div>
</body>

</html>