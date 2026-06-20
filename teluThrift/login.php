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
    <title>Login Tel-U Thrift</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="bungkus">

<div class="halaman">
    <div class="kartu">

        <div class="logo">
            <h2>Tel-U Thrift</h2>
        </div>

        <div class="tab">
            <a href="login.php" class="tab-item aktif">Masuk</a>
            <a href="register.php" class="tab-item">Daftar</a>
        </div>

        <form action="proses_login.php" method="POST">
            <input type="hidden" name="aksi" value="login">
            <div class="grup">
                <label class="label">Username atau Email</label>
                <input type="text" class="input" name="login" placeholder="username atau email kamu" required autofocus>
            </div>
            <div class="grup">
                <label class="label">Password</label>
                <input type="password" class="input" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="tombol tombol-merah tombol-besar tombol-penuh">Masuk</button>
            <div class="footer-kartu">
                Belum punya akun? <a href="register.php">Daftar sekarang</a>
            </div>
        </form>
    </div>
</div>

</div>

<script>
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('register') && urlParams.get('register') === 'success') {
        alert('Registration successful! Please login.');
    }
    if (urlParams.has('error')) {
        var pesan = {
            'empty': 'Username/email dan password wajib diisi.',
            'invalid': 'Username/email atau password salah.'
        };
        alert(pesan[urlParams.get('error')] || 'Terjadi kesalahan.');
    }
};
</script>
</body>
</html>
