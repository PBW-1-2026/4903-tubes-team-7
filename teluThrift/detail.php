<?php require 'proses_detail.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produk['nama']) ?> Tel-U Thrift</title>
    <link rel="stylesheet" href="css/detail.css">
</head>
<body>
<div class="bungkus">

<nav class="navigasi">
    <h2>Tel-U Thrift</h2>
    <div class="kanan">
        <?php if (!$sudah_login): ?>
        <a href="login.php" class="tombol tombol-merah tombol-kecil">Masuk</a>
        <?php endif; ?>
    </div>
</nav>

<div class="tata-letak">
    <!--buat manggil gambar-->
    <div class="galeri">
        <div class="bingkai-utama" id="gambar-utama">
            <img src="uploads/<?= htmlspecialchars($gambar[0]) ?>" alt="<?= htmlspecialchars($produk['nama']) ?>" id="gambar-el">
        </div>
        <?php if (count($gambar) > 1): ?>
        <div class="miniatur">
            <?php foreach ($gambar as $i => $g): ?>
            <div class="miniatur-item<?php if ($i == 0): ?> aktif<?php endif; ?>"
                 onclick="gantiGambar('uploads/<?= htmlspecialchars($g) ?>', this)">
                <img src="uploads/<?= htmlspecialchars($g) ?>" alt="">
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!--menampilkna informasi product-->
    <div class="info-produk">
        <div class="merek"><?= htmlspecialchars($brand) ?></div>
        <div class="nama"><?= htmlspecialchars($produk['nama']) ?></div>
        <div class="harga">
            <?php if ($produk['status'] == 'terjual'): ?>
                <span class="harga-coret">
                    Rp <?= number_format($produk['harga'], 0, ',', '.') ?>
                </span>
                <span class="label label-abu label-terjual">Terjual</span>
            <?php else: ?>
                Rp <?= number_format($produk['harga'], 0, ',', '.') ?>
            <?php endif; ?>
        </div>

        <div class="lencana">
            <span class="label label-abu"><?= labelKondisi($produk['kondisi']) ?></span>
            <?php if ($produk['ukuran']): ?>
            <span class="label label-abu">Size <?= htmlspecialchars($produk['ukuran']) ?></span>
            <?php endif; ?>
            <span class="label label-abu"><?= ucfirst($produk['gender']) ?></span>
            <span class="label label-abu"><?= ucfirst($produk['kategori']) ?></span>
        </div>

        <?php if ($produk['status'] == 'tersedia'): ?>
        <div class="aksi">
            <?php if ($sudah_login && $user_id != $produk['uid']): ?>
                <a href="chat.php?produk=<?= $produk['id'] ?>&user=<?= $produk['uid'] ?>"
                   class="tombol tombol-merah tombol-lebar">
                    Chat Penjual
                </a>
                <a href="javascript:void(0)"
                   class="tombol tombol-garis tombol-favorit<?php if ($di_wishlist): ?> favorit-aktif<?php endif; ?>"
                   onclick="toggleWishlist(event, <?= $produk['id'] ?>, this)">
                    <?php if ($di_wishlist): ?><img src="icon/wishlist.svg"><?php else: ?><img src="icon/wish.svg"><?php endif; ?>
                </a>
            <?php elseif (!$sudah_login): ?>
                <a href="login.php" class="tombol tombol-merah tombol-lebar">
                    Login untuk Chat Penjual
                </a>
            <?php else: ?>
                <a href="profil.php" class="tombol tombol-garis">Lihat di Profilmu</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!--seller yang jual barang -->
        <a href="profil.php?id=<?= $produk['uid'] ?>" class="penjual">
            <div class="avatar avatar-sedang">
                <?php if ($produk['avatar']): ?>
                    <img src="uploads/<?= htmlspecialchars($produk['avatar']) ?>" alt="">
                <?php else: ?>
                    <img src="icon/profile.svg">
                <?php endif; ?>
            </div>
            <div class="isi-penjual">
                <div class="nama-penjual">@<?= htmlspecialchars($produk['username']) ?></div>
                <div class="sub-penjual">
                    <?= htmlspecialchars($produk['seller_nama']) ?> · <?= $produk['total_produk_seller'] ?> produk tersedia
                </div>
            </div>
            <span class="panah">›</span>
        </a>

        <?php if ($produk['deskripsi']): ?>
        <div class="deskripsi">
            <h4>Deskripsi</h4>
            <p><?= nl2br(htmlspecialchars($produk['deskripsi'])) ?></p>
        </div>
        <?php endif; ?>
        
        <div class="deskripsi">
            <h4>Info Produk</h4>
            <p class="daftar-info">
                <?php if ($produk['ukuran']): ?><span> Ukuran: <?= htmlspecialchars($produk['ukuran']) ?></span><?php endif; ?>
                <span> Kategori: <?= ucfirst($produk['kategori']) ?></span>
                <span> Diposting: <?= date('d M Y', strtotime($produk['created_at'])) ?></span>
            </p>
        </div>
    </div>
</div>

<!--produk lain yang dijual si seller-->
<?php if ($produk_lain_list): ?>
<div class="bagian bagian-garis">
    <div class="kepala">
        <div class="judul">Produk lain dari @<?= htmlspecialchars($produk['username']) ?></div>
        <a href="profil.php?id=<?= $produk['uid'] ?>" class="semua">Lihat semua →</a>
    </div>
    <div class="kisi">
        <?php foreach ($produk_lain_list as $produk_lain): ?>
        <a href="detail.php?id=<?= $produk_lain['id'] ?>" class="kartu">
            <div class="gambar-kartu">
                <img src="uploads/<?= htmlspecialchars($produk_lain['gambar']) ?>" alt="" loading="lazy">
            </div>
            <div class="isi-kartu">
                <div class="nama-kartu"><?= htmlspecialchars($produk_lain['nama']) ?></div>
                <div class="harga-kartu">Rp <?= number_format($produk_lain['harga'], 0, ',', '.') ?></div>
                <div class="meta-kartu">
                    <span class="titik-kondisi <?= $produk_lain['kondisi'] ?>"></span>
                    <span class="kondisi-kartu"><?= labelKondisi($produk_lain['kondisi']) ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="jarak"></div>
<nav class="nav-bawah">
    <a href="index.php" class="item-nav"><img src="icon/home.svg" alt="home" class="ikon-nav">Beranda</a>
    <a href="jelajah.php" class="item-nav"><img src="icon/search.svg" alt="search" class="ikon-nav">Jelajah</a>
    <a href="jual.php" class="item-nav"><img src="icon/add.svg" alt="sell" class="ikon-nav">Jual</a>
    <a href="chat.php" class="item-nav"><img src="icon/chat.svg" alt="chat" class="ikon-nav">Chat</a>
    <a href="<?php if ($sudah_login): ?>profil.php<?php else: ?>login.php<?php endif; ?>" class="item-nav"><img src="icon/profile.svg" alt="profil" class="ikon-nav">Profil</a>
</nav>

<script>
function gantiGambar(src, thumb) {
    document.getElementById('gambar-el').src = src;
    document.querySelectorAll('.miniatur-item').forEach(function(t) { t.classList.remove('aktif'); });
    thumb.classList.add('aktif');
}
</script>
</div>
<script src="js/ajax.js"></script>
</body>
</html>
