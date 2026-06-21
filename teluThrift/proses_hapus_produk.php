<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['username'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit();
}

$pid = (int)($_GET['id'] ?? 0);
if ($pid > 0) {
    $res_g = mysqli_query($conn, "SELECT nama_file FROM produk_gambar WHERE produk_id = $pid");
    while ($g = mysqli_fetch_assoc($res_g)) {
        if (file_exists('uploads/' . $g['nama_file'])) {
            unlink('uploads/' . $g['nama_file']);
        }
    }
    
    mysqli_query($conn, "DELETE FROM produk_gambar WHERE produk_id = $pid");
    mysqli_query($conn, "DELETE FROM wishlist WHERE produk_id = $pid");
    mysqli_query($conn, "DELETE FROM produk WHERE id = $pid");
}

header("Location: index.php");
exit();
?>
