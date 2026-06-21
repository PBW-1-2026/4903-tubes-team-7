<?php
header('Content-Type: application/json');

require_once "config/koneksi.php";

$sudah_login = isset($_SESSION['user_id']);
if ($sudah_login) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = 0;
}

if (isset($_GET['q']) && $_GET['q'] !== '') {
    $q = mysqli_real_escape_string($conn, trim($_GET['q']));
} else {
    $q = '';
}

if (isset($_GET['kategori']) && $_GET['kategori'] !== '') {
    $kategori = mysqli_real_escape_string($conn, trim($_GET['kategori']));
} else {
    $kategori = '';
}

if (isset($_GET['sort']) && $_GET['sort'] !== '') {
    $sort = $_GET['sort'];
} else {
    $sort = 'terbaru';
}

if (isset($_GET['kondisi'])) {
    $kondisi = (array)$_GET['kondisi'];
} else {
    $kondisi = [];
}

if (isset($_GET['gender']) && $_GET['gender'] !== '') {
    $gender = mysqli_real_escape_string($conn, trim($_GET['gender']));
} else {
    $gender = '';
}

if (isset($_GET['sale']) && $_GET['sale'] == '1') {
    $sale = true;
} else {
    $sale = false;
}

$conditions = [];
$conditions[] = "p.status = 'tersedia'";

if ($q != '') {
    $conditions[] = "(p.nama LIKE '%$q%' OR p.brand LIKE '%$q%' OR u.username LIKE '%$q%')";
}

if ($kategori != '') {
    $conditions[] = "p.kategori = '$kategori'";
}

if (!empty($kondisi)) {
    $esc = [];
    foreach ($kondisi as $k) {
        $esc[] = "'" . mysqli_real_escape_string($conn, $k) . "'";
    }
    $conditions[] = "p.kondisi IN (" . implode(',', $esc) . ")";
}

if ($gender != '') {
    $conditions[] = "p.gender = '$gender'";
}

if ($sale) {
    $conditions[] = "p.status = 'tersedia'";
    $conditions[] = "p.harga < 50000";
}

$where = "WHERE " . implode(" AND ", $conditions);

if ($sort == 'termurah') {
    $order = "ORDER BY p.harga ASC";
} elseif ($sort == 'termahal') {
    $order = "ORDER BY p.harga DESC";
} else {
    $order = "ORDER BY p.created_at DESC";
}

$sql_total = "SELECT COUNT(*) AS jumlah FROM produk p JOIN users u ON p.user_id = u.id $where";
$total = mysqli_fetch_assoc(mysqli_query($conn, $sql_total))['jumlah'];

$sql = "SELECT p.*, u.username,
        (SELECT nama_file FROM produk_gambar WHERE produk_id = p.id ORDER BY urutan LIMIT 1) AS gambar
        FROM produk p JOIN users u ON p.user_id = u.id
        $where $order";
$result = mysqli_query($conn, $sql);

$wishlist_ids = [];
if ($sudah_login) {
    $res_wish = mysqli_query($conn, "SELECT produk_id FROM wishlist WHERE user_id = $user_id");
    while ($w = mysqli_fetch_assoc($res_wish)) {
        $wishlist_ids[] = $w['produk_id'];
    }
}

$products = [];
while ($p = mysqli_fetch_assoc($result)) {
    $products[] = [
        'id'           => (int)$p['id'],
        'nama'         => $p['nama'],
        'harga'        => (int)$p['harga'],
        'brand'        => $p['brand'],
        'gambar'       => $p['gambar'],
        'ukuran'       => $p['ukuran'],
        'kondisi'      => $p['kondisi'],
        'status'       => $p['status'],
        'is_wishlisted'=> in_array($p['id'], $wishlist_ids)
    ];
}

echo json_encode([
    'total'       => (int)$total,
    'sudah_login' => $sudah_login,
    'products'    => $products
]);
