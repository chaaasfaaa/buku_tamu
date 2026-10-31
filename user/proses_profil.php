<?php

session_start();

include "../config/database.php";


/* ==============================
   CEK LOGIN
============================== */

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {

    header("Location: ../auth/login.php");
    exit;

}


/* ==============================
   AMBIL ID USER
============================== */

$id_user =
    $_SESSION['id_user']
    ?? $_SESSION['id']
    ?? 0;


if (!$id_user) {

    echo "<script>

        alert('Data pengguna tidak ditemukan. Silakan login kembali.');

        window.location.href='../auth/login.php';

    </script>";

    exit;

}


/* ==============================
   AMBIL DATA FORM
============================== */

$nama_lengkap =
    trim(
        $_POST['nama_lengkap']
        ?? ''
    );


$username =
    trim(
        $_POST['username']
        ?? ''
    );


$password_baru =
    trim(
        $_POST['password_baru']
        ?? ''
    );


/* ==============================
   VALIDASI NAMA DAN USERNAME
============================== */

if (
    $nama_lengkap === ''
    ||
    $username === ''
) {

    echo "<script>

        alert('Nama lengkap dan username wajib diisi!');

        window.history.back();

    </script>";

    exit;

}


/* ==============================
   AMANKAN DATA
============================== */

$nama_lengkap =
    mysqli_real_escape_string(
        $conn,
        $nama_lengkap
    );


$username =
    mysqli_real_escape_string(
        $conn,
        $username
    );


/* ==============================
   CEK USERNAME
============================== */

$cek_username = mysqli_query(
    $conn,
    "
    SELECT id
    FROM users
    WHERE username = '$username'
    AND id != '$id_user'
    LIMIT 1
    "
);


if (
    $cek_username
    &&
    mysqli_num_rows($cek_username) > 0
) {

    echo "<script>

        alert('Username sudah digunakan oleh pengguna lain!');

        window.history.back();

    </script>";

    exit;

}


/* =====================================================
   PROSES FOTO
===================================================== */

$nama_foto_baru = "";


/* Folder upload */

$folder_upload =
    "../uploads/profil/";


/* Buat folder jika belum ada */

if (!is_dir($folder_upload)) {

    mkdir(
        $folder_upload,
        0755,
        true
    );

}


/* Cek apakah ada foto yang diupload */

if (
    isset($_FILES['foto'])
    &&
    $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
) {


    /* ==============================
       CEK ERROR UPLOAD
    ============================== */

    if (
        $_FILES['foto']['error']
        !== UPLOAD_ERR_OK
    ) {

        echo "<script>

            alert('Foto gagal diupload.');

            window.history.back();

        </script>";

        exit;

    }


    /* ==============================
       CEK UKURAN
       MAKSIMAL 2 MB
    ============================== */

    if (
        $_FILES['foto']['size']
        > 2 * 1024 * 1024
    ) {

        echo "<script>

            alert('Ukuran foto maksimal 2 MB.');

            window.history.back();

        </script>";

        exit;

    }


    /* ==============================
       CEK TIPE FILE
    ============================== */

    $tmp_file =
        $_FILES['foto']['tmp_name'];


    $finfo =
        finfo_open(FILEINFO_MIME_TYPE);


    $mime_type =
        finfo_file(
            $finfo,
            $tmp_file
        );


    finfo_close($finfo);


    $allowed_types = [

        "image/jpeg" => "jpg",

        "image/png" => "png",

        "image/webp" => "webp"

    ];


    if (
        !isset(
            $allowed_types[$mime_type]
        )
    ) {

        echo "<script>

            alert('Format foto harus JPG, JPEG, PNG, atau WEBP.');

            window.history.back();

        </script>";

        exit;

    }


    /* ==============================
       CEK FILE BENAR-BENAR GAMBAR
    ============================== */

    if (
        getimagesize($tmp_file) === false
    ) {

        echo "<script>

            alert('File yang dipilih bukan gambar yang valid.');

            window.history.back();

        </script>";

        exit;

    }


    /* ==============================
       BUAT NAMA FILE
    ============================== */

    $ekstensi =
        $allowed_types[$mime_type];


    $nama_foto_baru =
        "user_" .
        $id_user .
        "_" .
        time() .
        "." .
        $ekstensi;


    $tujuan_file =
        $folder_upload .
        $nama_foto_baru;


    /* ==============================
       PINDAHKAN FILE
    ============================== */

    if (
        !move_uploaded_file(
            $tmp_file,
            $tujuan_file
        )
    ) {

        echo "<script>

            alert('Foto gagal disimpan ke server.');

            window.history.back();

        </script>";

        exit;

    }

}


/* =====================================================
   AMBIL FOTO LAMA
===================================================== */

$foto_lama = "";


$query_foto_lama = mysqli_query(
    $conn,
    "
    SELECT foto
    FROM users
    WHERE id = '$id_user'
    LIMIT 1
    "
);


if ($query_foto_lama) {

    $data_foto_lama =
        mysqli_fetch_assoc(
            $query_foto_lama
        );


    $foto_lama =
        $data_foto_lama['foto']
        ?? "";

}


/* =====================================================
   UPDATE DATA
===================================================== */

if ($password_baru !== '') {


    /* ==============================
       HASH PASSWORD
    ============================== */

    $password_hash =
        password_hash(
            $password_baru,
            PASSWORD_DEFAULT
        );


    $password_hash =
        mysqli_real_escape_string(
            $conn,
            $password_hash
        );


    if ($nama_foto_baru !== "") {

        $nama_foto_baru_db =
            mysqli_real_escape_string(
                $conn,
                $nama_foto_baru
            );


        $query = mysqli_query(
            $conn,
            "
            UPDATE users
            SET
                nama_lengkap = '$nama_lengkap',
                username = '$username',
                password = '$password_hash',
                foto = '$nama_foto_baru_db'
            WHERE id = '$id_user'
            "
        );

    } else {

        $query = mysqli_query(
            $conn,
            "
            UPDATE users
            SET
                nama_lengkap = '$nama_lengkap',
                username = '$username',
                password = '$password_hash'
            WHERE id = '$id_user'
            "
        );

    }


} else {


    if ($nama_foto_baru !== "") {

        $nama_foto_baru_db =
            mysqli_real_escape_string(
                $conn,
                $nama_foto_baru
            );


        $query = mysqli_query(
            $conn,
            "
            UPDATE users
            SET
                nama_lengkap = '$nama_lengkap',
                username = '$username',
                foto = '$nama_foto_baru_db'
            WHERE id = '$id_user'
            "
        );

    } else {

        $query = mysqli_query(
            $conn,
            "
            UPDATE users
            SET
                nama_lengkap = '$nama_lengkap',
                username = '$username'
            WHERE id = '$id_user'
            "
        );

    }

}


/* =====================================================
   HASIL UPDATE
===================================================== */

if ($query) {


    /* ==============================
       HAPUS FOTO LAMA
       JIKA ADA FOTO BARU
    ============================== */

    if (
        $nama_foto_baru !== ""
        &&
        $foto_lama !== ""
    ) {

        $file_lama =
            $folder_upload .
            basename($foto_lama);


        if (
            file_exists($file_lama)
        ) {

            unlink($file_lama);

        }

    }


    /* ==============================
       UPDATE SESSION
    ============================== */

    $_SESSION['nama'] =
        $nama_lengkap;


    $_SESSION['nama_lengkap'] =
        $nama_lengkap;


    $_SESSION['username'] =
        $username;


    /* ==============================
       BERHASIL
    ============================== */

    echo "<script>

        alert('Profil berhasil diperbarui!');

        window.location.href='profil.php';

    </script>";

    exit;


} else {


    /* Jika database gagal,
       hapus foto baru yang sudah terupload */

    if (
        $nama_foto_baru !== ""
    ) {

        $file_baru =
            $folder_upload .
            basename($nama_foto_baru);


        if (
            file_exists($file_baru)
        ) {

            unlink($file_baru);

        }

    }


    echo "<script>

        alert('Gagal memperbarui profil: " .
        addslashes(mysqli_error($conn)) .
        "');

        window.history.back();

    </script>";

    exit;

}

?>