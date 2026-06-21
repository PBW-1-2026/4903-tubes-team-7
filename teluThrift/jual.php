<?php require "proses_jual.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jual Barang Tel-U Thrift</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="bungkus">

<nav class="navigasi">
    <h2>Tel-U Thrift</h2>
    <div class="kanan">
        <a href="profil.php" class="tautan">Produkku</a>
        <a href="logout.php" class="tombol tombol-garis tombol-kecil">Keluar</a>
    </div>
</nav>

<div class="halaman-jual">
    <h2>Jual Barang</h2>
    <p class="desk-unggah">
        Isi detail barangmu. Semakin lengkap, semakin cepat terjual!
    </p>

    <?php if ($error): ?>
    <div class="peringatan peringatan-gagal"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="jual.php" method="POST" enctype="multipart/form-data">

        <!--form buat upload foto yaa-->
        <div class="area-unggah" id="areaUnggah" onclick="document.getElementById('inputGambar').click()">
            <div class="ikon-unggah"><img src="icon/image.svg"></div>
            <p>Klik untuk upload foto</p>
            <span>Maksimal 5 foto · JPG, PNG, WebP</span>
            <input type="file" id="inputGambar" name="gambar[]" multiple accept="image/*" style="display:none;" onchange="previewGambar(this)">
        </div>
        <div class="kisi-pratinjau" id="kisiPratinjau"></div>

        <div class="baris-form">
            <div class="grup-form">
                <label class="label-form">Nama Produk *</label>
                <input type="text" class="kontrol-form" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" placeholder="Cth: Kaos Oversize Navy" required>
            </div>
            <div class="grup-form">
                <label class="label-form">Brand</label>
                <input type="text" class="kontrol-form" name="brand" value="<?= htmlspecialchars($_POST['brand'] ?? '') ?>" placeholder="Cth: Uniqlo, H&M, ...">
            </div>
        </div>

        <div class="grup-form">
            <label class="label-form">Harga *</label>
            <div class="bungkus-harga">
                <span class="awalan-harga">Rp</span>
                <input type="number" class="kontrol-form input-harga" name="harga" value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>" placeholder="" min="1000" required>
            </div>
        </div>

        <!--selector buat kategori barangnya ;)-->
        <div class="baris-form">
            <div class="grup-form">
                <label class="label-form">Kategori *</label>
                <select class="kontrol-form" name="kategori" required>
                    <option value="">Pilih Kategori</option>
                    <?php
                    $kategori_list = ['atasan','bawahan','outerwear','dress','sepatu','tas','aksesoris'];
                    foreach ($kategori_list as $kategori):
                    ?>
                    <option value="<?= $kategori ?>" <?= ($_POST['kategori'] ?? '') == $kategori ? 'selected' : '' ?>>
                        <?= ucfirst($kategori) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grup-form">
                <label class="label-form">Gender</label>
                <select class="kontrol-form" name="gender">
                    <option value="unisex" <?= ($_POST['gender'] ?? '') == 'unisex' ? 'selected' : '' ?>>Unisex</option>
                    <option value="wanita" <?= ($_POST['gender'] ?? '') == 'wanita' ? 'selected' : '' ?>>Wanita</option>
                    <option value="pria" <?= ($_POST['gender'] ?? '') == 'pria' ? 'selected' : '' ?>>Pria</option>
                </select>
            </div>
        </div>

        <!--selector ukuran-->
        <div class="baris-form">
            <div class="grup-form">
                <label class="label-form">Ukuran</label>
                <select class="kontrol-form" name="ukuran">
                    <option value="">Pilih Ukuran</option>
                    <?php foreach (['XS','S','M','L','XL','XXL','Free'] as $ukuran): ?>
                    <option value="<?= $ukuran ?>" <?= ($_POST['ukuran'] ?? '') == $ukuran ? 'selected' : '' ?>>
                        <?= $ukuran ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grup-form">
                <label class="label-form">Kondisi *</label>
                <select class="kontrol-form" name="kondisi" required>
                    <option value="">Pilih Kondisi</option>
                    <option value="sangat_baik" <?= ($_POST['kondisi'] ?? '') == 'sangat_baik' ? 'selected' : '' ?>>Sangat Baik</option>
                    <option value="baik" <?= ($_POST['kondisi'] ?? '') == 'baik' ? 'selected' : '' ?>>Baik</option>
                    <option value="cukup_baik" <?= ($_POST['kondisi'] ?? '') == 'cukup_baik'  ? 'selected' : '' ?>>Cukup Baik</option>
                </select>
                <div class="petunjuk-form">Sangat Baik = seperti baru · Baik = mulus · Cukup Baik = ada sedikit cacat</div>
            </div>
        </div>

        <div class="grup-form">
            <label class="label-form">Deskripsi</label>
            <textarea class="kontrol-form" name="deskripsi" placeholder="Ceritakan kondisi barang, keunikan, atau info lainnya..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="tombol tombol-merah tombol-besar tombol-lebar">Upload & Jual Sekarang</button>
        <a href="profil.php" class="tombol tombol-transparan tombol-lebar tombol-batal">Batal</a>

    </form>
</div>

<div class="jarak"></div>

<nav class="nav-bawah">
    <a href="index.php" class="item-nav"><img src="icon/home.svg" alt="home" class="ikon-nav">Beranda</a>
    <a href="jelajah.php" class="item-nav"><img src="icon/search.svg" alt="search" class="ikon-nav">Jelajah</a>
    <a href="jual.php" class="item-nav aktif"><img src="icon/add.svg" alt="jual" class="ikon-nav">Jual</a>
    <a href="chat.php" class="item-nav"><img src="icon/chat.svg" alt="chat" class="ikon-nav">Chat</a>
    <a href="profil.php" class="item-nav"><img src="icon/profile.svg" alt="profil" class="ikon-nav">Profil</a> 
</nav>

<script>
function previewGambar(input) {
    var grid = document.getElementById('kisiPratinjau');
    grid.innerHTML = '';
    var files = Array.from(input.files).slice(0, 5);
    files.forEach(function(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var div = document.createElement('div');
            div.className = 'pratinjau';
            div.innerHTML = '<img src="' + e.target.result + '" alt="preview">';
            grid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}
</script>
</div>
</body>
</html>
