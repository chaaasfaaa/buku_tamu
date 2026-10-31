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

date_default_timezone_set("Asia/Jakarta");


/* =========================================
   DATA STATISTIK
========================================= */

$query_total_tamu = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tamu
");

$data_total_tamu = mysqli_fetch_assoc($query_total_tamu);

$total_tamu = $data_total_tamu['total'] ?? 0;


$query_bulan_ini = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tamu
    WHERE MONTH(tanggal) = MONTH(CURDATE())
    AND YEAR(tanggal) = YEAR(CURDATE())
");

$data_bulan_ini = mysqli_fetch_assoc($query_bulan_ini);

$bulan_ini = $data_bulan_ini['total'] ?? 0;


$query_total_keperluan = mysqli_query($conn, "
    SELECT COUNT(DISTINCT keperluan) AS total
    FROM tamu
    WHERE keperluan IS NOT NULL
    AND keperluan != ''
");

$data_total_keperluan = mysqli_fetch_assoc(
    $query_total_keperluan
);

$total_keperluan =
    $data_total_keperluan['total'] ?? 0;


/* =========================================
   DATA KEPERLUAN
   SEKALIGUS AMBIL FOTO USER
========================================= */

$data = mysqli_query($conn, "

    SELECT
        tamu.id,
        tamu.id_user,
        tamu.nama_tamu,
        tamu.keperluan,
        tamu.tanggal,
        tamu.jam_masuk,
        users.foto

    FROM tamu

    LEFT JOIN users
        ON tamu.id_user = users.id

    WHERE tamu.keperluan IS NOT NULL
    AND tamu.keperluan != ''

    ORDER BY tamu.id DESC

");


if (!$data) {

    die(
        "Terjadi kesalahan database: "
        . mysqli_error($conn)
    );

}


/* =========================================
   DATA TERBARU
========================================= */

$query_terbaru = mysqli_query($conn, "

    SELECT
        nama_tamu,
        keperluan,
        tanggal

    FROM tamu

    WHERE keperluan IS NOT NULL
    AND keperluan != ''

    ORDER BY id DESC

    LIMIT 1

");


$data_terbaru = null;


if ($query_terbaru) {

    $data_terbaru =
        mysqli_fetch_assoc($query_terbaru);

}


$nama_terbaru = "-";
$keperluan_terbaru = "-";
$tanggal_terbaru = "-";


if ($data_terbaru) {

    $nama_terbaru =
        $data_terbaru['nama_tamu']
        ?? "-";

    $keperluan_terbaru =
        $data_terbaru['keperluan']
        ?? "-";

    $tanggal_terbaru =
        !empty($data_terbaru['tanggal'])
        ? date(
            "d/m/Y",
            strtotime($data_terbaru['tanggal'])
        )
        : "-";

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
        Data Keperluan | Buku Tamu Digital
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
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- FONT AWESOME -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/keperluan.css?v=102"
    >

    <link
        rel="stylesheet"
        href="../assets/css/sidebar.css?v=100"
    >


    <!-- =========================================
         CSS FOTO + TOMBOL HAPUS
    ========================================= -->

    <style>

        .avatar {

            width: 40px;

            height: 40px;

            min-width: 40px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f8e8f0;

            color: #ad5c80;

            border-radius: 50%;

            font-size: 13px;

            font-weight: 700;

            overflow: hidden;

        }


        .avatar img {

            width: 100%;

            height: 100%;

            object-fit: cover;

            display: block;

        }


        /* =========================================
           TOMBOL HAPUS
        ========================================= */

        .btn-hapus {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            min-width: 75px;

            height: 36px;

            padding: 0 12px;

            background: #ffffff;

            color: #d46a7d;

            border: 1px solid #efb6c2;

            border-radius: 9px;

            text-decoration: none;

            font-family: "Poppins", sans-serif;

            font-size: 10px;

            font-weight: 700;

            transition: .2s ease;

        }


        .btn-hapus:hover {

            background: #fff1f4;

            border-color: #d46a7d;

            transform: translateY(-2px);

            box-shadow:
                0 5px 12px
                rgba(212,106,125,.10);

        }


        .aksi {

            text-align: center;

            vertical-align: middle;

        }


        .aksi-wrapper {

            display: flex;

            align-items: center;

            justify-content: center;

        }

    </style>

</head>


<body>


<?php include "sidebar.php"; ?>


<!-- =========================================
     CONTENT
========================================= -->

<div class="content">


    <!-- =====================================
         HEADER
    ====================================== -->

    <div class="page-header">


        <!-- KIRI -->

        <div class="title-section">


            <div class="title-heading">

                <div class="title-icon">

                    <i class="fa-solid fa-clipboard-list"></i>

                </div>


                <div>

                    <h1>
                        Data Keperluan
                    </h1>

                    <p>
                        Daftar keperluan yang disampaikan oleh tamu.
                    </p>

                </div>

            </div>


            <!-- SEARCH -->

            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    id="searchKeperluan"
                    placeholder="Cari keperluan..."
                >

            </div>


        </div>


        <!-- KANAN -->

        <div class="stats-container">


            <!-- TOTAL TAMU -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="fa-solid fa-users"></i>

                </div>


                <div class="stat-info">

                    <span>
                        Total Tamu
                    </span>

                    <strong>
                        <?= $total_tamu; ?>
                    </strong>

                    <small>
                        Orang
                    </small>

                </div>

            </div>


            <!-- BULAN INI -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="fa-solid fa-calendar-days"></i>

                </div>


                <div class="stat-info">

                    <span>
                        Bulan Ini
                    </span>

                    <strong>
                        <?= $bulan_ini; ?>
                    </strong>

                    <small>
                        Kunjungan
                    </small>

                </div>

            </div>


            <!-- JENIS KEPERLUAN -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="fa-solid fa-list-check"></i>

                </div>


                <div class="stat-info">

                    <span>
                        Jenis Keperluan
                    </span>

                    <strong>
                        <?= $total_keperluan; ?>
                    </strong>

                    <small>
                        Jenis
                    </small>

                </div>

            </div>


        </div>


    </div>


    <!-- =====================================
         TABLE
    ====================================== -->

    <div class="table-container">


        <!-- TABLE HEADER -->

        <div class="table-header">


            <div>

                <h2>

                    <i class="fa-solid fa-table-list"></i>

                    Daftar Keperluan

                </h2>


                <p>
                    Seluruh keperluan yang dicatat dari kunjungan tamu.
                </p>

            </div>


            <div class="data-count">

                <i class="fa-solid fa-database"></i>

                <?= $total_keperluan; ?>

                Jenis

            </div>


        </div>


        <!-- TABLE -->

        <div class="table-wrapper">


            <table>


                <thead>

                    <tr>

                        <th>
                            No
                        </th>

                        <th>
                            Nama Tamu
                        </th>

                        <th>
                            Keperluan
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Jam Masuk
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody id="tableBody">


                <?php

                $no = 1;


                if (mysqli_num_rows($data) > 0):


                    while (
                        $d = mysqli_fetch_assoc($data)
                    ):


                        $nama =
                            $d['nama_tamu']
                            ?? "-";


                        $inisial =
                            strtoupper(
                                substr(
                                    $nama,
                                    0,
                                    1
                                )
                            );

                ?>


                    <tr>


                        <!-- NO -->

                        <td class="nomor">

                            <span class="number-box">

                                <?= $no++; ?>

                            </span>

                        </td>


                        <!-- NAMA -->

                        <td class="nama">


                            <div class="nama-wrapper">


                                <!-- FOTO USER -->

                                <span class="avatar">


                                    <?php if (
                                        !empty($d['foto']) &&
                                        file_exists(
                                            __DIR__ .
                                            "/../uploads/profil/" .
                                            $d['foto']
                                        )
                                    ): ?>


                                        <img
                                            src="../uploads/profil/<?= htmlspecialchars($d['foto']); ?>"
                                            alt="Foto Profil"
                                        >


                                    <?php else: ?>


                                        <?= htmlspecialchars(
                                            $inisial
                                        ); ?>


                                    <?php endif; ?>


                                </span>


                                <strong>

                                    <?= htmlspecialchars(
                                        $nama
                                    ); ?>

                                </strong>


                            </div>


                        </td>


                        <!-- KEPERLUAN -->

                        <td class="keperluan">


                            <div class="data-wrapper">


                                <span class="icon-box">

                                    <i class="fa-regular fa-comment"></i>

                                </span>


                                <span>

                                    <?= htmlspecialchars(
                                        $d['keperluan']
                                    ); ?>

                                </span>


                            </div>


                        </td>


                        <!-- TANGGAL -->

                        <td class="tanggal">


                            <div class="data-wrapper">


                                <span class="icon-box">

                                    <i class="fa-regular fa-calendar"></i>

                                </span>


                                <div class="tanggal-text">


                                    <strong>

                                        <?php

                                        if (
                                            !empty(
                                                $d['tanggal']
                                            )
                                        ) {

                                            echo date(
                                                "d/m/Y",
                                                strtotime(
                                                    $d['tanggal']
                                                )
                                            );

                                        } else {

                                            echo "-";

                                        }

                                        ?>

                                    </strong>


                                    <small>

                                        <?php

                                        if (
                                            !empty(
                                                $d['tanggal']
                                            )
                                        ) {


                                            $hari =
                                                date(
                                                    "l",
                                                    strtotime(
                                                        $d['tanggal']
                                                    )
                                                );


                                            $hari_id = [

                                                "Sunday" =>
                                                    "Minggu",

                                                "Monday" =>
                                                    "Senin",

                                                "Tuesday" =>
                                                    "Selasa",

                                                "Wednesday" =>
                                                    "Rabu",

                                                "Thursday" =>
                                                    "Kamis",

                                                "Friday" =>
                                                    "Jumat",

                                                "Saturday" =>
                                                    "Sabtu"

                                            ];


                                            echo
                                                $hari_id[
                                                    $hari
                                                ]
                                                ?? $hari;

                                        }

                                        ?>

                                    </small>


                                </div>


                            </div>


                        </td>


                        <!-- JAM -->

                        <td class="jam">


                            <div class="data-wrapper">


                                <span class="icon-box">

                                    <i class="fa-regular fa-clock"></i>

                                </span>


                                <span>

                                    <?= htmlspecialchars(
                                        $d['jam_masuk']
                                        ?? "-"
                                    ); ?>

                                </span>


                            </div>


                        </td>


                        <!-- AKSI -->

                        <td class="aksi">


                            <div class="aksi-wrapper">


                                <a
                                    href="hapus_keperluan.php?id=<?= (int)$d['id']; ?>"
                                    class="btn-hapus"
                                    onclick="return confirm('Yakin ingin menghapus keperluan dari <?= htmlspecialchars($nama, ENT_QUOTES); ?>? Data tamu tetap akan disimpan.');"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                    Hapus

                                </a>


                            </div>


                        </td>


                    </tr>


                <?php

                    endwhile;


                else:

                ?>


                    <!-- DATA KOSONG -->

                    <tr>

                        <td colspan="6">


                            <div class="empty-data">


                                <i class="fa-solid fa-clipboard-list"></i>


                                <strong>
                                    Belum ada data keperluan
                                </strong>


                                <p>
                                    Belum ada keperluan yang dicatat dari tamu.
                                </p>


                            </div>


                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>


            </table>


        </div>


    </div>


    <!-- =====================================
         RINGKASAN
    ====================================== -->

    <div class="summary-card">


        <div class="summary-header">


            <div class="summary-icon">

                <i class="fa-solid fa-chart-simple"></i>

            </div>


            <div>

                <h2>
                    Ringkasan Keperluan
                </h2>

                <p>
                    Informasi singkat dari data keperluan yang tersimpan.
                </p>

            </div>


        </div>


        <div class="summary-content">


            <!-- JUMLAH JENIS -->

            <div class="summary-item">

                <div class="summary-item-icon">

                    <i class="fa-solid fa-list-check"></i>

                </div>


                <div>

                    <span>
                        Jenis Keperluan
                    </span>

                    <strong>
                        <?= $total_keperluan; ?> jenis
                    </strong>

                </div>

            </div>


            <!-- TERBARU -->

            <div class="summary-item">

                <div class="summary-item-icon">

                    <i class="fa-solid fa-clock-rotate-left"></i>

                </div>


                <div>

                    <span>
                        Data Terbaru
                    </span>

                    <strong>

                        <?= htmlspecialchars(
                            $nama_terbaru
                        ); ?>

                    </strong>

                    <small>

                        <?= $tanggal_terbaru; ?>

                    </small>

                </div>

            </div>


            <!-- KEPERLUAN TERBARU -->

            <div class="summary-item">

                <div class="summary-item-icon">

                    <i class="fa-solid fa-comment-dots"></i>

                </div>


                <div>

                    <span>
                        Keperluan Terbaru
                    </span>

                    <strong>

                        <?= htmlspecialchars(
                            $keperluan_terbaru
                        ); ?>

                    </strong>

                </div>

            </div>


            <!-- BULAN INI -->

            <div class="summary-item">

                <div class="summary-item-icon">

                    <i class="fa-solid fa-calendar-days"></i>

                </div>


                <div>

                    <span>
                        Kunjungan Bulan Ini
                    </span>

                    <strong>
                        <?= $bulan_ini; ?> kunjungan
                    </strong>

                </div>

            </div>


        </div>


    </div>


    <!-- =====================================
         FOOTER
    ====================================== -->

    <footer>

        © <?= date("Y"); ?> Buku Tamu Digital SMK Pelita

    </footer>


</div>


<!-- =========================================
     SEARCH
========================================= -->

<script>

const searchInput =
    document.getElementById(
        "searchKeperluan"
    );


const rows =
    document.querySelectorAll(
        "#tableBody tr"
    );


searchInput.addEventListener(
    "input",
    function () {

        const keyword =
            this.value
                .toLowerCase()
                .trim();


        rows.forEach(
            function (row) {

                const text =
                    row.textContent
                        .toLowerCase();


                row.style.display =
                    text.includes(keyword)
                    ? ""
                    : "none";

            }
        );

    }
);

</script>


</body>

</html>