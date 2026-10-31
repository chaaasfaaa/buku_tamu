<?php

session_start();

include "../config/database.php";

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
   AMBIL DATA FORM
===================================================== */

$nama_tamu = mysqli_real_escape_string(
    $conn,
    $_POST['nama_tamu'] ?? ''
);

$jenis_kelamin = mysqli_real_escape_string(
    $conn,
    $_POST['jenis_kelamin'] ?? ''
);

$instansi = mysqli_real_escape_string(
    $conn,
    $_POST['instansi'] ?? ''
);

$no_hp = mysqli_real_escape_string(
    $conn,
    $_POST['no_hp'] ?? ''
);

$keperluan = mysqli_real_escape_string(
    $conn,
    $_POST['keperluan'] ?? ''
);

$tujuan = mysqli_real_escape_string(
    $conn,
    $_POST['tujuan'] ?? ''
);


/* =====================================================
   VALIDASI
===================================================== */

if (
    empty($nama_tamu) ||
    empty($jenis_kelamin) ||
    empty($instansi) ||
    empty($no_hp) ||
    empty($keperluan) ||
    empty($tujuan)
) {

    echo "
    <script>
        alert('Semua data wajib diisi!');
        window.location.href='isi_tamu.php';
    </script>
    ";

    exit;
}


/* =====================================================
   WAKTU KUNJUNGAN
===================================================== */

$tanggal = date("Y-m-d");

/*
   Jam masuk = waktu saat tombol Simpan ditekan
*/
$jam_masuk = date("H:i:s");


/*
   Jam keluar BELUM ADA.
   Akan diisi nanti saat tombol Check Out ditekan.
*/
$jam_keluar = NULL;


/* =====================================================
   SIMPAN DATA TAMU
===================================================== */

$query = mysqli_query(
    $conn,
    "
    INSERT INTO tamu
    (
        id_user,
        nama_tamu,
        jenis_kelamin,
        instansi,
        no_hp,
        keperluan,
        tujuan,
        tanggal,
        jam,
        jam_masuk,
        jam_keluar
    )

    VALUES
    (
        '$id_user',
        '$nama_tamu',
        '$jenis_kelamin',
        '$instansi',
        '$no_hp',
        '$keperluan',
        '$tujuan',
        '$tanggal',
        '$jam_masuk',
        '$jam_masuk',
        NULL
    )
    "
);


/* =====================================================
   HASIL
===================================================== */

if ($query) {

    echo "
    <script>

        alert(
            'Data tamu berhasil disimpan!\\n\\n'
            + 'Jam masuk: $jam_masuk'
        );

        window.location.href='riwayat.php';

    </script>
    ";

} else {

    echo "
    <script>

        alert(
            'Data gagal disimpan: "
            . addslashes(mysqli_error($conn))
            . "'
        );

        window.location.href='isi_tamu.php';

    </script>
    ";

}

?>