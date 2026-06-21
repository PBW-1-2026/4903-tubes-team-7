<?php
session_start();
require_once "config/koneksi.php";

$sudah_login = isset($_SESSION['user_id']);
if ($sudah_login) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = 0;
}

if (isset($_GET['id'])) {
    $target_id = (int)$_GET['id'];
} else {
    if (!$sudah_login) {
        header("Location: login.php");
        exit(); 
    }
    $target_id = $user_id;
}

$res_user = mysqli_query($conn, "SELECT * FROM users WHERE id = $target_id");
$user = mysqli_fetch_assoc($res_user);
if (!$user) { header("Location: index.php"); exit(); }

$milik_sendiri = $sudah_login && $user_id == $target_id;

$jml_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM produk WHERE user_id=$target_id AND status='tersedia'"))['c'];
$jml_terjual = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM produk WHERE user_id=$target_id AND status='terjual'"))['c'];
if ($milik_sendiri) {
    $jml_wishlist = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM wishlist WHERE user_id=$target_id"))['c'];
} else {
    $jml_wishlist = 0;
}

if (isset($_GET['tab'])) {
    $tab = $_GET['tab'];
} else {
    $tab = 'aktif';
}

if ($tab == 'terjual') {
    $sql_p = "SELECT p.*, (SELECT nama_file FROM produk_gambar WHERE produk_id=p.id ORDER BY urutan LIMIT 1) AS gambar
              FROM produk p WHERE p.user_id=$target_id AND p.status='terjual' ORDER BY p.created_at DESC";
} elseif ($tab == 'wishlist' && $milik_sendiri) {
    $sql_p = "SELECT p.*, u.username,
              (SELECT nama_file FROM produk_gambar WHERE produk_id=p.id ORDER BY urutan LIMIT 1) AS gambar
              FROM wishlist w JOIN produk p ON p.id=w.produk_id JOIN users u ON u.id=p.user_id
              WHERE w.user_id=$target_id ORDER BY w.id DESC";
} else {
    $sql_p = "SELECT p.*, (SELECT nama_file FROM produk_gambar WHERE produk_id=p.id ORDER BY urutan LIMIT 1) AS gambar
              FROM produk p WHERE p.user_id=$target_id AND p.status='tersedia' ORDER BY p.created_at DESC";
}
$result_produk = mysqli_query($conn, $sql_p);

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $target_id"));
