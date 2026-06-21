<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error   = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $brand = mysqli_real_escape_string($conn, trim($_POST['brand']));
    $deskripsi = mysqli_real_escape_string($conn, trim($_POST['deskripsi']));
    $harga = (int)$_POST['harga'];
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $ukuran = mysqli_real_escape_string($conn, $_POST['ukuran']);
    $kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);

    if (empty($nama)) {
        $error = 'Nama produk wajib diisi.';
    } elseif ($harga < 1000) {
        $error = 'Harga minimal Rp 1.000.';
    } elseif (empty($kategori)) {
        $error = 'Pilih kategori produk.';
    } elseif (empty($kondisi)) {
        $error = 'Pilih kondisi barang.';
    } elseif (empty($_FILES['gambar']['name'][0])) {
        $error = 'Upload minimal 1 foto.';
    }

    if (empty($error)) {
        $sql = "INSERT INTO produk (user_id, nama, brand, deskripsi, harga, kategori, gender, ukuran, kondisi)
                VALUES ($user_id, '$nama', '$brand', '$deskripsi', $harga, '$kategori', '$gender', '$ukuran', '$kondisi')";
        mysqli_query($conn, $sql);
        $produk_id = mysqli_insert_id($conn);

        $tipe_diizinkan = ['image/jpeg', 'image/png', 'image/webp'];
        $urutan = 0;
        foreach ($_FILES['gambar']['tmp_name'] as $i => $tmp) {
            if (empty($tmp) || empty($_FILES['gambar']['name'][$i])) continue;

            $tipe = mime_content_type($tmp);
            if (!in_array($tipe, $tipe_diizinkan)) continue;

            $ekstensi  = pathinfo($_FILES['gambar']['name'][$i], PATHINFO_EXTENSION);
            $nama_file = 'produk_' . $produk_id . '_' . $urutan . '_' . time() . '.' . strtolower($ekstensi);
            $tujuan = 'uploads/' . $nama_file;

            if (move_uploaded_file($tmp, $tujuan)) {
                $sql_gambar = "INSERT INTO produk_gambar (produk_id, nama_file, urutan) VALUES ($produk_id, '$nama_file', $urutan)";
                mysqli_query($conn, $sql_gambar);
                $urutan++;
            }
        }

        header("Location: detail.php?id=$produk_id&baru=1");
        exit();
    }
}
