<?php
session_start();
require_once "config/koneksi.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tel-U Thrift</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
<div class="bungkus">

<div class="halaman">
    <div class="kartu">

        <div class="logo">
            <h2>Tel-U Thrift</h2>
        </div>

        <div class="tab">
            <a href="login.php" class="tab-item">Masuk</a>
            <a href="register.php" class="tab-item aktif">Daftar</a>
        </div>

        <!--ini bagian formnya yaa ges-->
        <form action="proses_register.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="register">

            <div class="unggah-avatar">
                <div class="pratinjau" id="pratinjauAvatar"
                     onclick="document.getElementById('inputAvatar').click()">
                     <img src="icon/user.svg">
                </div>
                
                <input type="file" id="inputAvatar" name="avatar" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
                <span class="ket-upload">Klik untuk upload foto profil (opsional)</span>
            </div>

            <div class="sejajar">
                <div class="grup">
                    <label class="label">Nama Lengkap *</label>
                    <input type="text" class="input" name="nama" value="" placeholder="Nama kamu" required>
                </div>
                <div class="grup">
                    <label class="label">Username *</label>
                    <input type="text" class="input" name="username" value="" placeholder="user_123" required>
                </div>
            </div>
            <div class="grup">
                <label class="label">Email *</label>
                <input type="email" class="input" name="email" value="" placeholder="nama@student.telkomuniversity.ac.id" required>
                <div class="bantuan">Gunakan email Telkom University kamu</div>
            </div>
            <div class="grup">
                <label class="label">NIM</label>
                <input type="text" class="input" name="nim" value="" placeholder="123456789">
            </div>
            <div class="grup">
                <label class="label">Password *</label>
                <input type="password" class="input" name="password" placeholder="Min. 6 karakter" required>
            </div>
            <div class="grup">
                <label class="label">Konfirmasi Password *</label>
                <input type="password" class="input" name="konfirm" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="tombol tombol-merah tombol-besar tombol-penuh">Buat Akun</button>
            <div class="footer-kartu">
                Sudah punya akun? <a href="login.php">Masuk di sini</a>
            </div>
        </form>

    </div>
</div>

</div>
<script>
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = {
        'empty': 'Semua kolom wajib diisi.',
        'short': 'Password minimal 6 karakter.',
        'mismatch': 'Konfirmasi password tidak cocok.',
        'taken': 'Email atau username sudah digunakan.',
        'failed': 'Gagal membuat akun.'
    };
    if (urlParams.has('error') && msg[urlParams.get('error')]) {
        alert(msg[urlParams.get('error')]);
    }
};
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('pratinjauAvatar');
            preview.innerHTML = '<img src="' + e.target.result + '" alt="preview">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>
