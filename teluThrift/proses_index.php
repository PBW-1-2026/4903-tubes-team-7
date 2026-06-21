<?php
$sudah_login = isset($_SESSION['user_id']);
$user_id = $sudah_login ? $_SESSION['user_id'] : 0;

$filter_sendiri = $sudah_login ? "AND p.user_id != $user_id" : "";

$sql_produk = "SELECT p.*, u.username,
               (SELECT nama_file FROM produk_gambar WHERE produk_id = p.id ORDER BY urutan LIMIT 1) AS gambar,
               (SELECT COUNT(*) FROM wishlist WHERE produk_id = p.id) AS jml_wishlist,
               (SELECT COUNT(*) FROM percakapan WHERE produk_id = p.id) AS jml_chat,
               ((SELECT COUNT(*) FROM wishlist WHERE produk_id = p.id) * 1 +
                (SELECT COUNT(*) FROM percakapan WHERE produk_id = p.id) * 2) AS skor
               FROM produk p
               JOIN users u ON p.user_id = u.id
               WHERE p.status = 'tersedia' $filter_sendiri
               ORDER BY skor DESC, p.created_at DESC
               LIMIT 6";
$result_produk = mysqli_query($conn, $sql_produk);

$filter_seller = $sudah_login ? "AND u.id != $user_id" : "";

$sql_seller = "SELECT u.id, u.username, u.nama, u.avatar,
               COUNT(DISTINCT p.id) AS jml_produk,
               (COALESCE(SUM((SELECT COUNT(*) FROM wishlist WHERE produk_id = p.id)), 0) * 1 +
                COALESCE(SUM((SELECT COUNT(*) FROM percakapan WHERE produk_id = p.id)), 0) * 2) AS skor
               FROM users u
               JOIN produk p ON p.user_id = u.id AND p.status = 'tersedia'
               WHERE 1=1 $filter_seller
               GROUP BY u.id
               ORDER BY skor DESC, jml_produk DESC
               LIMIT 6";
$result_seller = mysqli_query($conn, $sql_seller);

$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM produk WHERE status='tersedia'"))['c'];
$total_seller = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) AS c FROM produk"))['c'];
$harga_min = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MIN(harga) AS c FROM produk WHERE status='tersedia'"))['c'];

$wishlist_ids = [];
if ($sudah_login) {
    $res_wish = mysqli_query($conn, "SELECT produk_id FROM wishlist WHERE user_id = $user_id");
    while ($w = mysqli_fetch_assoc($res_wish)) {
        $wishlist_ids[] = $w['produk_id'];
    }
}
?>