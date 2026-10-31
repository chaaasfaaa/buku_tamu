<?php
session_start();

/* ==========================================
   CEK JIKA SUDAH LOGIN
========================================== */

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] === "admin") {
        header("Location: ../admin/dashboard.php");
        exit;
    }

    if ($_SESSION['role'] === "user") {
        header("Location: ../user/dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Daftar Akun | Buku Tamu Digital SMK Pelita</title>

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link
        rel="stylesheet"
        href="../assets/css/register.css?v=100">

</head>

<body>

<div class="decor decor-one"></div>
<div class="decor decor-two"></div>
<div class="decor decor-three"></div>
<div class="decor decor-four"></div>

<div class="register-container">

    <div class="left-panel">

        <div class="brand">

            <div class="brand-icon">

                <i class="fa-solid fa-book-open"></i>

            </div>

            <span>
                Buku Tamu Digital SMK Pelita
            </span>

        </div>

        <div class="illustration-wrapper">

            <img
                src="../assets/images/login-book.png?v=102"
                alt="Ilustrasi Buku Tamu Digital SMK Pelita"
                class="illustration">

        </div>

        <div class="welcome-content">

            <h2>
                Mari Bergabung
            </h2>

            <p>
                Buat akun baru untuk mulai menggunakan
                <strong>Buku Tamu Digital SMK Pelita</strong>
                dengan mudah, cepat dan praktis.
            </p>

        </div>

        <div class="features">

            <div class="feature">

                <div class="feature-icon">
                    <i class="fa-solid fa-user-plus"></i>
                </div>

                <div class="feature-text">

                    <strong>
                        Daftar Mudah
                    </strong>

                    <small>
                        Buat akun dengan cepat.
                    </small>

                </div>

            </div>

            <div class="feature">

                <div class="feature-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>

                <div class="feature-text">

                    <strong>
                        Aman
                    </strong>

                    <small>
                        Data akun tersimpan dengan aman.
                    </small>

                </div>

            </div>

            <div class="feature">

                <div class="feature-icon">
                    <i class="fa-solid fa-bolt"></i>
                </div>

                <div class="feature-text">

                    <strong>
                        Praktis
                    </strong>

                    <small>
                        Kelola buku tamu lebih mudah.
                    </small>

                </div>

            </div>

        </div>

    </div>

    <div class="right-panel">

        <div class="register-header">

            <h1>
                Hai <span>👋</span>
            </h1>

            <h2>
                Daftar Sekarang
            </h2>

            <p>
                Silakan membuat akun untuk melanjutkan.
            </p>

        </div>

        <?php if (isset($_GET['pesan'])): ?>

        <div class="alert alert-error" id="alertRegister">

            <div class="alert-icon">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>

            <div class="alert-content">

                <strong>Perhatian</strong>

                <span>
                    <?= htmlspecialchars($_GET['pesan']); ?>
                </span>

            </div>

            <button
                type="button"
                class="alert-close"
                onclick="tutupAlert()"
                aria-label="Tutup pesan">

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>

        <?php endif; ?>

        <form
            action="proses_register.php"
            method="POST"
            autocomplete="off">

            <div class="form-group">

                <label for="nama">
                    Nama Lengkap
                </label>

                <div class="input-box">

                    <i class="fa-solid fa-user"></i>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        placeholder="Masukkan nama lengkap"
                        autocomplete="name"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <div class="input-box">

                    <i class="fa-solid fa-user-tag"></i>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Buat username"
                        autocomplete="username"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <div class="input-box">

                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Buat password"
                        autocomplete="new-password"
                        required>

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="lihatPassword('password', 'eyePassword')"
                        aria-label="Tampilkan password">

                        <i
                            id="eyePassword"
                            class="fa-solid fa-eye">
                        </i>

                    </button>

                </div>

            </div>

            <div class="form-group">

                <label for="konfirmasi">
                    Konfirmasi Password
                </label>

                <div class="input-box">

                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        id="konfirmasi"
                        name="konfirmasi"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                        required>

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="lihatPassword('konfirmasi', 'eyeKonfirmasi')"
                        aria-label="Tampilkan konfirmasi password">

                        <i
                            id="eyeKonfirmasi"
                            class="fa-solid fa-eye">
                        </i>

                    </button>

                </div>

            </div>

            <button
                type="submit"
                class="btn-register">

                <i class="fa-solid fa-user-plus"></i>

                <span>
                    Daftar Sekarang
                </span>

            </button>

            <div class="login-link">

                <span>
                    Sudah punya akun?
                </span>

                <a href="login.php">
                    Login di sini
                </a>

            </div>

        </form>

        <div class="footer">

            <i class="fa-solid fa-shield-halved"></i>

            © <?= date("Y"); ?>

            Buku Tamu Digital SMK Pelita

        </div>

    </div>

</div>

<script>

function lihatPassword(inputId, eyeId) {

    const password =
        document.getElementById(inputId);

    const eye =
        document.getElementById(eyeId);

    if (password.type === "password") {

        password.type = "text";

        eye.classList.remove("fa-eye");

        eye.classList.add("fa-eye-slash");

    } else {

        password.type = "password";

        eye.classList.remove("fa-eye-slash");

        eye.classList.add("fa-eye");

    }

}

function tutupAlert() {

    const alertBox =
        document.getElementById("alertRegister");

    if (alertBox) {
        alertBox.style.display = "none";
    }

}

</script>

</body>

</html>