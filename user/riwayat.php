<?php

session_start();

include "../config/database.php";

/* =====================================================
   CEK LOGIN USER
===================================================== */

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

$id_user   = $_SESSION['id_user'] ?? 0;
$nama_user = $_SESSION['nama'] ?? "User";


/* =====================================================
   AMBIL DATA KUNJUNGAN + FOTO PROFIL USER
===================================================== */

$query = mysqli_query(
    $conn,
    "
    SELECT 
        tamu.*,
        users.foto
    FROM tamu
    LEFT JOIN users 
        ON tamu.id_user = users.id
    WHERE tamu.id_user = '$id_user'
    ORDER BY tamu.tanggal DESC, tamu.id DESC
    "
);


/* =====================================================
   HITUNG TOTAL KUNJUNGAN
===================================================== */

$total_kunjungan = 0;
$data_kunjungan = [];

if ($query) {

    while ($data = mysqli_fetch_assoc($query)) {
        $data_kunjungan[] = $data;
    }

    $total_kunjungan = count($data_kunjungan);
}


/* =====================================================
   DATA KUNJUNGAN TERAKHIR
===================================================== */

$kunjungan_terakhir = null;

if ($total_kunjungan > 0) {
    $kunjungan_terakhir = $data_kunjungan[0];
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

    <title>Riwayat Kunjungan</title>


    <!-- =================================================
         GOOGLE FONT
    ================================================= -->

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


    <!-- =================================================
         FONT AWESOME
    ================================================= -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >


    <!-- =================================================
         CSS SIDEBAR USER
    ================================================= -->

    <link
        rel="stylesheet"
        href="../assets/css/sidebar_user.css?v=1000"
    >


    <!-- =================================================
         CSS RIWAYAT
    ================================================= -->

    <link
        rel="stylesheet"
        href="../assets/css/riwayat.css?v=1600"
    >


    <style>

        /* =================================================
           FOTO PROFIL DI TABEL
        ================================================= */

        .mini-avatar {

            width: 32px;
            height: 32px;

            min-width: 32px;
            min-height: 32px;

            border-radius: 50%;

            overflow: hidden;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f8dce9;

            color: #d34f8d;

            font-size: 14px;

        }


        .mini-avatar img {

            width: 100%;
            height: 100%;

            object-fit: cover;

            display: block;

        }


        .nama-wrapper {

            display: flex;

            align-items: center;

            gap: 10px;

        }


        .nama-wrapper span {

            white-space: nowrap;

        }


        /* =================================================
           TOMBOL CHECK OUT
        ================================================= */

        .btn-checkout {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            padding: 8px 12px;

            border-radius: 9px;

            background: #d093b0;

            color: #ffffff !important;

            text-decoration: none !important;

            font-family: Poppins, sans-serif;

            font-size: 11px;

            font-weight: 600;

            white-space: nowrap;

            transition: 0.2s ease;

        }


        .btn-checkout:hover {

            background: #c47f9f;

            color: #ffffff !important;

            text-decoration: none !important;

            transform: translateY(-1px);

        }


        /* =================================================
           STATUS SELESAI
        ================================================= */

        .status-selesai {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            padding: 8px 12px;

            border-radius: 9px;

            background: #f4e3ec;

            color: #c76f95;

            font-family: Poppins, sans-serif;

            font-size: 11px;

            font-weight: 600;

            white-space: nowrap;

        }


        /* =================================================
           BADGE JAM KOSONG
        ================================================= */

        .time-empty {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 7px 10px;

            border-radius: 9px;

            background: #f7f0f4;

            color: #b99aaa;

            font-size: 11px;

            white-space: nowrap;

        }


        /* =================================================
           TABEL
        ================================================= */

        .table-wrapper {

            overflow-x: auto !important;

        }


        #riwayatTable {

            min-width: 1200px;

        }


        /* =================================================
           TOMBOL TAMBAH
        ================================================= */

        .btn-tambah {

            text-decoration: none !important;

        }


        /* =================================================
           FOOTER
        ================================================= */

        footer {

            margin-top: 28px;

            padding: 20px;

            text-align: center;

            color: #9f7187;

            font-family: Poppins, sans-serif;

            font-size: 13px;

        }


        footer i {

            margin-right: 6px;

        }


        /* =================================================
           RESPONSIVE
        ================================================= */

        @media (max-width: 768px) {

            #riwayatTable {

                min-width: 1100px;

            }

        }

    </style>

</head>


<body>


    <!-- =================================================
         SIDEBAR USER
    ================================================= -->

    <?php include "sidebar.php"; ?>


    <!-- =================================================
         CONTENT
    ================================================= -->

    <main class="content">


        <!-- =================================================
             TOP INFO
        ================================================= -->

        <div class="top-info">


            <!-- TANGGAL -->

            <div class="date-box">

                <div class="top-icon">

                    <i class="fa-solid fa-calendar-days"></i>

                </div>


                <div>

                    <strong id="tanggal"></strong>

                    <span id="hari"></span>

                </div>

            </div>


            <!-- JAM -->

            <div class="clock-box">

                <div class="top-icon">

                    <i class="fa-solid fa-clock"></i>

                </div>


                <div>

                    <small>
                        Jam Aktif
                    </small>

                    <strong id="jam"></strong>

                </div>

            </div>

        </div>


        <!-- =================================================
             PAGE HEADER
        ================================================= -->

        <section class="page-header">


            <div class="header-icon">

                <i class="fa-solid fa-clock-rotate-left"></i>

            </div>


            <div class="header-text">

                <h1>
                    Riwayat Kunjungan
                </h1>

                <p>
                    Lihat dan kelola riwayat kunjungan yang telah Anda isi.
                </p>

            </div>


        </section>


        <!-- =================================================
             STATISTIK
        ================================================= -->

        <section class="stats-container">


            <!-- TOTAL -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="fa-solid fa-list-check"></i>

                </div>


                <div class="stat-content">

                    <span>
                        Total Kunjungan
                    </span>

                    <strong>
                        <?= $total_kunjungan; ?>
                    </strong>

                    <small>
                        Semua riwayat Anda
                    </small>

                </div>

            </div>


            <!-- TERAKHIR -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="fa-solid fa-calendar-check"></i>

                </div>


                <div class="stat-content">

                    <span>
                        Kunjungan Terakhir
                    </span>


                    <strong class="stat-date">

                        <?php if ($kunjungan_terakhir): ?>

                            <?= htmlspecialchars(
                                $kunjungan_terakhir['tanggal']
                            ); ?>

                        <?php else: ?>

                            -

                        <?php endif; ?>

                    </strong>


                    <small>

                        <?php if ($kunjungan_terakhir): ?>

                            <?= htmlspecialchars(
                                $kunjungan_terakhir['jam_masuk']
                                ?? $kunjungan_terakhir['jam']
                                ?? ''
                            ); ?>

                        <?php else: ?>

                            Belum ada kunjungan

                        <?php endif; ?>

                    </small>

                </div>

            </div>


            <!-- STATUS -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="fa-solid fa-circle-check"></i>

                </div>


                <div class="stat-content">

                    <span>
                        Status Data
                    </span>


                    <strong>

                        <?php if ($total_kunjungan > 0): ?>

                            Aktif

                        <?php else: ?>

                            Kosong

                        <?php endif; ?>

                    </strong>


                    <small>

                        <?php if ($total_kunjungan > 0): ?>

                            Data kunjungan tersimpan

                        <?php else: ?>

                            Belum ada data

                        <?php endif; ?>

                    </small>

                </div>

            </div>


        </section>


        <!-- =================================================
             TABLE CONTAINER
        ================================================= -->

        <section class="table-container">


            <!-- TITLE -->

            <div class="table-title">


                <div>

                    <h2>

                        <i class="fa-solid fa-list"></i>

                        Data Kunjungan

                    </h2>


                    <p>
                        Daftar seluruh kunjungan yang pernah Anda masukkan.
                    </p>

                </div>


                <a
                    href="isi_tamu.php"
                    class="btn-tambah"
                >

                    <i class="fa-solid fa-plus"></i>

                    Isi Buku Tamu

                </a>


            </div>


            <!-- =================================================
                 FILTER
            ================================================= -->

            <div class="filter-box">


                <!-- SEARCH -->

                <div class="search-box">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Cari nama, instansi, keperluan..."
                        autocomplete="off"
                    >

                </div>


                <!-- JENIS KELAMIN -->

                <div class="filter-item">

                    <i class="fa-solid fa-venus-mars"></i>

                    <select id="genderFilter">

                        <option value="">
                            Semua Jenis Kelamin
                        </option>

                        <option value="Laki-laki">
                            Laki-laki
                        </option>

                        <option value="Perempuan">
                            Perempuan
                        </option>

                    </select>

                </div>


                <!-- RESET -->

                <button
                    type="button"
                    id="resetFilter"
                    class="btn-reset"
                >

                    <i class="fa-solid fa-rotate-left"></i>

                    Reset

                </button>


            </div>


            <!-- =================================================
                 HASIL FILTER
            ================================================= -->

            <div class="filter-info">

                <span>

                    Menampilkan

                    <strong id="jumlahData">
                        <?= $total_kunjungan; ?>
                    </strong>

                    data kunjungan

                </span>

            </div>


            <!-- =================================================
                 TABLE
            ================================================= -->

            <div class="table-wrapper">

                <table id="riwayatTable">


                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Nama Tamu
                            </th>

                            <th>
                                Jenis Kelamin
                            </th>

                            <th>
                                Instansi
                            </th>

                            <th>
                                No HP
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

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php if ($total_kunjungan > 0): ?>


                            <?php foreach (
                                $data_kunjungan
                                as $index => $data
                            ): ?>


                                <tr class="data-row">


                                    <!-- NO -->

                                    <td>
                                        <?= $index + 1; ?>
                                    </td>


                                    <!-- NAMA + FOTO PROFIL -->

                                    <td class="nama-cell">

                                        <div class="nama-wrapper">


                                            <div class="mini-avatar">

                                                <?php

                                                $foto_profil = $data['foto'] ?? '';

                                                $path_foto =
                                                    "../uploads/profil/" .
                                                    $foto_profil;

                                                ?>


                                                <?php if (
                                                    !empty($foto_profil) &&
                                                    file_exists($path_foto)
                                                ): ?>

                                                    <img
                                                        src="<?= htmlspecialchars($path_foto); ?>"
                                                        alt="Foto Profil"
                                                    >

                                                <?php else: ?>

                                                    <i class="fa-solid fa-user"></i>

                                                <?php endif; ?>


                                            </div>


                                            <span>

                                                <?= htmlspecialchars(
                                                    $data['nama_tamu']
                                                ); ?>

                                            </span>


                                        </div>

                                    </td>


                                    <!-- JENIS KELAMIN -->

                                    <td class="gender-cell">

                                        <?= htmlspecialchars(
                                            $data['jenis_kelamin']
                                        ); ?>

                                    </td>


                                    <!-- INSTANSI -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $data['instansi']
                                        ); ?>

                                    </td>


                                    <!-- NO HP -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $data['no_hp']
                                        ); ?>

                                    </td>


                                    <!-- KEPERLUAN -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $data['keperluan']
                                        ); ?>

                                    </td>


                                    <!-- TUJUAN -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $data['tujuan']
                                        ); ?>

                                    </td>


                                    <!-- TANGGAL -->

                                    <td>

                                        <span class="date-badge">

                                            <i class="fa-regular fa-calendar"></i>

                                            <?= htmlspecialchars(
                                                $data['tanggal']
                                            ); ?>

                                        </span>

                                    </td>


                                    <!-- JAM MASUK -->

                                    <td>

                                        <?php

                                        $jam_masuk_tampil =
                                            $data['jam_masuk']
                                            ?? $data['jam']
                                            ?? '';

                                        ?>


                                        <?php if (
                                            !empty($jam_masuk_tampil)
                                        ): ?>

                                            <span class="time-badge">

                                                <i class="fa-regular fa-clock"></i>

                                                <?= htmlspecialchars(
                                                    $jam_masuk_tampil
                                                ); ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="time-empty">
                                                —
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- JAM KELUAR -->

                                    <td>

                                        <?php if (
                                            !empty($data['jam_keluar']) &&
                                            $data['jam_keluar'] !== "00:00:00"
                                        ): ?>

                                            <span class="time-badge">

                                                <i class="fa-regular fa-clock"></i>

                                                <?= htmlspecialchars(
                                                    $data['jam_keluar']
                                                ); ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="time-empty">

                                                Belum Check Out

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- AKSI -->

                                    <td>

                                        <?php if (
                                            empty($data['jam_keluar']) ||
                                            $data['jam_keluar'] === "00:00:00"
                                        ): ?>


                                            <a
                                                href="proses_checkout.php?id=<?= (int)$data['id']; ?>"
                                                class="btn-checkout"
                                                onclick="return confirm('Apakah Anda yakin sudah selesai berkunjung?');"
                                            >

                                                <i class="fa-solid fa-right-from-bracket"></i>

                                                Check Out

                                            </a>


                                        <?php else: ?>


                                            <span class="status-selesai">

                                                <i class="fa-solid fa-circle-check"></i>

                                                Selesai

                                            </span>


                                        <?php endif; ?>


                                    </td>


                                </tr>


                            <?php endforeach; ?>


                            <!-- =================================================
                                 TIDAK DITEMUKAN
                            ================================================= -->

                            <tr
                                id="noResult"
                                class="no-result"
                            >

                                <td colspan="11">

                                    <i class="fa-solid fa-magnifying-glass"></i>

                                    <strong>
                                        Data tidak ditemukan
                                    </strong>

                                    <span>
                                        Coba gunakan kata pencarian lain.
                                    </span>

                                </td>

                            </tr>


                        <?php else: ?>


                            <!-- =================================================
                                 DATA KOSONG
                            ================================================= -->

                            <tr>

                                <td
                                    colspan="11"
                                    class="empty"
                                >

                                    <div class="empty-icon">

                                        <i class="fa-solid fa-folder-open"></i>

                                    </div>


                                    <strong>
                                        Belum Ada Riwayat
                                    </strong>


                                    <p>
                                        Anda belum memiliki data kunjungan.
                                    </p>


                                    <a href="isi_tamu.php">

                                        <i class="fa-solid fa-plus"></i>

                                        Isi Buku Tamu Sekarang

                                    </a>

                                </td>

                            </tr>


                        <?php endif; ?>


                    </tbody>


                </table>

            </div>


        </section>


        <!-- =================================================
             FOOTER
        ================================================= -->

        <footer>

            © <?= date("Y"); ?> Buku Tamu Digital SMK Pelita

        </footer>


    </main>


    <!-- =================================================
         JAVASCRIPT
    ================================================= -->

    <script>


        /* =================================================
           JAM & TANGGAL
        ================================================= */

        function updateTime() {

            const now = new Date();


            const hari = [

                "Minggu",
                "Senin",
                "Selasa",
                "Rabu",
                "Kamis",
                "Jumat",
                "Sabtu"

            ];


            const bulan = [

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


            const tanggal =

                now.getDate()
                + " "
                + bulan[now.getMonth()]
                + " "
                + now.getFullYear();


            const waktu =

                String(
                    now.getHours()
                ).padStart(2, "0")

                + ":"

                + String(
                    now.getMinutes()
                ).padStart(2, "0")

                + ":"

                + String(
                    now.getSeconds()
                ).padStart(2, "0");


            document.getElementById(
                "tanggal"
            ).textContent = tanggal;


            document.getElementById(
                "hari"
            ).textContent =
                hari[now.getDay()];


            document.getElementById(
                "jam"
            ).textContent = waktu;

        }


        updateTime();


        setInterval(
            updateTime,
            1000
        );


        /* =================================================
           SEARCH & FILTER
        ================================================= */

        const searchInput =
            document.getElementById(
                "searchInput"
            );


        const genderFilter =
            document.getElementById(
                "genderFilter"
            );


        const resetFilter =
            document.getElementById(
                "resetFilter"
            );


        const jumlahData =
            document.getElementById(
                "jumlahData"
            );


        const rows =
            document.querySelectorAll(
                "#riwayatTable .data-row"
            );


        const noResult =
            document.getElementById(
                "noResult"
            );


        function filterData() {

            const search =
                searchInput
                    ? searchInput.value
                        .toLowerCase()
                        .trim()
                    : "";


            const gender =
                genderFilter
                    ? genderFilter.value
                        .toLowerCase()
                        .trim()
                    : "";


            let jumlah = 0;


            rows.forEach(
                function(row) {

                    const text =
                        row.textContent
                            .toLowerCase();


                    const genderCell =
                        row.querySelector(
                            ".gender-cell"
                        );


                    const rowGender =
                        genderCell
                            ? genderCell.textContent
                                .toLowerCase()
                                .trim()
                            : "";


                    const cocokSearch =
                        text.includes(
                            search
                        );


                    const cocokGender =
                        gender === ""
                        ||
                        rowGender === gender;


                    if (
                        cocokSearch &&
                        cocokGender
                    ) {

                        row.style.display = "";

                        jumlah++;

                    } else {

                        row.style.display = "none";

                    }

                }
            );


            if (noResult) {

                if (jumlah === 0) {

                    noResult.style.display =
                        "table-row";

                } else {

                    noResult.style.display =
                        "none";

                }

            }


            if (jumlahData) {

                jumlahData.textContent =
                    jumlah;

            }


            updateNomor();

        }


        /* =================================================
           NOMOR TABEL
        ================================================= */

        function updateNomor() {

            let nomor = 1;


            rows.forEach(
                function(row) {

                    if (
                        row.style.display !== "none"
                    ) {

                        const nomorCell =
                            row.querySelector(
                                "td:first-child"
                            );


                        if (nomorCell) {

                            nomorCell.textContent =
                                nomor++;

                        }

                    }

                }
            );

        }


        /* =================================================
           EVENT SEARCH
        ================================================= */

        if (searchInput) {

            searchInput.addEventListener(
                "input",
                filterData
            );

        }


        if (genderFilter) {

            genderFilter.addEventListener(
                "change",
                filterData
            );

        }


        /* =================================================
           RESET FILTER
        ================================================= */

        if (resetFilter) {

            resetFilter.addEventListener(
                "click",
                function() {

                    if (searchInput) {

                        searchInput.value = "";

                    }


                    if (genderFilter) {

                        genderFilter.value = "";

                    }


                    filterData();


                    if (searchInput) {

                        searchInput.focus();

                    }

                }
            );

        }

    </script>


</body>

</html>