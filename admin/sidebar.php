<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<style>

/* =====================================================
   SIDEBAR ADMIN
===================================================== */

.sidebar {
    display: flex !important;
    flex-direction: column !important;
    height: 100vh !important;
}


/* =====================================================
   LOGO
===================================================== */

.sidebar .logo {

    display: flex !important;

    align-items: center !important;

    gap: 12px !important;

    padding: 0 12px !important;

    margin: 0 !important;

    color: #ffffff !important;
}


.sidebar .logo i {

    font-size: 30px !important;

    color: #ffffff !important;
}


.sidebar .logo h2 {

    margin: 0 !important;

    padding: 0 !important;

    color: #ffffff !important;

    font-family: 'Poppins', sans-serif !important;

    font-size: 27px !important;

    font-weight: 800 !important;

    line-height: 1.05 !important;

}


/* =====================================================
   GARIS PEMBATAS BUKU TAMU & MENU
===================================================== */

.sidebar .sidebar-line {

    display: block !important;

    width: calc(100% - 12px) !important;

    height: 1px !important;

    min-height: 1px !important;

    margin: 24px auto 20px !important;

    padding: 0 !important;

    background: rgba(255,255,255,0.35) !important;

    border: none !important;

}


/* =====================================================
   MENU
===================================================== */

.admin-menu {

    width: 100% !important;

    display: flex !important;

    flex-direction: column !important;

}


.admin-menu a {

    display: flex !important;

    align-items: center !important;

    gap: 15px !important;

    width: 100% !important;

    min-height: 50px !important;

    padding: 13px 16px !important;

    margin: 4px 0 !important;

    border-radius: 15px !important;

    color: #ffffff !important;

    text-decoration: none !important;

    font-family: 'Poppins', sans-serif !important;

    font-size: 15px !important;

    font-weight: 700 !important;

    transition:
        transform 0.25s ease,
        background 0.25s ease,
        box-shadow 0.25s ease !important;

}


.admin-menu a span {

    color: #ffffff !important;

    font-weight: 700 !important;

}


.admin-menu a i {

    width: 20px !important;

    min-width: 20px !important;

    text-align: center !important;

    color: #ffffff !important;

    font-size: 17px !important;

}


/* =====================================================
   HOVER
===================================================== */

.admin-menu a:hover {

    background: rgba(255,255,255,0.18) !important;

    transform: translateX(5px) !important;

}


/* =====================================================
   AKTIF
===================================================== */

.admin-menu a.active {

    background: rgba(255,255,255,0.25) !important;

    color: #ffffff !important;

}


/* =====================================================
   LOGOUT
===================================================== */

.sidebar-logout {

    width: 100% !important;

    margin-top: auto !important;

    padding-top: 15px !important;

    border-top:
        1px solid
        rgba(255,255,255,0.22) !important;

}


.sidebar-logout a {

    display: flex !important;

    align-items: center !important;

    gap: 15px !important;

    width: 100% !important;

    min-height: 50px !important;

    padding: 13px 16px !important;

    margin: 8px 0 !important;

    border-radius: 14px !important;

    color: #ffffff !important;

    text-decoration: none !important;

    font-family: 'Poppins', sans-serif !important;

    font-size: 14px !important;

    font-weight: 700 !important;

}


.sidebar-logout a span {

    color: #ffffff !important;

    font-weight: 700 !important;

}


.sidebar-logout a i {

    width: 22px !important;

    min-width: 22px !important;

    text-align: center !important;

    color: #ffffff !important;

    font-size: 17px !important;

}


.sidebar-logout a:hover {

    background: rgba(255,255,255,0.18) !important;

}


/* =====================================================
   TEBALKAN SEMUA TEKS
===================================================== */

.sidebar a,
.sidebar a span {

    font-weight: 700 !important;

}

</style>


<aside class="sidebar">


    <!-- =================================================
         LOGO
    ================================================= -->

    <div class="logo">

        <i class="fa-solid fa-book-open"></i>

        <h2>

            Buku<br>
            Tamu

        </h2>

    </div>


    <!-- =================================================
         GARIS PEMBATAS
    ================================================= -->

    <div
        class="sidebar-line"
        style="
            display:block !important;
            width:calc(100% - 12px) !important;
            height:1px !important;
            min-height:1px !important;
            margin:24px auto 20px !important;
            background:rgba(255,255,255,0.35) !important;
        "
    ></div>


    <!-- =================================================
         MENU
    ================================================= -->

    <nav class="admin-menu">


        <!-- DASHBOARD -->

        <a
            href="dashboard.php"
            class="<?= $currentPage == 'dashboard.php' ? 'active' : ''; ?>"
        >

            <i class="fa-solid fa-house"></i>

            <span>
                Dashboard
            </span>

        </a>


        <!-- DATA TAMU -->

        <a
            href="data_tamu.php"
            class="<?= $currentPage == 'data_tamu.php' ? 'active' : ''; ?>"
        >

            <i class="fa-solid fa-users"></i>

            <span>
                Data Tamu
            </span>

        </a>

        <!-- KEPERLUAN -->

        <a
            href="keperluan.php"
            class="<?= $currentPage == 'keperluan.php' ? 'active' : ''; ?>"
        >

            <i class="fa-solid fa-clipboard-list"></i>

            <span>
                Keperluan
            </span>

        </a>


        <!-- PENCARIAN -->

        <a
            href="pencarian.php"
            class="<?= $currentPage == 'pencarian.php' ? 'active' : ''; ?>"
        >

            <i class="fa-solid fa-magnifying-glass"></i>

            <span>
                Pencarian
            </span>

        </a>


        <!-- LAPORAN -->

        <a
            href="laporan.php"
            class="<?= $currentPage == 'laporan.php' ? 'active' : ''; ?>"
        >

            <i class="fa-solid fa-print"></i>

            <span>
                Laporan
            </span>

        </a>


    </nav>


    <!-- =================================================
         LOGOUT
    ================================================= -->

    <div class="sidebar-logout">

        <a href="../auth/logout.php">

            <i class="fa-solid fa-right-from-bracket"></i>

            <span>
                Logout
            </span>

        </a>

    </div>


</aside>