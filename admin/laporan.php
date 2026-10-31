<?php

session_start();

include "../config/database.php";


/* =====================================================
   CEK LOGIN ADMIN
===================================================== */

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("Location: ../auth/login.php");
    exit;

}

date_default_timezone_set("Asia/Jakarta");


/* =====================================================
   FILTER TANGGAL
===================================================== */

$tanggal_mulai = isset($_GET['tanggal_mulai'])
    ? trim($_GET['tanggal_mulai'])
    : "";

$tanggal_selesai = isset($_GET['tanggal_selesai'])
    ? trim($_GET['tanggal_selesai'])
    : "";


/* =====================================================
   WHERE
===================================================== */

$where = [];


/* TANGGAL MULAI */

if ($tanggal_mulai != "") {

    $tanggal_mulai_safe = mysqli_real_escape_string(
        $conn,
        $tanggal_mulai
    );

    $where[] =
        "tanggal >= '$tanggal_mulai_safe'";

}


/* TANGGAL SELESAI */

if ($tanggal_selesai != "") {

    $tanggal_selesai_safe = mysqli_real_escape_string(
        $conn,
        $tanggal_selesai
    );

    $where[] =
        "tanggal <= '$tanggal_selesai_safe'";

}


/* GABUNG WHERE */

$where_sql = "";

if (!empty($where)) {

    $where_sql =
        "WHERE " .
        implode(" AND ", $where);

}


/* =====================================================
   QUERY DATA
===================================================== */

$sql = "

    SELECT
        id,
        nama_tamu,
        instansi,
        keperluan,
        tujuan,
        tanggal,
        jam_masuk,
        jam_keluar

    FROM tamu

    $where_sql

    ORDER BY tanggal DESC, id DESC

";


$data = mysqli_query(
    $conn,
    $sql
);


/* =====================================================
   CEK QUERY
===================================================== */

if (!$data) {

    die(
        "Terjadi kesalahan database: " .
        mysqli_error($conn)
    );

}


/* =====================================================
   JUMLAH DATA
===================================================== */

$total_data = mysqli_num_rows($data);


/* =====================================================
   TEKS PERIODE
===================================================== */

if (
    $tanggal_mulai != "" &&
    $tanggal_selesai != ""
) {

    $range_text =
        date(
            "d/m/Y",
            strtotime($tanggal_mulai)
        )
        . " - "
        .
        date(
            "d/m/Y",
            strtotime($tanggal_selesai)
        );

} elseif ($tanggal_mulai != "") {

    $range_text =
        "Mulai " .
        date(
            "d/m/Y",
            strtotime($tanggal_mulai)
        );

} elseif ($tanggal_selesai != "") {

    $range_text =
        "Sampai " .
        date(
            "d/m/Y",
            strtotime($tanggal_selesai)
        );

} else {

    $range_text =
        "Menampilkan seluruh data tamu.";

}


/* =====================================================
   DATA TERBARU
===================================================== */

$data_terbaru = null;

$q_terbaru = mysqli_query($conn, "

    SELECT
        nama_tamu,
        tanggal,
        keperluan

    FROM tamu

    ORDER BY id DESC

    LIMIT 1

");


if ($q_terbaru) {

    $data_terbaru =
        mysqli_fetch_assoc(
            $q_terbaru
        );

}


$nama_terbaru = "-";
$tanggal_terbaru = "-";
$keperluan_terbaru = "-";


if ($data_terbaru) {

    $nama_terbaru =
        $data_terbaru['nama_tamu']
        ?? "-";

    $tanggal_terbaru =
        !empty($data_terbaru['tanggal'])
        ? date(
            "d/m/Y",
            strtotime(
                $data_terbaru['tanggal']
            )
        )
        : "-";

    $keperluan_terbaru =
        $data_terbaru['keperluan']
        ?? "-";

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
    Laporan Data Tamu | Buku Tamu Digital
</title>


<!-- =====================================================
     FONT
===================================================== -->

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


<!-- =====================================================
     FONT AWESOME
===================================================== -->

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
>


<!-- =====================================================
     CSS LAPORAN
===================================================== -->

<link
    rel="stylesheet"
    href="../assets/css/laporan.css?v=500"
>


<!-- =====================================================
     CSS SIDEBAR
===================================================== -->

<link
    rel="stylesheet"
    href="../assets/css/sidebar.css?v=500"
>


<!-- =====================================================
     PRINT
===================================================== -->

<style>

@media print {

    @page {
        size: A4 landscape;
        margin: 8mm;
    }


    * {
        box-sizing: border-box !important;
    }


    html,
    body {
        width: 100% !important;
        min-width: 0 !important;

        margin: 0 !important;
        padding: 0 !important;

        background: #ffffff !important;
    }


    body {
        font-family: Arial, sans-serif !important;

        color: #222222 !important;

        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }


    /* HILANGKAN SIDEBAR + FILTER + TOMBOL */

    .sidebar,
    .no-print {
        display: none !important;
    }


    /* CONTENT */

    .content {
        width: 100% !important;

        min-width: 0 !important;

        max-width: none !important;

        margin: 0 !important;

        padding: 0 !important;
    }


    /* HEADER */

    .report-header {
        width: 100% !important;

        display: flex !important;

        align-items: center !important;

        justify-content: flex-start !important;

        margin-bottom: 7mm !important;

        padding: 0 !important;
    }


    .report-heading {
        display: flex !important;

        align-items: center !important;

        gap: 4mm !important;
    }


    .report-icon {
        width: 15mm !important;

        height: 15mm !important;

        display: flex !important;

        align-items: center !important;

        justify-content: center !important;

        background: #f7e5ed !important;

        color: #a94f78 !important;

        border-radius: 4mm !important;

        font-size: 7mm !important;
    }


    .report-heading h1 {
        margin: 0 !important;

        color: #873e62 !important;

        font-family: Arial, sans-serif !important;

        font-size: 23pt !important;

        line-height: 1.1 !important;
    }


    .report-heading p {
        margin: 1.5mm 0 0 0 !important;

        color: #666666 !important;

        font-family: Arial, sans-serif !important;

        font-size: 9.5pt !important;
    }


    /* TABLE */

    .table-container {
        width: 100% !important;

        margin: 0 !important;

        padding: 0 !important;

        border: none !important;

        border-radius: 0 !important;

        box-shadow: none !important;

        overflow: visible !important;
    }


    .report-table {
        width: 100% !important;

        min-width: 0 !important;

        max-width: none !important;

        table-layout: fixed !important;

        border-collapse: collapse !important;
    }


    .report-table thead {
        display: table-header-group !important;
    }


    .report-table thead th {
        padding: 3mm 2mm !important;

        background: #c9799d !important;

        color: #ffffff !important;

        border: 1px solid #ad6083 !important;

        font-family: Arial, sans-serif !important;

        font-size: 10pt !important;

        font-weight: 700 !important;

        text-align: center !important;

        vertical-align: middle !important;
    }


    .report-table tbody td {
        padding: 3mm 2mm !important;

        background: #ffffff !important;

        color: #333333 !important;

        border: 1px solid #decbd4 !important;

        font-family: Arial, sans-serif !important;

        font-size: 9pt !important;

        line-height: 1.3 !important;

        text-align: center !important;

        vertical-align: middle !important;
    }


    .report-table .nama {
        color: #222222 !important;

        font-weight: 700 !important;
    }


    .number-box {
        display: inline !important;

        width: auto !important;

        height: auto !important;

        padding: 0 !important;

        background: transparent !important;

        border: none !important;

        color: #333333 !important;

        font-size: 9pt !important;
    }


    .jam-masuk,
    .jam-keluar {
        display: inline-block !important;

        min-width: 23mm !important;

        padding: 1.8mm 2mm !important;

        border-radius: 2mm !important;

        font-family: Arial, sans-serif !important;

        font-size: 8.5pt !important;

        white-space: nowrap !important;
    }


    .jam-masuk {
        background: #f7e7ef !important;

        color: #9f4b72 !important;

        border: 1px solid #efd2df !important;
    }


    .jam-keluar {
        background: #f2ebf7 !important;

        color: #76548f !important;

        border: 1px solid #dfd1ea !important;
    }


    .jam-masuk i,
    .jam-keluar i {
        display: none !important;
    }


    .report-table tr {
        page-break-inside: avoid !important;

        break-inside: avoid !important;
    }


    .report-table tbody tr:hover td {
        background: #ffffff !important;
    }


    footer {
        display: block !important;

        width: 100% !important;

        margin-top: 6mm !important;

        text-align: center !important;

        color: #666666 !important;

        font-family: Arial, sans-serif !important;

        font-size: 8.5pt !important;
    }

}

/* =========================================================
   PERBESAR TEKS RINGKASAN LAPORAN
   HANYA UKURAN TEKS
   WARNA & LAYOUT TETAP
========================================================= */

/* Judul Ringkasan Laporan */
.summary-header h2 {
    font-size: 21px !important;
}

/* Keterangan */
.summary-header p {
    font-size: 12px !important;
}

/* Judul kecil dalam kotak */
.summary-item span {
    font-size: 12px !important;
}

/* Isi utama kotak */
.summary-item strong {
    font-size: 15px !important;
}

/* Tanggal */
.summary-item small {
    font-size: 11px !important;
}

/* Footer */
footer {
    font-size: 11px !important;
}

/* =====================================================
   PRINT LAPORAN - SIDEBAR TIDAK IKUT TERCETAK
===================================================== */

@media print {

    @page {
        size: A4 landscape;
        margin: 10mm;
    }


    html,
    body {
        width: 100% !important;
        min-width: 0 !important;

        margin: 0 !important;
        padding: 0 !important;

        background: #ffffff !important;
    }


    body {
        font-family: Arial, sans-serif !important;

        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }


    /* =================================================
       HILANGKAN SIDEBAR
    ================================================= */

    .sidebar,
    aside.sidebar {
        display: none !important;

        visibility: hidden !important;

        width: 0 !important;
        height: 0 !important;

        margin: 0 !important;
        padding: 0 !important;
    }


    /* =================================================
       HILANGKAN BAGIAN YANG TIDAK PERLU DICETAK
    ================================================= */

    .no-print,
    .report-actions,
    .filter-box {
        display: none !important;
    }


    /* =================================================
       CONTENT JADI FULL WIDTH
    ================================================= */

    .content {

        margin: 0 !important;

        margin-left: 0 !important;

        width: 100% !important;

        max-width: none !important;

        min-width: 0 !important;

        padding: 0 !important;
    }


    /* =================================================
       HEADER LAPORAN
    ================================================= */

    .report-header {

        width: 100% !important;

        margin: 0 0 8mm 0 !important;

        padding: 0 !important;

        display: flex !important;

        justify-content: flex-start !important;
    }


    .report-heading {

        display: flex !important;

        align-items: center !important;

        gap: 5mm !important;
    }


    .report-icon {

        width: 15mm !important;

        height: 15mm !important;

        display: flex !important;

        align-items: center !important;

        justify-content: center !important;

        flex-shrink: 0 !important;
    }


    .report-heading h1 {

        font-family: Arial, sans-serif !important;

        font-size: 23pt !important;

        font-weight: 700 !important;

        margin: 0 !important;
    }


    .report-heading p {

        font-family: Arial, sans-serif !important;

        font-size: 10pt !important;

        margin-top: 1.5mm !important;
    }


    /* =================================================
       TABLE CONTAINER
    ================================================= */

    .table-container {

        width: 100% !important;

        max-width: none !important;

        margin: 0 !important;

        padding: 0 !important;

        border: none !important;

        border-radius: 0 !important;

        box-shadow: none !important;

        overflow: visible !important;

        background: #ffffff !important;
    }


    /* =================================================
       TABEL
    ================================================= */

    .report-table {

        width: 100% !important;

        min-width: 0 !important;

        max-width: none !important;

        table-layout: fixed !important;

        border-collapse: collapse !important;

        border-spacing: 0 !important;

        margin: 0 !important;
    }


    .report-table thead {

        display: table-header-group !important;
    }


    .report-table thead th {

        padding: 3mm 2mm !important;

        background: #c9799d !important;

        color: #ffffff !important;

        border: 1px solid #ad6083 !important;

        font-family: Arial, sans-serif !important;

        font-size: 10pt !important;

        font-weight: 700 !important;

        text-align: center !important;

        vertical-align: middle !important;
    }


    .report-table tbody td {

        padding: 3mm 2mm !important;

        background: #ffffff !important;

        color: #333333 !important;

        border: 1px solid #decbd4 !important;

        font-family: Arial, sans-serif !important;

        font-size: 9.5pt !important;

        text-align: center !important;

        vertical-align: middle !important;

        word-break: normal !important;

        overflow-wrap: break-word !important;
    }


    /* =================================================
       NAMA
    ================================================= */

    .report-table td.nama {

        font-weight: 700 !important;

        color: #222222 !important;
    }


    /* =================================================
       JAM
    ================================================= */

    .jam-masuk,
    .jam-keluar {

        display: inline-block !important;

        padding: 1.8mm 2.5mm !important;

        background: #f7e7ef !important;

        color: #9f4b72 !important;

        border: 1px solid #efd2df !important;

        border-radius: 2mm !important;

        font-family: Arial, sans-serif !important;

        font-size: 9pt !important;

        font-weight: 600 !important;

        white-space: nowrap !important;
    }


    .jam-masuk i,
    .jam-keluar i {

        display: none !important;
    }


    /* =================================================
       JANGAN PECAH BARIS DATA
    ================================================= */

    .report-table tr {

        page-break-inside: avoid !important;

        break-inside: avoid !important;
    }


    /* =================================================
       FOOTER
    ================================================= */

    footer {

        display: block !important;

        width: 100% !important;

        margin: 6mm 0 0 0 !important;

        padding: 0 !important;

        text-align: center !important;

        font-family: Arial, sans-serif !important;

        font-size: 8.5pt !important;

        color: #666666 !important;
    }

}

</style>

</head>


<body>


<?php include "sidebar.php"; ?>


<!-- =====================================================
     CONTENT
===================================================== -->

<div class="content">


    <!-- =================================================
         HEADER
    ================================================= -->

    <div class="report-header">


        <div class="report-heading">


            <div class="report-icon">

                <i class="fa-solid fa-file-lines"></i>

            </div>


            <div>

                <h1>
                    Laporan Data Tamu
                </h1>


                <p>
                    Cetak dan export data tamu berdasarkan tanggal.
                </p>

            </div>


        </div>


        <!-- =================================================
             TOMBOL
        ================================================= -->

        <div class="report-actions no-print">


            <!-- CETAK -->

            <button
                type="button"
                class="btn-cetak"
                onclick="window.print();"
            >

                <i class="fa-solid fa-print"></i>

                Cetak Laporan

            </button>


            <!-- EXCEL -->

            <a
                href="export_excel.php?tanggal_mulai=<?= urlencode($tanggal_mulai); ?>&tanggal_selesai=<?= urlencode($tanggal_selesai); ?>"
                class="btn-excel"
            >

                <i class="fa-solid fa-file-excel"></i>

                Export Excel

            </a>


        </div>


    </div>



    <!-- =================================================
         FILTER
    ================================================= -->

    <div class="filter-box no-print">


        <div class="filter-title">

            <i class="fa-solid fa-calendar-days"></i>

            Laporan

        </div>


        <form
            method="GET"
            action="laporan.php"
            class="filter-form"
        >


            <!-- TANGGAL MULAI -->

            <div class="filter-group">

                <label>
                    Tanggal Mulai
                </label>


                <div class="input-icon">

                    <i class="fa-regular fa-calendar"></i>


                    <input
                        type="date"
                        name="tanggal_mulai"
                        value="<?= htmlspecialchars($tanggal_mulai); ?>"
                    >

                </div>

            </div>


            <!-- TANGGAL SELESAI -->

            <div class="filter-group">

                <label>
                    Tanggal Selesai
                </label>


                <div class="input-icon">

                    <i class="fa-regular fa-calendar"></i>


                    <input
                        type="date"
                        name="tanggal_selesai"
                        value="<?= htmlspecialchars($tanggal_selesai); ?>"
                    >

                </div>

            </div>


            <!-- TAMPILKAN -->

            <button
                type="submit"
                class="btn-filter"
            >

                <i class="fa-solid fa-filter"></i>

                Tampilkan

            </button>


            <!-- RESET -->

            <a
                href="laporan.php"
                class="btn-reset"
            >

                <i class="fa-solid fa-rotate-left"></i>

                Reset

            </a>


        </form>


        <!-- =================================================
             INFO
        ================================================== -->

        <div class="report-summary">


            <div>

                <i class="fa-solid fa-database"></i>


                <span>

                    <strong>
                        <?= $total_data; ?>
                    </strong>

                    data ditemukan

                </span>

            </div>


            <span class="range-text">

                <?= htmlspecialchars($range_text); ?>

            </span>


        </div>


    </div>



    <!-- =================================================
         TABLE
    ================================================= -->

    <div class="table-container">


        <table class="report-table">


            <thead>

                <tr>

                    <th>
                        No
                    </th>

                    <th>
                        Nama Tamu
                    </th>

                    <th>
                        Instansi
                    </th>

                    <th>
                        Keperluan
                    </th>

                    <th>
                        Tujuan
                    </th>

                    <th>
                        Tanggal
                    </th>

                    <th>
                        Jam Masuk
                    </th>

                    <th>
                        Jam Keluar
                    </th>

                </tr>

            </thead>


            <tbody>


            <?php

            $no = 1;


            if ($total_data > 0):


                while (
                    $d = mysqli_fetch_assoc($data)
                ):

            ?>


                <tr>


                    <!-- NO -->

                    <td>

                        <span class="number-box">

                            <?= $no++; ?>

                        </span>

                    </td>


                    <!-- NAMA -->

                    <td class="nama">

                        <?= htmlspecialchars(
                            $d['nama_tamu'] ?? "-"
                        ); ?>

                    </td>


                    <!-- INSTANSI -->

                    <td>

                        <?= htmlspecialchars(
                            $d['instansi'] ?? "-"
                        ); ?>

                    </td>


                    <!-- KEPERLUAN -->

                    <td>

                        <?= htmlspecialchars(
                            $d['keperluan'] ?? "-"
                        ); ?>

                    </td>


                    <!-- TUJUAN -->

                    <td>

                        <?= htmlspecialchars(
                            $d['tujuan'] ?? "-"
                        ); ?>

                    </td>


                    <!-- TANGGAL -->

                    <td>

                        <?= !empty($d['tanggal'])

                            ? date(
                                "d/m/Y",
                                strtotime(
                                    $d['tanggal']
                                )
                            )

                            : "-";

                        ?>

                    </td>


                    <!-- JAM MASUK -->

                    <td>

                        <span class="jam-masuk">

                            <i class="fa-regular fa-clock"></i>

                            <?= htmlspecialchars(
                                $d['jam_masuk'] ?? "-"
                            ); ?>

                        </span>

                    </td>


                    <!-- JAM KELUAR -->

                    <td>

                        <span class="jam-keluar">

                            <i class="fa-regular fa-clock"></i>

                            <?= !empty(
                                $d['jam_keluar']
                            )

                                ? htmlspecialchars(
                                    $d['jam_keluar']
                                )

                                : "-";

                            ?>

                        </span>

                    </td>


                </tr>


            <?php

                endwhile;


            else:

            ?>


                <!-- DATA KOSONG -->

                <tr>

                    <td colspan="8">


                        <div class="empty-data">


                            <i class="fa-solid fa-file-circle-xmark"></i>


                            <p>

                                <?php

                                if (
                                    $tanggal_mulai != "" ||
                                    $tanggal_selesai != ""
                                ) {

                                    echo
                                        "Data tamu pada tanggal yang dipilih tidak ditemukan.";

                                } else {

                                    echo
                                        "Belum ada data tamu.";

                                }

                                ?>

                            </p>


                        </div>


                    </td>

                </tr>


            <?php endif; ?>


            </tbody>


        </table>


    </div>



    <!-- =================================================
         RINGKASAN LAPORAN
    ================================================= -->

    <div class="summary-card no-print">


        <div class="summary-header">


            <div class="summary-icon">

                <i class="fa-solid fa-chart-column"></i>

            </div>


            <div>

                <h2>
                    Ringkasan Laporan
                </h2>


                <p>
                    Informasi singkat dari laporan yang sedang ditampilkan.
                </p>

            </div>


        </div>


        <div class="summary-content">


            <!-- TOTAL DATA -->

            <div class="summary-item">


                <div class="summary-item-icon">

                    <i class="fa-solid fa-database"></i>

                </div>


                <div>

                    <span>
                        Total Data
                    </span>


                    <strong>
                        <?= $total_data; ?> data
                    </strong>

                </div>

            </div>


            <!-- PERIODE -->

            <div class="summary-item">


                <div class="summary-item-icon">

                    <i class="fa-solid fa-calendar-days"></i>

                </div>


                <div>

                    <span>
                        Periode
                    </span>


                    <strong>
                        <?= htmlspecialchars($range_text); ?>
                    </strong>

                </div>

            </div>


            <!-- DATA TERBARU -->

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


        </div>


    </div>



    <!-- =================================================
         FOOTER
    ================================================= -->

    <footer>

        © <?= date("Y"); ?> Buku Tamu Digital SMK Pelita


    </footer>


</div>


</body>

</html>