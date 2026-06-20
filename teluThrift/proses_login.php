<?php
session_start();
require_once "config/koneksi.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || ($_POST['aksi'] ?? '') != 'login') {
    header("Location: login.php");
    exit();
}

$login = mysqli_real_escape_string($conn, trim($_POST['login'] ?? ''));
$password = $_POST['password'] ?? '';

if (empty($login) || empty($password)) {
    header("Location: login.php?error=empty");
    exit();
}

$sql  = "SELECT * FROM users WHERE username = '$login' OR email = '$login'";
$res  = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama'] = $user['nama'];
    header("Location: index.php");
    exit();
}

header("Location: login.php?error=invalid");
exit();
