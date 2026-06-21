<?php
session_start();
$sudah_login = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajah - Tel-U Thrift</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap">
</head>
<body>

<div class="bungkus">

    <nav class="nav-atas">
        <a href="../index.php" class="logo-nav">
            <span class="titik-logo"></span> telUthrift
        </a>
        <div class="tab-nav">
            <a href="../jelajah.php" class="tab-nav-item aktif">Jelajahi</a>
            <a href="../jual.php" class="tab-nav-item">Jual</a>
        </div>
        <div class="kanan-nav">
            <a href="../login.php" class="tautan-nav">Masuk</a>
            <a href="../register.php" class="tombol tombol-merah tombol-kecil">Daftar</a>
        </div>
    </nav>

    <div class="bar-cari">
        <div class="bungkus-input">
            <img src="../icon/search.svg" alt="cari" class="ikon-input">
            <input type="text" placeholder="Cari pakaian, aksesoris...">
        </div>
        <button type="button" class="pil-filter">
            <img src="../icon/filter.svg" alt="filter" class="ikon-pil"> Filter
        </button>
    </div>

    <div class="baris-kategori">
        <a href="#" class="pil-kategori aktif">Semua</a>
        <a href="#" class="pil-kategori">Atasan</a>
        <a href="#" class="pil-kategori">Bawahan</a>
        <a href="#" class="pil-kategori">Outer</a>
        <a href="#" class="pil-kategori">Aksesoris</a>
        <a href="#" class="pil-kategori">Sepatu</a>
    </div>

    <div class="halaman-badan">

        <aside class="samping">
            <div class="grup-filter">
                <div class="judul-grup">Kategori</div>
                <label class="opsi-filter">
                    <input type="radio" name="kategori" checked> Semua
                </label>
                <label class="opsi-filter">
                    <input type="radio" name="kategori"> Atasan
                </label>
                <label class="opsi-filter">
                    <input type="radio" name="kategori"> Bawahan
                </label>
                <label class="opsi-filter">
                    <input type="radio" name="kategori"> Outer
                </label>
                <label class="opsi-filter">
                    <input type="radio" name="kategori"> Aksesoris
                </label>
            </div>

            <div class="grup-filter">
                <div class="judul-grup">Kondisi</div>
                <label class="opsi-filter">
                    <input type="checkbox"> Sangat Baik
                </label>
                <label class="opsi-filter">
                    <input type="checkbox"> Baik
                </label>
                <label class="opsi-filter">
                    <input type="checkbox"> Cukup Baik
                </label>
            </div>

            <button class="tombol-filter">Terapkan Filter</button>
            <a href="#" class="reset-filter">Reset</a>
        </aside>

        <main class="konten-utama">
            <p style="text-align:center;padding:2rem 0;color:#ccc;">Produk menyusul</p>
        </main>

    </div>

    <nav class="nav-bawah">
        <a href="../index.php" class="item-bawah">
            <img src="../icon/home.svg" alt="home" class="ikon-bawah"> Beranda
        </a>
        <a href="../jelajah.php" class="item-bawah aktif">
            <img src="../icon/search.svg" alt="search" class="ikon-bawah"> Jelajah
        </a>
        <a href="../jual.php" class="item-bawah">
            <img src="../icon/add.svg" alt="add" class="ikon-bawah"> Jual
        </a>
        <a href="../chat.php" class="item-bawah">
            <img src="../icon/chat.svg" alt="chat" class="ikon-bawah"> Chat
        </a>
        <a href="<?= $sudah_login ? '../profil.php' : '../login.php' ?>" class="item-bawah">
            <img src="../icon/profile.svg" alt="profile" class="ikon-bawah"> Profil
        </a>
    </nav>

</div>

</body>
</html>
