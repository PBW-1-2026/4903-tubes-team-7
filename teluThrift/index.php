<?php
session_start();
require_once "../koneksi.php";
require_once "proses_index.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tel-U Thrift — Marketplace Preloved Mahasiswa</title>

    <link rel="stylesheet" href="../css/index.css">

</head>
<body>
<div class="wrapper">

<nav class="navbar">
    <a href="../index/index.php" class="navbar-logo">
        Tel-U Thrift
    </a>

    <div class="navbar-right">
        <?php if ($sudah_login): ?>
            <a href="../jual/jual.php" class="navbar-link">Tambah produk</a>
            <a href="../profil/profil.php" class="navbar-link"><?= htmlspecialchars($_SESSION['username']) ?></a>
            <a href="../logout/logout.php" class="btn btn-outline btn-sm">Keluar</a>
        <?php else: ?>
            <a href="../jual/jual.php" class="navbar-link">Tambah produk</a>
            <a href="../login/login.php" class="btn btn-merah btn-sm">Masuk & Daftar</a>
        <?php endif; ?>
    </div>
</nav>

<div class="hero">
    <div>
        <div class="hero-eyebrow"><span></span> Telkom University</div>
        <h1>Jual-Beli pakaian<br>preloved <em>mahasiswa</em></h1>
        <p>Temukan pakaian unik dari sesama civitas Tel-U. Hemat, sustainable, dan tetap stylish.</p>
        <div class="hero-btns">
            <a href="../jelajah/jelajah.php" class="btn btn-merah">Mulai belanja</a>
            <a href="#cara-kerja" class="btn btn-outline">Cara kerjanya</a>
        </div>
    </div>
    <div class="hero-stats">
        <div><div class="hstat-num"><?= number_format($total_produk) ?>+</div><div class="hstat-lbl">Produk</div></div>
        <div><div class="hstat-num"><?= number_format($total_seller) ?>+</div><div class="hstat-lbl">Penjual</div></div>
        <div><div class="hstat-num">Rp <?= number_format($harga_min ?: 15000, 0, ',', '.') ?></div><div class="hstat-lbl">Mulai dari</div></div>
    </div>
</div>

<div id="cara-kerja" style="padding: 0 1.5rem 1.5rem;">
    <div class="how-strip">
        <div class="how-item"><div class="how-num">1</div><div class="how-judul">Foto barang</div><div class="how-desc">Upload foto pakaianmu yang jelas</div></div>
        <div class="how-item"><div class="how-num">2</div><div class="how-judul">Isi detail</div><div class="how-desc">Harga, ukuran, dan kondisi</div></div>
        <div class="how-item"><div class="how-num">3</div><div class="how-judul">Terima tawaran</div><div class="how-desc">Chat & deal langsung</div></div>
        <div class="how-item"><div class="how-num">4</div><div class="how-judul">Selesai!</div><div class="how-desc">Transaksi mudah antar mahasiswa</div></div>
    </div>
</div>

<div class="section" style="padding-top:0;">
    <div class="section-head">
        <div class="section-title">Hot items</div>
        <a href="../jelajah/jelajah.php" class="lihat-semua">Lihat semua →</a>
    </div>
    <div class="prod-grid">
        <?php while ($p = mysqli_fetch_assoc($result_produk)): ?>
        <a href="../detail/detail.php?id=<?= $p['id'] ?>" class="prod-card">
            <div class="prod-thumb">
                <?php if ($p['gambar']): ?>
                    <img src="../uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="<?= htmlspecialchars($p['nama']) ?>">
                <?php else: ?>👕<?php endif; ?>
                <?php if ($sudah_login): ?>
                <a href="javascript:void(0)"
                   class="btn-wishlist <?= in_array($p['id'], $wishlist_ids) ? 'aktif' : '' ?>"
                   onclick="toggleWishlist(event, <?= $p['id'] ?>, this)">
                    <?= in_array($p['id'], $wishlist_ids) ? '♥' : '♡' ?>
                </a>
                <?php endif; ?>
            </div>
            <div class="prod-body">
                <div class="prod-brand"><?= htmlspecialchars($p['brand'] ?: '—') ?></div>
                <div class="prod-nama"><?= htmlspecialchars($p['nama']) ?></div>
                <div class="prod-harga">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                <div class="prod-meta">
                    <span class="prod-ukuran"><?= htmlspecialchars($p['ukuran']) ?></span>
                    <span class="kondisi-dot <?= $p['kondisi'] ?>"></span>
                    <span class="prod-kondisi"><?= ucwords(str_replace('_', ' ', $p['kondisi'])) ?></span>
                </div>
            </div>
        </a>
        <?php endwhile; ?>
    </div>
</div>

<div class="section" style="padding-top:0;">
    <div class="section-head">
        <div class="section-title">Rekomendasi seller</div>
        <a href="../jelajah/jelajah.php" class="lihat-semua">Lihat semua →</a>
    </div>
    <div class="seller-row">
        <?php while ($s = mysqli_fetch_assoc($result_seller)): ?>
        <a href="../profil/profil.php?id=<?= $s['id'] ?>" class="seller-card">
            <div class="seller-avatar">
                <?php if ($s['avatar']): ?>
                    <img src="../uploads/<?= htmlspecialchars($s['avatar']) ?>" alt="">
                <?php else: ?>👤<?php endif; ?>
            </div>
            <div class="seller-nama">@<?= htmlspecialchars($s['username']) ?></div>
            <div class="seller-count"><?= $s['jml_produk'] ?> produk</div>
        </a>
        <?php endwhile; ?>
    </div>
</div>

<div class="cta-banner">
    <div>
        <h3>Punya baju nganggur?</h3>
        <p>Listing gratis. Jual langsung ke sesama mahasiswa Tel-U.</p>
    </div>
    <a href="../jual/jual.php" class="btn btn-merah">Mulai berjualan</a>
</div>

<div style="height:1rem;"></div>

<nav class="bottom-nav">
    <a href="../index/index.php" class="bnav-item active"><img src="../icon/home.svg" alt="home" class="bnav-icon">Beranda</a>
    <a href="../jelajah/jelajah.php" class="bnav-item"><img src="../icon/search.svg" alt="search" class="bnav-icon">Jelajah</a>
    <a href="../jual/jual.php" class="bnav-item"><img src="../icon/add.svg" alt="add" class="bnav-icon">Jual</a>
    <a href="../chat/chat.php" class="bnav-item"><img src="../icon/chat.svg" alt="chat" class="bnav-icon">Chat</a>
    <a href="<?= $sudah_login ? '../profil/profil.php' : '../login/login.php' ?>" class="bnav-item"><img src="../icon/profile.svg" alt="profile" class="bnav-icon">Profil</a>
</nav>

</div>
<script src="../js/main.js"></script>
</body>
</html>