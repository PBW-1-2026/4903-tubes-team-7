<?php
session_start();
require_once "config/koneksi.php";

$sudah_login = isset($_SESSION['user_id']);
if ($sudah_login) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = 0;
}

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

if (isset($_GET['gender']) && $_GET['gender'] !== '') {
    $gender = trim($_GET['gender']);
} else {
    $gender = '';
}

if (isset($_GET['sort']) && $_GET['sort'] !== '') {
    $sort = $_GET['sort'];
} else {
    $sort = 'terbaru';
}

if (isset($_GET['sale']) && $_GET['sale'] == '1') {
    $sale = true;
} else {
    $sale = false;
}

$kategori_list = ['atasan','bawahan','outerwear','dress','sepatu','tas','aksesoris'];
$ukuran_list   = ['XS','S','M','L','XL','XXL','Free'];

if (isset($_GET['kondisi'])) {
    if (is_array($_GET['kondisi'])) {
        $kondisi_aktif = $_GET['kondisi'];
    } else {
        $kondisi_aktif = [];
    }
} else {
    $kondisi_aktif = [];
}

if (isset($_GET['ukuran'])) {
    if (is_array($_GET['ukuran'])) {
        $ukuran_aktif = $_GET['ukuran'];
    } else {
        $ukuran_aktif = [];
    }
} else {
    $ukuran_aktif = [];
}

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
        <button class="pil-filter" onclick="toggleSidebar()">
            <img src="icon/filter.svg" style="width:16px;height:16px;"> Filter
        </button>
        <button class="tombol tombol-merah tombol-kecil" onclick="muat()">Cari</button>
    </div>

    <div class="baris-kategori">
        <a href="#" class="pil-kategori <?php if ($kategori=='') echo 'aktif'; ?>" onclick="gantiKategori('', this); return false;">Semua</a>
        <?php foreach ($kategori_list as $k): ?>
        <a href="#" class="pil-kategori <?php if ($kategori==$k) echo 'aktif'; ?>" onclick="gantiKategori('<?php echo $k; ?>', this); return false;"><?php echo ucfirst($k); ?></a>
        <?php endforeach; ?>
    </div>

    <div class="halaman-badan">

        <input type="hidden" id="inputGender" value="<?php echo $gender; ?>">

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
                <?php
                $daftar_kondisi = ['sangat_baik'=>'Sangat Baik','baik'=>'Baik','cukup_baik'=>'Cukup Baik'];
                foreach ($daftar_kondisi as $val => $label):
                ?>
                <label class="opsi-filter">
                    <input type="checkbox" name="kondisi" value="<?php echo $val; ?>" <?php if (in_array($val, $kondisi_aktif)) echo 'checked'; ?> onchange="muat()">
                    <?php echo $label; ?>
                </label>
                <?php endforeach; ?>
            </div>

            <div class="grup-filter">
                <div class="judul-grup">Ukuran</div>
                <div class="chip-ukuran">
                    <?php foreach ($ukuran_list as $u): ?>
                    <div class="chip-wrap">
                        <input type="checkbox" name="ukuran" value="<?php echo $u; ?>" style="display:none;" <?php if (in_array($u, $ukuran_aktif)) echo 'checked'; ?>>
                         <button type="button" class="item-ukuran <?php if (in_array($u, $ukuran_aktif)) echo 'aktif'; ?>" onclick="piliUkuran(event, this)"><?php echo $u; ?></button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="grup-filter">
                <div class="judul-grup">Harga</div>
                <div style="display:flex;gap:6px;align-items:center;margin-bottom:8px;">
                    <input class="form-control" type="number" id="hargaMin" placeholder="Min" value="<?php if (isset($_GET['harga_min']) && $_GET['harga_min']>0) echo (int)$_GET['harga_min']; ?>" style="padding:6px 8px;font-size:12px;border:1.5px solid #eee;border-radius:6px;width:80px;outline:none;font-family:Inter,sans-serif;">
                    <span style="color:#ccc;flex-shrink:0;">-</span>
                    <input class="form-control" type="number" id="hargaMax" placeholder="Max" value="<?php if (isset($_GET['harga_max']) && $_GET['harga_max']>0) echo (int)$_GET['harga_max']; ?>" style="padding:6px 8px;font-size:12px;border:1.5px solid #eee;border-radius:6px;width:80px;outline:none;font-family:Inter,sans-serif;">
                    <button class="tombol-filter" onclick="muat()">Terapkan Harga</button>
                </div>
            </div>

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
