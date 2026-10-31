<?php

session_start();

include "../config/database.php";


/* CEK LOGIN ADMIN */

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {

    header("Location: ../auth/login.php");
    exit;

}


/* =========================
   VARIABEL PENCARIAN
========================= */

$nama = "";
$instansi = "";
$tanggal = "";
$keperluanCari = "";


/* =========================
   QUERY DASAR
========================= */

$query = "
    SELECT *
    FROM tamu
    WHERE 1=1
";


/* =========================
   PROSES PENCARIAN
========================= */

if (isset($_POST['cari'])) {

    $nama = mysqli_real_escape_string(
        $conn,
        $_POST['nama'] ?? ''
    );

    $instansi = mysqli_real_escape_string(
        $conn,
        $_POST['instansi'] ?? ''
    );

    $tanggal = mysqli_real_escape_string(
        $conn,
        $_POST['tanggal'] ?? ''
    );

    $keperluanCari = mysqli_real_escape_string(
        $conn,
        $_POST['keperluan'] ?? ''
    );


    if ($nama != "") {

        $query .= "
            AND nama_tamu LIKE '%$nama%'
        ";

    }


    if ($instansi != "") {

        $query .= "
            AND instansi LIKE '%$instansi%'
        ";

    }


    if ($tanggal != "") {

        $query .= "
            AND tanggal = '$tanggal'
        ";

    }


    if ($keperluanCari != "") {

        $query .= "
            AND keperluan LIKE '%$keperluanCari%'
        ";

    }

}


/* =========================
   URUTKAN DATA
========================= */

$query .= "
    ORDER BY id DESC
";


$data = mysqli_query($conn, $query);


/* =========================
   DATA KEPERLUAN
========================= */

$keperluan = mysqli_query($conn, "
    SELECT *
    FROM keperluan
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Pencarian | Buku Tamu Digital</title>


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


<!-- CSS SIDEBAR -->

<link rel="stylesheet"
      href="../assets/css/sidebar.css?v=403">


<!-- CSS KHUSUS PENCARIAN -->

<link rel="stylesheet"
      href="../assets/css/pencarian.css?v=1">

</head>


<body>

<?php include "sidebar.php"; ?>

<!-- =========================
     CONTENT
========================= -->

<div class="content">


    <div class="page-header">

        <div>

            <h1>
                Pencarian Data Tamu
            </h1>

            <p>
                Cari data tamu berdasarkan nama, instansi, keperluan atau tanggal.
            </p>

        </div>

    </div>



    <!-- =========================
         FORM PENCARIAN
    ========================= -->

    <div class="search-box">


        <form method="POST">


            <div class="form-grid">


                <!-- NAMA -->

                <div class="form-group">

                    <label>
                        <i class="fa-solid fa-user"></i>
                        Nama Tamu
                    </label>

                    <input
                        type="text"
                        name="nama"
                        value="<?= htmlspecialchars($nama); ?>"
                        placeholder="Masukkan nama tamu">

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
                        value="<?= htmlspecialchars($instansi); ?>"
                        placeholder="Masukkan instansi">

                </div>



                <!-- TANGGAL -->

                <div class="form-group">

                    <label>
                        <i class="fa-solid fa-calendar"></i>
                        Tanggal
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        value="<?= htmlspecialchars($tanggal); ?>">

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
                        list="list_keperluan"
                        value="<?= htmlspecialchars($keperluanCari); ?>"
                        placeholder="Ketik keperluan">


                    <datalist id="list_keperluan">

                    <?php

                    while ($k = mysqli_fetch_assoc($keperluan)) {

                    ?>

                        <option value="<?= htmlspecialchars($k['keperluan']); ?>">

                    <?php

                    }

                    ?>

                    </datalist>

                </div>

            </div>



            <!-- BUTTON -->

            <div class="search-buttons">

                <button
                    type="submit"
                    name="cari"
                    class="btn-search">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    Cari Data

                </button>


                <a
                    href="pencarian.php"
                    class="btn-reset">

                    <i class="fa-solid fa-rotate-left"></i>

                    Reset

                </a>

            </div>


        </form>

    </div>



    <!-- =========================
         HASIL
    ========================= -->

    <div class="result-header">

        <div>

            <h2>
                Hasil Pencarian
            </h2>

            <p>
                Data tamu yang ditemukan.
            </p>

        </div>

    </div>



    <!-- =========================
         TABLE
    ========================= -->

    <div class="table-container">


        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>Nama</th>

                    <th>Jenis Kelamin</th>

                    <th>Instansi</th>

                    <th>No HP</th>

                    <th>Keperluan</th>

                    <th>Tujuan</th>

                    <th>Tanggal</th>

                    <th>Jam Masuk</th>

                </tr>

            </thead>


            <tbody>


            <?php

            $no = 1;


            if (mysqli_num_rows($data) > 0) {

                while ($d = mysqli_fetch_assoc($data)) {

            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($d['nama_tamu']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($d['jenis_kelamin']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($d['instansi']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($d['no_hp']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($d['keperluan']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($d['tujuan']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($d['tanggal']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($d['jam_masuk']); ?>
                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="9" class="empty">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        Data tamu tidak ditemukan.

                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>


    </div>


</div>


</body>

</html>