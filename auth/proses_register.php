<?php

session_start();

include "../config/database.php";


/* =========================
   CEK METHOD
========================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: register.php");
    exit;

}


/* =========================
   AMBIL DATA
========================= */

$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi'] ?? '';


/* =========================
   VALIDASI NAMA
========================= */

if ($nama === '') {

    header("Location: register.php?jenis=error&pesan=Nama lengkap wajib diisi");
    exit;

}


/* =========================
   VALIDASI USERNAME
========================= */

if ($username === '') {

    header("Location: register.php?jenis=error&pesan=Username wajib diisi");
    exit;

}


/* =========================
   VALIDASI PASSWORD
========================= */

if ($password === '') {

    header("Location: register.php?jenis=error&pesan=Password wajib diisi");
    exit;

}


/* =========================
   PASSWORD MINIMAL
========================= */

if (strlen($password) < 6) {

    header("Location: register.php?jenis=error&pesan=Password minimal 6 karakter");
    exit;

}


/* =========================
   KONFIRMASI PASSWORD
========================= */

if ($password !== $konfirmasi) {

    header("Location: register.php?jenis=error&pesan=Konfirmasi password tidak sama");
    exit;

}


/* =========================
   CEK USERNAME
========================= */

$username_safe = mysqli_real_escape_string($conn, $username);

$cek = mysqli_query($conn, "
    SELECT id
    FROM users
    WHERE username='$username_safe'
    LIMIT 1
");


if (!$cek) {

    header("Location: register.php?jenis=error&pesan=Terjadi kesalahan pada sistem");
    exit;

}


if (mysqli_num_rows($cek) > 0) {

    header("Location: register.php?jenis=error&pesan=Username sudah digunakan");
    exit;

}


/* =========================
   SIMPAN PASSWORD
========================= */

$passwordHash = password_hash($password, PASSWORD_DEFAULT);


/* =========================
   SIMPAN USER
========================= */

$nama_safe = mysqli_real_escape_string($conn, $nama);
$password_safe = mysqli_real_escape_string($conn, $passwordHash);

$simpan = mysqli_query($conn, "
    INSERT INTO users
    (nama_lengkap, username, password, role)
    VALUES
    ('$nama_safe', '$username_safe', '$password_safe', 'user')
");


/* =========================
   CEK HASIL
========================= */

if (!$simpan) {

    header("Location: register.php?jenis=error&pesan=Registrasi gagal. Silakan coba lagi");
    exit;

}


/* =========================
   BERHASIL
========================= */

header("Location: login.php?jenis=success&pesan=Registrasi berhasil. Silakan login");
exit;

?>