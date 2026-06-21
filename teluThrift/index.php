<?php
session_start();
require_once "config/koneksi.php";
require_once "proses_index.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tel-U Thrift</title>
    <link rel="stylesheet" href="css/index.css?v=2">

</head>
<body>
<div class="pembungkus">

<nav class="navigasi">
    <h3>Tel-U Thrift</h3>
    <div class="navigasi-kanan">
        <?php if ($sudah_login): ?>
            <a href="profil.php" class="tautan-navigasi"><?= htmlspecialchars($_SESSION['username']) ?></a>
            <a href="logout.php" class="tombol tombol-garis tombol-kecil">Keluar</a>
        <?php else: ?>
            <a href="login.php" class="tombol tombol-merah tombol-kecil">Masuk & Daftar</a>
        <?php endif; ?>
    </div>
</nav>

<div class="utama">
    <div>
        <div class="utama-pembuka">Telkom University</div>
        <h1>Jual-Beli pakaian<br>preloved <em>mahasiswa</em></h1>
        <p>Temukan pakaian unik dari sesama civitas Tel-U. Hemat, sustainable, dan tetap stylish.</p>
    </div>
    <div class="utama-statistik">
        <div><div class="ustat-angka"><?= number_format($total_produk) ?>+</div><div class="ustat-label">Produk</div></div>
        <div><div class="ustat-angka"><?= number_format($total_seller) ?>+</div><div class="ustat-label">Penjual</div></div>
        <div><div class="ustat-angka">Rp <?= number_format($harga_min ?: 15000, 0, ',', '.') ?></div><div class="ustat-label">Mulai dari</div></div>
    </div>
</div>

<div id="cara-kerja" style="padding: 0 1.5rem 1.5rem;">
    <div class="kepala-bagian">
        <div class="judul-bagian">Cara kerjanya</div>
    </div>
    <div class="strip-cara">
        <div class="item-cara"><div class="angka-cara">1</div><div class="judul-cara">Foto barang</div><div class="deskripsi-cara">Upload foto pakaianmu yang jelas</div></div>
        <div class="item-cara"><div class="angka-cara">2</div><div class="judul-cara">Isi detail</div><div class="deskripsi-cara">Harga, ukuran, dan kondisi</div></div>
        <div class="item-cara"><div class="angka-cara">3</div><div class="judul-cara">Terima tawaran</div><div class="deskripsi-cara">Chat & deal langsung</div></div>
        <div class="item-cara"><div class="angka-cara">4</div><div class="judul-cara">Selesai!</div><div class="deskripsi-cara">Transaksi mudah antar mahasiswa</div></div>
    </div>
</div>

<div class="bagian" style="padding-top:0;">
    <div class="kepala-bagian">
        <div class="judul-bagian">Hot items</div>
    </div>
    <div class="grid-produk">
        <?php while ($p = mysqli_fetch_assoc($result_produk)): ?>
        <a href="detail.php?id=<?= $p['id'] ?>" class="kartu-produk">
            <div class="thumb-produk">
                <?php if ($p['gambar']): ?>
                    <img src="uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="<?= htmlspecialchars($p['nama']) ?>" loading="lazy">
                <?php else: ?><img src="icon/apparel.svg" alt="Produk" style="object-fit: contain; padding: 10%; opacity: 0.2;"><?php endif; ?>
                <?php if ($sudah_login): ?>
                <button type="button" class="tombol-favorit <?= in_array($p['id'], $wishlist_ids) ? 'aktif' : '' ?>" onclick="toggleWishlist(event, <?= $p['id'] ?>, this)">
                    <?= in_array($p['id'], $wishlist_ids) ? '<img src="icon/wishlist.svg" class="ikon-wish">' : '<img src="icon/wish.svg" class="ikon-wish">' ?>
                </button>
                <?php endif; ?>
            </div>
            <div class="badan-produk">
                <div class="merek-produk"><?= htmlspecialchars($p['brand'] ?: '—') ?></div>
                <div class="nama-produk"><?= htmlspecialchars($p['nama']) ?></div>
                <div class="harga-produk">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                <div class="meta-produk">
                    <span class="ukuran-produk"><?= htmlspecialchars($p['ukuran']) ?></span>
                    <span class="dot-kondisi <?= $p['kondisi'] ?>"></span>
                    <span class="kondisi-produk"><?= ucwords(str_replace('_', ' ', $p['kondisi'])) ?></span>
                </div>
            </div>
        </a>
        <?php endwhile; ?>
    </div>
</div>

<div class="bagian" style="padding-top:0;">
    <div class="kepala-bagian">
        <div class="judul-bagian">Rekomendasi Seller</div>
    </div>
    <div class="baris-penjual">
        <?php while ($s = mysqli_fetch_assoc($result_seller)): ?>
        <a href="profil.php?id=<?= $s['id'] ?>" class="kartu-penjual">
            <div class="avatar-penjual">
                <?php if ($s['avatar']): ?>
                    <img src="uploads/<?= htmlspecialchars($s['avatar']) ?>" alt="">
                <?php else: ?><img src="icon/profile.svg" alt="User" style="object-fit: contain; padding: 4px;"><?php endif; ?>
            </div>
            <div class="nama-penjual">@<?= htmlspecialchars($s['username']) ?></div>
            <div class="jumlah-penjual"><?= $s['jml_produk'] ?> produk</div>
        </a>
        <?php endwhile; ?>
    </div>
</div>

<div class="banner-ajakan">
    <div>
        <h3>Punya baju nganggur?</h3>
        <p>Listing gratis. Jual langsung ke sesama mahasiswa Tel-U.</p>
    </div>
    <a href="jual.php" class="tombol tombol-merah">Mulai berjualan</a>
</div>

<div style="height:1rem;"></div>

<nav class="navigasi-bawah">
    <a href="index.php" class="item-navbawah aktif"><img src="icon/home.svg" alt="home" class="ikon-navbawah">Beranda</a>
    <a href="jelajah.php" class="item-navbawah"><img src="icon/search.svg" alt="search" class="ikon-navbawah">Jelajah</a>
    <a href="jual.php" class="item-navbawah"><img src="icon/add.svg" alt="add" class="ikon-navbawah">Jual</a>
    <a href="chat.php" class="item-navbawah"><img src="icon/chat.svg" alt="chat" class="ikon-navbawah">Chat</a>
    <a href="<?= $sudah_login ? 'profil.php' : 'login.php' ?>" class="item-navbawah"><img src="icon/profile.svg" alt="profile" class="ikon-navbawah">Profil</a>
</nav>

</div>
<script src="js/ajax.js"></script>
</body>
</html>