<?php

session_start();
include "../config/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");


/* =========================
   FILTER
========================= */

$keyword = isset($_GET['keyword'])
    ? trim($_GET['keyword'])
    : "";

$tanggal_mulai = isset($_GET['tanggal_mulai'])
    ? trim($_GET['tanggal_mulai'])
    : "";

$tanggal_selesai = isset($_GET['tanggal_selesai'])
    ? trim($_GET['tanggal_selesai'])
    : "";


/* =========================
   WHERE
========================= */

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


/* =========================
   DATA
========================= */

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

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Cetak Data Tamu</title>

<style>

* {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    margin: 30px;
    color: #222;
}

.header {
    text-align: center;
    margin-bottom: 25px;
}

.header h1 {
    margin: 0;
    font-size: 24px;
}

.header p {
    margin-top: 8px;
    font-size: 13px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #D093B0;
    color: white;
    border: 1px solid #999;
    padding: 8px;
    font-size: 12px;
}

td {
    border: 1px solid #999;
    padding: 7px;
    font-size: 11px;
}

.footer {
    margin-top: 20px;
    font-size: 12px;
}

@media print {

    body {
        margin: 10px;
    }

    .no-print {
        display: none;
    }

}

</style>

</head>

<body>


<div class="no-print" style="margin-bottom:20px;">

    <button
        onclick="window.print()"
        style="
            padding:10px 18px;
            border:0;
            border-radius:8px;
            background:#D093B0;
            color:white;
            cursor:pointer;
        "
    >
        🖨️ Cetak
    </button>

    <!-- TOMBOL KEMBALI KE DASHBOARD -->

<a href="dashboard.php" class="dashboard-print-btn">
    🏠 Dashboard
</a>

</div>


<div class="header">

    <h1>DATA BUKU TAMU DIGITAL</h1>

    <p>
        Laporan Data Kunjungan Tamu
    </p>

    <p>
        Dicetak:
        <?= date("d-m-Y H:i:s"); ?>
    </p>

</div>


<table>

<thead>

<tr>

    <th>No</th>

    <th>Nama Tamu</th>

    <th>Jenis Kelamin</th>

    <th>Instansi</th>

    <th>No HP</th>

    <th>Keperluan</th>

    <th>Tujuan</th>

    <th>Tanggal</th>

    <th>Jam</th>

</tr>

</thead>


<tbody>

<?php

$no = 1;

while ($data = mysqli_fetch_assoc($query)):

?>

<tr>

    <td><?= $no++; ?></td>

    <td>
        <?= htmlspecialchars($data['nama_tamu'] ?? '-'); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['jenis_kelamin'] ?? '-'); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['instansi'] ?? '-'); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['no_hp'] ?? '-'); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['keperluan'] ?? '-'); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['tujuan'] ?? '-'); ?>
    </td>

    <td>

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

    <td>
        <?= htmlspecialchars($data['jam'] ?? '-'); ?>
    </td>

</tr>

<?php endwhile; ?>

</tbody>

</table>


<div class="footer">

    Total data:
    <b><?= mysqli_num_rows($query); ?></b>
    kunjungan

</div>


</body>

</html>