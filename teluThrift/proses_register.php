<?php
session_start();
require_once "config/koneksi.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || ($_POST['aksi'] ?? '') != 'register') {
    header("Location: register.php");
    exit();
}

$nama = mysqli_real_escape_string($conn, trim($_POST['nama'] ?? ''));
$username = mysqli_real_escape_string($conn, trim($_POST['username'] ?? ''));
$email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
$nim = mysqli_real_escape_string($conn, trim($_POST['nim'] ?? ''));
$password = $_POST['password'] ?? '';
$konfirm  = $_POST['konfirm'] ?? '';

if (empty($nama) || empty($username) || empty($email) || empty($password)) {
    header("Location: register.php?error=empty");
    exit();
} elseif (strlen($password) < 6) {
    header("Location: register.php?error=short");
    exit();
} elseif ($password != $konfirm) {
    header("Location: register.php?error=mismatch");
    exit();
} else {
    $cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' OR username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        header("Location: register.php?error=taken");
        exit();
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $avatar = '';
        if (!empty($_FILES['avatar']['tmp_name'])) {
            $tipe_ok = ['image/jpeg', 'image/png', 'image/webp'];
            $tipe = mime_content_type($_FILES['avatar']['tmp_name']);
            if (in_array($tipe, $tipe_ok)) {
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $avatar = 'avatar_' . time() . '_' . rand(100, 999) . '.' . strtolower($ext);
                move_uploaded_file($_FILES['avatar']['tmp_name'], 'uploads/' . $avatar);
            }
        }

        $sql = "INSERT INTO users (username, email, password, nama, nim, avatar) VALUES ('$username', '$email', '$hash', '$nama', '$nim', '$avatar')";
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php?register=success");
            exit();
        }

        header("Location: register.php?error=failed");
        exit();
    }
}
