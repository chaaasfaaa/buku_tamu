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
$role_user = ucfirst($_SESSION['role'] ?? "user");


/* =====================================================
   AMBIL FOTO PROFIL USER
===================================================== */

$foto_user = "";

$query_user = mysqli_query(
    $conn,
    "
    SELECT foto
    FROM users
    WHERE id = '$id_user'
    LIMIT 1
    "
);

if ($query_user) {

    $data_user = mysqli_fetch_assoc($query_user);

    $foto_user = $data_user['foto'] ?? "";

}


/* =====================================================
   JUMLAH KUNJUNGAN USER
===================================================== */

$query = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM tamu
    WHERE id_user = '$id_user'
    "
);

$data_total = mysqli_fetch_assoc($query);

$total_kunjungan = $data_total['total'] ?? 0;


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


/* =====================================================
   CEK FOTO
===================================================== */

$foto_file = "";

if (!empty($foto_user)) {

    $lokasi_foto = __DIR__ . "/../uploads/profil/" . $foto_user;

    if (file_exists($lokasi_foto)) {

        $foto_file =
            "../uploads/profil/" .
            rawurlencode($foto_user);

    }

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
    Dashboard User - Buku Tamu Digital SMK Pelita
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
     SIDEBAR USER
===================================================== -->

<link
    rel="stylesheet"
    href="../assets/css/sidebar_user.css?v=1000"
>


<!-- =====================================================
     DASHBOARD USER
===================================================== -->

<link
    rel="stylesheet"
    href="../assets/css/dashboard_user.css?v=300"
>


<style>

/* =====================================================
   FOTO USER DI TOPBAR
===================================================== */

.user-profile-photo {

    width: 34px;
    height: 34px;

    border-radius: 50%;

    overflow: hidden;

    background: #f5dbe7;

    color: #c77fa3;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    border: 2px solid #f2d3e1;

}

.user-profile-photo img {

    width: 100%;
    height: 100%;

    object-fit: cover;

    display: block;

}

.user-profile-photo i {

    font-size: 15px;

}


/* =====================================================
   ROLE BOX
===================================================== */

.user-role-box {

    height: 50px;

    min-width: 165px;

    background: #ffffff;

    border-radius: 16px;

    padding: 0 14px;

    display: flex;

    align-items: center;

    gap: 10px;

    box-shadow:
        0 8px 25px rgba(199,127,163,.08);

}

.user-role-box .user-profile-photo {

    width: 34px;
    height: 34px;

}

.user-role-box span {

    color: #352b40;

    font-size: 13px;

    font-weight: 700;

}


/* =====================================================
   TOPBAR
===================================================== */

.topbar-user {

    width: 100%;

    display: flex;

    justify-content: flex-end;

    align-items: center;

    margin-bottom: 28px;

}


/* =====================================================
   TOP RIGHT
===================================================== */

.user-top-right {

    display: flex;

    align-items: center;

    gap: 12px;

}


/* =====================================================
   TANGGAL
===================================================== */

.user-top-date {

    min-width: 200px;

    height: 50px;

    background: #ffffff;

    border-radius: 16px;

    padding: 0 18px;

    display: flex;

    align-items: center;

    gap: 12px;

    box-shadow:
        0 8px 25px rgba(199,127,163,.08);

}


.user-top-date-icon {

    width: 34px;

    height: 34px;

    border-radius: 50%;

    background: #f5dbe7;

    color: #c77fa3;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 15px;

}


.user-top-date-text {

    display: flex;

    flex-direction: column;

}


.user-top-date-text strong {

    font-size: 13px;

    color: #352b40;

    font-weight: 700;

}


.user-top-date-text span {

    margin-top: 1px;

    color: #927c8c;

    font-size: 11px;

}


/* =====================================================
   NOTIFIKASI
===================================================== */

.notification-wrapper {

    position: relative;

}


.notification-button {

    position: relative;

    width: 50px;

    height: 50px;

    border: none;

    border-radius: 16px;

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

}


.notification-button:hover {

    transform: translateY(-3px);

    box-shadow:
        0 12px 28px rgba(199,127,163,.18);

}


.notification-button.has-new i {

    animation:
        bellShake .7s ease;

}


@keyframes bellShake {

    0%, 100% {
        transform: rotate(0);
    }

    20% {
        transform: rotate(-15deg);
    }

    40% {
        transform: rotate(15deg);
    }

    60% {
        transform: rotate(-10deg);
    }

    80% {
        transform: rotate(8deg);
    }

}


/* =====================================================
   BADGE
===================================================== */

.notification-badge {

    position: absolute;

    top: -5px;

    right: -5px;

    min-width: 21px;

    height: 21px;

    padding: 0 5px;

    border-radius: 50px;

    background: #d65b87;

    color: white;

    font-size: 9px;

    font-weight: 700;

    display: none;

    align-items: center;

    justify-content: center;

    border: 2px solid white;

}


.notification-badge.show {

    display: flex;

}


/* =====================================================
   DROPDOWN NOTIFIKASI
===================================================== */

.notification-dropdown {

    position: absolute;

    top: 60px;

    right: 0;

    width: 380px;

    background: white;

    border-radius: 20px;

    box-shadow:
        0 20px 50px rgba(120,70,100,.18);

    border: 1px solid #f3dce7;

    overflow: hidden;

    z-index: 9999;

    display: none;

}


.notification-dropdown.show {

    display: block;

    animation:
        notificationOpen .2s ease;

}


@keyframes notificationOpen {

    from {

        opacity: 0;

        transform: translateY(-8px);

    }

    to {

        opacity: 1;

        transform: translateY(0);

    }

}


/* =====================================================
   HEADER NOTIFIKASI
===================================================== */

.notification-header {

    padding: 17px 19px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    background:
        linear-gradient(
            135deg,
            #c985a7,
            #d295b2
        );

    color: white;

}


.notification-header strong {

    font-size: 15px;

}


.notification-header strong i {

    margin-right: 6px;

}


.notification-header span {

    font-size: 10px;

    background:
        rgba(255,255,255,.20);

    padding: 5px 9px;

    border-radius: 20px;

    font-weight: 600;

}


/* =====================================================
   LIST NOTIFIKASI
===================================================== */

.notification-list {

    max-height: 370px;

    overflow-y: auto;

}


.notification-item {

    display: flex;

    gap: 11px;

    padding: 14px 17px;

    border-bottom:
        1px solid #f6e9ef;

    transition: .2s;

}


.notification-item:hover {

    background: #fff7fb;

}


.notification-item.unread {

    background: #fff4f8;

}


/* =====================================================
   ICON NOTIFIKASI
===================================================== */

.notification-icon {

    flex: 0 0 40px;

    width: 40px;

    height: 40px;

    border-radius: 12px;

    background: #f3d1df;

    color: #c77fa3;

    display: flex;

    align-items: center;

    justify-content: center;

}


/* =====================================================
   ISI NOTIFIKASI
===================================================== */

.notification-content {

    min-width: 0;

    flex: 1;

}


.notification-content strong {

    display: block;

    color: #3a2d3e;

    font-size: 12px;

    margin-bottom: 3px;

}


.notification-content p {

    color: #806d79;

    font-size: 10px;

    line-height: 1.5;

    margin: 0;

}


.notification-content small {

    display: block;

    color: #b095a4;

    font-size: 9px;

    margin-top: 5px;

}


/* =====================================================
   TITIK BARU
===================================================== */

.notification-dot {

    width: 7px;

    height: 7px;

    border-radius: 50%;

    background: #d65b87;

    margin-top: 5px;

    flex: 0 0 7px;

}


/* =====================================================
   KOSONG
===================================================== */

.notification-empty {

    text-align: center;

    padding: 35px 20px;

    color: #a28c98;

    font-size: 12px;

}


.notification-empty i {

    display: block;

    font-size: 28px;

    color: #e1b7c9;

    margin-bottom: 10px;

}


/* =====================================================
   LOADING
===================================================== */

.notification-loading {

    text-align: center;

    padding: 25px;

    color: #a28c98;

    font-size: 11px;

}


/* =====================================================
   FOOTER NOTIFIKASI
===================================================== */

.notification-footer {

    padding: 11px 17px;

    text-align: center;

    background: #fffafd;

    color: #c77fa3;

    font-size: 10px;

    font-weight: 600;

    border-top:
        1px solid #f2e1e9;

}


/* =====================================================
   WELCOME BANNER
===================================================== */

.welcome-card {

    position: relative;

    overflow: hidden;

    min-height: 288px;

    padding: 38px 46px;

    border-radius: 25px;

    background:
        linear-gradient(
            135deg,
            #c985a7,
            #d295b2
        );

    color: white;

    margin-bottom: 32px;

}


.welcome-card::before {

    content: "";

    position: absolute;

    width: 190px;

    height: 190px;

    border-radius: 50%;

    background:
        rgba(255,255,255,.12);

    right: -20px;

    top: -70px;

}


.welcome-card::after {

    content: "";

    position: absolute;

    width: 170px;

    height: 170px;

    border-radius: 50%;

    background:
        rgba(255,255,255,.10);

    left: 55%;

    bottom: -115px;

}


/* =====================================================
   ISI BANNER
===================================================== */

.welcome-content {

    position: relative;

    z-index: 2;

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

}


/* =====================================================
   JUDUL BANNER
===================================================== */

.welcome-card h1 {

    font-size: 38px;

    line-height: 1.35;

    margin: 0 0 18px;

    color: white;

}


.welcome-card h1 strong {

    color: white;

}


/* =====================================================
   PARAGRAF BANNER
===================================================== */

.welcome-card p {

    font-size: 16px;

    margin: 0;

    color: white;

}


.welcome-card p strong {

    color: white;

}


/* =====================================================
   JAM AKTIF
===================================================== */

.banner-clock {

    background:
        rgba(255,255,255,.18);

    border:
        1px solid rgba(255,255,255,.45);

    border-radius: 18px;

    padding: 12px 18px;

    display: flex;

    align-items: center;

    gap: 12px;

    min-width: 195px;

    position: relative;

    z-index: 5;

}


.banner-clock-icon {

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


.banner-clock-text {

    display: flex;

    flex-direction: column;

}


.banner-clock-text small {

    font-size: 11px;

    opacity: .9;

    color: white;

}


.banner-clock-text strong {

    font-size: 20px;

    font-weight: 800;

    letter-spacing: 1px;

    color: white;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:800px) {

    .user-top-date {

        min-width: auto;

    }

    .user-role-box {

        min-width: auto;

    }

}


@media(max-width:600px) {

    .user-top-right {

        justify-content: space-between;

    }

    .user-top-date {

        flex: 1;

    }

    .user-role-box {

        flex: 1;

    }

    .notification-dropdown {

        position: fixed;

        top: 75px;

        left: 15px;

        right: 15px;

        width: auto;

    }

    .welcome-card {

        padding: 30px 25px;

    }

    .welcome-card h1 {

        font-size: 30px;

    }

    .welcome-card p {

        font-size: 14px;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     SIDEBAR USER
===================================================== -->

<?php include "sidebar.php"; ?>


<!-- =====================================================
     CONTENT
===================================================== -->

<main class="content">


<!-- =====================================================
     TOPBAR
===================================================== -->

<div class="topbar-user">

    <div class="user-top-right">


        <!-- =================================================
             NOTIFIKASI
        ================================================== -->

        <div class="notification-wrapper">

            <button
                type="button"
                class="notification-button"
                id="notificationButton"
                title="Notifikasi"
            >

                <i class="fa-solid fa-bell"></i>

                <span
                    class="notification-badge"
                    id="notificationBadge"
                >
                    0
                </span>

            </button>


            <!-- DROPDOWN -->

            <div
                class="notification-dropdown"
                id="notificationDropdown"
            >

                <div class="notification-header">

                    <strong>

                        <i class="fa-solid fa-bell"></i>

                        Notifikasi

                    </strong>

                    <span id="notificationCountText">

                        Terbaru

                    </span>

                </div>


                <div
                    class="notification-list"
                    id="notificationList"
                >

                    <div class="notification-loading">

                        Memuat notifikasi...

                    </div>

                </div>


                <div class="notification-footer">

                    <i class="fa-solid fa-circle-info"></i>

                    Informasi kunjungan Anda

                </div>

            </div>

        </div>


        <!-- =================================================
             TANGGAL
        ================================================== -->

        <div class="user-top-date">

            <div class="user-top-date-icon">

                <i class="fa-solid fa-calendar-days"></i>

            </div>


            <div class="user-top-date-text">

                <strong id="tanggal"></strong>

                <span id="hari"></span>

            </div>

        </div>


        <!-- =================================================
             FOTO + ROLE USER
        ================================================== -->

        <div class="user-role-box">


            <div class="user-profile-photo">

                <?php if (!empty($foto_file)): ?>

                    <img
                        src="<?= htmlspecialchars($foto_file); ?>"
                        alt="Foto Profil"
                    >

                <?php else: ?>

                    <i class="fa-solid fa-user"></i>

                <?php endif; ?>

            </div>


            <span>

                <?= htmlspecialchars($role_user); ?>

            </span>

        </div>

    </div>

</div>


<!-- =====================================================
     WELCOME BANNER
===================================================== -->

<section class="welcome-card">

    <div class="welcome-content">


        <!-- TEKS -->

        <div>

            <h1>

                <?= $salam ?>,

                <br>

                <strong>
                    <?= htmlspecialchars($nama_user); ?>
                </strong>

            </h1>


            <p>

                Selamat datang di
                <strong>Aplikasi Buku Tamu SMK Pelita.</strong>

                Silakan isi buku tamu dan lihat
                riwayat kunjungan Anda.

            </p>

        </div>


        <!-- JAM AKTIF -->

        <div class="banner-clock">

            <div class="banner-clock-icon">

                <i class="fa-solid fa-clock"></i>

            </div>


            <div class="banner-clock-text">

                <small>
                    Jam Aktif
                </small>

                <strong id="jam">
                    <?= date("H:i:s"); ?>
                </strong>

            </div>

        </div>

    </div>

</section>


<!-- =====================================================
     TITLE
===================================================== -->

<h2 class="page-title">

    <i class="fa-solid fa-chart-column"></i>

    Dashboard User

</h2>


<!-- =====================================================
     CARDS
===================================================== -->

<div class="cards">


    <!-- JUMLAH KUNJUNGAN -->

    <div class="card">

        <div class="card-icon">

            <i class="fa-solid fa-book"></i>

        </div>

        <h4>
            Jumlah Kunjungan
        </h4>

        <h2>
            <?= $total_kunjungan; ?>
        </h2>

        <i class="decoration fa-solid fa-users"></i>

    </div>


    <!-- HARI INI -->

    <div class="card">

        <div class="card-icon">

            <i class="fa-solid fa-calendar-days"></i>

        </div>

        <h4>
            Hari Ini
        </h4>

        <h2 id="cardTanggal"></h2>

        <i class="decoration fa-solid fa-calendar"></i>

    </div>


    <!-- NAMA -->

    <div class="card">

        <div class="card-icon">

            <i class="fa-solid fa-user"></i>

        </div>

        <h4>
            Nama User
        </h4>

        <h2>
            <?= htmlspecialchars($nama_user); ?>
        </h2>

        <i class="decoration fa-solid fa-user"></i>

    </div>


    <!-- STATUS -->

    <div class="card">

        <div class="card-icon">

            <i class="fa-solid fa-circle-check"></i>

        </div>

        <h4>
            Status
        </h4>

        <h2>
            Aktif
        </h2>

        <p>
            Akun Anda aktif
        </p>

        <i class="decoration fa-solid fa-shield-halved"></i>

    </div>

</div>


<!-- =====================================================
     MENU CEPAT
===================================================== -->

<h2 class="section-title">

    Menu Cepat

</h2>


<div class="quick-menu">


    <!-- ISI BUKU TAMU -->

    <a
        href="isi_tamu.php"
        class="quick-card"
    >

        <div class="quick-icon">

            <i class="fa-solid fa-pen-to-square"></i>

        </div>


        <div>

            <strong>
                Isi Buku Tamu
            </strong>

            <span>
                Tambah kunjungan
            </span>

        </div>

    </a>


    <!-- RIWAYAT -->

    <a
        href="riwayat.php"
        class="quick-card"
    >

        <div class="quick-icon">

            <i class="fa-solid fa-clock-rotate-left"></i>

        </div>


        <div>

            <strong>
                Riwayat Kunjungan
            </strong>

            <span>
                Lihat kunjungan
            </span>

        </div>

    </a>


    <!-- LOGOUT -->

    <a
        href="../auth/logout.php"
        class="quick-card"
    >

        <div class="quick-icon">

            <i class="fa-solid fa-right-from-bracket"></i>

        </div>


        <div>

            <strong>
                Logout
            </strong>

            <span>
                Keluar dari sistem
            </span>

        </div>

    </a>

</div>


<!-- =====================================================
     INFORMASI
===================================================== -->

<div class="info-box">

    <h3>

        <i class="fa-solid fa-circle-info"></i>

        Informasi

    </h3>


    <p>

        Silakan isi buku tamu setiap melakukan
        kunjungan.

        Gunakan menu
        <b>Riwayat Kunjungan</b>
        untuk melihat
        data kunjungan Anda.

    </p>

</div>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer>

    © <?= date("Y"); ?>

    Buku Tamu Digital SMK Pelita

</footer>


</main>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

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


    document
        .getElementById("tanggal")
        .textContent = tanggal;


    document
        .getElementById("hari")
        .textContent =
        hari[now.getDay()];


    document
        .getElementById("jam")
        .textContent = waktu;


    document
        .getElementById("cardTanggal")
        .textContent = tanggal;

}


updateTime();


setInterval(
    updateTime,
    1000
);


/* =====================================================
   ELEMENT NOTIFIKASI
===================================================== */

const notificationButton =
    document.getElementById(
        "notificationButton"
    );


const notificationDropdown =
    document.getElementById(
        "notificationDropdown"
    );


const notificationBadge =
    document.getElementById(
        "notificationBadge"
    );


const notificationList =
    document.getElementById(
        "notificationList"
    );


const notificationCountText =
    document.getElementById(
        "notificationCountText"
    );


let lastNotificationCount = 0;


/* =====================================================
   BUKA NOTIFIKASI
===================================================== */

notificationButton.addEventListener(
    "click",
    function(event) {

        event.stopPropagation();


        notificationDropdown
            .classList
            .toggle("show");


        if (
            notificationDropdown
                .classList
                .contains("show")
        ) {

            loadNotifications();


            setTimeout(
                markNotificationsAsRead,
                300
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

            notificationDropdown
                .classList
                .remove("show");

        }

    }
);


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

    .then(
        function(response) {

            if (!response.ok) {

                throw new Error(
                    "Gagal mengambil notifikasi."
                );

            }

            return response.json();

        }
    )

    .then(
        function(data) {

            if (!data.success) {

                return;

            }


            const jumlahBaru =
                parseInt(
                    data.unread_count || 0
                );


            /* BADGE */

            if (jumlahBaru > 0) {

                notificationBadge
                    .textContent =
                    jumlahBaru > 99
                    ? "99+"
                    : jumlahBaru;


                notificationBadge
                    .classList
                    .add("show");


                if (
                    jumlahBaru >
                    lastNotificationCount
                ) {

                    notificationButton
                        .classList
                        .remove(
                            "has-new"
                        );


                    void notificationButton
                        .offsetWidth;


                    notificationButton
                        .classList
                        .add(
                            "has-new"
                        );

                }

            } else {

                notificationBadge
                    .classList
                    .remove(
                        "show"
                    );

            }


            lastNotificationCount =
                jumlahBaru;


            /* HEADER */

            if (jumlahBaru > 0) {

                notificationCountText
                    .textContent =
                    jumlahBaru + " baru";

            } else {

                notificationCountText
                    .textContent =
                    "Terbaru";

            }


            /* DATA KOSONG */

            if (
                !data.notifications ||
                data.notifications.length === 0
            ) {

                notificationList.innerHTML = `

                    <div class="notification-empty">

                        <i class="fa-regular fa-bell-slash"></i>

                        Belum ada informasi kunjungan.

                    </div>

                `;

                return;

            }


            /* BUAT LIST */

            let html = "";


            data.notifications.forEach(
                function(item) {

                    const unreadClass =
                        item.baru
                        ? "unread"
                        : "";


                    const dot =
                        item.baru
                        ?
                        `<span class="notification-dot"></span>`
                        :
                        "";


                    html += `

                        <div
                            class="notification-item ${unreadClass}"
                        >

                            <div class="notification-icon">

                                <i class="fa-solid fa-book-open"></i>

                            </div>


                            <div class="notification-content">

                                <strong>

                                    Kunjungan Tercatat

                                </strong>


                                <p>

                                    Kunjungan Anda untuk

                                    <b>
                                        ${escapeHtml(item.keperluan)}
                                    </b>

                                    telah tercatat.

                                </p>


                                <small>

                                    <i class="fa-solid fa-calendar-days"></i>

                                    ${escapeHtml(item.tanggal)}

                                    &nbsp; • &nbsp;

                                    <i class="fa-solid fa-clock"></i>

                                    ${escapeHtml(item.jam)}

                                </small>

                            </div>


                            ${dot}

                        </div>

                    `;

                }
            );


            notificationList.innerHTML =
                html;

        }
    )

    .catch(
        function(error) {

            console.error(error);


            notificationList.innerHTML = `

                <div class="notification-empty">

                    <i class="fa-solid fa-triangle-exclamation"></i>

                    Notifikasi tidak dapat dimuat.

                </div>

            `;

        }
    );

}


/* =====================================================
   TANDAI SUDAH DIBACA
===================================================== */

function markNotificationsAsRead() {

    fetch(
        "notifikasi_dibaca.php",
        {

            method: "POST",

            headers: {

                "Content-Type":
                    "application/x-www-form-urlencoded"

            },

            body: "read=1"

        }
    )

    .then(
        function(response) {

            return response.json();

        }
    )

    .then(
        function(data) {

            if (data.success) {

                notificationBadge
                    .classList
                    .remove("show");


                lastNotificationCount = 0;


                notificationCountText
                    .textContent =
                    "Terbaru";


                document
                    .querySelectorAll(
                        ".notification-item.unread"
                    )
                    .forEach(
                        function(item) {

                            item.classList
                                .remove(
                                    "unread"
                                );

                        }
                    );


                document
                    .querySelectorAll(
                        ".notification-dot"
                    )
                    .forEach(
                        function(dot) {

                            dot.remove();

                        }
                    );

            }

        }
    )

    .catch(
        function(error) {

            console.error(
                "Gagal menandai notifikasi:",
                error
            );

        }
    );

}


/* =====================================================
   AMANKAN HTML
===================================================== */

function escapeHtml(text) {

    if (
        text === null ||
        text === undefined
    ) {

        return "";

    }


    return String(text)

        .replace(
            /&/g,
            "&amp;"
        )

        .replace(
            /</g,
            "&lt;"
        )

        .replace(
            />/g,
            "&gt;"
        )

        .replace(
            /"/g,
            "&quot;"
        )

        .replace(
            /'/g,
            "&#039;"
        );

}


/* =====================================================
   LOAD AWAL
===================================================== */

loadNotifications();


/* =====================================================
   CEK NOTIFIKASI SETIAP 5 DETIK
===================================================== */

setInterval(
    function() {

        loadNotifications();

    },
    5000
);

</script>


</body>

</html>