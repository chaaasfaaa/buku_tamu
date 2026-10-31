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


$id_user = (int)($_SESSION['id_user'] ?? 0);


if ($id_user <= 0) {

    header("Location: ../auth/login.php");

    exit;

}


/* =====================================================
   CEK REQUEST
===================================================== */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: pengaduan.php");

    exit;

}


/* =====================================================
   AMBIL DATA
===================================================== */

$aksi = $_POST['aksi'] ?? '';


$jenis_masalah = trim(
    $_POST['jenis_masalah'] ?? ''
);


$pesan = trim(
    $_POST['pesan'] ?? ''
);


$id_pengaduan = (int)(
    $_POST['id_pengaduan'] ?? 0
);


/* =====================================================
   VALIDASI
===================================================== */

if (
    $jenis_masalah === "" ||
    $pesan === ""
) {

    header(
        "Location: pengaduan.php"
        . "?status=error"
        . "&pesan="
        . urlencode(
            "Silakan lengkapi semua data pengaduan."
        )
    );

    exit;

}


/* =====================================================
   AMANKAN DATA
===================================================== */

$jenis_safe = mysqli_real_escape_string(
    $conn,
    $jenis_masalah
);


$pesan_safe = mysqli_real_escape_string(
    $conn,
    $pesan
);


/* =====================================================
   TAMBAH PENGADUAN BARU
===================================================== */

if ($aksi === "tambah") {


    $insert = mysqli_query(
        $conn,
        "
        INSERT INTO pengaduan
        (
            id_user,
            jenis_masalah,
            pesan,
            status,
            tanggal
        )
        VALUES
        (
            '$id_user',
            '$jenis_safe',
            '$pesan_safe',
            'Menunggu',
            NOW()
        )
        "
    );


    if ($insert) {

        header(
            "Location: pengaduan.php"
            . "?status=success"
        );

        exit;

    }


    header(
        "Location: pengaduan.php"
        . "?status=error"
        . "&pesan="
        . urlencode(
            "Pengaduan gagal dikirim."
        )
    );

    exit;

}


/* =====================================================
   EDIT PENGADUAN
===================================================== */

if (
    $aksi === "edit" &&
    $id_pengaduan > 0
) {


    /* =============================================
       CEK DATA MILIK USER
    ============================================= */

    $cek = mysqli_query(
        $conn,
        "
        SELECT
            id_pengaduan,
            status
        FROM pengaduan
        WHERE id_pengaduan = '$id_pengaduan'
          AND id_user = '$id_user'
        LIMIT 1
        "
    );


    if (
        !$cek ||
        mysqli_num_rows($cek) == 0
    ) {

        header(
            "Location: pengaduan.php"
            . "?status=error"
            . "&pesan="
            . urlencode(
                "Pengaduan tidak ditemukan."
            )
        );

        exit;

    }


    $data = mysqli_fetch_assoc($cek);


    /* =============================================
       HANYA STATUS MENUNGGU YANG BOLEH DIEDIT
    ============================================= */

    if ($data['status'] !== "Menunggu") {

        header(
            "Location: pengaduan.php"
            . "?status=error"
            . "&pesan="
            . urlencode(
                "Pengaduan yang sudah dibalas admin tidak dapat diedit."
            )
        );

        exit;

    }


    /* =============================================
       UPDATE
    ============================================= */

    $update = mysqli_query(
        $conn,
        "
        UPDATE pengaduan

        SET
            jenis_masalah = '$jenis_safe',
            pesan = '$pesan_safe'

        WHERE id_pengaduan = '$id_pengaduan'
          AND id_user = '$id_user'
          AND status = 'Menunggu'
        "
    );


    if ($update) {

        header(
            "Location: pengaduan.php"
            . "?status=success"
            . "&aksi=edit"
        );

        exit;

    }


    header(
        "Location: pengaduan.php"
        . "?status=error"
        . "&pesan="
        . urlencode(
            "Pengaduan gagal diperbarui."
        )
    );

    exit;

}


/* =====================================================
   AKSI TIDAK DIKENAL
===================================================== */

header(
    "Location: pengaduan.php"
    . "?status=error"
    . "&pesan="
    . urlencode(
        "Permintaan tidak valid."
    )
);

exit;

?>