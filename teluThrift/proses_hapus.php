<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || ($_POST['aksi'] ?? '') != 'hapus') {
    header("Location: profil.php");
    exit();
} 

$user_id = $_SESSION['user_id'];
$pid     = (int)($_POST['produk_id'] ?? 0);

if ($pid > 0) {
    $res_g = mysqli_query($conn, "SELECT nama_file FROM produk_gambar WHERE produk_id = $pid");
    while ($g = mysqli_fetch_assoc($res_g)) {
        if (file_exists('uploads/' . $g['nama_file'])) {
            unlink('uploads/' . $g['nama_file']);
        }
    }
    mysqli_query($conn, "DELETE FROM produk WHERE id=$pid AND user_id=$user_id");
}

header("Location: profil.php");
exit();
