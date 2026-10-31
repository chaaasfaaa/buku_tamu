<?php

session_start();

/* =====================================================

   JIKA SUDAH LOGIN

===================================================== */

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] === "admin") {

        header("Location: admin/dashboard.php");

        exit;

    } elseif ($_SESSION['role'] === "user") {

        header("Location: user/dashboard.php");

        exit;

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

    <title>Buku Tamu Digital | SMK Pelita</title>

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

    <style>

        /* =================================================

           RESET

        ================================================= */

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }

        html {

            scroll-behavior: smooth;

        }

        body {

            font-family: "Poppins", sans-serif;

            background: #fff8fb;

            color: #45333d;

            overflow-x: hidden;

        }

        /* =================================================

           NAVBAR

        ================================================= */

        .navbar {

            position: fixed;

            top: 0;

            left: 0;

            width: 100%;

            height: 72px;

            padding: 0 7%;

            display: flex;

            align-items: center;

            justify-content: space-between;

            background: rgba(255, 255, 255, 0.97);

            border-bottom: 1px solid #f2dce7;

            z-index: 999;

        }

        .logo {

            display: flex;

            align-items: center;

            gap: 11px;

            text-decoration: none;

        }

        .logo-icon {

            width: 42px;

            height: 42px;

            border-radius: 11px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #cf7fa4;

            color: white;

            font-size: 18px;

        }

        .logo-text strong {

            display: block;

            color: #b95280;

            font-size: 15px;

            font-weight: 800;

            line-height: 1.2;

        }

        .logo-text span {

            display: block;

            color: #9d8490;

            font-size: 9px;

            margin-top: 2px;

        }

        .nav-menu {

            display: flex;

            align-items: center;

            gap: 28px;

        }

        .nav-menu a {

            text-decoration: none;

            color: #6f5964;

            font-size: 12px;

            font-weight: 500;

            transition: 0.2s;

        }

        .nav-menu a:hover {

            color: #c3608b;

        }

        .nav-login {

            padding: 10px 19px;

            border-radius: 9px;

            background: #ce80a3;

            color: white !important;

            font-weight: 600 !important;

            box-shadow: 0 6px 15px rgba(199, 112, 150, 0.18);

        }

        .nav-login:hover {

            background: #bf7094;

        }

        /* =================================================

           HERO

        ================================================= */

        .hero {

            min-height: 640px;

            padding: 125px 7% 70px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 70px;

            background:

                radial-gradient(

                    circle at 90% 10%,

                    #f9dce9 0,

                    transparent 25%

                ),

                linear-gradient(

                    135deg,

                    #fff8fb 0%,

                    #fff4f8 100%

                );

        }

        .hero-content {

            max-width: 600px;

        }

        .small-label {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 7px 13px;

            border-radius: 30px;

            background: #f8e5ed;

            color: #bd5d88;

            font-size: 10px;

            font-weight: 600;

            margin-bottom: 17px;

        }

        .small-label i {

            font-size: 9px;

        }

        .hero h1 {

            font-size: clamp(36px, 4.7vw, 56px);

            line-height: 1.12;

            font-weight: 800;

            color: #493640;

            margin-bottom: 18px;

        }

        .hero h1 span {

            color: #c65e89;

        }

        .hero-description {

            max-width: 560px;

            color: #806d77;

            font-size: 13px;

            line-height: 1.9;

            margin-bottom: 27px;

        }

        .hero-buttons {

            display: flex;

            gap: 11px;

            flex-wrap: wrap;

        }

        .btn-primary {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            padding: 12px 20px;

            border-radius: 9px;

            background: #cf80a4;

            color: white;

            text-decoration: none;

            font-size: 12px;

            font-weight: 600;

            transition: 0.2s;

        }

        .btn-primary:hover {

            background: #bd7095;

            transform: translateY(-2px);

        }

        .btn-secondary {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            padding: 11px 20px;

            border-radius: 9px;

            background: white;

            border: 1px solid #e8c6d5;

            color: #b65b83;

            text-decoration: none;

            font-size: 12px;

            font-weight: 600;

            transition: 0.2s;

        }

        .btn-secondary:hover {

            background: #fff0f5;

            transform: translateY(-2px);

        }

        /* =================================================

           HERO VISUAL

        ================================================= */

        .hero-right {

            width: 420px;

            min-width: 380px;

            position: relative;

        }

        .main-card {

            position: relative;

            width: 100%;

            min-height: 360px;

            padding: 30px;

            border-radius: 25px;

            background: linear-gradient(

                145deg,

                #d995b4,

                #c8779d

            );

            box-shadow:

                0 25px 45px rgba(174, 91, 128, 0.18);

            overflow: hidden;

        }

        .circle-one {

            position: absolute;

            width: 230px;

            height: 230px;

            border-radius: 50%;

            background: rgba(255,255,255,0.11);

            top: -90px;

            right: -55px;

        }

        .circle-two {

            position: absolute;

            width: 180px;

            height: 180px;

            border-radius: 50%;

            background: rgba(255,255,255,0.09);

            bottom: -90px;

            left: -60px;

        }

        .book-box {

            width: 92px;

            height: 92px;

            margin: 35px auto 22px;

            border-radius: 22px;

            background: white;

            display: flex;

            align-items: center;

            justify-content: center;

            color: #c76891;

            font-size: 39px;

            position: relative;

            z-index: 2;

            box-shadow:

                0 12px 25px rgba(111, 57, 81, 0.12);

        }

        .main-card h2 {

            position: relative;

            z-index: 2;

            text-align: center;

            color: white;

            font-size: 21px;

            font-weight: 700;

            margin-bottom: 7px;

        }

        .main-card p {

            position: relative;

            z-index: 2;

            text-align: center;

            color: rgba(255,255,255,0.9);

            font-size: 11px;

            line-height: 1.7;

        }

        .floating {

            position: absolute;

            z-index: 5;

            display: flex;

            align-items: center;

            gap: 9px;

            background: white;

            border-radius: 10px;

            padding: 11px 14px;

            box-shadow:

                0 10px 25px rgba(101, 56, 77, 0.12);

            color: #664f5a;

            font-size: 10px;

            font-weight: 600;

        }

        .floating i {

            color: #cb7197;

            font-size: 14px;

        }

        .floating-left {

            left: -27px;

            top: 65px;

        }

        .floating-right {

            right: -27px;

            bottom: 65px;

        }

        /* =================================================

           QUICK INFO

        ================================================= */

        .quick-info {

            display: flex;

            gap: 35px;

            margin-top: 30px;

        }

        .quick-item {

            display: flex;

            align-items: center;

            gap: 9px;

        }

        .quick-icon {

            width: 30px;

            height: 30px;

            border-radius: 8px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #f8e5ed;

            color: #c86d94;

            font-size: 11px;

        }

        .quick-item strong {

            display: block;

            color: #59434e;

            font-size: 10px;

        }

        .quick-item span {

            display: block;

            color: #a18c96;

            font-size: 9px;

            margin-top: 2px;

        }

        /* =================================================

           SECTION

        ================================================= */

        .section {

            padding: 80px 7%;

        }

        .section-heading {

            text-align: center;

            max-width: 650px;

            margin: 0 auto 42px;

        }

        .section-heading .label {

            color: #c35e89;

            font-size: 10px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 1px;

        }

        .section-heading h2 {

            color: #493640;

            font-size: 28px;

            margin: 7px 0 10px;

        }

        .section-heading p {

            color: #8c7781;

            font-size: 12px;

            line-height: 1.8;

        }

        /* =================================================

           FEATURES

        ================================================= */

        .features {

            max-width: 1150px;

            margin: auto;

            display: grid;

            grid-template-columns:

                repeat(4, 1fr);

            gap: 17px;

        }

        .feature {

            background: white;

            border: 1px solid #f1dfe7;

            border-radius: 16px;

            padding: 23px 19px;

            transition: 0.25s;

        }

        .feature:hover {

            transform: translateY(-4px);

            box-shadow:

                0 15px 30px rgba(158, 92, 119, 0.09);

        }

        .feature-icon {

            width: 48px;

            height: 48px;

            border-radius: 12px;

            background: #fae8ef;

            color: #c66991;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 18px;

            margin-bottom: 15px;

        }

        .feature h3 {

            font-size: 13px;

            color: #59434e;

            margin-bottom: 7px;

        }

        .feature p {

            color: #907c86;

            font-size: 10px;

            line-height: 1.7;

        }

        /* =================================================

           ABOUT

        ================================================= */

        .about-section {

            background: white;

            border-top: 1px solid #f5e1e9;

            border-bottom: 1px solid #f5e1e9;

        }

        .about {

            max-width: 1100px;

            margin: auto;

            display: grid;

            grid-template-columns: 0.8fr 1.2fr;

            gap: 65px;

            align-items: center;

        }

        .about-card {

            min-height: 270px;

            border-radius: 22px;

            background: linear-gradient(

                145deg,

                #f5cfde,

                #e7abc4

            );

            display: flex;

            align-items: center;

            justify-content: center;

            position: relative;

            overflow: hidden;

        }

        .about-card i {

            width: 105px;

            height: 105px;

            border-radius: 27px;

            background: white;

            color: #c76b94;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 45px;

            box-shadow:

                0 15px 30px rgba(150, 76, 108, 0.12);

            position: relative;

            z-index: 2;

        }

        .about-text .label {

            color: #c15c86;

            font-size: 10px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 1px;

        }

        .about-text h2 {

            color: #493640;

            font-size: 28px;

            margin: 8px 0 13px;

        }

        .about-text p {

            color: #806c77;

            font-size: 12px;

            line-height: 1.9;

            margin-bottom: 18px;

        }

        .about-points {

            display: grid;

            grid-template-columns:

                repeat(2, 1fr);

            gap: 10px;

        }

        .point {

            display: flex;

            align-items: center;

            gap: 7px;

            color: #705966;

            font-size: 10px;

        }

        .point i {

            color: #c66a92;

            font-size: 10px;

        }

        /* =================================================

           CTA

        ================================================= */

        .cta {

            margin: 0;

            padding: 65px 7%;

            text-align: center;

            background: linear-gradient(

                135deg,

                #cf81a5,

                #bd6d94

            );

            color: white;

        }

        .cta h2 {

            font-size: 27px;

            margin-bottom: 9px;

        }

        .cta p {

            color: rgba(255,255,255,0.9);

            font-size: 12px;

            margin-bottom: 22px;

        }

        .cta-button {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 11px 21px;

            border-radius: 9px;

            background: white;

            color: #bc5f88;

            text-decoration: none;

            font-size: 12px;

            font-weight: 700;

            transition: 0.2s;

        }

        .cta-button:hover {

            transform: translateY(-2px);

        }

        /* =================================================

           FOOTER

        ================================================= */

        footer {

            padding: 23px 7%;

            background: #fff;

            text-align: center;

            color: #99828d;

            font-size: 10px;

        }

        footer strong {

            color: #c1608a;

        }

        /* =================================================

           RESPONSIVE

        ================================================= */

        @media (max-width: 950px) {

            .hero {

                flex-direction: column;

                text-align: center;

                padding-top: 115px;

            }

            .hero-description {

                margin-left: auto;

                margin-right: auto;

            }

            .hero-buttons {

                justify-content: center;

            }

            .quick-info {

                justify-content: center;

            }

            .hero-right {

                max-width: 420px;

                width: 100%;

                min-width: 0;

            }

            .features {

                grid-template-columns:

                    repeat(2, 1fr);

            }

            .about {

                grid-template-columns: 1fr;

                max-width: 650px;

            }

        }

        @media (max-width: 650px) {

            .navbar {

                padding: 0 5%;

            }

            .nav-menu {

                gap: 10px;

            }

            .nav-menu a:not(.nav-login) {

                display: none;

            }

            .hero {

                padding-left: 5%;

                padding-right: 5%;

            }

            .hero h1 {

                font-size: 35px;

            }

            .quick-info {

                flex-direction: column;

                align-items: center;

                gap: 10px;

            }

            .floating-left {

                left: 0;

            }

            .floating-right {

                right: 0;

            }

            .section {

                padding-left: 5%;

                padding-right: 5%;

            }

            .features {

                grid-template-columns: 1fr;

            }

            .about-points {

                grid-template-columns: 1fr;

            }

        }

    

        /* =================================================
           PENYEMPURNAAN TAMPILAN LANDING PAGE
        ================================================= */
        .hero {
            min-height: 570px;
            padding-top: 105px;
            padding-bottom: 55px;
            gap: 55px;
        }

        .hero-content { max-width: 570px; }

        .hero h1 {
            font-size: clamp(34px, 4.2vw, 51px);
            line-height: 1.15;
            margin-bottom: 15px;
        }

        .hero-description {
            line-height: 1.8;
            margin-bottom: 22px;
        }

        .hero-right {
            width: 390px;
            max-width: 390px;
            min-width: 350px;
        }

        .main-card {
            min-height: 330px;
            border-radius: 22px;
        }

        .section { padding-top: 58px; padding-bottom: 58px; }
        .section-heading { margin-bottom: 30px; }
        .section-heading h2 { font-size: 26px; }
        .features { gap: 20px; }

        .feature {
            padding: 24px 21px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(158, 92, 119, 0.06);
        }

        .feature-icon {
            width: 45px;
            height: 45px;
            margin-bottom: 12px;
        }

        .feature h3 { font-size: 13px; margin-bottom: 8px; }
        .feature p { font-size: 11px; line-height: 1.8; }
        .about { gap: 45px; }
        .about-card { min-height: 250px; border-radius: 20px; }
        .about-text h2 { font-size: 26px; }
        .cta { padding-top: 48px; padding-bottom: 48px; }
        .cta h2 { font-size: 25px; }
        footer { padding-top: 18px; padding-bottom: 18px; }

        @media (max-width: 950px) {
            .hero {
                min-height: auto;
                padding-top: 115px;
                padding-bottom: 55px;
                gap: 40px;
            }
            .hero-right {
                width: 100%;
                max-width: 390px;
                min-width: 0;
            }
            .section { padding: 50px 5%; }
            .about { gap: 30px; }
        }

        @media (max-width: 650px) {
            .hero { padding-top: 105px; padding-bottom: 45px; }
            .hero h1 { font-size: 34px; }
            .hero-right { max-width: 340px; }
            .main-card { min-height: 300px; padding: 22px; }
            .section { padding: 42px 5%; }
            .section-heading { margin-bottom: 25px; }
            .section-heading h2 { font-size: 23px; }
            .features { grid-template-columns: 1fr; gap: 13px; }
            .feature { padding: 20px; }
            .about-card { min-height: 220px; }
            .about-text h2 { font-size: 23px; }
            .cta { padding: 40px 5%; }
            .cta h2 { font-size: 22px; }
        }

</style>

</head>

<body>

    <!-- =================================================

         NAVBAR

    ================================================= -->

    <nav class="navbar">

        <a

            href="index.php"

            class="logo"

        >

            <div class="logo-icon">

                <i class="fa-solid fa-book-open"></i>

            </div>

            <div class="logo-text">

                <strong>

                    Buku Tamu Digital

                </strong>

                <span>

                    SMK Pelita

                </span>

            </div>

        </a>

        <div class="nav-menu">

            <a href="#beranda">

                Beranda

            </a>

            <a href="#fitur">

                Fitur

            </a>

            <a href="#tentang">

                Tentang

            </a>

            <a

                href="auth/login.php"

                class="nav-login"

            >

                <i class="fa-solid fa-right-to-bracket"></i>

                Login

            </a>

        </div>

    </nav>

    <!-- =================================================

         HERO

    ================================================= -->

    <section

        class="hero"

        id="beranda"

    >

        <div class="hero-content">

            <div class="small-label">

                <i class="fa-solid fa-circle-check"></i>

                Sistem Buku Tamu Digital

            </div>

            <h1>

                Selamat Datang di

                <span>

                    Buku Tamu Digital

                </span>

                SMK Pelita

            </h1>

            <p class="hero-description">

                Solusi digital untuk mempermudah proses

                pencatatan kunjungan tamu di SMK Pelita.

                Lebih cepat, lebih rapi, dan data lebih

                mudah dikelola.

            </p>

            <div class="hero-buttons">

                <a

                    href="auth/login.php"

                    class="btn-primary"

                >

                    <i class="fa-solid fa-right-to-bracket"></i>

                    Masuk ke Aplikasi

                </a>

                <a

                    href="auth/register.php"

                    class="btn-secondary"

                >

                    <i class="fa-solid fa-user-plus"></i>

                    Daftar sebagai Tamu

                </a>

            </div>

            <!-- QUICK INFO -->

            <div class="quick-info">

                <div class="quick-item">

                    <div class="quick-icon">

                        <i class="fa-solid fa-bolt"></i>

                    </div>

                    <div>

                        <strong>

                            Cepat

                        </strong>

                        <span>

                            Proses lebih praktis

                        </span>

                    </div>

                </div>

                <div class="quick-item">

                    <div class="quick-icon">

                        <i class="fa-solid fa-database"></i>

                    </div>

                    <div>

                        <strong>

                            Tersimpan

                        </strong>

                        <span>

                            Data tersimpan digital

                        </span>

                    </div>

                </div>

                <div class="quick-item">

                    <div class="quick-icon">

                        <i class="fa-solid fa-magnifying-glass"></i>

                    </div>

                    <div>

                        <strong>

                            Mudah Dicari

                        </strong>

                        <span>

                            Pencarian data lebih mudah

                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!-- =================================================

             HERO RIGHT

        ================================================= -->

        <div class="hero-right">

            <div class="main-card">

                <div class="circle-one"></div>

                <div class="circle-two"></div>

                <div class="book-box">

                    <i class="fa-solid fa-book-open"></i>

                </div>

                <h2>

                    Buku Tamu Digital

                </h2>

                <p>

                    Catat dan kelola kunjungan tamu

                    secara digital dengan mudah.

                </p>

            </div>

            <div class="floating floating-left">

                <i class="fa-solid fa-user-check"></i>

                Data Tamu

            </div>

            <div class="floating floating-right">

                <i class="fa-solid fa-clock"></i>

                Kunjungan Tercatat

            </div>

        </div>

    </section>

    <!-- =================================================

         FITUR

    ================================================= -->

    <section

        class="section"

        id="fitur"

    >

        <div class="section-heading">

            <span class="label">

                Fitur Aplikasi

            </span>

            <h2>

                Semua Lebih Mudah dan Teratur

            </h2>

            <p>

                Sistem Buku Tamu Digital SMK Pelita

                menyediakan berbagai fitur untuk

                membantu proses pencatatan kunjungan.

            </p>

        </div>

        <div class="features">

            <!-- 1 -->

            <div class="feature">

                <div class="feature-icon">

                    <i class="fa-solid fa-clipboard-list"></i>

                </div>

                <h3>

                    Pencatatan Tamu

                </h3>

                <p>

                    Mencatat data tamu dan informasi

                    kunjungan secara digital.

                </p>

            </div>

            <!-- 2 -->

            <div class="feature">

                <div class="feature-icon">

                    <i class="fa-solid fa-magnifying-glass"></i>

                </div>

                <h3>

                    Pencarian Data

                </h3>

                <p>

                    Data kunjungan dapat dicari

                    dengan cepat dan mudah.

                </p>

            </div>

            <!-- 3 -->

            <div class="feature">

                <div class="feature-icon">

                    <i class="fa-solid fa-clock-rotate-left"></i>

                </div>

                <h3>

                    Riwayat Kunjungan

                </h3>

                <p>

                    Pengguna dapat melihat kembali

                    riwayat kunjungan yang dilakukan.

                </p>

            </div>

            <!-- 4 -->

            <div class="feature">

                <div class="feature-icon">

                    <i class="fa-solid fa-print"></i>

                </div>

                <h3>

                    Laporan

                </h3>

                <p>

                    Admin dapat melihat dan mencetak

                    laporan data kunjungan.

                </p>

            </div>

        </div>

    </section>

    <!-- =================================================

         TENTANG

    ================================================= -->

    <section

        class="section about-section"

        id="tentang"

    >

        <div class="about">

            <div class="about-card">

                <i class="fa-solid fa-school"></i>

            </div>

            <div class="about-text">

                <span class="label">

                    Tentang Aplikasi

                </span>

                <h2>

                    Buku Tamu Digital SMK Pelita

                </h2>

                <p>

                    Buku Tamu Digital merupakan aplikasi

                    berbasis web yang dibuat untuk membantu

                    SMK Pelita dalam melakukan pencatatan,

                    penyimpanan, pencarian, dan pengelolaan

                    data kunjungan tamu secara digital.

                </p>

                <div class="about-points">

                    <div class="point">

                        <i class="fa-solid fa-check"></i>

                        Pencatatan lebih cepat

                    </div>

                    <div class="point">

                        <i class="fa-solid fa-check"></i>

                        Data lebih rapi

                    </div>

                    <div class="point">

                        <i class="fa-solid fa-check"></i>

                        Mudah mencari data

                    </div>

                    <div class="point">

                        <i class="fa-solid fa-check"></i>

                        Riwayat tersimpan

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- =================================================

         CTA

    ================================================= -->

    <section class="cta">

        <h2>

            Siap Mengisi Buku Tamu?

        </h2>

        <p>

            Silakan masuk ke aplikasi untuk

            mencatat kunjungan Anda.

        </p>

        <a

            href="auth/login.php"

            class="cta-button"

        >

            Mulai Sekarang

            <i class="fa-solid fa-arrow-right"></i>

        </a>

    </section>

    <!-- =================================================

         FOOTER

    ================================================= -->

    <footer>

        © <?= date("Y"); ?>

        <strong>

            Buku Tamu Digital SMK Pelita

        </strong>

        — Sistem Pencatatan Kunjungan Digital

    </footer>

</body>

</html>