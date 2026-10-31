<?php

session_start();

include "../config/database.php";

/* =========================
   CEK DATA LOGIN
========================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? "";


/* =========================
   VALIDASI INPUT
========================= */

if ($username === '') {
    header("Location: login.php?jenis=error&pesan=Username wajib diisi");
    exit;
}

if ($password === '') {
    header("Location: login.php?jenis=error&pesan=Password wajib diisi");
    exit;
}


/* =========================
   CARI USER
========================= */

$username_safe = mysqli_real_escape_string($conn, $username);

$query = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE username='$username_safe'
    LIMIT 1
");

if (!$query) {
    header("Location: login.php?jenis=error&pesan=Terjadi kesalahan pada sistem");
    exit;
}


/* =========================
   USER TIDAK DITEMUKAN
========================= */

if (mysqli_num_rows($query) == 0) {

    header("Location: login.php?jenis=error&pesan=Username tidak ditemukan");
    exit;
}


$user = mysqli_fetch_assoc($query);


/* =========================
   CEK PASSWORD
========================= */

$password_benar = false;


/* Password hash */

if (password_verify($password, $user['password'])) {

    $password_benar = true;

}


/* Password biasa */

elseif ($password === $user['password']) {

    $password_benar = true;

}


/* =========================
   PASSWORD SALAH
========================= */

if (!$password_benar) {

    header("Location: login.php?jenis=error&pesan=Password salah. Silakan coba lagi");
    exit;
}


/* =========================
   CEK ROLE
========================= */

$role = strtolower(trim($user['role']));

if ($role !== "admin" && $role !== "user") {

    header("Location: login.php?jenis=error&pesan=Role akun tidak dikenali");
    exit;
}


/* =========================
   BUAT SESSION
========================= */

$_SESSION['id'] = $user['id'];

$_SESSION['id_user'] = $user['id'];

$_SESSION['nama'] = $user['nama_lengkap'];

$_SESSION['username'] = $user['username'];

$_SESSION['role'] = $role;


/* =========================
   REDIRECT
========================= */

if ($role === "admin") {

    header("Location: ../admin/dashboard.php");
    exit;

}

if ($role === "user") {

    header("Location: ../user/dashboard.php");
    exit;

}

?>