<?php
require_once "config/koneksi.php";

if (isset($_GET['q']) && $_GET['q'] !== '') {
    $q = trim($_GET['q']);
} else {
    $q = '';
}
if (isset($_GET['kategori']) && $_GET['kategori'] !== '') {
    $kategori = trim($_GET['kategori']);
} else {
    $kategori = '';
}
if (isset($_GET['kondisi'])) {
    $kondisi = (array)$_GET['kondisi'];
} else {
    $kondisi = [];
}
if (isset($_GET['sort']) && $_GET['sort'] !== '') {
    $sort = $_GET['sort'];
} else {
    $sort = 'terbaru';
}

$conditions = [];
$conditions[] = "1=1";

if ($q !== '') {
    $sq = mysqli_real_escape_string($conn, $q);
    $conditions[] = "(p.nama LIKE '%$sq%' OR p.brand LIKE '%$sq%')";
}
if ($kategori !== '') {
    $sk = mysqli_real_escape_string($conn, $kategori);
    $conditions[] = "p.kategori = '$sk'";
}
if (!empty($kondisi)) {
    $esc = [];
    foreach ($kondisi as $k) { $esc[] = "'" . mysqli_real_escape_string($conn, $k) . "'"; }
    $conditions[] = "p.kondisi IN (" . implode(',', $esc) . ")";
}

$where = "WHERE " . implode(" AND ", $conditions);

if ($sort == 'termurah') $order = "ORDER BY p.harga ASC";
elseif ($sort == 'termahal') $order = "ORDER BY p.harga DESC";
else $order = "ORDER BY p.created_at DESC";

$total = (int) mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM produk p $where"))[0];

$sql = "SELECT p.*,
        (SELECT nama_file FROM produk_gambar WHERE produk_id = p.id ORDER BY urutan LIMIT 1) AS foto
        FROM produk p $where $order";
$produkList = mysqli_fetch_all(mysqli_query($conn, $sql), MYSQLI_ASSOC);
?>
<div class="bar-urut">
    <div class="jumlah-hasil">Menampilkan <strong><?= $total ?></strong> produk</div>
    <div class="kanan-urut">
        <span style="font-size:12px;color:#aaa;">Urutkan</span>
        <select name="sort" class="pilih-urut" onchange="muat()">
            <option value="terbaru"<?php if ($sort=='terbaru'): ?> selected<?php endif; ?>>Terbaru</option>
            <option value="termurah"<?php if ($sort=='termurah'): ?> selected<?php endif; ?>>Termurah</option>
            <option value="termahal"<?php if ($sort=='termahal'): ?> selected<?php endif; ?>>Termahal</option>
        </select>
    </div>
</div>

<div class="grid-produk">
    <?php if (empty($produkList)): ?>
        <div style="grid-column:1/-1;text-align:center;padding:3rem 0;color:#aaa;">Tidak ada produk</div>
    <?php else: ?>
        <?php foreach ($produkList as $p): ?>
            <a href="detail.php?id=<?= $p['id'] ?>" class="kartu-produk">
                <div class="thumb-produk"><?php if ($p['foto']): ?><img src="../uploads/<?= $p['foto'] ?>" alt="<?= htmlspecialchars($p['nama']) ?>" loading="lazy"><?php endif; ?></div>
                <div class="badan-produk">
                    <div class="merek-produk"><?php if ($p['brand']) { $brand = $p['brand']; } else { $brand = '—'; } echo htmlspecialchars($brand); ?></div>
                    <div class="nama-produk"><?= htmlspecialchars($p['nama']) ?></div>
                    <div class="harga-produk">Rp<?= number_format($p['harga'],0,',','.') ?></div>
                    <div class="meta-produk">
                        <span class="dot-kondisi <?= $p['kondisi'] ?>"></span>
                        <span class="kondisi-produk"><?= ucwords(str_replace('_',' ',$p['kondisi'])) ?></span>
                        <span class="ukuran-produk">· <?= $p['ukuran'] ?></span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
