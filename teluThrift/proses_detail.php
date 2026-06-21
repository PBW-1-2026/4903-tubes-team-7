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
    $id = (int)$_GET['id'];
} else {
    $id = 0;
}
if ($id == 0) {
    header("Location: jelajah.php");
    exit();
}

$sql  = "SELECT p.*, u.id AS uid, u.username, u.nama AS seller_nama, u.avatar,
         (SELECT COUNT(*) FROM produk WHERE user_id = u.id AND status = 'tersedia') AS total_produk_seller
         FROM produk p
         JOIN users u ON p.user_id = u.id
         WHERE p.id = $id";
$hasil = mysqli_query($conn, $sql);
$produk = mysqli_fetch_assoc($hasil);

if (!$produk) {
    header("Location: jelajah.php");
    exit();
}

if ($produk['brand']) {
    $brand = $produk['brand'];
} else {
    $brand = 'No Brand';
}

$sql_gambar = "SELECT nama_file FROM produk_gambar WHERE produk_id = $id ORDER BY urutan";
$hasil_gambar = mysqli_query($conn, $sql_gambar);
$gambar = [];
while ($gambar_item = mysqli_fetch_assoc($hasil_gambar)) {
    $gambar[] = $gambar_item['nama_file'];
}

$di_wishlist = false;
if ($sudah_login) {
    $hasil_wish = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id = $user_id AND produk_id = $id");
    $di_wishlist = mysqli_num_rows($hasil_wish) > 0;
}

$produk_lain_list = [];
$sql_lain  = "SELECT p2.*, (SELECT nama_file FROM produk_gambar WHERE produk_id = p2.id ORDER BY urutan LIMIT 1) AS gambar
              FROM produk p2
              WHERE p2.user_id = {$produk['uid']} AND p2.id != $id AND p2.status = 'tersedia'
              ORDER BY p2.created_at DESC LIMIT 4";
$hasil_lain  = mysqli_query($conn, $sql_lain);
while ($pl = mysqli_fetch_assoc($hasil_lain)) {
    $produk_lain_list[] = $pl;
}

function labelKondisi($kondisi) {
    if ($kondisi == 'sangat_baik') return 'Sangat Baik';
    if ($kondisi == 'baik') return 'Baik';
    if ($kondisi == 'cukup_baik')  return 'Cukup Baik';
    return $kondisi;
}
