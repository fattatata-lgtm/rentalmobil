<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];

if ($role === 'admin') {
    header("Location: index_admin.php");
    exit;
} else {
    header("Location: index_user.php");
    exit;
}
