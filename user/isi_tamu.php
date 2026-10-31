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

$nama_user = $_SESSION['nama'] ?? "User";

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
        Isi Buku Tamu - Buku Tamu SMK Pelita
    </title>

    <!-- =====================================================
         GOOGLE FONT
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
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
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
         CSS SIDEBAR USER
    ===================================================== -->

    <link
        rel="stylesheet"
        href="../assets/css/sidebar_user.css?v=1000"
    >

    <!-- =====================================================
         CSS FORM
    ===================================================== -->

    <link
        rel="stylesheet"
        href="../assets/css/isi_tamu.css?v=1000"
    >

</head>

<body>

<!-- =====================================================
     SIDEBAR
===================================================== -->

<?php include "sidebar.php"; ?>


<!-- =====================================================
     CONTENT
===================================================== -->

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
         FORM
    ================================================= -->

    <section class="form-container">

        <!-- HEADER FORM -->

        <div class="form-header">

            <div class="book-icon">

                <i class="fa-solid fa-book-open"></i>

            </div>

            <div>

                <h1>
                    Form Buku Tamu
                </h1>

                <p>
                    Silakan isi data kunjungan dengan lengkap.
                </p>

            </div>

        </div>


        <!-- FORM DATA -->

        <form
            action="proses_isi_tamu.php"
            method="POST"
        >

            <div class="form-grid">


                <!-- NAMA TAMU -->

                <div class="form-group">

                    <label>

                        <i class="fa-solid fa-user"></i>

                        Nama Tamu

                    </label>

                    <div class="input-box">

                        <i
                            class="fa-solid fa-user input-icon"
                        ></i>

                        <input
                            type="text"
                            name="nama_tamu"
                            placeholder="Masukkan nama tamu"
                            autocomplete="off"
                            required
                        >

                    </div>

                </div>


                <!-- JENIS KELAMIN -->

                <div class="form-group">

                    <label>

                        <i class="fa-solid fa-venus-mars"></i>

                        Jenis Kelamin

                    </label>

                    <div class="input-box">

                        <i
                            class="fa-solid fa-venus-mars input-icon"
                        ></i>

                        <select
                            name="jenis_kelamin"
                            required
                        >

                            <option value="">
                                Pilih Jenis Kelamin
                            </option>

                            <option value="Laki-laki">
                                Laki-laki
                            </option>

                            <option value="Perempuan">
                                Perempuan
                            </option>

                        </select>

                    </div>

                </div>


                <!-- ASAL / KETERANGAN -->

                <div class="form-group">

                    <label>

                        <i class="fa-solid fa-circle-info"></i>

                        Asal/Keterangan

                    </label>

                    <div class="input-box">

                        <i
                            class="fa-solid fa-circle-info input-icon"
                        ></i>

                        <input
                            type="text"
                            name="instansi"
                            placeholder="Masukkan asal atau keterangan"
                            autocomplete="off"
                            required
                        >

                    </div>

                </div>


                <!-- NO HP -->

                <div class="form-group">

                    <label>

                        <i class="fa-solid fa-phone"></i>

                        No HP

                    </label>

                    <div class="input-box">

                        <i
                            class="fa-solid fa-phone input-icon"
                        ></i>

                        <input
                            type="text"
                            name="no_hp"
                            placeholder="Masukkan nomor HP"
                            inputmode="numeric"
                            autocomplete="off"
                            required
                        >

                    </div>

                </div>


                <!-- KEPERLUAN -->

                <div class="form-group">

                    <label>

                        <i class="fa-solid fa-list-check"></i>

                        Keperluan

                    </label>

                    <div class="input-box">

                        <i
                            class="fa-solid fa-list-check input-icon"
                        ></i>

                        <input
                            type="text"
                            name="keperluan"
                            placeholder="Masukkan keperluan"
                            autocomplete="off"
                            required
                        >

                    </div>

                </div>


                <!-- TUJUAN -->

                <div class="form-group">

                    <label>

                        <i class="fa-solid fa-location-dot"></i>

                        Tujuan

                    </label>

                    <div class="input-box">

                        <i
                            class="fa-solid fa-location-dot input-icon"
                        ></i>

                        <input
                            type="text"
                            name="tujuan"
                            placeholder="Masukkan tujuan"
                            autocomplete="off"
                            required
                        >

                    </div>

                </div>


            </div>


            <!-- BUTTON -->

            <div class="form-actions">

                <button
                    type="submit"
                    class="btn-simpan"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan

                </button>


                <a
                    href="dashboard.php"
                    class="btn-dashboard"
                >

                    <i class="fa-solid fa-house"></i>

                    Dashboard

                </a>

            </div>


        </form>

    </section>


    <!-- =================================================
         PANDUAN & BANTUAN
    ================================================= -->

    <section class="panduan-card">

        <!-- HEADER -->

        <div class="panduan-header">

            <div class="panduan-icon">

                <i class="fa-solid fa-circle-info"></i>

            </div>

            <div>

                <h2>
                    Panduan
                </h2>

                <p>
                    Klik bagian di bawah untuk melihat bantuan pengisian.
                </p>

            </div>

        </div>


        <!-- PANDUAN -->

        <div class="panduan-grid">


            <!-- DATA DIRI -->

            <a
                href="#"
                class="panduan-item"
                onclick="
                    tampilkanBantuan(
                        'Isi Data Diri',
                        'Isi nama tamu dan asal/keterangan sesuai dengan identitas agar data kunjungan tercatat dengan benar.'
                    );
                    return false;
                "
            >

                <div class="panduan-item-icon">

                    <i class="fa-solid fa-user-check"></i>

                </div>

                <div>

                    <strong>
                        Isi Data Diri
                    </strong>

                    <span>
                        Klik untuk melihat panduan.
                    </span>

                </div>

                <i class="fa-solid fa-chevron-right panduan-arrow"></i>

            </a>


            <!-- NOMOR HP -->

            <a
                href="#"
                class="panduan-item"
                onclick="
                    tampilkanBantuan(
                        'Nomor HP Aktif',
                        'Masukkan nomor HP yang masih aktif agar dapat dihubungi apabila diperlukan.'
                    );
                    return false;
                "
            >

                <div class="panduan-item-icon">

                    <i class="fa-solid fa-phone"></i>

                </div>

                <div>

                    <strong>
                        Nomor HP Aktif
                    </strong>

                    <span>
                        Klik untuk melihat panduan.
                    </span>

                </div>

                <i class="fa-solid fa-chevron-right panduan-arrow"></i>

            </a>


            <!-- PERIKSA DATA -->

            <a
                href="#"
                class="panduan-item"
                onclick="
                    tampilkanBantuan(
                        'Periksa Sebelum Simpan',
                        'Pastikan nama, jenis kelamin, asal/keterangan, nomor HP, keperluan, dan tujuan sudah benar sebelum menekan tombol Simpan.'
                    );
                    return false;
                "
            >

                <div class="panduan-item-icon">

                    <i class="fa-solid fa-clipboard-check"></i>

                </div>

                <div>

                    <strong>
                        Periksa Sebelum Simpan
                    </strong>

                    <span>
                        Klik untuk melihat panduan.
                    </span>

                </div>

                <i class="fa-solid fa-chevron-right panduan-arrow"></i>

            </a>


        </div>

    </section>


    <!-- =================================================
         MODAL BANTUAN
    ================================================= -->

    <div
        class="bantuan-modal"
        id="bantuanModal"
    >

        <div
            class="bantuan-overlay"
            onclick="tutupBantuan()"
        ></div>

        <div class="bantuan-box">

            <button
                type="button"
                class="bantuan-close"
                onclick="tutupBantuan()"
            >

                <i class="fa-solid fa-xmark"></i>

            </button>


            <div class="bantuan-modal-icon">

                <i class="fa-solid fa-circle-info"></i>

            </div>


            <h3 id="bantuanJudul">
                Bantuan
            </h3>


            <p id="bantuanIsi">
                Informasi bantuan.
            </p>


            <button
                type="button"
                class="bantuan-ok"
                onclick="tutupBantuan()"
            >

                Mengerti

            </button>

        </div>

    </div>


    <!-- =================================================
         FOOTER
    ================================================= -->

    <footer>

        © <?= date("Y"); ?> Buku Tamu Digital SMK Pelita


    </footer>

</main>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

/* =====================================================
   BUKA MODAL BANTUAN
===================================================== */

function tampilkanBantuan(judul, isi) {

    document.getElementById(
        "bantuanJudul"
    ).textContent = judul;

    document.getElementById(
        "bantuanIsi"
    ).textContent = isi;

    document.getElementById(
        "bantuanModal"
    ).classList.add("show");

}


/* =====================================================
   TUTUP MODAL
===================================================== */

function tutupBantuan() {

    document.getElementById(
        "bantuanModal"
    ).classList.remove("show");

}


/* =====================================================
   TUTUP DENGAN ESC
===================================================== */

document.addEventListener(
    "keydown",
    function(event) {

        if (event.key === "Escape") {

            tutupBantuan();

        }

    }
);


/* =====================================================
   JAM & TANGGAL
===================================================== */

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


    const tanggalLengkap =

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
    ).textContent = tanggalLengkap;


    document.getElementById(
        "hari"
    ).textContent = hari[
        now.getDay()
    ];


    document.getElementById(
        "jam"
    ).textContent = waktu;

}


updateTime();


setInterval(
    updateTime,
    1000
);

</script>


</body>
</html>