<?php
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);
    $role     = 'user';

    $ktpFile = '';
    if (!empty($_FILES['ktp']['name'])) {
        $targetDir = "uploads/ktp/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['ktp']['name']);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png'];

        if (in_array($fileType, $allowed)) {
            if (move_uploaded_file($_FILES['ktp']['tmp_name'], $targetFile)) {
                $ktpFile = $fileName;
            } else {
                $message = "❌ Gagal upload KTP.";
            }
        } else {
            $message = "❌ Format KTP harus JPG/PNG.";
        }
    }

    if (strlen($password) < 8) {
        $message = "⚠️ Password minimal 8 karakter.";
    }

    if (empty($message) && $username && $password && $email && $phone && $address && $ktpFile) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $message = "❌ Username sudah digunakan.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email, phone, address, ktp) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$username, $hash, $role, $email, $phone, $address, $ktpFile])) {
                header("Location: login.php?status=success");
                exit;
            } else {
                header("Location: login.php?status=failed");
                exit;
            }
        }
    } else {
        if (empty($message)) $message = "⚠️ Isi semua field dengan benar.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Rental Mobil</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: url('img/WhatsApp\ Image\ 2025-10-13\ at\ 07.32.11_e5bb7ce5.jpg') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }


        /* 🎬 ANIMASI MUNCUL */
        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            text-align: center;
            animation: fadeSlideUp 0.9s ease forwards;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* ✨ Efek hover container */
        .register-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
            font-weight: 700;
        }

        form input,
        form textarea,
        form button {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            transition: 0.3s;
        }

        form input:focus,
        form textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
            outline: none;
        }

        form button {
            background: #007bff;
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s, transform 0.2s;
        }

        form button:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }

        .message {
            margin: 15px 0;
            font-size: 14px;
            color: red;
            min-height: 18px;
            transition: opacity 0.4s ease;
        }

        .login-link {
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Daftar Akun</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password (min 8 karakter)" required minlength="8">
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Nomor HP" required>
            <textarea name="address" placeholder="Alamat lengkap" required></textarea>
            <label>Upload Foto KTP (JPG/PNG):</label>
            <input type="file" name="ktp" accept=".jpg,.jpeg,.png" required>
            <button type="submit">Daftar</button>
        </form>
        <div class="message"><?= $message ?></div>
        <p>Sudah punya akun? <a href="login.php" class="login-link">Login di sini</a></p>
    </div>
</body>

</html>
