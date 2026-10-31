<?php

session_start();
include "../config/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");


/* =====================================================
   FILTER PENCARIAN
===================================================== */

$keyword = isset($_GET['keyword'])
    ? trim($_GET['keyword'])
    : "";

$tanggal_mulai = isset($_GET['tanggal_mulai'])
    ? trim($_GET['tanggal_mulai'])
    : "";

$tanggal_selesai = isset($_GET['tanggal_selesai'])
    ? trim($_GET['tanggal_selesai'])
    : "";


/* =====================================================
   QUERY DATA
===================================================== */

$where = [];


if ($keyword !== "") {

    $keyword_safe = mysqli_real_escape_string(
        $conn,
        $keyword
    );

    $where[] = "(
        nama_tamu LIKE '%$keyword_safe%'
        OR instansi LIKE '%$keyword_safe%'
        OR no_hp LIKE '%$keyword_safe%'
        OR keperluan LIKE '%$keyword_safe%'
        OR tujuan LIKE '%$keyword_safe%'
    )";
}


if ($tanggal_mulai !== "") {

    $tanggal_mulai_safe = mysqli_real_escape_string(
        $conn,
        $tanggal_mulai
    );

    $where[] = "tanggal >= '$tanggal_mulai_safe'";
}


if ($tanggal_selesai !== "") {

    $tanggal_selesai_safe = mysqli_real_escape_string(
        $conn,
        $tanggal_selesai
    );

    $where[] = "tanggal <= '$tanggal_selesai_safe'";
}


$where_sql = "";

if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}


/* =====================================================
   AMBIL DATA
===================================================== */

$query = mysqli_query($conn, "
    SELECT
        id,
        nama_tamu,
        jenis_kelamin,
        instansi,
        no_hp,
        keperluan,
        tujuan,
        tanggal,
        jam
    FROM tamu
    $where_sql
    ORDER BY tanggal DESC, jam DESC
");


if (!$query) {
    die("Gagal mengambil data: " . mysqli_error($conn));
}


/* =====================================================
   NAMA FILE
===================================================== */

$filename = "data_tamu_" . date("Y-m-d_H-i-s") . ".xls";


/* =====================================================
   HEADER EXCEL
===================================================== */

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<style>

/* =====================================================
   UMUM
===================================================== */

body {
    font-family: Arial, sans-serif;
    font-size: 11pt;
    color: #222222;
}


/* =====================================================
   JUDUL
===================================================== */

.title {
    font-size: 18pt;
    font-weight: bold;
    text-align: center;
    height: 30px;
    vertical-align: middle;
}


/* =====================================================
   SUBJUDUL
===================================================== */

.subtitle {
    font-size: 11pt;
    text-align: center;
    color: #555555;
    height: 22px;
    vertical-align: middle;
}


/* =====================================================
   INFORMASI CETAK
===================================================== */

.info {
    font-size: 10pt;
    color: #666666;
    height: 22px;
    vertical-align: middle;
}


/* =====================================================
   SPASI
===================================================== */

.spacer {
    height: 12px;
}


/* =====================================================
   TABEL
===================================================== */

table {
    border-collapse: collapse;
    width: 100%;
}


/* =====================================================
   HEADER TABEL
===================================================== */

th {
    background-color: #D093B0;
    color: #FFFFFF;
    font-weight: bold;
    text-align: center;
    vertical-align: middle;

    border: 1px solid #999999;

    padding: 9px 7px;

    height: 30px;
}


/* =====================================================
   DATA TABEL
===================================================== */

td {
    border: 1px solid #B8B8B8;

    padding: 8px 7px;

    height: 25px;

    vertical-align: middle;
}


/* =====================================================
   ALIGNMENT
===================================================== */

.center {
    text-align: center;
}

.left {
    text-align: left;
}


/* =====================================================
   NOMOR
===================================================== */

.no {
    text-align: center;
    width: 45px;
}


/* =====================================================
   NAMA
===================================================== */

.nama {
    width: 130px;
}


/* =====================================================
   JENIS KELAMIN
===================================================== */

.jenis {
    width: 110px;
}


/* =====================================================
   INSTANSI
===================================================== */

.instansi {
    width: 150px;
}


/* =====================================================
   NO HP
===================================================== */

.nohp {
    width: 120px;
    mso-number-format: "\@";
}


/* =====================================================
   KEPERLUAN
===================================================== */

.keperluan {
    width: 190px;
}


/* =====================================================
   TUJUAN
===================================================== */

.tujuan {
    width: 180px;
}


/* =====================================================
   TANGGAL
===================================================== */

.tanggal {
    width: 100px;
    text-align: center;
}


/* =====================================================
   JAM
===================================================== */

.jam {
    width: 90px;
    text-align: center;
}


/* =====================================================
   TOTAL
===================================================== */

.total {
    margin-top: 12px;
    font-size: 11pt;
    font-weight: normal;
}


/* =====================================================
   CETAK
===================================================== */

@page {
    size: landscape;
    margin: 0.5in;
}

</style>

</head>


<body>


<table>

    <!-- =================================================
         JUDUL
    ================================================== -->

    <tr>

        <td colspan="9" class="title">
            DATA BUKU TAMU DIGITAL
        </td>

    </tr>


    <!-- =================================================
         SUBJUDUL
    ================================================== -->

    <tr>

        <td colspan="9" class="subtitle">
            Laporan Data Kunjungan Tamu
        </td>

    </tr>


    <!-- =================================================
         WAKTU CETAK
    ================================================== -->

    <tr>

        <td colspan="9" class="info">
            Dicetak pada:
            <?= date("d-m-Y H:i:s"); ?>
        </td>

    </tr>


    <!-- =================================================
         SPASI
    ================================================== -->

    <tr>

        <td colspan="9" class="spacer"></td>

    </tr>


    <!-- =================================================
         HEADER TABEL
    ================================================== -->

    <tr>

        <th class="no">
            No
        </th>

        <th class="nama">
            Nama Tamu
        </th>

        <th class="jenis">
            Jenis Kelamin
        </th>

        <th class="instansi">
            Instansi
        </th>

        <th class="nohp">
            No HP
        </th>

        <th class="keperluan">
            Keperluan
        </th>

        <th class="tujuan">
            Tujuan
        </th>

        <th class="tanggal">
            Tanggal
        </th>

        <th class="jam">
            Jam
        </th>

    </tr>


<?php

$no = 1;

$total_data = 0;

while ($data = mysqli_fetch_assoc($query)):

    $total_data++;

?>

    <!-- =================================================
         BARIS DATA
    ================================================== -->

    <tr>

        <td class="no">
            <?= $no++; ?>
        </td>


        <td class="nama">
            <?= htmlspecialchars(
                $data['nama_tamu'] ?? '-'
            ); ?>
        </td>


        <td class="jenis center">
            <?= htmlspecialchars(
                $data['jenis_kelamin'] ?? '-'
            ); ?>
        </td>


        <td class="instansi">
            <?= htmlspecialchars(
                $data['instansi'] ?? '-'
            ); ?>
        </td>


        <td class="nohp">
            <?= htmlspecialchars(
                $data['no_hp'] ?? '-'
            ); ?>
        </td>


        <td class="keperluan">
            <?= htmlspecialchars(
                $data['keperluan'] ?? '-'
            ); ?>
        </td>


        <td class="tujuan">
            <?= htmlspecialchars(
                $data['tujuan'] ?? '-'
            ); ?>
        </td>


        <td class="tanggal">

            <?php

            if (!empty($data['tanggal'])) {

                echo date(
                    "d-m-Y",
                    strtotime($data['tanggal'])
                );

            } else {

                echo "-";

            }

            ?>

        </td>


        <td class="jam">

            <?= !empty($data['jam'])
                ? htmlspecialchars($data['jam'])
                : "-";
            ?>

        </td>

    </tr>


<?php endwhile; ?>


    <!-- =================================================
         TOTAL DATA
    ================================================== -->

    <tr>

        <td colspan="9"
            style="
                border: 0;
                padding-top: 12px;
                font-weight: bold;
            ">

            Total data:
            <?= $total_data; ?>
            kunjungan

        </td>

    </tr>


</table>


</body>

</html>