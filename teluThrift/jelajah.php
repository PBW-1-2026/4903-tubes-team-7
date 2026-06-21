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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap">
</head>
<body>

<div class="bungkus">
    <nav class="nav-atas">
        <a href="../index.php" class="logo-nav">Tel-U Thrift</a>
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
        <button type="button" class="pil-filter" onclick="toggleSidebar()">
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
        <aside class="samping" id="samping">
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

            <div class="bar-urut">
                <div class="jumlah-hasil">Menampilkan <strong>6</strong> produk</div>
                <div class="kanan-urut">
                    <span style="font-size:12px;color:#aaa;">Urutkan</span>
                    <select class="pilih-urut">
                        <option>Terbaru</option>
                        <option>Termurah</option>
                        <option>Termahal</option>
                    </select>
                </div>
            </div>

            <div class="grid-produk">

            <!-- ini buat sementara dulu ya pak kartu nya hehe -->

                <a href="#" class="kartu-produk">
                    <div class="thumb-produk"></div>
                    <div class="badan-produk">
                        <div class="merek-produk">Uniqlo</div>
                        <div class="nama-produk">Plain White Cotton T-Shirt</div>
                        <div class="harga-produk">Rp79.000</div>
                        <div class="meta-produk">
                            <span class="ukuran-produk">L</span>
                            <span class="dot-kondisi sangat_baik"></span>
                            <span class="kondisi-produk">Sangat Baik</span>
                        </div>
                    </div>
                </a>

                <a href="#" class="kartu-produk">
                    <div class="thumb-produk"></div>
                    <div class="badan-produk">
                        <div class="merek-produk">H&M</div>
                        <div class="nama-produk">Floral Midi Dress</div>
                        <div class="harga-produk">Rp149.000</div>
                        <div class="meta-produk">
                            <span class="ukuran-produk">M</span>
                            <span class="dot-kondisi baik"></span>
                            <span class="kondisi-produk">Baik</span>
                        </div>
                    </div>
                </a>

                <a href="#" class="kartu-produk">
                    <div class="thumb-produk"></div>
                    <div class="badan-produk">
                        <div class="merek-produk">Zara</div>
                        <div class="nama-produk">Classic Beige Blazer</div>
                        <div class="harga-produk">Rp259.000</div>
                        <div class="meta-produk">
                            <span class="ukuran-produk">M</span>
                            <span class="dot-kondisi sangat_baik"></span>
                            <span class="kondisi-produk">Sangat Baik</span>
                        </div>
                    </div>
                </a>

                <a href="#" class="kartu-produk">
                    <div class="thumb-produk"></div>
                    <div class="badan-produk">
                        <div class="merek-produk">Nike</div>
                        <div class="nama-produk">Air Force 1 White</div>
                        <div class="harga-produk">Rp899.000</div>
                        <div class="meta-produk">
                            <span class="ukuran-produk">42</span>
                            <span class="dot-kondisi cukup_baik"></span>
                            <span class="kondisi-produk">Cukup Baik</span>
                        </div>
                    </div>
                </a>

                <a href="#" class="kartu-produk">
                    <div class="thumb-produk"></div>
                    <div class="badan-produk">
                        <div class="merek-produk">Adidas</div>
                        <div class="nama-produk">Originals Backpack</div>
                        <div class="harga-produk">Rp199.000</div>
                        <div class="meta-produk">
                            <span class="ukuran-produk">Free</span>
                            <span class="dot-kondisi baik"></span>
                            <span class="kondisi-produk">Baik</span>
                        </div>
                    </div>
                </a>

                <a href="#" class="kartu-produk">
                    <div class="thumb-produk"></div>
                    <div class="badan-produk">
                        <div class="merek-produk">Casio</div>
                        <div class="nama-produk">Classic Digital Watch</div>
                        <div class="harga-produk">Rp129.000</div>
                        <div class="meta-produk">
                            <span class="ukuran-produk">Free</span>
                            <span class="dot-kondisi sangat_baik"></span>
                            <span class="kondisi-produk">Sangat Baik</span>
                        </div>
                    </div>
                </a>
            </div>
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

<script>
function toggleSidebar() {
    document.getElementById('samping').classList.toggle('buka');
}
</script>
</body>
</html>
