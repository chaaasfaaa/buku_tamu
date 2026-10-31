<?php

session_start();

include "../config/database.php";


/* =========================================
   CEK LOGIN ADMIN
========================================= */

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("Location: ../auth/login.php");
    exit;

}


/* =========================================
   AMBIL ID TAMU
========================================= */

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


if ($id <= 0) {

    echo "<script>

        alert('Data keperluan tidak ditemukan!');

        window.location.href='keperluan.php';

    </script>";

    exit;

}


/* =========================================
   HAPUS KEPERLUAN SAJA
   DATA TAMU TETAP ADA
========================================= */

$query = mysqli_query(
    $conn,
    "
    UPDATE tamu
    SET keperluan = ''
    WHERE id = '$id'
    "
);


/* =========================================
   HASIL
========================================= */

if ($query) {

    echo "<script>

        alert('Keperluan berhasil dihapus!');

        window.location.href='keperluan.php';

    </script>";

    exit;

} else {

    echo "<script>

        alert('Gagal menghapus keperluan!');

        window.location.href='keperluan.php';

    </script>";

    exit;

}

?>