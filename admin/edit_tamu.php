<?php

session_start();

include "../config/database.php";


/* =========================================
   CEK LOGIN ADMIN
========================================= */

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("Location: ../auth/login.php");
    exit;

}


/* =========================================
   AMBIL ID
========================================= */

if (!isset($_GET['id'])) {

    header("Location: data_tamu.php");
    exit;

}

$id = (int) $_GET['id'];


/* =========================================
   AMBIL DATA TAMU
========================================= */

$query = mysqli_query($conn, "
    SELECT *
    FROM tamu
    WHERE id = $id
");

$data = mysqli_fetch_assoc($query);


if (!$data) {

    echo "Data tamu tidak ditemukan.";
    exit;

}

?>


<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Edit Data Tamu | Buku Tamu</title>


<!-- FONT -->

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet">


<!-- FONT AWESOME -->

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


<style>

/* =========================================
   RESET
========================================= */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}


body{

    font-family:'Poppins',sans-serif;

    background:#FAF4F7;

    color:#5F4050;

    min-height:100vh;

}


/* =========================================
   SIDEBAR
========================================= */

.sidebar{

    position:fixed;

    left:0;
    top:0;

    width:230px;

    height:100vh;

    background:
        linear-gradient(
            180deg,
            #C98FA7,
            #B97892
        );

    padding:28px 15px;

    box-shadow:
        5px 0 20px rgba(182,83,125,.15);

    z-index:1000;

}


.logo{

    display:flex;

    align-items:center;

    gap:10px;

    color:white;

    margin-bottom:45px;

    padding-left:5px;

}


.logo i{

    font-size:30px;

}


.logo h2{

    font-size:25px;

    font-weight:700;

    color:white;

}


.sidebar a{

    display:flex;

    align-items:center;

    gap:14px;

    color:white;

    text-decoration:none;

    padding:15px 18px;

    margin-bottom:10px;

    border-radius:16px;

    font-size:14px;

    font-weight:500;

    transition:.3s;

}


.sidebar a i{

    width:22px;

    text-align:center;

    font-size:17px;

}


.sidebar a:hover{

    background:rgba(255,255,255,.18);

    transform:translateX(4px);

}


.sidebar a.active{

    background:white;

    color:#B6537D;

    box-shadow:
        0 8px 20px rgba(0,0,0,.08);

}


/* =========================================
   MAIN
========================================= */

.main{

    margin-left:230px;

    min-height:100vh;

    padding:40px;

}


/* =========================================
   HEADER
========================================= */

.page-header{

    margin-bottom:25px;

}


.page-header h1{

    color:#9F3F68;

    font-size:30px;

    font-weight:700;

    margin-bottom:7px;

}


.page-header p{

    color:#8A6575;

    font-size:14px;

}


/* =========================================
   FORM CARD
========================================= */

.form-card{

    background:white;

    border-radius:22px;

    padding:32px;

    width:100%;

    max-width:none;

    box-shadow:
        0 12px 30px rgba(182,83,125,.10);

    border:1px solid #F1DCE5;

}


/* =========================================
   FORM GROUP
========================================= */

.form-group{

    margin-bottom:22px;

}


.form-group label{

    display:block;

    margin-bottom:8px;

    color:#684A59;

    font-size:14px;

    font-weight:600;

}


.form-group label i{

    color:#B6537D;

    margin-right:7px;

}


/* =========================================
   INPUT
========================================= */

.form-group input,
.form-group select,
.form-group textarea{

    width:100%;

    padding:13px 15px;

    border:1px solid #E6CBD6;

    border-radius:12px;

    background:#FFFBFD;

    color:#5F4050;

    font-family:'Poppins',sans-serif;

    font-size:14px;

    outline:none;

    transition:.3s;

}


.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus{

    border-color:#B6537D;

    background:white;

    box-shadow:
        0 0 0 3px rgba(182,83,125,.10);

}


.form-group textarea{

    min-height:110px;

    resize:vertical;

}


/* =========================================
   BUTTON AREA
========================================= */

.button-area{

    display:flex;

    gap:12px;

    margin-top:28px;

    padding-top:22px;

    border-top:1px solid #F1DCE5;

}


/* =========================================
   UPDATE BUTTON
========================================= */

.btn-update{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:8px;

    padding:13px 25px;

    border:none;

    border-radius:12px;

    background:
        linear-gradient(
            135deg,
            #C96A91,
            #B6537D
        );

    color:white;

    font-family:'Poppins',sans-serif;

    font-size:14px;

    font-weight:600;

    text-decoration:none;

    cursor:pointer;

    box-shadow:
        0 8px 18px rgba(182,83,125,.18);

    transition:.3s;

}


.btn-update:hover{

    transform:translateY(-2px);

    background:
        linear-gradient(
            135deg,
            #B6537D,
            #9F3F68
        );

}


/* =========================================
   KEMBALI
========================================= */

.btn-kembali{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:8px;

    padding:13px 25px;

    border-radius:12px;

    background:#F8EAF0;

    color:#9F3F68;

    font-size:14px;

    font-weight:600;

    text-decoration:none;

    transition:.3s;

}


.btn-kembali:hover{

    background:#F1D5E1;

    transform:translateY(-2px);

}


/* =========================================
   RESPONSIVE
========================================= */

@media(max-width:768px){

    .sidebar{

        width:75px;

        padding:20px 10px;

    }


    .logo h2{

        display:none;

    }


    .logo{

        justify-content:center;

    }


    .sidebar a{

        justify-content:center;

        padding:15px 10px;

        font-size:0;

    }


    .sidebar a i{

        font-size:19px;

    }


    .main{

        margin-left:75px;

        padding:25px 20px;

    }


    .page-header h1{

        font-size:25px;

    }


    .form-card{

        padding:22px;

    }


    .button-area{

        flex-direction:column;

    }


    .btn-update,
    .btn-kembali{

        width:100%;

    }

}

</style>

</head>


<body>


<!-- =========================================
     SIDEBAR
========================================= -->

<div class="sidebar">


    <div class="logo">

        <i class="fa-solid fa-book-open"></i>

        <h2>
            Buku Tamu
        </h2>

    </div>


    <a href="dashboard.php">

        <i class="fa-solid fa-house"></i>

        Dashboard

    </a>


    <a href="data_tamu.php" class="active">

        <i class="fa-solid fa-users"></i>

        Data Tamu

    </a>


    <a href="keperluan.php">

        <i class="fa-solid fa-clipboard-list"></i>

        Keperluan

    </a>


    <a href="pencarian.php">

        <i class="fa-solid fa-magnifying-glass"></i>

        Pencarian

    </a>


    <a href="laporan.php">

        <i class="fa-solid fa-print"></i>

        Laporan

    </a>


    <a href="../auth/logout.php">

        <i class="fa-solid fa-right-from-bracket"></i>

        Logout

    </a>


</div>



<!-- =========================================
     MAIN
========================================= -->

<div class="main">


    <div class="page-header">

        <h1>
            <i class="fa-solid fa-user-pen"></i>
            Edit Data Tamu
        </h1>

        <p>
            Perbarui informasi data tamu yang sudah tersimpan.
        </p>

    </div>



    <!-- =====================================
         FORM
    ====================================== -->

    <div class="form-card">


        <form action="proses_edit.php"
              method="POST">


            <!-- ID -->

            <input
                type="hidden"
                name="id"
                value="<?= $data['id']; ?>">



            <!-- NAMA -->

            <div class="form-group">

                <label>

                    <i class="fa-solid fa-user"></i>

                    Nama Tamu

                </label>

                <input
                    type="text"
                    name="nama_tamu"
                    value="<?= htmlspecialchars($data['nama_tamu']); ?>"
                    placeholder="Masukkan nama tamu"
                    required>

            </div>



            <!-- JENIS KELAMIN -->

            <div class="form-group">

                <label>

                    <i class="fa-solid fa-venus-mars"></i>

                    Jenis Kelamin

                </label>

                <select name="jenis_kelamin" required>

                    <option value="Laki-laki"
                        <?= $data['jenis_kelamin']=="Laki-laki" ? "selected" : ""; ?>>

                        Laki-laki

                    </option>


                    <option value="Perempuan"
                        <?= $data['jenis_kelamin']=="Perempuan" ? "selected" : ""; ?>>

                        Perempuan

                    </option>

                </select>

            </div>



            <!-- INSTANSI -->

            <div class="form-group">

                <label>

                    <i class="fa-solid fa-building"></i>

                    Instansi

                </label>

                <input
                    type="text"
                    name="instansi"
                    value="<?= htmlspecialchars($data['instansi']); ?>"
                    placeholder="Masukkan instansi"
                    required>

            </div>



            <!-- NO HP -->

            <div class="form-group">

                <label>

                    <i class="fa-solid fa-phone"></i>

                    No HP

                </label>

                <input
                    type="text"
                    name="no_hp"
                    value="<?= htmlspecialchars($data['no_hp']); ?>"
                    placeholder="Masukkan nomor HP"
                    required>

            </div>



            <!-- KEPERLUAN -->

            <div class="form-group">

                <label>

                    <i class="fa-solid fa-clipboard-list"></i>

                    Keperluan

                </label>

                <input
                    type="text"
                    name="keperluan"
                    value="<?= htmlspecialchars($data['keperluan']); ?>"
                    placeholder="Masukkan keperluan"
                    required>

            </div>



            <!-- TUJUAN -->

            <div class="form-group">

                <label>

                    <i class="fa-solid fa-location-dot"></i>

                    Tujuan

                </label>

                <input
                    type="text"
                    name="tujuan"
                    value="<?= htmlspecialchars($data['tujuan']); ?>"
                    placeholder="Masukkan tujuan"
                    required>

            </div>



            <!-- BUTTON -->

            <div class="button-area">


                <button
                    type="submit"
                    class="btn-update">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan Perubahan

                </button>


                <a
                    href="data_tamu.php"
                    class="btn-kembali">

                    <i class="fa-solid fa-arrow-left"></i>

                    Kembali

                </a>


            </div>


        </form>


    </div>


</div>


</body>

</html>