<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<aside class="user-sidebar">

    <!-- =========================================
         LOGO / JUDUL
    ========================================== -->

    <div class="user-sidebar-logo">

        <i class="fa-solid fa-book-open"></i>

        <h2>
            Buku<br>
            Tamu
        </h2>

    </div>


    <!-- GARIS PEMBATAS -->

    <div class="user-sidebar-line"></div>


    <!-- =========================================
         MENU UTAMA
    ========================================== -->

    <nav class="user-sidebar-menu">

        <!-- DASHBOARD -->

        <a
            href="dashboard.php"
            class="<?= $currentPage === 'dashboard.php' ? 'active' : ''; ?>"
        >

            <i class="fa-solid fa-house"></i>

            <span>
                Dashboard
            </span>

        </a>


        <!-- ISI BUKU TAMU -->

        <a
            href="isi_tamu.php"
            class="<?= $currentPage === 'isi_tamu.php' ? 'active' : ''; ?>"
        >

            <i class="fa-solid fa-pen-to-square"></i>

            <span>
                Isi Buku Tamu
            </span>

        </a>


        <!-- RIWAYAT -->

        <a
            href="riwayat.php"
            class="<?= $currentPage === 'riwayat.php' ? 'active' : ''; ?>"
        >

            <i class="fa-solid fa-clock-rotate-left"></i>

            <span>
                Riwayat<br>
                Kunjungan
            </span>

        </a>


        <!-- PROFIL -->

        <a
            href="profil.php"
            class="<?= $currentPage === 'profil.php' ? 'active' : ''; ?>"
        >

            <i class="fa-solid fa-user"></i>

            <span>
                Profil
            </span>

        </a>

    </nav>


    <!-- =========================================
         LOGOUT
    ========================================== -->

    <div class="user-sidebar-logout">

        <a href="../auth/logout.php">

            <i class="fa-solid fa-right-from-bracket"></i>

            <span>
                Logout
            </span>

        </a>

    </div>

</aside>