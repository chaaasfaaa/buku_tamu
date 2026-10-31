<?php

session_start();

include "../config/database.php";

header("Content-Type: application/json; charset=UTF-8");


/* =====================================================
   CEK LOGIN USER
===================================================== */

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {

    echo json_encode([
        "success" => false,
        "message" => "User belum login."
    ]);

    exit;
}


/* =====================================================
   ID USER
===================================================== */

$id_user = isset($_SESSION['id_user'])
    ? (int)$_SESSION['id_user']
    : 0;


if ($id_user <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "ID user tidak ditemukan."
    ]);

    exit;
}


/* =====================================================
   CEK KONEKSI DATABASE
===================================================== */

if (!$conn) {

    echo json_encode([
        "success" => false,
        "message" => "Koneksi database gagal."
    ]);

    exit;
}


/* =====================================================
   AMBIL SEMUA DATA KUNJUNGAN USER
===================================================== */

$query = mysqli_query(
    $conn,
    "
    SELECT
        id,
        nama_tamu,
        instansi,
        keperluan,
        tujuan,
        tanggal,
        jam
    FROM tamu
    WHERE id_user = '$id_user'
    ORDER BY id DESC
    LIMIT 8
    "
);


/* =====================================================
   CEK QUERY
===================================================== */

if (!$query) {

    echo json_encode([
        "success" => false,
        "message" => mysqli_error($conn)
    ]);

    exit;
}


/* =====================================================
   AMBIL DATA
===================================================== */

$notifications = [];


while ($row = mysqli_fetch_assoc($query)) {

    $notifications[] = [

        "id" => (int)$row['id'],

        "nama_tamu" =>
            $row['nama_tamu'] ?? "-",

        "instansi" =>
            $row['instansi'] ?? "-",

        "keperluan" =>
            $row['keperluan'] ?? "-",

        "tujuan" =>
            $row['tujuan'] ?? "-",

        "tanggal" =>
            !empty($row['tanggal'])
            ? date(
                "d/m/Y",
                strtotime($row['tanggal'])
            )
            : "-",

        "jam" =>
            !empty($row['jam'])
            ? date(
                "H:i",
                strtotime($row['jam'])
            )
            : "-",

        "baru" => false

    ];

}


/* =====================================================
   HITUNG JUMLAH DATA
===================================================== */

$jumlah = count($notifications);


/* =====================================================
   RESPONSE
===================================================== */

echo json_encode(

    [

        "success" => true,

        "unread_count" => $jumlah,

        "notifications" => $notifications

    ],

    JSON_UNESCAPED_UNICODE

);

exit;

?>