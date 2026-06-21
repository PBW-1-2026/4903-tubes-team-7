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

if (isset($_GET['gender']) && $_GET['gender'] !== '') {
    $gender = mysqli_real_escape_string($conn, trim($_GET['gender']));
} else {
    $gender = '';
}

if (isset($_GET['sort']) && $_GET['sort'] !== '') {
    $sort = $_GET['sort'];
} else {
    $sort = 'terbaru';
}

if (isset($_GET['harga_min']) && $_GET['harga_min'] !== '') {
    $harga_min = (int)$_GET['harga_min'];
} else {
    $harga_min = 0;
}

if (isset($_GET['harga_max']) && $_GET['harga_max'] !== '') {
    $harga_max = (int)$_GET['harga_max'];
} else {
    $harga_max = 0;
}

if (isset($_GET['kondisi']) && is_array($_GET['kondisi'])) {
    $kondisi = $_GET['kondisi'];
} else {
    $kondisi = [];
}

if (isset($_GET['ukuran']) && is_array($_GET['ukuran'])) {
    $ukuran = $_GET['ukuran'];
} else {
    $ukuran = [];
}

if (isset($_GET['sale']) && $_GET['sale'] == '1') {
    $sale = true;
} else {
    $sale = false;
}

if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
} else {
    $page = 1;
}

if ($page < 1) {
    $page = 1;
}

$per_page = 12;
$offset = ($page - 1) * $per_page;

$conditions = [];
$conditions[] = "p.status = 'tersedia'";

if ($sudah_login) {
    $conditions[] = "p.user_id != $user_id";
}

if ($q != '') {
    $conditions[] = "(p.nama LIKE '%$q%' OR p.brand LIKE '%$q%' OR u.username LIKE '%$q%')";
}

if ($kategori != '') {
    $conditions[] = "p.kategori = '$kategori'";
}

if ($gender != '') {
    $conditions[] = "p.gender = '$gender'";
}

if (!empty($kondisi)) {
    $k_bersih = [];
    foreach ($kondisi as $k) {
        $k_bersih[] = "'" . mysqli_real_escape_string($conn, $k) . "'";
    }
    $conditions[] = "p.kondisi IN (" . implode(',', $k_bersih) . ")";
}

if (!empty($ukuran)) {
    $u_bersih = [];
    foreach ($ukuran as $u) {
        $u_bersih[] = "'" . mysqli_real_escape_string($conn, $u) . "'";
    }
    $conditions[] = "p.ukuran IN (" . implode(',', $u_bersih) . ")";
}

if ($harga_min > 0) {
    $conditions[] = "p.harga >= $harga_min";
}

if ($harga_max > 0) {
    $conditions[] = "p.harga <= $harga_max";
}

if ($sale) {
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
$total_page = max(1, ceil($total / $per_page));

$sql = "SELECT p.*, u.username,
        (SELECT nama_file FROM produk_gambar WHERE produk_id = p.id ORDER BY urutan LIMIT 1) AS gambar
        FROM produk p JOIN users u ON p.user_id = u.id
        $where $order LIMIT $per_page OFFSET $offset";
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

$is_admin = (isset($_SESSION['username']) && strtolower($_SESSION['username']) === 'admin');

echo json_encode([
    'total'       => (int)$total,
    'total_page'  => (int)$total_page,
    'page'        => $page,
    'sudah_login' => $sudah_login,
    'is_admin'    => $is_admin,
    'products'    => $products
]);
