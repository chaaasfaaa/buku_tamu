<?php

session_start();

include "../config/database.php";

/* =========================================
   CEK LOGIN ADMIN
========================================= */

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {

    header("Location: ../auth/login.php");
    exit;

}

date_default_timezone_set("Asia/Jakarta");


/* =========================================
   AMBIL ID TAMU
========================================= */

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


if ($id <= 0) {

    echo "
    <script>
        alert('Data tamu tidak ditemukan.');
        window.location.href='data_tamu.php';
    </script>
    ";

    exit;

}


/* =========================================
   AMBIL DATA TAMU
   SEKALIGUS FOTO USER
========================================= */

$query = mysqli_query($conn, "

    SELECT
        tamu.*,
        users.foto

    FROM tamu

    LEFT JOIN users
        ON tamu.id_user = users.id

    WHERE tamu.id = '$id'

    LIMIT 1

");


if (!$query) {

    die(
        "Terjadi kesalahan database: "
        . mysqli_error($conn)
    );

}


$d = mysqli_fetch_assoc($query);


if (!$d) {

    echo "
    <script>
        alert('Data tamu tidak ditemukan.');
        window.location.href='data_tamu.php';
    </script>
    ";

    exit;

}


/* =========================================
   DATA TAMU
========================================= */

$nama_tamu =
    $d['nama_tamu']
    ?? "-";


$jenis_kelamin =
    $d['jenis_kelamin']
    ?? "-";


$instansi =
    $d['instansi']
    ?? "-";


$no_hp =
    $d['no_hp']
    ?? "-";


$keperluan =
    $d['keperluan']
    ?? "-";


$tujuan =
    $d['tujuan']
    ?? "-";


$tanggal =
    !empty($d['tanggal'])
    ? date(
        "d F Y",
        strtotime($d['tanggal'])
    )
    : "-";


$jam_masuk =
    !empty($d['jam_masuk'])
    ? $d['jam_masuk']
    : "-";


$jam_keluar =
    !empty($d['jam_keluar'])
    ? $d['jam_keluar']
    : "";


$created_at =
    !empty($d['created_at'])
    ? date(
        "d F Y, H:i:s",
        strtotime($d['created_at'])
    )
    : "-";


/* =========================================
   INISIAL
========================================= */

$inisial = strtoupper(
    substr(
        $nama_tamu,
        0,
        1
    )
);


/* =========================================
   CEK FOTO
========================================= */

$foto_ada = false;

if (!empty($d['foto'])) {

    $lokasi_foto =
        __DIR__
        . "/../uploads/profil/"
        . $d['foto'];

    if (file_exists($lokasi_foto)) {

        $foto_ada = true;

    }

}


/* =========================================
   STATUS KUNJUNGAN
========================================= */

if (!empty($jam_keluar)) {

    $status_text = "Kunjungan Selesai";

    $status_class = "selesai";

    $status_icon =
        "fa-circle-check";

} else {

    $status_text = "Masih Berkunjung";

    $status_class = "berkunjung";

    $status_icon =
        "fa-clock";

}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Detail Data Tamu | Buku Tamu Digital
    </title>


    <!-- FONT -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- FONT AWESOME -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            font-family: "Poppins", sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #fff9fc,
                    #fdf2f7
                );

            color: #453044;

            min-height: 100vh;

        }


        /* =========================================
           SIDEBAR
        ========================================= */

        .sidebar {

            position: fixed;

            left: 0;
            top: 0;

            width: 218px;

            height: 100vh;

            background:
                linear-gradient(
                    180deg,
                    #c986a8,
                    #c47fa3
                );

            padding: 25px 15px;

            z-index: 100;

        }


        .logo {

            display: flex;

            align-items: center;

            gap: 10px;

            color: white;

            padding: 0 7px 25px;

            margin-bottom: 18px;

            border-bottom:
                1px solid
                rgba(255,255,255,0.35);

        }


        .logo i {

            font-size: 31px;

        }


        .logo h2 {

            font-size: 23px;

            line-height: 1;

            font-weight: 800;

        }


        .sidebar a {

            display: flex;

            align-items: center;

            gap: 15px;

            text-decoration: none;

            color: white;

            padding: 14px 16px;

            margin: 7px 0;

            border-radius: 15px;

            font-size: 14px;

            font-weight: 600;

            transition: 0.25s;

        }


        .sidebar a i {

            width: 20px;

            text-align: center;

            font-size: 16px;

        }


        .sidebar a:hover {

            background:
                rgba(255,255,255,0.15);

        }


        .sidebar a.active {

            background: white;

            color: #c47fa3;

            box-shadow:
                0 8px 20px
                rgba(110,55,80,0.12);

        }


        /* =========================================
           MAIN
        ========================================= */

        .main {

            margin-left: 218px;

            min-height: 100vh;

            padding: 35px 45px 50px;

        }


        /*
        =========================================
        KONTEN DIBUAT DI TENGAH
        =========================================
        */

        .topbar,
        .detail-box,
        .page-footer {

            width: 100%;

            max-width: 1050px;

            margin-left: auto;

            margin-right: auto;

        }


        /* =========================================
           TOP HEADER
        ========================================= */

        .topbar {

            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 30px;

        }


        .breadcrumb {

            display: flex;

            align-items: center;

            gap: 8px;

            color: #a17b91;

            font-size: 13px;

            margin-bottom: 6px;

        }


        .breadcrumb a {

            color: #b96e95;

            text-decoration: none;

            font-weight: 600;

        }


        .breadcrumb i {

            font-size: 10px;

        }


        .topbar h1 {

            font-size: 30px;

            color: #3e263b;

            font-weight: 800;

        }


        .topbar-subtitle {

            color: #957687;

            font-size: 14px;

            margin-top: 4px;

        }


        /* =========================================
           DETAIL CARD
        ========================================= */

        .detail-box {

            background: white;

            border-radius: 24px;

            padding: 30px;

            box-shadow:
                0 12px 35px
                rgba(164, 102, 132, 0.10);

            border:
                1px solid
                #f3dce7;

        }


        /* =========================================
           PROFILE HEADER
        ========================================= */

        .profile-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 25px;

            padding-bottom: 25px;

            border-bottom:
                1px solid
                #f2e1e8;

            margin-bottom: 25px;

        }


        .profile-left {

            display: flex;

            align-items: center;

            gap: 18px;

        }


        .profile-photo {

            width: 82px;

            height: 82px;

            min-width: 82px;

            border-radius: 50%;

            overflow: hidden;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #f9dce9,
                    #f2c8dc
                );

            color: #b65e87;

            font-size: 27px;

            font-weight: 700;

            border:
                4px solid
                #fff;

            box-shadow:
                0 5px 18px
                rgba(181, 101, 139, 0.18);

        }


        .profile-photo img {

            width: 100%;

            height: 100%;

            object-fit: cover;

        }


        .profile-name h2 {

            color: #432d40;

            font-size: 22px;

            font-weight: 800;

            margin-bottom: 4px;

        }


        .profile-name p {

            color: #987c8e;

            font-size: 13px;

        }


        /* =========================================
           STATUS
        ========================================= */

        .visit-status {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 10px 15px;

            border-radius: 30px;

            font-size: 12px;

            font-weight: 700;

            white-space: nowrap;

        }


        .visit-status.berkunjung {

            background: #fff1d7;

            color: #c27c17;

        }


        .visit-status.selesai {

            background: #e5f8ee;

            color: #26915d;

        }


        /* =========================================
           SECTION TITLE
        ========================================= */

        .section-title {

            display: flex;

            align-items: center;

            gap: 10px;

            margin-bottom: 17px;

        }


        .section-title-icon {

            width: 36px;

            height: 36px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f8dce9;

            color: #b35d84;

        }


        .section-title h3 {

            color: #493044;

            font-size: 16px;

            font-weight: 700;

        }


        /* =========================================
           DATA GRID
        ========================================= */

        .data-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 14px;

            margin-bottom: 25px;

        }


        .data-item {

            background: #fff9fc;

            border:
                1px solid
                #f2dfe8;

            border-radius: 15px;

            padding: 16px 17px;

            transition: 0.2s;

        }


        .data-item:hover {

            border-color: #e5b8ce;

            transform: translateY(-1px);

        }


        .data-label {

            display: flex;

            align-items: center;

            gap: 8px;

            color: #a17d91;

            font-size: 11px;

            font-weight: 600;

            margin-bottom: 7px;

            text-transform: uppercase;

            letter-spacing: 0.3px;

        }


        .data-label i {

            color: #c2759b;

            font-size: 12px;

        }


        .data-value {

            color: #463041;

            font-size: 14px;

            font-weight: 600;

            line-height: 1.5;

        }


        /* =========================================
           HIGHLIGHT DATA
        ========================================= */

        .highlight-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 15px;

            margin-bottom: 25px;

        }


        .highlight-card {

            border-radius: 17px;

            padding: 18px;

            display: flex;

            gap: 14px;

            align-items: flex-start;

        }


        .highlight-card.keperluan {

            background:
                linear-gradient(
                    135deg,
                    #fff0f6,
                    #fff8fb
                );

            border:
                1px solid
                #f4d4e3;

        }


        .highlight-card.tujuan {

            background:
                linear-gradient(
                    135deg,
                    #f9eefb,
                    #fff9fc
                );

            border:
                1px solid
                #ead9ee;

        }


        .highlight-icon {

            width: 42px;

            height: 42px;

            min-width: 42px;

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: white;

            color: #b65e87;

            box-shadow:
                0 4px 12px
                rgba(150,80,110,0.08);

        }


        .highlight-card h4 {

            color: #9b6b83;

            font-size: 11px;

            text-transform: uppercase;

            margin-bottom: 5px;

            letter-spacing: .4px;

        }


        .highlight-card p {

            color: #432d3d;

            font-size: 14px;

            font-weight: 700;

            line-height: 1.5;

        }


        /* =========================================
           WAKTU KUNJUNGAN
        ========================================= */

        .visit-box {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 14px;

            margin-bottom: 25px;

        }


        .visit-item {

            padding: 17px;

            border-radius: 15px;

            background: #faf7f9;

            border:
                1px solid
                #eee0e7;

        }


        .visit-item .visit-icon {

            width: 36px;

            height: 36px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f6dce8;

            color: #b55f86;

            margin-bottom: 9px;

        }


        .visit-item span {

            display: block;

            color: #a18191;

            font-size: 11px;

            margin-bottom: 4px;

        }


        .visit-item strong {

            color: #44303f;

            font-size: 14px;

        }


        /* =========================================
           CREATED INFO
        ========================================= */

        .created-info {

            display: flex;

            align-items: center;

            gap: 9px;

            padding-top: 17px;

            border-top:
                1px solid
                #f0e1e7;

            color: #9a8290;

            font-size: 12px;

        }


        .created-info i {

            color: #c2779b;

        }


        /* =========================================
           BUTTON
        ========================================= */

        .button-area {

            display: flex;

            align-items: center;

            gap: 10px;

            flex-wrap: wrap;

            margin-top: 25px;

        }


        .btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            min-height: 43px;

            padding: 11px 18px;

            border-radius: 12px;

            text-decoration: none;

            font-family: "Poppins", sans-serif;

            font-size: 13px;

            font-weight: 600;

            border: none;

            cursor: pointer;

            transition: 0.25s;

        }


        .btn:hover {

            transform: translateY(-2px);

        }


        .btn-primary {

            background:
                linear-gradient(
                    135deg,
                    #d18aac,
                    #c47ca2
                );

            color: white;

            box-shadow:
                0 6px 15px
                rgba(185, 110, 149, 0.20);

        }


        .btn-primary:hover {

            box-shadow:
                0 8px 20px
                rgba(185, 110, 149, 0.28);

        }


        .btn-secondary {

            background: #f7e7ef;

            color: #a6557c;

        }


        .btn-secondary:hover {

            background: #f1d7e4;

        }


        .btn-success {

            background:
                linear-gradient(
                    135deg,
                    #6fc29b,
                    #50aa80
                );

            color: white;

            box-shadow:
                0 6px 15px
                rgba(75, 170, 125, 0.18);

        }


        .btn-success:hover {

            box-shadow:
                0 8px 20px
                rgba(75, 170, 125, 0.25);

        }


        /* =========================================
           FOOTER
        ========================================= */

        .page-footer {

            text-align: center;

            color: #ae94a2;

            font-size: 11px;

            margin-top: 25px;

        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 1100px) {

            .main {

                padding: 30px 25px 45px;

            }

        }


        @media (max-width: 900px) {

            .main {

                margin-left: 0;

                padding: 25px 20px;

            }


            .sidebar {

                position: relative;

                width: 100%;

                height: auto;

            }


            .data-grid,
            .highlight-grid {

                grid-template-columns: 1fr;

            }


            .visit-box {

                grid-template-columns: 1fr;

            }


            .topbar,
            .detail-box,
            .page-footer {

                max-width: 100%;

            }

        }


        @media (max-width: 600px) {

            .topbar {

                display: block;

            }


            .topbar h1 {

                font-size: 24px;

            }


            .detail-box {

                padding: 20px;

                border-radius: 18px;

            }


            .profile-header {

                align-items: flex-start;

                flex-direction: column;

            }


            .profile-left {

                align-items: flex-start;

            }


            .button-area {

                flex-direction: column;

                align-items: stretch;

            }


            .btn {

                width: 100%;

            }

        }

    </style>

</head>


<body>


<!-- =========================================
     SIDEBAR
========================================= -->

<div class="sidebar">


    <div class="logo">

        <i class="fa-solid fa-book-open"></i>

        <h2>
            Buku Tamu
        </h2>

    </div>


    <a href="dashboard.php">

        <i class="fa-solid fa-house"></i>

        Dashboard

    </a>


    <a
        class="active"
        href="data_tamu.php"
    >

        <i class="fa-solid fa-users"></i>

        Data Tamu

    </a>


    <a href="keperluan.php">

        <i class="fa-solid fa-list-check"></i>

        Keperluan

    </a>


    <a href="pencarian.php">

        <i class="fa-solid fa-magnifying-glass"></i>

        Pencarian

    </a>


    <a href="laporan.php">

        <i class="fa-solid fa-print"></i>

        Laporan

    </a>


    <a href="../auth/logout.php">

        <i class="fa-solid fa-right-from-bracket"></i>

        Logout

    </a>


</div>



<!-- =========================================
     MAIN
========================================= -->

<div class="main">


    <!-- =====================================
         HEADER
    ====================================== -->

    <div class="topbar">


        <div>

            <div class="breadcrumb">

                <a href="data_tamu.php">
                    Data Tamu
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <span>
                    Detail
                </span>

            </div>


            <h1>
                Detail Data Tamu
            </h1>


            <p class="topbar-subtitle">
                Informasi lengkap mengenai kunjungan tamu.
            </p>

        </div>


    </div>



    <!-- =====================================
         DETAIL
    ====================================== -->

    <div class="detail-box">


        <!-- =================================
             PROFILE HEADER
        ================================== -->

        <div class="profile-header">


            <div class="profile-left">


                <div class="profile-photo">


                    <?php if ($foto_ada): ?>

                        <img
                            src="../uploads/profil/<?= htmlspecialchars($d['foto']); ?>"
                            alt="Foto <?= htmlspecialchars($nama_tamu); ?>"
                        >

                    <?php else: ?>

                        <?= htmlspecialchars($inisial); ?>

                    <?php endif; ?>


                </div>


                <div class="profile-name">

                    <h2>

                        <?= htmlspecialchars($nama_tamu); ?>

                    </h2>


                    <p>

                        <i class="fa-solid fa-calendar-days"></i>

                        <?= htmlspecialchars($tanggal); ?>

                    </p>

                </div>


            </div>


            <div class="visit-status <?= $status_class; ?>">

                <i class="fa-solid <?= $status_icon; ?>"></i>

                <?= $status_text; ?>

            </div>


        </div>



        <!-- =================================
             INFORMASI TAMU
        ================================== -->

        <div class="section-title">

            <div class="section-title-icon">

                <i class="fa-solid fa-user"></i>

            </div>

            <h3>
                Informasi Tamu
            </h3>

        </div>


        <div class="data-grid">


            <!-- NAMA -->

            <div class="data-item">

                <div class="data-label">

                    <i class="fa-solid fa-user"></i>

                    Nama Tamu

                </div>

                <div class="data-value">

                    <?= htmlspecialchars($nama_tamu); ?>

                </div>

            </div>


            <!-- JENIS KELAMIN -->

            <div class="data-item">

                <div class="data-label">

                    <i class="fa-solid fa-venus-mars"></i>

                    Jenis Kelamin

                </div>

                <div class="data-value">

                    <?= htmlspecialchars($jenis_kelamin); ?>

                </div>

            </div>


            <!-- INSTANSI -->

            <div class="data-item">

                <div class="data-label">

                    <i class="fa-solid fa-building"></i>

                    Instansi / Keterangan

                </div>

                <div class="data-value">

                    <?= htmlspecialchars($instansi); ?>

                </div>

            </div>


            <!-- NO HP -->

            <div class="data-item">

                <div class="data-label">

                    <i class="fa-solid fa-phone"></i>

                    Nomor HP

                </div>

                <div class="data-value">

                    <?= htmlspecialchars($no_hp); ?>

                </div>

            </div>


        </div>



        <!-- =================================
             KEPERLUAN & TUJUAN
        ================================== -->

        <div class="section-title">

            <div class="section-title-icon">

                <i class="fa-solid fa-clipboard-list"></i>

            </div>

            <h3>
                Keperluan Kunjungan
            </h3>

        </div>


        <div class="highlight-grid">


            <!-- KEPERLUAN -->

            <div class="highlight-card keperluan">


                <div class="highlight-icon">

                    <i class="fa-regular fa-comment-dots"></i>

                </div>


                <div>

                    <h4>
                        Keperluan
                    </h4>

                    <p>

                        <?= htmlspecialchars($keperluan); ?>

                    </p>

                </div>


            </div>


            <!-- TUJUAN -->

            <div class="highlight-card tujuan">


                <div class="highlight-icon">

                    <i class="fa-solid fa-location-dot"></i>

                </div>


                <div>

                    <h4>
                        Tujuan
                    </h4>

                    <p>

                        <?= htmlspecialchars($tujuan); ?>

                    </p>

                </div>


            </div>


        </div>



        <!-- =================================
             WAKTU KUNJUNGAN
        ================================== -->

        <div class="section-title">

            <div class="section-title-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <h3>
                Waktu Kunjungan
            </h3>

        </div>


        <div class="visit-box">


            <!-- TANGGAL -->

            <div class="visit-item">


                <div class="visit-icon">

                    <i class="fa-solid fa-calendar-days"></i>

                </div>


                <span>
                    Tanggal
                </span>


                <strong>

                    <?= htmlspecialchars($tanggal); ?>

                </strong>


            </div>


            <!-- JAM MASUK -->

            <div class="visit-item">


                <div class="visit-icon">

                    <i class="fa-solid fa-right-to-bracket"></i>

                </div>


                <span>
                    Jam Masuk
                </span>


                <strong>

                    <?= htmlspecialchars($jam_masuk); ?>

                </strong>


            </div>


            <!-- JAM KELUAR -->

            <div class="visit-item">


                <div class="visit-icon">

                    <i class="fa-solid fa-right-from-bracket"></i>

                </div>


                <span>
                    Jam Keluar
                </span>


                <strong>

                    <?= !empty($jam_keluar)
                        ? htmlspecialchars($jam_keluar)
                        : "Belum keluar"; ?>

                </strong>


            </div>


        </div>



        <!-- =================================
             DIBUAT
        ================================== -->

        <div class="created-info">

            <i class="fa-solid fa-circle-info"></i>

            Data kunjungan dibuat pada

            <strong>

                <?= htmlspecialchars($created_at); ?>

            </strong>

        </div>



        <!-- =================================
             BUTTON
        ================================== -->

        <div class="button-area">


            <?php if (empty($jam_keluar)): ?>

                <a
                    href="proses_jam_keluar.php?id=<?= $d['id']; ?>"
                    class="btn btn-success"
                    onclick="return confirm('Catat jam keluar tamu sekarang?');"
                >

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Catat Jam Keluar

                </a>

            <?php endif; ?>


            <a
                href="edit_tamu.php?id=<?= $d['id']; ?>"
                class="btn btn-primary"
            >

                <i class="fa-solid fa-pen"></i>

                Edit Data

            </a>


            <a
                href="data_tamu.php"
                class="btn btn-secondary"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Kembali

            </a>


        </div>


    </div>



    <!-- =====================================
         FOOTER
    ====================================== -->

    <div class="page-footer">

        © <?= date("Y"); ?>

        Buku Tamu Digital SMK Pelita

    </div>


</div>


</body>

</html>