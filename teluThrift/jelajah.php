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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajah - Tel-U Thrift</title>
    <link rel="stylesheet" href="css/jelajah.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap">
</head>
<body>
<div class="bungkus">

    <nav class="nav-atas">
        <a href="index.php" class="logo-nav">
            Tel-U Thrift
        </a>
        <div class="tab-nav">
            <a href="jelajah.php" class="tab-nav-item aktif">Jelajahi</a>
            <a href="jual.php" class="tab-nav-item">Jual</a>
        </div>
        <div class="kanan-nav">
            <a href="login.php" class="tautan-nav">Masuk</a>
            <a href="register.php" class="tombol tombol-merah tombol-kecil">Daftar</a>
        </div>
    </nav>

    <form method="GET" action="jelajah.php">
    <div class="bar-cari">
        <div class="bungkus-input">
            <img src="icon/search.svg" style="width:18px;height:18px;opacity:.5;">
            <input type="text" name="q" placeholder="Cari pakaian, aksesoris..." value="<?= htmlspecialchars($q) ?>">
        </div>
        <button type="button" class="pil-filter" onclick="toggleSidebar()">
            <img src=icon/filter.svg" style="width:16px;height:16px;"> Filter
        </button>
    </div>

    <div class="baris-kategori">
        <a href="<?= http_build_query(array_merge($_GET, ['kategori'=>''])) ?>" class="pil-kategori<?php if (!$kategori): ?> aktif<?php endif; ?>">Semua</a>
        <?php foreach (['Atasan','Bawahan','Outer','Aksesoris','Sepatu'] as $kat): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['kategori'=>$kat])) ?>" class="pil-kategori<?php if ($kategori===$kat): ?> aktif<?php endif; ?>"><?= $kat ?></a>
        <?php endforeach; ?>
    </div>

    <div class="halaman-badan">
        <aside class="samping" id="samping">
            <div class="grup-filter">
                <div class="judul-grup">Kategori</div>
                <?php foreach ([''=>'Semua','Atasan','Bawahan','Outer','Aksesoris','Sepatu'] as $val => $label): ?>
                    <label class="opsi-filter">
                        <input type="radio" name="kategori" value="<?= $val ?>" onchange="this.form.submit()"<?php if ($kategori===$val): ?> checked<?php endif; ?>>
                        <?= $label ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <div class="grup-filter">
                <div class="judul-grup">Kondisi</div>
                <?php foreach (['sangat_baik'=>'Sangat Baik','baik'=>'Baik','cukup_baik'=>'Cukup Baik'] as $v => $l): ?>
                    <label class="opsi-filter">
                        <input type="checkbox" name="kondisi[]" value="<?= $v ?>" onchange="this.form.submit()"<?php if (in_array($v, $kondisi)): ?> checked<?php endif; ?>>
                        <?= $l ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="tombol-filter">Terapkan Filter</button>
            <a href="jelajah.php" class="reset-filter">Reset</a>
        </aside>

        <main class="konten-utama">
            <div class="bar-urut">
                <div class="jumlah-hasil">Menampilkan <strong><?= $total ?></strong> produk</div>
                <div class="kanan-urut">
                    <span style="font-size:12px;color:#aaa;">Urutkan</span>
                    <select name="sort" class="pilih-urut" onchange="this.form.submit()">
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
                            <div class="thumb-produk"><?php if ($p['foto']) { $foto = $p['foto']; } else { $foto = ''; } echo htmlspecialchars($foto); ?></div>
                            <div class="badan-produk">
                                <div class="merek-produk"><?php if ($p['brand']) { $brand = $p['brand']; } else { $brand = '-'; } echo htmlspecialchars($brand); ?></div>
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
        </main>
    </div>
    </form>

    <nav class="nav-bawah">
        <a href="index.php" class="item-bawah">
            <img src="icon/home.svg" class="ikon-bawah"> Beranda
        </a>
        <a href="jelajah.php" class="item-bawah aktif">
            <img src="icon/search.svg" class="ikon-bawah"> Jelajahi
        </a>
        <a href="jual.php" class="item-bawah">
            <img src="icon/add.svg" class="ikon-bawah"> Jual
        </a>
        <a href="chat.php" class="item-bawah">
            <img src="icon/chat.svg" class="ikon-bawah"> Chat
        </a>
        <a href="profil.php" class="item-bawah">
            <img src="icon/profile.svg" class="ikon-bawah"> Profil
        </a>
    </nav>

</div>
<script>
function toggleSidebar() {
    document.getElementById('samping').classList.toggle('buka');
}
</script>
</body>
</html>