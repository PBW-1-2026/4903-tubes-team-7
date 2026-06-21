<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || ($_POST['aksi'] ?? '') != 'edit') {
    header("Location: profil.php");
    exit();
}

$user_id  = $_SESSION['user_id'];
$nama     = mysqli_real_escape_string($conn, trim($_POST['nama'] ?? ''));
$bio      = mysqli_real_escape_string($conn, trim($_POST['bio'] ?? ''));
$fakultas = mysqli_real_escape_string($conn, trim($_POST['fakultas'] ?? ''));

// Ambil avatar lama buat keperluan hapus
$res = mysqli_query($conn, "SELECT avatar FROM users WHERE id = $user_id");
$user_lama = mysqli_fetch_assoc($res);

$avatar_sql = "";
if (!empty($_FILES['avatar']['tmp_name'])) {
    $tipe_ok = ['image/jpeg', 'image/png', 'image/webp'];
    $tipe    = mime_content_type($_FILES['avatar']['tmp_name']);
    if (in_array($tipe, $tipe_ok)) {
        if ($user_lama['avatar'] && file_exists('uploads/' . $user_lama['avatar'])) {
            unlink('uploads/' . $user_lama['avatar']);
        }
        $ext    = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $avatar = 'avatar_' . $user_id . '_' . time() . '.' . strtolower($ext);
        move_uploaded_file($_FILES['avatar']['tmp_name'], 'uploads/' . $avatar);
        $avatar_sql = ", avatar = '$avatar'";
    }
}

if (!empty($nama)) {
    mysqli_query($conn, "UPDATE users SET nama='$nama', bio='$bio', fakultas='$fakultas' $avatar_sql WHERE id=$user_id");
    $_SESSION['nama'] = $nama;
}

header("Location: profil.php");
exit();
