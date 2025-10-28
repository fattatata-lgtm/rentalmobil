<?php
require 'config.php';

$message = '';

if (isset($_GET['status'])) {
    $statusMessages = [
        'success' => "<span class='success-msg'>✅ Registrasi berhasil, silakan login.</span>",
        'failed'  => "<span class='error-msg'>❌ Registrasi gagal, coba lagi.</span>"
    ];
    $message = $statusMessages[$_GET['status']] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect berdasarkan role
        if ($user['role'] === 'admin') {
            header("Location: index_admin.php");
        } else {
            header("Location: index_user.php");
        }
        exit;
    } else {
        $message = "<span class='error-msg'>❌ Username atau password salah.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Rental Mobil</title>
    <style>
        /* ===== Global ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: url('img/WhatsApp\ Image\ 2025-10-13\ at\ 07.32.11_e5bb7ce5.jpg') no-repeat center center;
            background-size: cover;
        }

        /* ===== Navbar ===== */
        header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #222;
            padding: 15px 20px;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header .welcome {
            font-size: 16px;
        }

        /* Hamburger menu */
        .hamburger {
            display: flex;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
            transition: transform 0.3s ease;
        }

        .hamburger.active div:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active div:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active div:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        .hamburger div {
            width: 25px;
            height: 3px;
            background: #fff;
            border-radius: 2px;
            transition: 0.3s ease;
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 20px;
            background: #333;
            flex-direction: column;
            padding: 10px;
            border-radius: 8px;
            z-index: 1001;
        }

        .dropdown.show {
            display: flex;
        }

        .dropdown a {
            color: #fff;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .dropdown a:hover {
            background: #fff;
            color: #222;
            transform: translateX(5px);
        }

        /* ===== Login Box ===== */
        .login-container {
            margin-top: 50px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
            opacity: 0;
            transform: translateY(-50px);
            animation: fadeSlide 0.8s forwards;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
            font-weight: 700;
        }

        /* Input fields */
        form .input-group {
            position: relative;
            margin: 10px 0;
        }

        form input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            transition: 0.3s;
        }

        form input:focus {
            border-color: #007bff;
            box-shadow: 0 0 12px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        /* Toggle password icon */
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            width: 24px;
            height: 24px;
            fill: #888;
            transition: fill 0.3s;
        }

        .toggle-password:hover {
            fill: #007bff;
        }

        /* Button with gradient hover */
        form button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background: linear-gradient(90deg, #007bff, #0056b3);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        form button:hover {
            background: linear-gradient(90deg, #0056b3, #007bff);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Messages */
        .message {
            margin: 20px 0 10px;
            font-size: 14px;
        }

        .error-msg {
            color: red;
        }

        .success-msg {
            color: green;
        }

        /* Register link */
        .register-link {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
            text-align: center;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .register-link a:hover {
            text-decoration: underline;
            color: #0056b3;
        }

        /* ===== Animations ===== */
        @keyframes fadeSlide {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="welcome">Selamat Datang, Silahkan login / daftar jika belum memiliki akun</div>
        <div class="hamburger" onclick="toggleDropdown()">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </header>

    <div class="dropdown" id="dropdownMenu">
        <a href="index.php">Kembali ke Home</a>
    </div>

    <div class="login-container">
        <h2>Login Akun</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <svg id="eyeClosed" class="toggle-password" viewBox="0 0 24 24">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM2 2l20 20" stroke="#888" stroke-width="2" />
                </svg>
                <svg id="eyeOpen" class="toggle-password" style="display:none;" viewBox="0 0 24 24">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 13c-3.03 0-5.5-2.47-5.5-5.5S8.97 6.5 12 6.5 17.5 8.97 17.5 12 15.03 17.5 12 17.5zm0-9a3.5 3.5 0 100 7 3.5 3.5 0 000-7z" />
                </svg>
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="message"><?= $message ?></div>

        <p class="register-link">
            Belum Punya Akun? <a href="register.php">Daftar sekarang.</a>
        </p>
    </div>

    <script>
        // Toggle password visibility
        const password = document.querySelector('#password');
        const eyeOpen = document.querySelector('#eyeOpen');
        const eyeClosed = document.querySelector('#eyeClosed');

        function togglePassword() {
            if (password.type === "password") {
                password.type = "text";
                eyeClosed.style.display = "none";
                eyeOpen.style.display = "block";
            } else {
                password.type = "password";
                eyeClosed.style.display = "block";
                eyeOpen.style.display = "none";
            }
        }
        eyeOpen.addEventListener('click', togglePassword);
        eyeClosed.addEventListener('click', togglePassword);

        // Hamburger toggle dropdown & animation
        const hamburger = document.querySelector('.hamburger');

        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('show');
            hamburger.classList.toggle('active');
        }
    </script>

</body>

</html>