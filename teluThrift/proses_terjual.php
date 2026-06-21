<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || ($_POST['aksi'] ?? '') != 'terjual') {
    header("Location: profil.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$pid     = (int)($_POST['produk_id'] ?? 0);

if ($pid > 0) {
    mysqli_query($conn, "UPDATE produk SET status='terjual' WHERE id=$pid AND user_id=$user_id");
}

header("Location: profil.php");
exit();
