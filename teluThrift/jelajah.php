<?php
require_once "config/koneksi.php";

$sudah_login = isset($_SESSION['user_id']);

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
if (isset($_GET['sort']) && $_GET['sort'] !== '') {
    $sort = $_GET['sort'];
} else {
    $sort = 'terbaru';
}
if (isset($_GET['kondisi'])) {
    $kondisi = (array)$_GET['kondisi'];
} else {
    $kondisi = [];
}

if (isset($_GET['gender']) && $_GET['gender'] !== '') {
    $gender = trim($_GET['gender']);
} else {
    $gender = '';
}
if (isset($_GET['sale']) && $_GET['sale'] == '1') {
    $sale = true;
} else {
    $sale = false;
}

$kategori_list = ['atasan','bawahan','outerwear','dress','sepatu','tas','aksesoris'];

$res_kat = mysqli_query($conn, "SELECT kategori, COUNT(*) AS jml FROM produk WHERE status='tersedia' GROUP BY kategori");
$jml_kategori = [];
while ($k = mysqli_fetch_assoc($res_kat)) {
    $jml_kategori[$k['kategori']] = $k['jml'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajah - Tel-U Thrift</title>
    <link rel="stylesheet" href="css/jelajah.css">
    <script>var filterGender = '<?php echo $gender; ?>', filterSale = <?php if ($sale) echo 'true'; else echo 'false'; ?>;</script>
    <script src="js/ajax.js"></script>
</head>
<body onload="muat()">

<div class="bungkus">

    <nav class="nav-atas">
        <a href="index.php" class="logo-nav">Tel-U Thrift</a>
        <div class="tab-nav">
            <a href="jelajah.php" class="tab-nav-item <?php if (!isset($_GET['gender']) && !isset($_GET['sale'])) echo 'aktif'; ?>">Semua</a>
            <a href="jelajah.php?gender=wanita" class="tab-nav-item <?php if (isset($_GET['gender']) && $_GET['gender']=='wanita') echo 'aktif'; ?>">Wanita</a>
            <a href="jelajah.php?gender=pria" class="tab-nav-item <?php if (isset($_GET['gender']) && $_GET['gender']=='pria') echo 'aktif'; ?>">Pria</a>
            <a href="jelajah.php?sale=1" class="tab-nav-item <?php if (isset($_GET['sale'])) echo 'aktif'; ?>">Sale</a>
        </div>
        <div class="kanan-nav">
            <?php if ($sudah_login): ?>
                <a href="jual.php" class="tautan-nav">Tambah produk</a>
                <a href="profil.php" class="tautan-nav"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <?php else: ?>
                <a href="login.php" class="tombol tombol-merah tombol-kecil">Masuk & Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="bar-cari">
        <div class="bungkus-input">
            <img src="icon/search.svg" alt="cari" style="width:18px;height:18px;display:block;">
            <input type="text" id="inputCari" value="<?php echo htmlspecialchars($q); ?>" placeholder="Cari pakaian, brand..." onkeypress="if(event.key==='Enter') muat()">
        </div>
        <button class="tombol tombol-merah tombol-kecil" onclick="muat()">Cari</button>
        <button type="button" class="pil-filter" onclick="toggleSidebar()">
            <img src="icon/filter.svg" style="width:16px;height:16px;"> Filter
         </button>
    </div>

    <div class="baris-kategori">
        <a href="#" class="pil-kategori <?php if ($kategori=='') echo 'aktif'; ?>" onclick="gantiKategori('', this); return false;">Semua</a>
        <?php foreach ($kategori_list as $k): ?>
        <a href="#" class="pil-kategori <?php if ($kategori==$k) echo 'aktif'; ?>" onclick="gantiKategori('<?php echo $k; ?>', this); return false;"><?php echo ucfirst($k); ?></a>
        <?php endforeach; ?>
    </div>

    <div class="halaman-badan">

        <aside class="samping" id="samping">
            <div class="grup-filter">
                <div class="judul-grup">Kategori</div>
                <label class="opsi-filter">
                    <input type="radio" name="kategori" value="" <?php if ($kategori=='') echo 'checked'; ?> onchange="muat()">
                    Semua Kategori
                </label>
                <?php foreach ($kategori_list as $k): ?>
                <label class="opsi-filter">
                    <input type="radio" name="kategori" value="<?php echo $k; ?>" <?php if ($kategori==$k) echo 'checked'; ?> onchange="muat()">
                    <?php echo ucfirst($k); ?>
                    <span style="margin-left:auto;font-size:11px;color:#ccc;"><?php if (isset($jml_kategori[$k])) echo $jml_kategori[$k]; else echo 0; ?></span>
                </label>
                <?php endforeach; ?>
            </div>

            <div class="grup-filter">
                <div class="judul-grup">Kondisi</div>
                <?php $kondisi_list = ['sangat_baik'=>'Sangat Baik','baik'=>'Baik','cukup_baik'=>'Cukup Baik']; ?>
                <?php foreach ($kondisi_list as $val => $label): ?>
                <label class="opsi-filter">
                    <input type="checkbox" name="kondisi" value="<?php echo $val; ?>" onchange="muat()"<?php if (in_array($val, $kondisi)) echo ' checked'; ?>>
                    <?php echo $label; ?>
                </label>
                <?php endforeach; ?>
            </div>

            <div class="grup-filter">
                <div class="judul-grup">Urutkan</div>
                <label class="opsi-filter">
                    <input type="radio" name="sort" value="terbaru" <?php if ($sort=='terbaru') echo 'checked'; ?> onchange="muat()"> Terbaru
                </label>
                <label class="opsi-filter">
                    <input type="radio" name="sort" value="termurah" <?php if ($sort=='termurah') echo 'checked'; ?> onchange="muat()"> Termurah
                </label>
                <label class="opsi-filter">
                    <input type="radio" name="sort" value="termahal" <?php if ($sort=='termahal') echo 'checked'; ?> onchange="muat()"> Termahal
                </label>
            </div>

            <button class="tombol-filter" onclick="muat()">Cari</button>
            <a href="#" class="reset-filter" onclick="resetFilter(); return false;">Reset filter</a>
        </aside>

        <main class="konten-utama">
            <div id="hasilProduk">
                <div class="memuat">
                    <div class="pemutar"></div>
                    Memuat produk...
                </div>
            </div>
        </main>
    </div>

    <nav class="nav-bawah">
        <a href="index.php" class="item-bawah">
            <img src="icon/home.svg" class="ikon-bawah"> Beranda
        </a>
        <a href="jelajah.php" class="item-bawah aktif">
            <img src="icon/search.svg" class="ikon-bawah"> Jelajah
        </a>
        <a href="jual.php" class="item-bawah">
            <img src="icon/add.svg" class="ikon-bawah"> Jual
        </a>
        <a href="chat.php" class="item-bawah">
            <img src="icon/chat.svg" class="ikon-bawah"> Chat
        </a>
        <?php if ($sudah_login): ?>
        <a href="profil.php" class="item-bawah">
        <?php else: ?>
        <a href="login.php" class="item-bawah">
        <?php endif; ?>
            <img src="icon/profile.svg" class="ikon-bawah"> Profil
        </a>
    </nav>

</div>
<script src="js/main.js"></script>
</body>
</html>
