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


/* =====================================================
   AMBIL DATA USER
===================================================== */

$query = mysqli_query(
    $conn,
    "
    SELECT id, nama_lengkap, username, foto
    FROM users
    WHERE id = '$id_user'
    LIMIT 1
    "
);


if (!$query) {

    die(
        "Query error: " .
        mysqli_error($conn)
    );

}


$user = mysqli_fetch_assoc($query);


if (!$user) {

    session_destroy();

    header("Location: ../auth/login.php");
    exit;

}


$nama_user =
    $user['nama_lengkap']
    ?? "";


$username =
    $user['username']
    ?? "";


$foto =
    $user['foto']
    ?? "";


/* =====================================================
   TENTUKAN FOTO PROFIL
===================================================== */

$foto_path = "";

if (
    !empty($foto) &&
    file_exists("../uploads/profil/" . $foto)
) {

    $foto_path =
        "../uploads/profil/" . htmlspecialchars($foto);

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

    <title>
        Profil Saya - Buku Tamu
    </title>


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


    <!-- =================================================
         CSS SIDEBAR USER
    ================================================= -->

    <link
        rel="stylesheet"
        href="../assets/css/sidebar_user.css?v=1000"
    >


    <!-- =================================================
         CSS PROFIL
    ================================================= -->

    <link
        rel="stylesheet"
        href="../assets/css/profil.css?v=1200"
    >


    <!-- =================================================
         CSS FOTO PROFIL
    ================================================= -->

    <style>

        .profile-photo-section {

            display: flex;

            align-items: center;

            gap: 22px;

            margin-bottom: 28px;

            padding: 18px;

            background: #fff7fb;

            border: 1px solid #f3dce7;

            border-radius: 18px;

        }


        .profile-photo {

            width: 100px;

            height: 100px;

            border-radius: 50%;

            object-fit: cover;

            border: 5px solid #f5dbe7;

            box-shadow:
                0 8px 20px rgba(199,127,163,.18);

        }


        .profile-photo-placeholder {

            width: 100px;

            height: 100px;

            border-radius: 50%;

            background: #f5dbe7;

            color: #c77fa3;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 38px;

            border: 5px solid #f9eaf1;

            box-shadow:
                0 8px 20px rgba(199,127,163,.18);

        }


        .profile-photo-info {

            flex: 1;

        }


        .profile-photo-info h3 {

            margin: 0 0 5px;

            color: #352b40;

            font-size: 16px;

        }


        .profile-photo-info p {

            margin: 0 0 12px;

            color: #927c8c;

            font-size: 12px;

        }


        .photo-upload {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 9px 15px;

            background: #cf8bab;

            color: white;

            border-radius: 10px;

            cursor: pointer;

            font-size: 12px;

            font-weight: 600;

            transition: .3s;

        }


        .photo-upload:hover {

            transform: translateY(-2px);

            background: #c77fa3;

        }


        .photo-upload input {

            display: none;

        }


        .photo-name {

            display: block;

            margin-top: 7px;

            color: #a88c9b;

            font-size: 10px;

        }


        @media(max-width:600px) {

            .profile-photo-section {

                flex-direction: column;

                text-align: center;

            }

        }

    </style>

</head>


<body>


    <!-- =================================================
         SIDEBAR USER
    ================================================= -->

    <?php include "sidebar.php"; ?>


    <!-- =================================================
         CONTENT
    ================================================= -->

    <main class="content">


        <!-- =================================================
             TOP HEADER
        ================================================= -->

        <div class="topbar">


            <!-- SEARCH -->

            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <span>
                    Cari di sini...
                </span>

            </div>


            <!-- RIGHT -->

            <div class="top-right">


                <!-- TANGGAL -->

                <div class="top-card">

                    <div class="top-card-icon">

                        <i class="fa-solid fa-calendar-days"></i>

                    </div>


                    <div>

                        <strong id="tanggal">

                            <?= date("d F Y"); ?>

                        </strong>


                        <small id="hari">

                            <?= date("l"); ?>

                        </small>

                    </div>

                </div>


                <!-- JAM -->

                <div class="top-card">

                    <div class="top-card-icon">

                        <i class="fa-solid fa-clock"></i>

                    </div>


                    <div>

                        <small>
                            Jam Aktif
                        </small>


                        <strong id="jam">

                            <?= date("H:i:s"); ?>

                        </strong>

                    </div>

                </div>


                <!-- USER -->

                <div class="user-top">

                    <?php if ($foto_path !== "") { ?>

                        <img
                            src="<?= $foto_path; ?>"
                            alt="Foto Profil"
                            style="
                                width:32px;
                                height:32px;
                                border-radius:50%;
                                object-fit:cover;
                            "
                        >

                    <?php } else { ?>

                        <i class="fa-solid fa-user"></i>

                    <?php } ?>

                    <i class="fa-solid fa-chevron-down"></i>

                </div>


            </div>

        </div>


        <!-- =================================================
             PROFILE BANNER
        ================================================= -->

        <section class="profile-header">


            <div class="profile-header-left">


                <div class="profile-header-icon">

                    <i class="fa-solid fa-user"></i>

                </div>


                <div>

                    <h1>
                        Profil Saya
                    </h1>


                    <p>
                        Kelola informasi akun Anda.
                    </p>

                </div>


            </div>


            <!-- DECORATION -->

            <div class="profile-art">


                <div class="profile-paper">

                    <div class="paper-user">

                        <i class="fa-solid fa-user"></i>

                    </div>


                    <div class="paper-line line-one"></div>

                    <div class="paper-line line-two"></div>

                </div>


                <div class="profile-gear">

                    <i class="fa-solid fa-gear"></i>

                </div>


                <span class="spark spark-one">

                    <i class="fa-solid fa-wand-magic-sparkles"></i>

                </span>


                <span class="spark spark-two">

                    <i class="fa-solid fa-minus"></i>

                </span>


                <span class="spark spark-three">

                    <i class="fa-solid fa-minus"></i>

                </span>


            </div>


        </section>


        <!-- =================================================
             PROFILE CARD
        ================================================= -->

        <section class="profile-card">


            <!-- TITLE -->

            <div class="profile-title">


                <div class="profile-title-icon">

                    <i class="fa-solid fa-user-pen"></i>

                </div>


                <div>

                    <h2>
                        Informasi Akun
                    </h2>


                    <p>
                        Anda dapat mengubah informasi akun di bawah ini.
                    </p>

                </div>


            </div>


            <!-- =================================================
                 FORM
            ================================================= -->

            <form
                action="proses_profil.php"
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- =================================================
                     FOTO PROFIL
                ================================================= -->

                <div class="profile-photo-section">


                    <?php if ($foto_path !== "") { ?>

                        <img
                            src="<?= $foto_path; ?>"
                            alt="Foto Profil"
                            class="profile-photo"
                            id="previewFoto"
                        >

                    <?php } else { ?>

                        <div
                            class="profile-photo-placeholder"
                            id="previewPlaceholder"
                        >

                            <i class="fa-solid fa-user"></i>

                        </div>

                        <img
                            src=""
                            alt="Foto Profil"
                            class="profile-photo"
                            id="previewFoto"
                            style="display:none;"
                        >

                    <?php } ?>


                    <div class="profile-photo-info">

                        <h3>
                            Foto Profil
                        </h3>

                        <p>
                            Gunakan foto JPG, JPEG, PNG, atau WEBP.
                            Maksimal 2 MB.
                        </p>


                        <label class="photo-upload">

                            <i class="fa-solid fa-camera"></i>

                            Pilih Foto

                            <input
                                type="file"
                                name="foto"
                                id="foto"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >

                        </label>


                        <span
                            class="photo-name"
                            id="photoName"
                        >
                            <?php
                            if ($foto !== "") {
                                echo htmlspecialchars($foto);
                            } else {
                                echo "Belum ada foto dipilih";
                            }
                            ?>
                        </span>

                    </div>


                </div>


                <!-- =================================================
                     NAMA
                ================================================= -->

                <div class="form-group">

                    <label>

                        <i class="fa-solid fa-user"></i>

                        Nama Lengkap

                    </label>


                    <div class="input-wrapper">

                        <i class="fa-solid fa-user"></i>


                        <input
                            type="text"
                            name="nama_lengkap"
                            value="<?= htmlspecialchars($nama_user); ?>"
                            required
                        >

                    </div>

                </div>


                <!-- =================================================
                     USERNAME
                ================================================= -->

                <div class="form-group">

                    <label>

                        <i class="fa-solid fa-at"></i>

                        Username

                    </label>


                    <div class="input-wrapper">

                        <i class="fa-solid fa-at"></i>


                        <input
                            type="text"
                            name="username"
                            value="<?= htmlspecialchars($username); ?>"
                            required
                        >

                    </div>

                </div>


                <!-- =================================================
                     PASSWORD
                ================================================= -->

                <div class="form-group">

                    <label>

                        <i class="fa-solid fa-lock"></i>

                        Password Baru

                    </label>


                    <div class="input-wrapper password-wrapper">

                        <i class="fa-solid fa-lock"></i>


                        <input
                            type="password"
                            name="password_baru"
                            id="password"
                            placeholder="Kosongkan jika tidak ingin mengubah password"
                        >


                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            title="Tampilkan password"
                        >

                            <i class="fa-regular fa-eye"></i>

                        </button>

                    </div>

                </div>


                <!-- =================================================
                     BUTTON
                ================================================= -->

                <div class="button-area">


                    <button
                        type="submit"
                        class="btn-save"
                    >

                        <i class="fa-solid fa-floppy-disk"></i>

                        Simpan Perubahan

                    </button>


                    <a
                        href="dashboard.php"
                        class="btn-dashboard"
                    >

                        <i class="fa-solid fa-house"></i>

                        Dashboard

                    </a>


                </div>


            </form>


        </section>


        <!-- =================================================
             FOOTER
        ================================================= -->

        <footer>

            <span></span>

            © <?= date("Y"); ?>

            Buku Tamu Digital SMK Pelita

            <span></span>

        </footer>


    </main>


    <!-- =================================================
         DATE TIME
    ================================================= -->

    <script>

        function updateDateTime() {

            const now = new Date();


            const hari = [

                "Minggu",
                "Senin",
                "Selasa",
                "Rabu",
                "Kamis",
                "Jumat",
                "Sabtu"

            ];


            const bulan = [

                "Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "November",
                "Desember"

            ];


            const tanggal =

                String(
                    now.getDate()
                ).padStart(2, "0")

                + " "

                + bulan[
                    now.getMonth()
                ]

                + " "

                + now.getFullYear();


            const jam =

                String(
                    now.getHours()
                ).padStart(2, "0")

                + ":"

                + String(
                    now.getMinutes()
                ).padStart(2, "0")

                + ":"

                + String(
                    now.getSeconds()
                ).padStart(2, "0");


            document.getElementById(
                "tanggal"
            ).textContent = tanggal;


            document.getElementById(
                "hari"
            ).textContent =
                hari[
                    now.getDay()
                ];


            document.getElementById(
                "jam"
            ).textContent = jam;

        }


        updateDateTime();


        setInterval(
            updateDateTime,
            1000
        );

    </script>


    <!-- =================================================
         PREVIEW FOTO
    ================================================= -->

    <script>

        const fotoInput =
            document.getElementById("foto");

        const previewFoto =
            document.getElementById("previewFoto");

        const previewPlaceholder =
            document.getElementById("previewPlaceholder");

        const photoName =
            document.getElementById("photoName");


        if (fotoInput) {

            fotoInput.addEventListener(
                "change",
                function() {

                    const file =
                        this.files[0];

                    if (!file) {
                        return;
                    }


                    if (file.size > 2 * 1024 * 1024) {

                        alert(
                            "Ukuran foto maksimal 2 MB."
                        );

                        this.value = "";

                        return;
                    }


                    const allowedTypes = [

                        "image/jpeg",
                        "image/png",
                        "image/webp"

                    ];


                    if (
                        !allowedTypes.includes(
                            file.type
                        )
                    ) {

                        alert(
                            "Format foto harus JPG, JPEG, PNG, atau WEBP."
                        );

                        this.value = "";

                        return;
                    }


                    const reader =
                        new FileReader();


                    reader.onload =
                        function(event) {

                            previewFoto.src =
                                event.target.result;

                            previewFoto.style.display =
                                "block";


                            if (
                                previewPlaceholder
                            ) {

                                previewPlaceholder.style.display =
                                    "none";

                            }

                        };


                    reader.readAsDataURL(file);


                    photoName.textContent =
                        file.name;

                }
            );

        }

    </script>


    <!-- =================================================
         TOGGLE PASSWORD
    ================================================= -->

    <script>

        const password =
            document.getElementById(
                "password"
            );


        const togglePassword =
            document.getElementById(
                "togglePassword"
            );


        if (
            password &&
            togglePassword
        ) {

            togglePassword.addEventListener(
                "click",
                function() {


                    const icon =
                        togglePassword.querySelector(
                            "i"
                        );


                    if (
                        password.type ===
                        "password"
                    ) {

                        password.type =
                            "text";


                        icon.classList.remove(
                            "fa-eye"
                        );


                        icon.classList.add(
                            "fa-eye-slash"
                        );


                        togglePassword.title =
                            "Sembunyikan password";


                    } else {

                        password.type =
                            "password";


                        icon.classList.remove(
                            "fa-eye-slash"
                        );


                        icon.classList.add(
                            "fa-eye"
                        );


                        togglePassword.title =
                            "Tampilkan password";

                    }

                }
            );

        }

    </script>


</body>

</html>