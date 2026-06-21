<?php require "proses_profil.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@<?= htmlspecialchars($user['username']) ?> Tel-U Thrift</title>
    <link rel="stylesheet" href="css/profil.css">
</head>
<body>
<div class="bungkus">

<nav class="navigasi">
    <h2>Tel-U Thrift</h2>
    <div class="kanan">
        <?php if ($milik_sendiri): ?>
            <a href="logout.php" class="tombol tombol-garis tombol-kecil">Keluar</a>
        <?php else: ?>
            <a href="jelajah.php" class="tautan">← Jelajah</a>
        <?php endif; ?>
    </div>
</nav>

<div class="profil-header">
    <div class="avatar avatar-besar">
        <?php if ($user['avatar']): ?>
            <img src="uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="">
        <?php else: ?><img src="icon/user.svg"><?php endif; ?>
    </div>
    <div class="profil-info">
        <div class="profil-nama"><?= htmlspecialchars($user['nama']) ?></div>
        <div class="profil-username">@<?= htmlspecialchars($user['username']) ?></div>
        <?php if ($user['fakultas']): ?>
        <div class="profil-fakultas"><?= htmlspecialchars($user['fakultas']) ?></div>
        <?php endif; ?>
        <?php if ($user['bio']): ?>
        <div class="profil-bio"><?= htmlspecialchars($user['bio']) ?></div>
        <?php endif; ?>
        <div class="profil-stats">
            <div><div class="profil-stat-num"><?= $jml_aktif ?></div><div class="profil-stat-lbl">Dijual</div></div>
            <div><div class="profil-stat-num"><?= $jml_terjual ?></div><div class="profil-stat-lbl">Terjual</div></div>
            <?php if ($milik_sendiri): ?>
            <div><div class="profil-stat-num"><?= $jml_wishlist ?></div><div class="profil-stat-lbl">Wishlist</div></div>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($milik_sendiri): ?>
        <button class="tombol tombol-garis tombol-kecil" onclick="toggleEditForm()">Edit Profil</button>
    <?php else: ?>
        <a href="chat.php?user=<?= $user['id'] ?>&produk=0" class="tombol tombol-merah tombol-kecil">Chat</a>
    <?php endif; ?>
</div>

<?php if ($milik_sendiri): ?>
<div id="formEdit" class="form-edit">
    <form action="proses_editprofil.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="aksi" value="edit">

        <!--Upload foto profil di edit -->
        <div class="unggah-avatar">
            <div class="pratinjau-avatar" id="pratinjauAvatarEdit"
                 onclick="document.getElementById('inputAvatarEdit').click()">
                <?php if ($user['avatar']): ?>
                    <img src="uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="">
                <?php else: ?><img src="icon/user.svg"><?php endif; ?>
            </div>
            <input type="file" id="inputAvatarEdit" name="avatar" accept="image/*" onchange="previewAvatarEdit(this)">
            <span class="petunjuk-unggah">Klik foto untuk ganti</span>
        </div>

        <div class="baris-form">
            <div class="grup-form">
                <label class="label-form">Nama Lengkap</label>
                <input type="text" class="kontrol-form" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
            </div>
            <div class="grup-form">
                <label class="label-form">Fakultas</label>
                <input type="text" class="kontrol-form" name="fakultas" value="<?= htmlspecialchars($user['fakultas'] ?? '') ?>" placeholder="Cth: Fakultas Informatika">
            </div>
        </div>
        <div class="grup-form">
            <label class="label-form">Bio</label>
            <textarea class="kontrol-form" name="bio" rows="2" placeholder="Ceritakan sedikit tentang dirimu..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
        </div>
        <div class="aksi-form">
            <button type="submit" class="tombol tombol-merah tombol-kecil">Simpan</button>
            <button type="button" class="tombol tombol-transparan tombol-kecil" onclick="toggleEditForm()">Batal</button>
        </div>
    </form>
</div>
<?php endif; ?>

<div class="profil-tabs">
    <a href="profil.php<?php if (!$milik_sendiri) { echo '?id=' . $target_id; } ?>"
       class="profil-tab <?php if ($tab == 'aktif') { echo 'aktif'; } ?>">
        Dijual (<?= $jml_aktif ?>)
    </a>
    <a href="profil.php?<?php if (!$milik_sendiri) { echo 'id=' . $target_id . '&'; } ?>tab=terjual"
       class="profil-tab <?php if ($tab == 'terjual') { echo 'aktif'; } ?>">
        Terjual (<?= $jml_terjual ?>)
    </a>
    <?php if ($milik_sendiri): ?>
    <a href="profil.php?tab=wishlist"
       class="profil-tab <?php if ($tab == 'wishlist') { echo 'aktif'; } ?>">
        Wishlist (<?= $jml_wishlist ?>)
    </a>
    <?php endif; ?>
</div>

<div class="bagian">
    <?php if (mysqli_num_rows($result_produk) == 0): ?>
    <div class="keadaan-kosong">
        <p class="teks-kosong">
            <?php if ($tab=='aktif'): ?>
                Belum ada produk.   
            <?php elseif ($tab=='wishlist'): ?>
                Wishlist kosong. 
            <?php else: ?>Belum ada yang terjual.<?php endif; ?>
        </p>
    </div>
    <?php endif; ?>

    <div class="kisi-produk">
        <?php while ($p = mysqli_fetch_assoc($result_produk)): ?>
        <div>
            <a href="detail.php?id=<?= $p['id'] ?>" class="kartu-produk <?php if ($p['status'] == 'terjual') { echo 'terjual'; } ?>">
                <div class="gambar-produk">
                    <img src="uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="" loading="lazy">
                    <?php if ($p['status']=='terjual'): ?><span class="label-produk terjual">Terjual</span><?php endif; ?>
                </div>
                <div class="isi-produk">
                    <div class="prod-nama"><?= htmlspecialchars($p['nama']) ?></div>
                    <div class="prod-harga <?php if ($p['status'] == 'terjual') { echo 'coret'; } ?>">
                        Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                    </div>
                    <div class="meta-produk">
                        <span class="kondisi-dot <?= $p['kondisi'] ?>"></span>
                        <span class="prod-kondisi"><?= ucwords(str_replace('_',' ',$p['kondisi'])) ?></span>
                    </div>
                </div>
            </a>
            <?php if ($milik_sendiri && $tab=='aktif'): ?>
            <div class="aksi-produk">
                <form action="proses_terjual.php" method="POST">
                    <input type="hidden" name="aksi" value="terjual">
                    <input type="hidden" name="produk_id" value="<?= $p['id'] ?>">
                    <button type="submit" class="tombol tombol-garis tombol-kecil tombol-lebar tombol-terjual"
                            onclick="return confirm('Tandai terjual?')">Terjual</button>
                </form>
                <form action="proses_hapus.php" method="POST">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="produk_id" value="<?= $p['id'] ?>">
                    <button type="submit" class="tombol tombol-transparan tombol-kecil tombol-hapus"
                            onclick="return confirm('Hapus produk ini?')"><img src="icon/ex.svg"></button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<nav class="nav-bawah">
    <a href="index.php" class="item-nav"><img src="icon/home.svg" alt="home" class="ikon-nav">Beranda</a>
    <a href="jelajah.php" class="item-nav"><img src="icon/search.svg" alt="search" class="ikon-nav">Jelajah</a>
    <a href="jual.php" class="item-nav"><img src="icon/add.svg" alt="jual" class="ikon-nav">Jual</a>
    <a href="chat.php" class="item-nav"><img src="icon/chat.svg" alt="chat" class="ikon-nav">Chat</a>
    <a href="profil.php" class="item-nav aktif"><img src="icon/profile.svg" alt="profil" class="ikon-nav">Profil</a>
</nav>

</div>
<script>
function toggleEditForm() {
    var f = document.getElementById('formEdit');
    if (f.style.display === 'none') { f.style.display = 'block'; } else { f.style.display = 'none'; }
}
function previewAvatarEdit(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var p = document.getElementById('pratinjauAvatarEdit');
            p.innerHTML = '<img src="' + e.target.result + '" alt="preview">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
window.addEventListener('pageshow', function(e) {
    if (e.persisted) location.reload();
});
</script>
</body>
</html>
