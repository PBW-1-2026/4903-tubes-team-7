<?php
session_start();
require_once "config/koneksi.php";
require_once "proses_chat.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat — Tel-U Thrift</title>
    <link rel="stylesheet" href="css/chat.css?v=2">

</head>
<body>
<div class="pembungkus">

<nav class="navigasi">
    <a href="index.php" class="logo-navigasi">Tel-U Thrift</a>
    <div class="navigasi-kanan">
        <a href="profil.php" class="tautan-navigasi"><?= htmlspecialchars($_SESSION['username']) ?></a>
    </div>
</nav>

<div class="tata-letak-chat">

    <!-- DAFTAR PERCAKAPAN -->
    <div class="daftar-chat" id="chatList">
        <div class="kepala-daftar-chat">Pesan</div>

        <?php if (mysqli_num_rows($result_list) == 0): ?>
        <div style="padding:2rem;text-align:center;color:#aaa;">
            <div style="margin-bottom:8px;"><img src="icon/chat.svg" alt="chat" style="width: 32px; height: 32px; opacity: 0.3;"></div>
            <p style="font-size:13px;">Belum ada percakapan</p>
            <a href="jelajah.php" class="tombol tombol-garis tombol-kecil" style="margin-top:1rem;">Mulai belanja</a>
        </div>
        <?php endif; ?>

        <?php while ($c = mysqli_fetch_assoc($result_list)): ?>
        <a href="chat.php?id=<?= $c['id'] ?>"
           class="item-chat <?= $conv_id == $c['id'] ? 'aktif' : '' ?>">
            <div class="avatar avatar-kecil">
                <?php if ($c['partner_avatar']): ?>
                    <img src="uploads/<?= htmlspecialchars($c['partner_avatar']) ?>" alt="">
                <?php else: ?>
                    <img src="icon/profile.svg" alt="User" style="object-fit: contain; padding: 4px;">
                <?php endif; ?>
            </div>
            <div class="info-item-chat">
                <div class="nama-item-chat">@<?= htmlspecialchars($c['partner_username']) ?></div>
                <div class="akhir-item-chat">
                    <?php if ($c['produk_nama']): ?>
                        <span style="display:flex; align-items:center; gap:4px;"><img src="icon/package.svg" alt="Package" style="width: 1em; height: 1em;"> <?= htmlspecialchars($c['produk_nama']) ?></span>
                    <?php else: ?>
                        <?= htmlspecialchars($c['pesan_terakhir'] ?? 'Mulai percakapan...') ?>
                    <?php endif; ?>
                </div>
            </div>

        </a>
        <?php endwhile; ?>
    </div>

    <!-- JENDELA CHAT -->
    <div class="jendela-chat <?= $conv_aktif ? 'buka' : '' ?>" id="chatWindow">

        <?php if (!$conv_aktif): ?>
        <!-- TIDAK ADA PERCAKAPAN DIPILIH -->
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#aaa;">
            <div style="margin-bottom:12px;"><img src="icon/chat.svg" alt="chat" style="width: 48px; height: 48px; opacity: 0.3;"></div>
            <p style="font-weight:600;color:#888;">Pilih percakapan</p>
            <p style="font-size:12px;margin-top:4px;">atau chat penjual dari halaman produk</p>
        </div>

        <?php else: ?>

        <!-- HEADER CHAT -->
        <div class="kepala-chat">
            <a href="chat.php" style="color:#aaa;font-size:18px;margin-right:4px;text-decoration:none;" id="tombolKembali">←</a>
            <div class="avatar avatar-kecil">
                <?php if ($conv_aktif['partner_avatar']): ?>
                    <img src="uploads/<?= htmlspecialchars($conv_aktif['partner_avatar']) ?>" alt="">
                <?php else: ?>
                    <img src="icon/profile.svg" alt="User" style="object-fit: contain; padding: 4px;">
                <?php endif; ?>
            </div>
            <div style="flex:1;">
                <div class="nama-kepala-chat">@<?= htmlspecialchars($conv_aktif['partner_username']) ?></div>
                <div class="sub-kepala-chat"><?= htmlspecialchars($conv_aktif['partner_nama']) ?></div>
            </div>

            <!-- INFO PRODUK (kalau ada) -->
            <?php if ($conv_aktif['produk_id']): ?>
            <a href="detail.php?id=<?= $conv_aktif['produk_id'] ?>"
               style="display:flex;align-items:center;gap:8px;padding:6px 10px;border:1px solid #eee;border-radius:8px;text-decoration:none;color:inherit;">
                <div style="width:36px;height:36px;background:#f5f5f5;border-radius:6px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:16px;">
                    <?php if ($conv_aktif['produk_gambar']): ?>
                        <img src="uploads/<?= htmlspecialchars($conv_aktif['produk_gambar']) ?>" style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                        <img src="icon/apparel.svg" alt="Produk" style="object-fit: contain; padding: 10%; opacity: 0.2; width: 100%; height: 100%;">
                    <?php endif; ?>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:600;color:#111;">
                        <?= htmlspecialchars(mb_strimwidth($conv_aktif['produk_nama'], 0, 20, '...')) ?>
                    </div>
                    <div style="font-size:11px;color:#E31E24;">
                        Rp <?= number_format($conv_aktif['produk_harga'], 0, ',', '.') ?>
                    </div>
                </div>
            </a>
            <?php endif; ?>
        </div>

        <!-- PESAN-PESAN -->
        <div class="pesan-chat" id="areaPesan">
            <?php if (mysqli_num_rows($pesan_list) == 0): ?>
            <div style="text-align:center;color:#aaa;font-size:12px;padding:1rem 0;">
                Mulai percakapan! Tanyakan tentang produk ini.
            </div>
            <?php endif; ?>

            <?php while ($m = mysqli_fetch_assoc($pesan_list)):
                $saya = ($m['pengirim_id'] == $user_id);
            ?>
            <div class="pesan <?= $saya ? 'pesan-keluar' : 'pesan-masuk' ?>">
                <div class="gelembung-pesan"><?= nl2br(htmlspecialchars($m['isi'])) ?></div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- INPUT PESAN -->
        <div class="area-input-chat">
            <form action="chat.php" method="POST"
                  style="display:flex;gap:8px;flex:1;align-items:center;">
                <input type="hidden" name="conv_id" value="<?= $conv_id ?>">
                <input class="input-chat" type="text" name="isi" id="inputPesan" placeholder="Ketik pesan..." autocomplete="off" required>
                <button type="submit" class="kirim-chat" style="display:flex; align-items:center; justify-content:center;"><svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg></button>
            </form>
        </div>

        <?php endif; ?>
    </div>
</div>

<nav class="navigasi-bawah">
    <a href="index.php" class="item-navbawah"><img src="icon/home.svg" alt="home" class="ikon-navbawah">Beranda</a>
    <a href="jelajah.php" class="item-navbawah"><img src="icon/search.svg" alt="search" class="ikon-navbawah">Jelajah</a>
    <a href="jual.php" class="item-navbawah"><img src="icon/add.svg" alt="add" class="ikon-navbawah">Jual</a>
    <a href="chat.php" class="item-navbawah aktif"><img src="icon/chat.svg" alt="chat" class="ikon-navbawah">Chat</a>
    <a href="profil.php" class="item-navbawah"><img src="icon/profile.svg" alt="profile" class="ikon-navbawah">Profil</a>
</nav>
<script>
// Auto scroll ke pesan terbawah
var areaPesan = document.getElementById('areaPesan');
if (areaPesan) areaPesan.scrollTop = areaPesan.scrollHeight;

// Kirim pesan dengan Enter
var inputPesan = document.getElementById('inputPesan');
if (inputPesan) {
    inputPesan.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            this.closest('form').submit();
        }
    });
}
</script>
</div><!-- end wrapper -->
</body>
</html>