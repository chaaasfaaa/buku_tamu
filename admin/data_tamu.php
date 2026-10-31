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
   TOTAL TAMU
===================================================== */

$query_total = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tamu
");

$data_total = mysqli_fetch_assoc($query_total);

$total_tamu = $data_total['total'] ?? 0;


/* =====================================================
   TAMU HARI INI
===================================================== */

$tanggal_hari_ini = date("Y-m-d");

$query_hari_ini = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tamu
    WHERE tanggal = '$tanggal_hari_ini'
");

$data_hari_ini = mysqli_fetch_assoc($query_hari_ini);

$total_hari_ini = $data_hari_ini['total'] ?? 0;


/* =====================================================
   TOTAL JENIS KEPERLUAN
===================================================== */

$query_keperluan = mysqli_query($conn, "
    SELECT COUNT(DISTINCT keperluan) AS total
    FROM tamu
    WHERE keperluan IS NOT NULL
    AND keperluan != ''
");

$data_keperluan = mysqli_fetch_assoc($query_keperluan);

$total_keperluan = $data_keperluan['total'] ?? 0;


/* =====================================================
   DATA TAMU + FOTO PROFIL USER
===================================================== */

$query_data = mysqli_query($conn, "

    SELECT
        tamu.*,
        users.foto AS foto_user

    FROM tamu

    LEFT JOIN users
        ON tamu.id_user = users.id

    ORDER BY tamu.id DESC

");


if (!$query_data) {

    die(
        "Terjadi kesalahan database: "
        . mysqli_error($conn)
    );

}


$jumlah_data = mysqli_num_rows($query_data);


/* =====================================================
   DATA TERBARU
===================================================== */

$data_terbaru = null;

$query_terbaru = mysqli_query($conn, "

    SELECT nama_tamu, tanggal, keperluan
    FROM tamu
    ORDER BY id DESC
    LIMIT 1

");

if ($query_terbaru) {

    $data_terbaru = mysqli_fetch_assoc(
        $query_terbaru
    );

}


/* =====================================================
   FORMAT DATA TERBARU
===================================================== */

$nama_terbaru = "-";
$tanggal_terbaru = "-";
$keperluan_terbaru = "-";

if ($data_terbaru) {

    $nama_terbaru =
        $data_terbaru['nama_tamu'] ?? "-";

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
        $data_terbaru['keperluan'] ?? "-";

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
        Data Tamu | Buku Tamu Digital
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
         CSS
    ===================================================== -->

    <link
        rel="stylesheet"
        href="../assets/css/data_tamu.css?v=302"
    >

    <link
        rel="stylesheet"
        href="../assets/css/sidebar.css?v=300"
    >


    <!-- =====================================================
         CSS FOTO PROFIL
    ===================================================== -->

    <style>

        .avatar-photo {

            width: 35px;

            height: 35px;

            min-width: 35px;

            display: block;

            object-fit: cover;

            border-radius: 50%;

            background: #f4dce7;

        }

    </style>

</head>


<body>


<?php include "sidebar.php"; ?>


<!-- =====================================================
     MAIN CONTENT
===================================================== -->

<div class="main-content">


    <!-- =================================================
         HEADER
    ================================================= -->

    <div class="page-header">

        <div class="header-text">

            <div class="title-icon">

                <i class="fa-solid fa-users"></i>

            </div>

            <div>

                <h1>
                    Data Tamu
                </h1>

                <p>
                    Kelola seluruh data tamu yang tersimpan dalam sistem.
                </p>

            </div>

        </div>


        <a
            href="tambah_tamu.php"
            class="btn-tambah"
        >

            <i class="fa-solid fa-plus"></i>

            Tambah Tamu

        </a>

    </div>


    <!-- =================================================
         STATISTIK
    ================================================= -->

    <div class="statistik">


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


        <!-- TAMU HARI INI -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-calendar-day"></i>

            </div>

            <div class="stat-info">

                <span>
                    Tamu Hari Ini
                </span>

                <strong>
                    <?= $total_hari_ini; ?>
                </strong>

                <small>
                    Kunjungan
                </small>

            </div>

        </div>


        <!-- KEPERLUAN -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-clipboard-list"></i>

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


    <!-- =================================================
         DAFTAR DATA TAMU
    ================================================= -->

    <div class="table-card">


        <!-- TABLE HEADER -->

        <div class="table-header">

            <div>

                <h2>

                    <i class="fa-solid fa-table-list"></i>

                    Daftar Data Tamu

                </h2>

                <p>
                    Seluruh data tamu yang tersimpan dalam sistem.
                </p>

            </div>


            <div class="data-count">

                <i class="fa-solid fa-database"></i>

                <?= $jumlah_data; ?>

                Data

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
                            Nama
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
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php

                $no = 1;

                if ($jumlah_data > 0):

                    while (
                        $d = mysqli_fetch_assoc(
                            $query_data
                        )
                    ):


                        $nama =
                            $d['nama_tamu'] ?? "-";


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

                        <td>

                            <span class="nomor">

                                <?= $no++; ?>

                            </span>

                        </td>


                        <!-- NAMA -->

                        <td>

                            <div class="nama-wrapper">


                                <?php

                                /*
                                 * CEK FOTO PROFIL USER
                                 */

                                $foto_user =
                                    $d['foto_user'] ?? "";

                                $lokasi_foto =
                                    __DIR__
                                    . "/../uploads/profil/"
                                    . $foto_user;

                                ?>


                                <?php if (
                                    !empty($foto_user)
                                    &&
                                    file_exists($lokasi_foto)
                                ): ?>


                                    <!-- FOTO PROFIL -->

                                    <img
                                        src="../uploads/profil/<?= htmlspecialchars($foto_user); ?>"
                                        class="avatar avatar-photo"
                                        alt="Foto Profil"
                                    >


                                <?php else: ?>


                                    <!-- INISIAL JIKA TIDAK ADA FOTO -->

                                    <div class="avatar">

                                        <?= htmlspecialchars(
                                            $inisial
                                        ); ?>

                                    </div>


                                <?php endif; ?>


                                <strong>

                                    <?= htmlspecialchars(
                                        $nama
                                    ); ?>

                                </strong>


                            </div>

                        </td>


                        <!-- INSTANSI -->

                        <td>

                            <div class="data-wrapper">

                                <span class="data-icon">

                                    <i class="fa-solid fa-building"></i>

                                </span>

                                <span>

                                    <?= htmlspecialchars(
                                        $d['instansi'] ?? "-"
                                    ); ?>

                                </span>

                            </div>

                        </td>


                        <!-- KEPERLUAN -->

                        <td>

                            <div class="data-wrapper">

                                <span class="data-icon">

                                    <i class="fa-regular fa-message"></i>

                                </span>

                                <span>

                                    <?= htmlspecialchars(
                                        $d['keperluan'] ?? "-"
                                    ); ?>

                                </span>

                            </div>

                        </td>


                        <!-- TUJUAN -->

                        <td>

                            <div class="data-wrapper">

                                <span class="data-icon">

                                    <i class="fa-solid fa-location-dot"></i>

                                </span>

                                <span>

                                    <?= htmlspecialchars(
                                        $d['tujuan'] ?? "-"
                                    ); ?>

                                </span>

                            </div>

                        </td>


                        <!-- TANGGAL -->

                        <td>

                            <div class="tanggal-wrapper">

                                <span class="data-icon">

                                    <i class="fa-regular fa-calendar"></i>

                                </span>

                                <div>

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

                                            $nomor_hari =
                                                date(
                                                    "N",
                                                    strtotime(
                                                        $d['tanggal']
                                                    )
                                                );


                                            $nama_hari = [

                                                1 => "Senin",
                                                2 => "Selasa",
                                                3 => "Rabu",
                                                4 => "Kamis",
                                                5 => "Jumat",
                                                6 => "Sabtu",
                                                7 => "Minggu"

                                            ];


                                            echo
                                                $nama_hari[
                                                    $nomor_hari
                                                ]
                                                ?? "";

                                        }

                                        ?>

                                    </small>

                                </div>

                            </div>

                        </td>


                        <!-- AKSI -->

                        <td>

                            <div class="aksi">


                                <a
                                    href="detail_tamu.php?id=<?= $d['id']; ?>"
                                    class="btn-detail"
                                >

                                    <i class="fa-solid fa-eye"></i>

                                    Detail

                                </a>


                                <a
                                    href="edit_tamu.php?id=<?= $d['id']; ?>"
                                    class="btn-edit"
                                >

                                    <i class="fa-solid fa-pen"></i>

                                    Edit

                                </a>


                                <a
                                    href="hapus_tamu.php?id=<?= $d['id']; ?>"
                                    class="btn-hapus"
                                    onclick="return confirm('Yakin ingin menghapus data tamu ini?')"
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


                    <tr>

                        <td
                            colspan="7"
                            class="data-kosong"
                        >

                            <div>

                                <i class="fa-regular fa-folder-open"></i>

                                <strong>
                                    Belum ada data tamu
                                </strong>

                                <span>
                                    Silakan tambahkan data tamu terlebih dahulu.
                                </span>

                                <a
                                    href="tambah_tamu.php"
                                    class="btn-empty"
                                >

                                    <i class="fa-solid fa-plus"></i>

                                    Tambah Tamu

                                </a>

                            </div>

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>

            </table>

        </div>

    </div>


    <!-- =================================================
         RINGKASAN DATA
    ================================================= -->

    <div class="summary-card">


        <div class="summary-heading">

            <div class="summary-title">

                <div class="summary-icon">

                    <i class="fa-solid fa-chart-simple"></i>

                </div>

                <div>

                    <h2>
                        Ringkasan Data
                    </h2>

                    <p>
                        Informasi singkat dari data tamu yang tersimpan.
                    </p>

                </div>

            </div>

        </div>


        <div class="summary-content">


            <!-- TOTAL -->

            <div class="summary-item">

                <div class="summary-item-icon">

                    <i class="fa-solid fa-users"></i>

                </div>

                <div>

                    <span>
                        Seluruh Tamu
                    </span>

                    <strong>
                        <?= $total_tamu; ?> orang
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


            <!-- HARI INI -->

            <div class="summary-item">

                <div class="summary-item-icon">

                    <i class="fa-solid fa-calendar-day"></i>

                </div>

                <div>

                    <span>
                        Kunjungan Hari Ini
                    </span>

                    <strong>
                        <?= $total_hari_ini; ?> kunjungan
                    </strong>

                </div>

            </div>


            <!-- KEPERLUAN -->

            <div class="summary-item">

                <div class="summary-item-icon">

                    <i class="fa-solid fa-list-check"></i>

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


        </div>

    </div>


    <!-- =================================================
         FOOTER
    ================================================= -->

    <div class="footer">

        © <?= date("Y"); ?> Buku Tamu Digital SMK Pelita

    </div>


</div>


</body>

</html>