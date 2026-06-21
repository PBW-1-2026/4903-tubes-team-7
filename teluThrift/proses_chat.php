<?php

// Harus login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ===== BUKA PERCAKAPAN BARU (dari halaman detail produk) =====
if (isset($_GET['produk']) && isset($_GET['user'])) {
    $target_id = (int)$_GET['user'];
    $produk_id = (int)$_GET['produk'];

    if ($target_id != $user_id) {
        // Cek apakah percakapan sudah ada
        $uid1 = min($user_id, $target_id);
        $uid2 = max($user_id, $target_id);

        $cek = mysqli_query($conn, "SELECT id FROM percakapan
                                    WHERE user1_id = $uid1 AND user2_id = $uid2 AND produk_id = $produk_id");
        if (mysqli_num_rows($cek) > 0) {
            $row = mysqli_fetch_assoc($cek);
            $conv_id = $row['id'];
        } else {
            mysqli_query($conn, "INSERT INTO percakapan (user1_id, user2_id, produk_id)
                                 VALUES ($uid1, $uid2, $produk_id)");
            $conv_id = mysqli_insert_id($conn);
        }
        header("Location: chat.php?id=$conv_id");
        exit();
    }
}

// ===== KIRIM PESAN =====
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['isi']) && isset($_POST['conv_id'])) {
    $isi = mysqli_real_escape_string($conn, trim($_POST['isi']));
    $conv_id = (int)$_POST['conv_id'];

    if (!empty($isi)) {
        // Validasi user adalah bagian dari percakapan
        $cek = mysqli_query($conn, "SELECT id FROM percakapan
                                    WHERE id = $conv_id AND (user1_id = $user_id OR user2_id = $user_id)");
        if (mysqli_num_rows($cek) > 0) {
            mysqli_query($conn, "INSERT INTO pesan (percakapan_id, pengirim_id, isi)
                                 VALUES ($conv_id, $user_id, '$isi')");
        }
    }
    header("Location: chat.php?id=$conv_id");
    exit();
}

// ===== AMBIL DAFTAR PERCAKAPAN =====
$sql_list = "SELECT c.*,
             IF(c.user1_id = $user_id, c.user2_id, c.user1_id) AS partner_id,
             u.username AS partner_username,
             u.nama AS partner_nama,
             u.avatar AS partner_avatar,
             p.nama AS produk_nama,
             p.harga AS produk_harga,
             (SELECT isi FROM pesan WHERE percakapan_id = c.id ORDER BY created_at DESC LIMIT 1) AS pesan_terakhir,
             (SELECT created_at FROM pesan WHERE percakapan_id = c.id ORDER BY created_at DESC LIMIT 1) AS waktu_terakhir
             FROM percakapan c
             JOIN users u ON u.id = IF(c.user1_id = $user_id, c.user2_id, c.user1_id)
             LEFT JOIN produk p ON p.id = c.produk_id
             WHERE c.user1_id = $user_id OR c.user2_id = $user_id
             ORDER BY waktu_terakhir DESC";
$result_list = mysqli_query($conn, $sql_list);

// ===== BUKA PERCAKAPAN TERTENTU =====
$conv_aktif = null;
$pesan_list = null;
$conv_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($conv_id > 0) {
    $sql_conv = "SELECT c.*,
                 IF(c.user1_id = $user_id, c.user2_id, c.user1_id) AS partner_id,
                 u.username AS partner_username,
                 u.nama AS partner_nama,
                 u.avatar AS partner_avatar,
                 p.nama AS produk_nama,
                 p.harga AS produk_harga,
                 p.id AS produk_id,
                 (SELECT nama_file FROM produk_gambar WHERE produk_id = p.id ORDER BY urutan LIMIT 1) AS produk_gambar
                 FROM percakapan c
                 JOIN users u ON u.id = IF(c.user1_id = $user_id, c.user2_id, c.user1_id)
                 LEFT JOIN produk p ON p.id = c.produk_id
                 WHERE c.id = $conv_id AND (c.user1_id = $user_id OR c.user2_id = $user_id)";
    $res_conv = mysqli_query($conn, $sql_conv);
    $conv_aktif = mysqli_fetch_assoc($res_conv);

    if ($conv_aktif) {
        // Ambil semua pesan
        $sql_pesan = "SELECT m.*, u.username FROM pesan m
                      JOIN users u ON u.id = m.pengirim_id
                      WHERE m.percakapan_id = $conv_id
                      ORDER BY m.created_at ASC";
        $pesan_list = mysqli_query($conn, $sql_pesan);
    }
}
?>