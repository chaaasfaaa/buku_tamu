<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");


/* =====================================================
   DATA DASHBOARD
===================================================== */

/* Total tamu */
$query_tamu = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tamu
");

$data_tamu = mysqli_fetch_assoc($query_tamu);


/* Total user */
$query_user = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM users
");

$data_user = mysqli_fetch_assoc($query_user);


/* Total keperluan */
$query_keperluan = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM keperluan
");

$data_keperluan = mysqli_fetch_assoc($query_keperluan);


/* Jumlah tamu hari ini */
$tanggal_hari_ini = date("Y-m-d");

$query_hari_ini = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tamu
    WHERE tanggal = '$tanggal_hari_ini'
");

$data_hari_ini = mysqli_fetch_assoc($query_hari_ini);


/* =====================================================
   GRAFIK KUNJUNGAN BERDASARKAN BULAN
===================================================== */

/* Ambil bulan dari pilihan admin */
$bulan = isset($_GET['bulan'])
    ? (int) $_GET['bulan']
    : (int) date("m");


/* Ambil tahun dari pilihan admin */
$tahun = isset($_GET['tahun'])
    ? (int) $_GET['tahun']
    : (int) date("Y");


/* Validasi bulan */
if ($bulan < 1 || $bulan > 12) {
    $bulan = (int) date("m");
}


/* Validasi tahun */
if ($tahun < 2000 || $tahun > 2100) {
    $tahun = (int) date("Y");
}


/* =====================================================
   AMBIL DATA KUNJUNGAN DARI DATABASE
===================================================== */

$query_grafik = mysqli_query($conn, "
    SELECT
        tanggal,
        COUNT(*) AS jumlah
    FROM tamu
    WHERE MONTH(tanggal) = $bulan
    AND YEAR(tanggal) = $tahun
    GROUP BY tanggal
    ORDER BY tanggal ASC
");


/* =====================================================
   SIMPAN DATA GRAFIK
===================================================== */

$grafik = [];

while ($row = mysqli_fetch_assoc($query_grafik)) {

    $grafik[$row['tanggal']] = (int) $row['jumlah'];

}


/* =====================================================
   NAMA BULAN
===================================================== */

$nama_bulan = [
    1  => "Januari",
    2  => "Februari",
    3  => "Maret",
    4  => "April",
    5  => "Mei",
    6  => "Juni",
    7  => "Juli",
    8  => "Agustus",
    9  => "September",
    10 => "Oktober",
    11 => "November",
    12 => "Desember"
];


$nama_bulan_dipilih = $nama_bulan[$bulan];


/* =====================================================
   JUMLAH HARI PADA BULAN YANG DIPILIH
===================================================== */

$jumlah_hari = cal_days_in_month(
    CAL_GREGORIAN,
    $bulan,
    $tahun
);


/* =====================================================
   LABEL DAN DATA GRAFIK
===================================================== */

$label_grafik = [];
$data_grafik = [];


for ($i = 1; $i <= $jumlah_hari; $i++) {

    $tanggal_lengkap = sprintf(
        "%04d-%02d-%02d",
        $tahun,
        $bulan,
        $i
    );


    $label_grafik[] = str_pad(
        $i,
        2,
        "0",
        STR_PAD_LEFT
    );


    $data_grafik[] =
        isset($grafik[$tanggal_lengkap])
        ? $grafik[$tanggal_lengkap]
        : 0;
}


/* =====================================================
   SALAM
===================================================== */

$jam = date("H");


if ($jam >= 5 && $jam < 11) {

    $salam = "🌅 Selamat Pagi";

} elseif ($jam >= 11 && $jam < 15) {

    $salam = "☀️ Selamat Siang";

} elseif ($jam >= 15 && $jam < 18) {

    $salam = "🌇 Selamat Sore";

} else {

    $salam = "🌙 Selamat Malam";

}

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin - Buku Tamu Digital SMK Pelita</title>


<!-- FONT POPPINS -->

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
      rel="stylesheet">


<!-- SIDEBAR CSS -->

<link rel="stylesheet"
      href="../assets/css/sidebar.css?v=401">


<!-- FONT AWESOME -->

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


<!-- CHART JS -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<style>

/* =====================================================
   RESET
===================================================== */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/* =====================================================
   BODY
===================================================== */

body {
    font-family: Poppins, Arial, sans-serif;
    background: #fff7fb;
    color: #352b40;
}


/* =====================================================
   CONTENT
===================================================== */

.content {
    margin-left: 210px;
    padding: 28px;
    min-height: 100vh;
}


/* =====================================================
   TOP BAR
===================================================== */

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
}


.search-box {
    width: 325px;
    background: white;
    border-radius: 16px;
    padding: 14px 18px;

    display: flex;
    align-items: center;
    gap: 12px;

    box-shadow:
        0 8px 25px rgba(199,127,163,.08);
}


.search-box i {
    color: #cf8bab;
}


.search-box span {
    color: #927c8c;
    font-size: 14px;
}


.top-right {
    display: flex;
    align-items: center;
    gap: 12px;
}


.top-info {
    background: white;
    padding: 13px 20px;
    border-radius: 16px;

    font-size: 14px;
    font-weight: 600;

    box-shadow:
        0 8px 25px rgba(199,127,163,.08);
}


.top-info i {
    color: #cf8bab;
    margin-right: 8px;
}


/* =====================================================
   WELCOME BANNER
===================================================== */

.banner {
    position: relative;
    overflow: hidden;

    min-height: 288px;

    padding: 38px 46px;

    border-radius: 25px;

    background: linear-gradient(
        135deg,
        #c985a7,
        #d295b2
    );

    color: white;

    margin-bottom: 32px;
}


.banner::before {
    content: "";

    position: absolute;

    width: 190px;
    height: 190px;

    border-radius: 50%;

    background: rgba(255,255,255,.12);

    right: -20px;
    top: -70px;
}


.banner::after {
    content: "";

    position: absolute;

    width: 170px;
    height: 170px;

    border-radius: 50%;

    background: rgba(255,255,255,.10);

    left: 55%;
    bottom: -115px;
}


.greeting-row {
    position: relative;
    z-index: 2;

    display: flex;

    justify-content: space-between;
    align-items: flex-start;
}


.banner h1 {
    font-size: 38px;
    line-height: 1.35;
    margin-bottom: 18px;
}


.banner p {
    font-size: 16px;
    margin-bottom: 25px;
}


.btn-laporan {
    display: inline-flex;

    align-items: center;

    gap: 9px;

    padding: 14px 24px;

    background: white;

    color: #c77fa3;

    text-decoration: none;

    border-radius: 30px;

    font-weight: 700;

    transition: .3s;
}


.btn-laporan:hover {
    transform: translateY(-3px);

    box-shadow:
        0 8px 20px rgba(0,0,0,.10);
}


/* =====================================================
   JAM AKTIF
===================================================== */

.real-time-clock {
    background: rgba(255,255,255,.18);

    border: 1px solid rgba(255,255,255,.45);

    border-radius: 18px;

    padding: 12px 18px;

    display: flex;

    align-items: center;

    gap: 12px;

    min-width: 180px;
}


.clock-icon {
    width: 44px;
    height: 44px;

    border-radius: 50%;

    background: white;

    color: #c77fa3;

    display: flex;

    align-items: center;
    justify-content: center;

    font-size: 20px;
}


.clock-text {
    display: flex;
    flex-direction: column;
}


.clock-text small {
    font-size: 11px;
    opacity: .9;
}


.clock-text span {
    font-size: 20px;

    font-weight: 800;

    letter-spacing: 1px;
}


/* =====================================================
   STATISTIC CARDS
===================================================== */

.stats-cards {
    display: grid;

    grid-template-columns:
        repeat(4,1fr);

    gap: 20px;

    margin-bottom: 35px;
}


.stat-card {
    background: white;

    border-radius: 20px;

    padding: 22px;

    display: flex;

    align-items: center;

    gap: 16px;

    box-shadow:
        0 10px 30px rgba(199,127,163,.10);

    transition: .3s;
}


.stat-card:hover {
    transform: translateY(-4px);

    box-shadow:
        0 15px 35px rgba(199,127,163,.16);
}


.stat-icon {
    width: 62px;
    height: 62px;

    border-radius: 17px;

    background: #cf8bab;

    color: white;

    display: flex;

    align-items: center;
    justify-content: center;

    font-size: 25px;
}


.stat-info h2 {
    font-size: 28px;
    margin-bottom: 3px;
}


.stat-info p {
    color: #8e7889;
    font-size: 14px;
}


/* =====================================================
   MENU CEPAT
===================================================== */

.quick-section {
    margin-top: 28px;
    width: 100%;
}


.quick-title {
    margin: 0 0 16px;

    color: #202642 !important;

    font-family: 'Poppins', sans-serif !important;

    font-size: 23px !important;

    font-weight: 800 !important;
}


.quick-grid {
    width: 100%;

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 18px;
}


.quick-box {
    position: relative;

    width: 100%;

    min-height: 135px;

    padding: 20px;

    display: flex !important;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    text-align: center;

    background: #ffffff !important;

    border: 1px solid #f2dce6;

    border-radius: 18px;

    text-decoration: none !important;

    color: #3f3040 !important;

    box-shadow:
        0 8px 22px
        rgba(208,147,176,0.08);

    overflow: hidden;

    transition: all 0.25s ease;
}


.quick-box,
.quick-box:link,
.quick-box:visited,
.quick-box:hover,
.quick-box:active {
    text-decoration: none !important;
}


.quick-box::after {
    content: "";

    position: absolute;

    width: 65px;

    height: 65px;

    right: -22px;

    top: -22px;

    background: #fdf1f6;

    border-radius: 50%;
}


.quick-icon {
    width: 50px;

    height: 50px;

    display: flex;

    align-items: center;

    justify-content: center;

    margin-bottom: 10px;

    border-radius: 14px;

    background: #f4d8e5;

    color: #c77f9f;

    font-size: 20px;
}


.quick-icon i {
    width: auto !important;

    height: auto !important;

    margin: 0 !important;

    background: transparent !important;

    color: #c77f9f !important;

    font-size: 20px !important;
}


.quick-box strong {
    position: relative;

    z-index: 2;

    display: block;

    color: #3f3040 !important;

    font-family: 'Poppins', sans-serif;

    font-size: 15px;

    font-weight: 800;
}


.quick-box span {
    position: relative;

    z-index: 2;

    display: block;

    margin-top: 4px;

    color: #9b8490 !important;

    font-family: 'Poppins', sans-serif;

    font-size: 11px;

    font-weight: 500;
}


.quick-box:hover {
    background: #fff8fa !important;

    color: #3f3040 !important;

    transform: translateY(-5px);

    box-shadow:
        0 14px 28px
        rgba(208,147,176,0.16);
}


/* =====================================================
   GRAFIK + INFORMASI
===================================================== */

.dashboard-grid {
    display: grid;

    grid-template-columns:
        2fr 1fr;

    gap: 20px;

    margin-top: 20px;
}


.chart-box,
.info-box {
    background: white;

    border-radius: 20px;

    padding: 25px;

    box-shadow:
        0 10px 30px rgba(199,127,163,.08);
}


/* =====================================================
   HEADER GRAFIK
===================================================== */

.chart-header {
    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    gap: 20px;

    margin-bottom: 20px;
}


.chart-header h2 {
    color: #c77fa3;

    font-size: 23px;

    margin-bottom: 3px;
}


.chart-subtitle {
    margin: 0;

    color: #9b8290;

    font-size: 13px;
}


/* =====================================================
   FILTER BULAN
===================================================== */

.filter-bulan {
    display: flex;

    align-items: center;

    gap: 8px;
}


.filter-bulan select {
    padding: 10px 12px;

    border: 1px solid #ead3df;

    border-radius: 10px;

    background: white;

    color: #5d4757;

    font-family: Poppins, sans-serif;

    font-size: 12px;

    outline: none;

    cursor: pointer;
}


.filter-bulan select:focus {
    border-color: #c77fa3;
}


.filter-bulan button {
    padding: 10px 14px;

    border: none;

    border-radius: 10px;

    background: #c77fa3;

    color: white;

    font-family: Poppins, sans-serif;

    font-size: 12px;

    font-weight: 600;

    cursor: pointer;

    transition: .2s;
}


.filter-bulan button:hover {
    background: #b96f94;

    transform: translateY(-2px);
}


/* =====================================================
   UKURAN GRAFIK
===================================================== */

.chart-container {
    position: relative;

    width: 100%;

    height: 350px;
}


/* =====================================================
   INFORMASI
===================================================== */

.info-box h2 {
    color: #c77fa3;

    font-size: 23px;

    margin-bottom: 20px;
}


.info-item {
    padding: 16px 0;

    border-bottom:
        1px solid #f0dce6;

    color: #6e5c69;
}


.info-item:last-child {
    border-bottom: none;
}


.info-item i {
    color: #cf8bab;

    margin-right: 10px;
}


/* =====================================================
   FOOTER
===================================================== */

.footer {
    text-align: center;

    margin-top: 30px;

    color: #927c8c;

    font-size: 13px;
}


/* =====================================================
   NOTIFIKASI
===================================================== */

.notification-wrapper {
    position: relative;
}


.notification-button {
    width: 48px;
    height: 48px;

    border: none;

    border-radius: 15px;

    background: #ffffff;

    color: #c77fa3;

    display: flex;

    align-items: center;

    justify-content: center;

    cursor: pointer;

    font-size: 19px;

    box-shadow:
        0 8px 25px rgba(199,127,163,.10);

    transition: .3s;

    position: relative;
}


.notification-button:hover {
    transform: translateY(-3px);

    box-shadow:
        0 12px 28px rgba(199,127,163,.18);
}


.notification-button.has-notification i {
    animation: bellShake .8s ease-in-out;
}


@keyframes bellShake {

    0% {
        transform: rotate(0);
    }

    20% {
        transform: rotate(12deg);
    }

    40% {
        transform: rotate(-12deg);
    }

    60% {
        transform: rotate(8deg);
    }

    80% {
        transform: rotate(-8deg);
    }

    100% {
        transform: rotate(0);
    }

}


.notification-badge {
    position: absolute;

    top: -5px;
    right: -5px;

    min-width: 21px;
    height: 21px;

    padding: 0 5px;

    border-radius: 50px;

    background: #d093b0;

    color: white;

    border: 2px solid white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 10px;

    font-weight: 700;

    opacity: 0;

    transform: scale(.5);

    transition: .25s;
}


.notification-badge.show {
    opacity: 1;

    transform: scale(1);
}


.notification-dropdown {
    position: absolute;

    top: 60px;

    right: 0;

    width: 370px;

    background: white;

    border-radius: 20px;

    box-shadow:
        0 20px 50px rgba(120,70,100,.18);

    border: 1px solid #f3dce7;

    overflow: hidden;

    z-index: 9999;

    opacity: 0;

    visibility: hidden;

    transform:
        translateY(-10px)
        scale(.98);

    transform-origin: top right;

    transition: .25s;
}


.notification-dropdown.show {
    opacity: 1;

    visibility: visible;

    transform:
        translateY(0)
        scale(1);
}


.notification-header {
    padding: 18px 20px;

    background:
        linear-gradient(
            135deg,
            #c985a7,
            #d295b2
        );

    color: white;

    display: flex;

    justify-content: space-between;

    align-items: center;
}


.notification-header h3 {
    margin: 0;

    font-size: 16px;
}


.notification-header span {
    font-size: 11px;

    background:
        rgba(255,255,255,.20);

    padding: 5px 9px;

    border-radius: 20px;
}


.notification-list {
    max-height: 360px;

    overflow-y: auto;
}


.notification-item {
    display: flex;

    gap: 12px;

    padding: 15px 18px;

    text-decoration: none;

    color: #352b40;

    border-bottom:
        1px solid #f5e7ee;

    transition: .25s;
}


.notification-item:hover {
    background: #fff7fb;
}


.notification-icon {
    flex-shrink: 0;

    width: 42px;
    height: 42px;

    border-radius: 13px;

    background: #f3d1df;

    color: #c77fa3;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 17px;
}


.notification-text {
    min-width: 0;

    flex: 1;
}


.notification-text strong {
    display: block;

    font-size: 13px;

    margin-bottom: 3px;

    color: #3d3040;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


.notification-text p {
    margin: 0;

    font-size: 11px;

    color: #927c8c;

    line-height: 1.5;
}


.notification-time {
    margin-top: 4px;

    font-size: 10px;

    color: #b08fa0;
}


.notification-empty {
    padding: 35px 20px;

    text-align: center;

    color: #a58c9a;
}


.notification-empty i {
    font-size: 35px;

    margin-bottom: 10px;

    color: #d8b0c2;
}


.notification-empty p {
    margin: 0;

    font-size: 13px;
}


.notification-footer {
    padding: 13px 18px;

    text-align: center;

    border-top:
        1px solid #f3e1e9;

    background: #fffafd;
}


.notification-footer a {
    color: #c77fa3;

    text-decoration: none;

    font-size: 12px;

    font-weight: 700;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width:1100px) {

    .stats-cards {
        grid-template-columns:
            repeat(2,1fr);
    }

    .quick-grid {
        grid-template-columns:
            repeat(2,1fr);
    }

}


@media (max-width:1000px) {

    .dashboard-grid {
        grid-template-columns: 1fr;
    }

}


@media (max-width:700px) {

    .sidebar {
        width: 75px;
    }

    .sidebar h2 {
        font-size: 0;
        justify-content: center;
        padding: 0;
    }

    .sidebar h2 i {
        font-size: 24px;
    }

    .sidebar a {
        justify-content: center;
        padding: 15px 5px;
    }

    .sidebar a span {
        display: none;
    }

    .content {
        margin-left: 75px;
        padding: 18px;
    }

    .stats-cards {
        grid-template-columns: 1fr;
    }

    .quick-grid {
        grid-template-columns: 1fr;
    }

    .greeting-row {
        flex-direction: column;
        gap: 25px;
    }

    .topbar {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }

    .search-box {
        width: 100%;
    }

    .chart-header {
        flex-direction: column;
    }

    .filter-bulan {
        width: 100%;
        flex-wrap: wrap;
    }

    .notification-dropdown {
        position: fixed;

        top: 75px;

        left: 15px;

        right: 15px;

        width: auto;
    }

}

</style>

</head>


<body>


<?php include "sidebar.php"; ?>


<div class="content">


<!-- =====================================================
     TOP BAR
===================================================== -->

<div class="topbar">


    <div class="search-box">

        <i class="fa-solid fa-magnifying-glass"></i>

        <span>
            Cari data tamu...
        </span>

    </div>


    <div class="top-right">


        <!-- NOTIFIKASI -->

        <div class="notification-wrapper">

            <button
                type="button"
                class="notification-button"
                id="notificationButton"
                title="Notifikasi">

                <i class="fa-solid fa-bell"></i>

                <span
                    class="notification-badge"
                    id="notificationBadge">
                    0
                </span>

            </button>


            <div
                class="notification-dropdown"
                id="notificationDropdown">


                <div class="notification-header">

                    <h3>

                        <i class="fa-solid fa-bell"></i>

                        Notifikasi

                    </h3>


                    <span id="notificationCountText">
                        0 baru
                    </span>

                </div>


                <div
                    class="notification-list"
                    id="notificationList">

                    <div class="notification-empty">

                        <i class="fa-regular fa-bell-slash"></i>

                        <p>
                            Memuat notifikasi...
                        </p>

                    </div>

                </div>


                <div class="notification-footer">

                    <a href="data_tamu.php">

                        Lihat Semua Data Tamu

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>


            </div>

        </div>


        <!-- TANGGAL -->

        <div class="top-info">

            <i class="fa-solid fa-calendar-days"></i>

            <?= date("d F Y"); ?>

        </div>


        <!-- ADMIN -->

        <div class="top-info">

            <i class="fa-solid fa-user"></i>

            Administrator

        </div>


    </div>

</div>



<!-- =====================================================
     WELCOME BANNER
===================================================== -->

<div class="banner">


    <div class="greeting-row">


        <div>

            <h1>

                <?= $salam ?>,

                <br>

                Administrator

            </h1>


            <p>

                Kelola seluruh data Buku Tamu Digital
                lebih mudah, cepat dan profesional.

            </p>


            <a
                href="laporan.php"
                class="btn-laporan">

                <i class="fa-solid fa-print"></i>

                Lihat Laporan

            </a>

        </div>


        <!-- JAM -->

        <div class="real-time-clock">

            <div class="clock-icon">

                <i class="fa-solid fa-clock"></i>

            </div>


            <div class="clock-text">

                <small>
                    Jam Aktif
                </small>

                <span id="jamAktif">

                    <?= date("H:i:s"); ?>

                </span>

            </div>

        </div>


    </div>

</div>



<!-- =====================================================
     STATISTIK CARDS
===================================================== -->

<div class="stats-cards">


    <!-- TOTAL TAMU -->

    <div class="stat-card">

        <div class="stat-icon">

            <i class="fa-solid fa-users"></i>

        </div>


        <div class="stat-info">

            <h2>
                <?= $data_tamu['total']; ?>
            </h2>

            <p>
                Total Tamu
            </p>

        </div>

    </div>


    <!-- TOTAL USER -->

    <div class="stat-card">

        <div class="stat-icon">

            <i class="fa-solid fa-user-group"></i>

        </div>


        <div class="stat-info">

            <h2>
                <?= $data_user['total']; ?>
            </h2>

            <p>
                Total User
            </p>

        </div>

    </div>


    <!-- KEPERLUAN -->

    <div class="stat-card">

        <div class="stat-icon">

            <i class="fa-solid fa-list-check"></i>

        </div>


        <div class="stat-info">

            <h2>
                <?= $data_keperluan['total']; ?>
            </h2>

            <p>
                Keperluan
            </p>

        </div>

    </div>


    <!-- HARI INI -->

    <div class="stat-card">

        <div class="stat-icon">

            <i class="fa-solid fa-calendar-days"></i>

        </div>


        <div class="stat-info">

            <h2>
                <?= $data_hari_ini['total']; ?>
            </h2>

            <p>
                Hari Ini
            </p>

        </div>

    </div>


</div>



<!-- =====================================================
     MENU CEPAT
===================================================== -->

<section class="quick-section">


    <h2 class="quick-title">
        Menu Cepat
    </h2>


    <div class="quick-grid">


        <a
            href="data_tamu.php"
            class="quick-box">

            <div class="quick-icon">

                <i class="fa-solid fa-users"></i>

            </div>

            <strong>
                Data Tamu
            </strong>

            <span>
                Kelola data tamu
            </span>

        </a>


        <a
            href="pengaduan.php"
            class="quick-box">

            <div class="quick-icon">

                <i class="fa-solid fa-headset"></i>

            </div>

            <strong>
                Pengaduan
            </strong>

            <span>
                Kelola pengaduan
            </span>

        </a>


        <a
            href="keperluan.php"
            class="quick-box">

            <div class="quick-icon">

                <i class="fa-solid fa-clipboard-list"></i>

            </div>

            <strong>
                Keperluan
            </strong>

            <span>
                Kelola keperluan tamu
            </span>

        </a>


        <a
            href="pencarian.php"
            class="quick-box">

            <div class="quick-icon">

                <i class="fa-solid fa-magnifying-glass"></i>

            </div>

            <strong>
                Pencarian
            </strong>

            <span>
                Cari data tamu
            </span>

        </a>


        <a
            href="laporan.php"
            class="quick-box">

            <div class="quick-icon">

                <i class="fa-solid fa-print"></i>

            </div>

            <strong>
                Laporan
            </strong>

            <span>
                Cetak laporan tamu
            </span>

        </a>


        <a
            href="../auth/logout.php"
            class="quick-box">

            <div class="quick-icon">

                <i class="fa-solid fa-right-from-bracket"></i>

            </div>

            <strong>
                Logout
            </strong>

            <span>
                Keluar dari sistem
            </span>

        </a>


    </div>

</section>



<!-- =====================================================
     GRAFIK + INFORMASI
===================================================== -->

<div class="dashboard-grid">


    <!-- =================================================
         GRAFIK KUNJUNGAN
    ================================================== -->

    <div class="chart-box">


        <div class="chart-header">


            <div>

                <h2>
                    Statistik Kunjungan
                </h2>

                <p class="chart-subtitle">

                    <?= $nama_bulan_dipilih . " " . $tahun; ?>

                </p>

            </div>


            <!-- =================================================
                 FILTER BULAN DAN TAHUN
            ================================================== -->

            <form
                method="GET"
                class="filter-bulan">


                <!-- BULAN -->

                <select name="bulan">

                    <?php foreach ($nama_bulan as $nomor => $nama): ?>

                        <option
                            value="<?= $nomor; ?>"
                            <?= ($bulan == $nomor) ? 'selected' : ''; ?>>

                            <?= $nama; ?>

                        </option>

                    <?php endforeach; ?>

                </select>


                <!-- TAHUN -->

                <select name="tahun">

                    <?php

                    $tahun_sekarang = date("Y");

                    for (
                        $t = $tahun_sekarang - 5;
                        $t <= $tahun_sekarang;
                        $t++
                    ):

                    ?>

                        <option
                            value="<?= $t; ?>"
                            <?= ($tahun == $t) ? 'selected' : ''; ?>>

                            <?= $t; ?>

                        </option>

                    <?php endfor; ?>

                </select>


                <!-- BUTTON -->

                <button type="submit">

                    <i class="fa-solid fa-filter"></i>

                    Tampilkan

                </button>


            </form>


        </div>


        <!-- CANVAS GRAFIK -->

        <div class="chart-container">

            <canvas id="grafikKunjungan"></canvas>

        </div>


    </div>



    <!-- =================================================
         INFORMASI
    ================================================== -->

    <div class="info-box">


        <h2>
            Informasi
        </h2>


        <div class="info-item">

            📖 Kelola data tamu dengan mudah.

        </div>


        <div class="info-item">

            📊 Lihat statistik kunjungan.

        </div>


        <div class="info-item">

            🖨️ Cetak laporan kapan saja.

        </div>


        <div class="info-item">

            👤 Kelola data pengguna.

        </div>


    </div>


</div>



<!-- =====================================================
     FOOTER
===================================================== -->

<div class="footer">

    © <?= date("Y"); ?>

    Buku Tamu Digital SMK Pelita

</div>


</div>



<!-- =====================================================
     JAM REAL-TIME
===================================================== -->

<script>

function updateClock() {

    const sekarang = new Date();


    let jam =
        String(sekarang.getHours())
        .padStart(2, "0");


    let menit =
        String(sekarang.getMinutes())
        .padStart(2, "0");


    let detik =
        String(sekarang.getSeconds())
        .padStart(2, "0");


    document.getElementById("jamAktif").textContent =
        jam + ":" + menit + ":" + detik;

}


updateClock();


setInterval(
    updateClock,
    1000
);

</script>



<!-- =====================================================
     GRAFIK KUNJUNGAN
===================================================== -->

<script>


const labelGrafik =
    <?= json_encode($label_grafik); ?>;


const dataGrafik =
    <?= json_encode($data_grafik); ?>;


/* =====================================================
   BATAS ATAS GRAFIK DINAMIS
===================================================== */

const nilaiMaksimum =
    Math.max(...dataGrafik, 0);


let batasAtas;


if (nilaiMaksimum === 0) {

    batasAtas = 5;

}
else if (nilaiMaksimum <= 5) {

    batasAtas = nilaiMaksimum + 1;

}
else {

    batasAtas =
        Math.ceil(nilaiMaksimum * 1.2);

}


/* =====================================================
   CANVAS
===================================================== */

const ctx =
    document.getElementById(
        "grafikKunjungan"
    );


/* =====================================================
   CHART
===================================================== */

new Chart(ctx, {

    type: "bar",


    data: {

        labels: labelGrafik,


        datasets: [

            {

                label: "Jumlah Kunjungan",

                data: dataGrafik,

                backgroundColor: "#c77fa3",

                borderColor: "#b96f94",

                borderWidth: 1,

                borderRadius: 8,

                maxBarThickness: 35

            }

        ]

    },


    options: {

        responsive: true,

        maintainAspectRatio: false,


        plugins: {

            legend: {

                display: true,


                labels: {

                    color: "#b96f94",


                    font: {

                        family: "Poppins",

                        size: 13,

                        weight: "600"

                    }

                }

            }

        },


        scales: {


            x: {

                title: {

                    display: true,

                    text: "Tanggal",

                    color: "#8f7585"

                },


                ticks: {

                    color: "#9b8290"

                },


                grid: {

                    display: false

                }

            },


            y: {

                beginAtZero: true,

                max: batasAtas,


                ticks: {

                    precision: 0,

                    stepSize: 1,

                    color: "#9b8290"

                },


                title: {

                    display: true,

                    text: "Jumlah Kunjungan",

                    color: "#8f7585"

                },


                grid: {

                    color: "#f1dfe8"

                }

            }

        }

    }

});

</script>



<!-- =====================================================
     NOTIFIKASI ADMIN
===================================================== -->

<script>


const notificationButton =
    document.getElementById("notificationButton");


const notificationDropdown =
    document.getElementById("notificationDropdown");


const notificationBadge =
    document.getElementById("notificationBadge");


const notificationList =
    document.getElementById("notificationList");


const notificationCountText =
    document.getElementById("notificationCountText");


let jumlahNotifikasiSebelumnya = 0;


/* =====================================================
   FORMAT TANGGAL
===================================================== */

function formatTanggal(tanggal) {

    if (!tanggal) {
        return "";
    }


    const parts =
        tanggal.split("-");


    if (parts.length !== 3) {
        return tanggal;
    }


    const tahun = parts[0];

    const bulan = parts[1];

    const hari = parts[2];


    const namaBulan = [

        "Januari",

        "Februari",

        "Maret",

        "April",

        "Mei",

        "Juni",

        "Juli",

        "Agustus",

        "September",

        "Oktober",

        "November",

        "Desember"

    ];


    return hari + " " +
           namaBulan[parseInt(bulan) - 1] +
           " " +
           tahun;

}


/* =====================================================
   LOAD NOTIFIKASI
===================================================== */

function loadNotifications() {

    fetch(
        "notifikasi.php",
        {
            method: "GET",
            cache: "no-store"
        }
    )

    .then(response => response.json())

    .then(result => {


        if (result.status !== "success") {
            return;
        }


        const total =
            parseInt(result.total) || 0;


        /* BADGE */

        if (total > 0) {

            notificationBadge.textContent =
                total > 99 ? "99+" : total;

            notificationBadge.classList.add(
                "show"
            );

            notificationButton.classList.add(
                "has-notification"
            );

        } else {

            notificationBadge.textContent = "0";

            notificationBadge.classList.remove(
                "show"
            );

            notificationButton.classList.remove(
                "has-notification"
            );

        }


        /* HEADER */

        notificationCountText.textContent =
            total + " baru";


        /* LIST */

        if (
            !result.data ||
            result.data.length === 0
        ) {

            notificationList.innerHTML = `

                <div class="notification-empty">

                    <i class="fa-regular fa-bell-slash"></i>

                    <p>
                        Belum ada kunjungan.
                    </p>

                </div>

            `;

            return;
        }


        let html = "";


        result.data.forEach(item => {

            html += `

                <a
                    href="data_tamu.php"
                    class="notification-item">

                    <div class="notification-icon">

                        <i class="fa-solid fa-user-plus"></i>

                    </div>


                    <div class="notification-text">

                        <strong>
                            ${escapeHtml(item.nama_tamu)}
                        </strong>


                        <p>
                            ${
                                escapeHtml(
                                    item.instansi ||
                                    "Instansi tidak diisi"
                                )
                            }
                        </p>


                        <p>

                            Keperluan:

                            ${
                                escapeHtml(
                                    item.keperluan || "-"
                                )
                            }

                        </p>


                        <div class="notification-time">

                            <i class="fa-regular fa-calendar"></i>

                            ${formatTanggal(item.tanggal)}

                            &nbsp;

                            <i class="fa-regular fa-clock"></i>

                            ${escapeHtml(item.jam)}

                        </div>

                    </div>

                </a>

            `;

        });


        notificationList.innerHTML = html;


        /* ANIMASI */

        if (
            jumlahNotifikasiSebelumnya !== 0 &&
            total > jumlahNotifikasiSebelumnya
        ) {

            notificationButton.classList.remove(
                "has-notification"
            );


            void notificationButton.offsetWidth;


            notificationButton.classList.add(
                "has-notification"
            );

        }


        jumlahNotifikasiSebelumnya = total;

    })


    .catch(error => {

        console.error(
            "Gagal mengambil notifikasi:",
            error
        );

    });

}


/* =====================================================
   ESCAPE HTML
===================================================== */

function escapeHtml(text) {

    if (
        text === null ||
        text === undefined
    ) {

        return "";

    }


    const div =
        document.createElement("div");


    div.textContent = text;


    return div.innerHTML;

}


/* =====================================================
   BUKA / TUTUP NOTIFIKASI
===================================================== */

notificationButton.addEventListener(
    "click",
    function(event) {


        event.stopPropagation();


        notificationDropdown.classList.toggle(
            "show"
        );


        if (
            notificationDropdown.classList.contains(
                "show"
            )
        ) {

            fetch(
                "notifikasi_dibaca.php",
                {

                    method: "POST",

                    headers: {

                        "Content-Type":
                            "application/x-www-form-urlencoded"

                    },

                    body:
                        "total=" +
                        encodeURIComponent(
                            jumlahNotifikasiSebelumnya
                        )

                }
            );

        }

    }
);


/* =====================================================
   KLIK DI LUAR
===================================================== */

document.addEventListener(
    "click",
    function(event) {


        if (
            !event.target.closest(
                ".notification-wrapper"
            )
        ) {

            notificationDropdown.classList.remove(
                "show"
            );

        }

    }
);


/* =====================================================
   LOAD PERTAMA
===================================================== */

loadNotifications();


/* =====================================================
   CEK SETIAP 5 DETIK
===================================================== */

setInterval(
    loadNotifications,
    5000
);

</script>


</body>

</html>