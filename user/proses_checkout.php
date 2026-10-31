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

$id_user = $_SESSION['id_user'] ?? 0;

if ($id_user == 0) {

    header("Location: ../auth/login.php");
    exit;

}


/* =====================================================
   AMBIL ID TAMU
===================================================== */

$id_tamu = (int)($_GET['id'] ?? 0);

if ($id_tamu <= 0) {

    echo "
    <script>

        alert('Data kunjungan tidak ditemukan.');

        window.location.href='riwayat.php';

    </script>
    ";

    exit;
}


/* =====================================================
   CEK DATA TAMU
===================================================== */

$cek = mysqli_query(
    $conn,
    "
    SELECT id, nama_tamu, jam_masuk, jam_keluar
    FROM tamu
    WHERE id = '$id_tamu'
      AND id_user = '$id_user'
    LIMIT 1
    "
);


if (!$cek || mysqli_num_rows($cek) == 0) {

    echo "
    <script>

        alert('Data kunjungan tidak ditemukan.');

        window.location.href='riwayat.php';

    </script>
    ";

    exit;
}


$data = mysqli_fetch_assoc($cek);


/* =====================================================
   CEK SUDAH CHECK OUT ATAU BELUM
===================================================== */

if (
    !empty($data['jam_keluar']) &&
    $data['jam_keluar'] !== "00:00:00"
) {

    echo "
    <script>

        alert('Kunjungan ini sudah melakukan Check Out.');

        window.location.href='riwayat.php';

    </script>
    ";

    exit;
}


/* =====================================================
   CATAT JAM KELUAR
===================================================== */

$jam_keluar = date("H:i:s");


/* =====================================================
   UPDATE JAM KELUAR
===================================================== */

$query = mysqli_query(
    $conn,
    "
    UPDATE tamu

    SET jam_keluar = '$jam_keluar'

    WHERE id = '$id_tamu'
      AND id_user = '$id_user'
      AND (
          jam_keluar IS NULL
          OR jam_keluar = ''
          OR jam_keluar = '00:00:00'
      )
    "
);


/* =====================================================
   HASIL
===================================================== */

if ($query && mysqli_affected_rows($conn) > 0) {

    echo "
    <script>

        alert(
            'Check Out berhasil!\\n\\n'
            + 'Jam keluar: $jam_keluar'
        );

        window.location.href='riwayat.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert('Check Out gagal dilakukan.');

        window.location.href='riwayat.php';

    </script>
    ";

}

?>