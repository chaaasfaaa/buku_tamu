<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");


// ==========================================
// AMBIL TANGGAL DARI LAPORAN.PHP
// ==========================================

$tanggal = isset($_GET['tanggal'])
    ? $_GET['tanggal']
    : date("Y-m-d");


// ==========================================
// AMBIL DATA SESUAI TANGGAL
// ==========================================

$query = mysqli_query($conn, "

    SELECT tamu.*

    FROM tamu

    WHERE tamu.tanggal = '$tanggal'

    ORDER BY tamu.jam_masuk ASC

");

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<title>Cetak Laporan Data Tamu</title>


<style>

/* =========================================
   RESET
========================================= */

*{
    box-sizing:border-box;
}


/* =========================================
   BODY
========================================= */

body{

    font-family:Arial, sans-serif;

    margin:0;

    padding:0;

    color:#684A59;

    background:white;

}


/* =========================================
   HALAMAN LAPORAN
========================================= */

.laporan{

    width:100%;

    padding:25px 35px;

}


/* =========================================
   JUDUL
========================================= */

.header{

    text-align:center;

    margin-bottom:25px;

}


.header h1{

    font-size:25px;

    margin:0 0 8px;

    font-weight:700;

    color:#9F3F68;

}


.header h2{

    font-size:18px;

    margin:0 0 8px;

    font-weight:600;

    color:#B6537D;

}


.header p{

    font-size:14px;

    margin:0;

    color:#684A59;

}


.header p strong{

    color:#9F3F68;

}


/* =========================================
   GARIS PEMBATAS
========================================= */

.header::after{

    content:"";

    display:block;

    width:100%;

    height:3px;

    margin-top:18px;

    border-radius:10px;

    background:
    linear-gradient(
        90deg,
        #C96A91,
        #B6537D,
        #C96A91
    );

}


/* =========================================
   TABEL
========================================= */

table{

    width:100%;

    border-collapse:collapse;

    table-layout:fixed;

    border:1px solid #B6537D;

}


/* =========================================
   HEADER TABEL
========================================= */

th{

    background:
    linear-gradient(
        135deg,
        #C96A91,
        #B6537D
    );

    color:white;

    border:1px solid #9F3F68;

    padding:11px 6px;

    font-size:13px;

    font-weight:bold;

    text-align:center;

}


/* =========================================
   ISI TABEL
========================================= */

td{

    border:1px solid #E5C3D1;

    padding:10px 6px;

    font-size:13px;

    line-height:18px;

    text-align:center;

    vertical-align:middle;

    color:#684A59;

    background:white;

}


/* =========================================
   BARIS SELANG-SELING
========================================= */

tbody tr:nth-child(even) td{

    background:#FFF7FA;

}


/* =========================================
   HOVER
========================================= */

tbody tr:hover td{

    background:#FCF0F5;

}


/* =========================================
   NAMA TAMU
========================================= */

.nama{

    font-weight:600;

    color:#9F3F68;

}


/* =========================================
   LEBAR KOLOM
========================================= */

th:nth-child(1),
td:nth-child(1){

    width:5%;

}


th:nth-child(2),
td:nth-child(2){

    width:12%;

}


th:nth-child(3),
td:nth-child(3){

    width:11%;

}


th:nth-child(4),
td:nth-child(4){

    width:13%;

}


th:nth-child(5),
td:nth-child(5){

    width:13%;

}


th:nth-child(6),
td:nth-child(6){

    width:15%;

}


th:nth-child(7),
td:nth-child(7){

    width:14%;

}


th:nth-child(8),
td:nth-child(8){

    width:9%;

}


th:nth-child(9),
td:nth-child(9){

    width:8%;

}


/* =========================================
   FOOTER
========================================= */

.footer{

    margin-top:30px;

    display:flex;

    justify-content:flex-end;

}


.ttd{

    width:230px;

    text-align:center;

    font-size:14px;

    color:#684A59;

}


.ttd .tanggal-cetak{

    margin-bottom:55px;

}


.ttd strong{

    color:#9F3F68;

}


/* =========================================
   PRINT
========================================= */

@media print{

    @page{

        size:A4 landscape;

        margin:12mm;

    }


    body{

        background:white;

    }


    .laporan{

        padding:0;

    }


    .header{

        margin-bottom:20px;

    }


    .header h1{

        font-size:24px;

    }


    .header h2{

        font-size:17px;

    }


    .header p{

        font-size:13px;

    }


    .header::after{

        height:2px;

        margin-top:12px;

    }


    th{

        font-size:12px;

        padding:9px 5px;

        background:#B6537D !important;

        color:white !important;

        -webkit-print-color-adjust:exact;

        print-color-adjust:exact;

    }


    td{

        font-size:12px;

        padding:9px 5px;

        background:white !important;

        color:#684A59 !important;

        border-color:#E5C3D1 !important;

        -webkit-print-color-adjust:exact;

        print-color-adjust:exact;

    }


    tbody tr:nth-child(even) td{

        background:#FFF7FA !important;

        -webkit-print-color-adjust:exact;

        print-color-adjust:exact;

    }


    .nama{

        color:#9F3F68 !important;

    }


    .footer{

        margin-top:25px;

    }

}

</style>

</head>


<body>


<!-- =========================================
     HEADER LAPORAN
========================================= -->

<div class="header">

    <h1>
        LAPORAN DATA TAMU
    </h1>


    <h2>
        APLIKASI BUKU TAMU DIGITAL
    </h2>


    <p>

        Tanggal:

        <strong>

            <?= date("d-m-Y", strtotime($tanggal)); ?>

        </strong>

    </p>

</div>



<!-- =========================================
     TABEL DATA TAMU
========================================= -->

<table>

<thead>

<tr>

    <th>No</th>

    <th>Nama Tamu</th>

    <th>JK</th>

    <th>Instansi</th>

    <th>No HP</th>

    <th>Keperluan</th>

    <th>Tujuan</th>

    <th>Tanggal</th>

    <th>Jam Masuk</th>

</tr>

</thead>


<tbody>

<?php

$no = 1;


if(mysqli_num_rows($query) > 0){

    while($d = mysqli_fetch_assoc($query)){

?>

<tr>

    <td>
        <?= $no++; ?>
    </td>


    <td class="nama">

        <?= htmlspecialchars($d['nama_tamu']); ?>

    </td>


    <td>

        <?= htmlspecialchars($d['jenis_kelamin']); ?>

    </td>


    <td>

        <?= htmlspecialchars($d['instansi']); ?>

    </td>


    <td>

        <?= htmlspecialchars($d['no_hp']); ?>

    </td>


    <td>

        <?= htmlspecialchars($d['keperluan']); ?>

    </td>


    <td>

        <?= htmlspecialchars($d['tujuan']); ?>

    </td>


    <td>

        <?= htmlspecialchars($d['tanggal']); ?>

    </td>


    <td>

        <?= htmlspecialchars($d['jam_masuk']); ?>

    </td>

</tr>


<?php

    }

}else{

?>

<tr>

    <td colspan="9">

        Tidak ada data tamu pada tanggal ini.

    </td>

</tr>

<?php } ?>

</tbody>

</table>



<!-- =========================================
     TANDA TANGAN
========================================= -->

<div class="footer">

    <div class="ttd">

        <div class="tanggal-cetak">

            Dicetak tanggal:

            <?= date("d-m-Y"); ?>

        </div>


        <strong>
            Administrator
        </strong>

    </div>

</div>



<!-- =========================================
     AUTO PRINT
========================================= -->

<script>

window.onload = function(){

    window.print();

};

</script>


</body>

</html>