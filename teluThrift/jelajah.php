<?php
require_once "config/koneksi.php";
if (isset($_GET['kategori']) && $_GET['kategori'] !== '') {
    $kategori = $_GET['kategori'];
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
if (isset($_GET['q']) && $_GET['q'] !== '') {
    $q = trim($_GET['q']);
} else {
    $q = '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajah - Tel-U Thrift</title>
    <link rel="stylesheet" href="jelajah.css">
    <script src="ajax.js"></script>
</head>
<body onload="muat()">
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

    <div class="bar-cari">
        <div class="bungkus-input">
            <img src="icon/search.svg" style="width:18px;height:18px;opacity:.5;">
            <input type="text" name="q" placeholder="Cari pakaian, aksesoris..." onkeyup="if(event.key==='Enter')muat()">
        </div>
        <button type="button" class="pil-filter" onclick="toggleSidebar()">
            <img src="icon/filter.svg" style="width:16px;height:16px;"> Filter
        </button>
    </div>

    <div class="baris-kategori">
        <?php $kats = [''=>'Semua','atasan'=>'Atasan','bawahan'=>'Bawahan','outerwear'=>'Outer','dress'=>'Dress','sepatu'=>'Sepatu','tas'=>'Tas','aksesoris'=>'Aksesoris']; ?>
        <?php foreach ($kats as $val => $label): ?>
        <a href="#" class="pil-kategori<?php if ($val===''): ?> aktif<?php endif; ?>" onclick="document.querySelector('input[name=\"kategori\"][value=\"<?= $val ?>\"]').checked=true;muat()"><?= $label ?></a>
        <?php endforeach; ?>
    </div>

    <div class="halaman-badan">
        <aside class="samping" id="samping">
            <div class="grup-filter">
                <div class="judul-grup">Kategori</div>
                <?php $kats = [''=>'Semua','atasan'=>'Atasan','bawahan'=>'Bawahan','outerwear'=>'Outer','dress'=>'Dress','sepatu'=>'Sepatu','tas'=>'Tas','aksesoris'=>'Aksesoris']; ?>
                <?php foreach ($kats as $val => $label): ?>
                    <label class="opsi-filter">
                        <input type="radio" name="kategori" value="<?= $val ?>" onchange="muat()"<?php if ($kategori===$val): ?> checked<?php endif; ?>>
                        <?= $label ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <div class="grup-filter">
                <div class="judul-grup">Kondisi</div>
                <?php foreach (['sangat_baik'=>'Sangat Baik','baik'=>'Baik','cukup_baik'=>'Cukup Baik'] as $v => $l): ?>
                    <label class="opsi-filter">
                        <input type="checkbox" name="kondisi" value="<?= $v ?>" onchange="muat()"<?php if (in_array($v, $kondisi)): ?> checked<?php endif; ?>>
                        <?= $l ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <button class="tombol-filter" onclick="muat()">Terapkan Filter</button>
            <a href="jelajah.php" class="reset-filter" onclick="muat()">Reset</a>
        </aside>

        <main class="konten-utama">
            <div id="hasilProduk">
                <div style="text-align:center;padding:3rem 0;color:#aaa;">Memuat produk...</div>
            </div>
        </main>
    </div>

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
