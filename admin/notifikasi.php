<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

header("Content-Type: application/json; charset=UTF-8");

/* =====================================================
   AMBIL KUNJUNGAN TERBARU
===================================================== */

$query = mysqli_query($conn, "
    SELECT
        id,
        nama_tamu,
        instansi,
        keperluan,
        tujuan,
        tanggal,
        jam
    FROM tamu
    ORDER BY tanggal DESC, jam DESC, id DESC
    LIMIT 8
");

$data = [];

while ($row = mysqli_fetch_assoc($query)) {

    $data[] = [
        "id" => $row["id"],
        "nama_tamu" => $row["nama_tamu"],
        "instansi" => $row["instansi"],
        "keperluan" => $row["keperluan"],
        "tujuan" => $row["tujuan"],
        "tanggal" => $row["tanggal"],
        "jam" => $row["jam"]
    ];
}

/* =====================================================
   JUMLAH KUNJUNGAN HARI INI
===================================================== */

$tanggal_hari_ini = date("Y-m-d");

$query_count = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tamu
    WHERE tanggal = '$tanggal_hari_ini'
");

$data_count = mysqli_fetch_assoc($query_count);

$total_hari_ini = (int)($data_count["total"] ?? 0);

/* =====================================================
   OUTPUT JSON
===================================================== */

echo json_encode([
    "status" => "success",
    "total" => $total_hari_ini,
    "data" => $data
]);

exit;
?>