<?php

session_start();

include "../config/database.php";


/* =========================================
   AMBIL DATA KEPERLUAN
========================================= */

$keperluan = mysqli_query(
    $conn,
    "SELECT * FROM keperluan"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tambah Data Tamu | Buku Tamu Digital</title>


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


    <!-- CSS -->

    <link rel="stylesheet"
          href="../assets/css/tambah_tamu.css?v=2">

</head>


<body>


<div class="form-container">


    <!-- =====================================
         HEADER
    ====================================== -->

    <div class="form-header">

        <div class="title-icon">

            <i class="fa-solid fa-user-plus"></i>

        </div>


        <div>

            <h1>
                Tambah Data Tamu
            </h1>

            <p>
                Silakan isi data tamu dengan lengkap.
            </p>

        </div>

    </div>



    <!-- =====================================
         FORM
    ====================================== -->

    <form
        action="proses_tambah.php"
        method="POST">


        <!-- NAMA -->

        <div class="form-group">

            <label for="nama_tamu">
                <i class="fa-solid fa-user"></i>
                Nama Tamu
            </label>

            <input
                type="text"
                id="nama_tamu"
                name="nama_tamu"
                placeholder="Masukkan nama tamu"
                required>

        </div>



        <!-- JENIS KELAMIN -->

        <div class="form-group">

            <label for="jenis_kelamin">

                <i class="fa-solid fa-venus-mars"></i>

                Jenis Kelamin

            </label>


            <select
                id="jenis_kelamin"
                name="jenis_kelamin"
                required>

                <option value="Laki-laki">
                    Laki-laki
                </option>

                <option value="Perempuan">
                    Perempuan
                </option>

            </select>

        </div>



        <!-- INSTANSI -->

        <div class="form-group">

            <label for="instansi">

                <i class="fa-solid fa-building"></i>

                Instansi

            </label>


            <input
                type="text"
                id="instansi"
                name="instansi"
                placeholder="Masukkan instansi">

        </div>



        <!-- NO HP -->

        <div class="form-group">

            <label for="no_hp">

                <i class="fa-solid fa-phone"></i>

                No HP

            </label>


            <input
                type="text"
                id="no_hp"
                name="no_hp"
                placeholder="Masukkan nomor HP">

        </div>



        <!-- KEPERLUAN -->

        <div class="form-group">

            <label for="keperluan">

                <i class="fa-solid fa-clipboard-list"></i>

                Keperluan

            </label>


            <input
                type="text"
                id="keperluan"
                name="keperluan"
                placeholder="Masukkan keperluan"
                required>

        </div>



        <!-- TUJUAN -->

        <div class="form-group">

            <label for="tujuan">

                <i class="fa-solid fa-location-dot"></i>

                Tujuan

            </label>


            <input
                type="text"
                id="tujuan"
                name="tujuan"
                placeholder="Masukkan tujuan">

        </div>



        <!-- BUTTON -->

        <div class="form-buttons">


            <a
                href="data_tamu.php"
                class="btn-kembali">

                <i class="fa-solid fa-arrow-left"></i>

                Kembali

            </a>


            <button
                type="submit"
                class="btn-simpan">

                <i class="fa-solid fa-floppy-disk"></i>

                Simpan

            </button>


        </div>


    </form>


</div>


</body>

</html>