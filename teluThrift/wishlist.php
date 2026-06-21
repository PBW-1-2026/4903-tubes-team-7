<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'not_logged_in']);
        exit();
    }
    header("Location: login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$produk_id = isset($_GET['produk_id']) ? (int)$_GET['produk_id'] : 0;
$aksi      = isset($_GET['aksi']) ? $_GET['aksi'] : '';
$dari      = isset($_GET['dari']) ? $_GET['dari'] : 'index';

if ($produk_id > 0 && $aksi == 'toggle') {
    $cek = mysqli_query($conn, "SELECT id FROM wishlist WHERE user_id = $user_id AND produk_id = $produk_id");
    $is_wishlisted = false;

    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "DELETE FROM wishlist WHERE user_id = $user_id AND produk_id = $produk_id");
        $is_wishlisted = false;
    } else {
        mysqli_query($conn, "INSERT INTO wishlist (user_id, produk_id) VALUES ($user_id, $produk_id)");
        $is_wishlisted = true;
    }

    if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'is_wishlisted' => $is_wishlisted]);
        exit();
    }
}

if ($dari == 'detail') {
    header("Location: detail.php?id=$produk_id");
} elseif ($dari == 'profil') {
    header("Location: profil.php?tab=wishlist");
} elseif ($dari == 'jelajah') {
    $params = $_GET;
    unset($params['aksi'], $params['produk_id'], $params['dari']);
    $query = http_build_query($params);
    header("Location: jelajah.php" . ($query ? "?$query" : ""));
} else {
    header("Location: index.php");
}
exit();
?>
