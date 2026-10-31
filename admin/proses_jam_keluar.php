<?php

session_start();

include "../config/database.php";

date_default_timezone_set("Asia/Jakarta");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: data_tamu.php");
    exit;
}

$jam_keluar = date("H:i:s");

$query = mysqli_query($conn, "
    UPDATE tamu
    SET jam_keluar = '$jam_keluar'
    WHERE id = '$id'
");

if (!$query) {
    die("Gagal menyimpan jam keluar: " . mysqli_error($conn));
}

header("Location: detail_tamu.php?id=" . $id);
exit;

?>