<?php
session_start();

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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Buku Tamu Digital SMK Pelita</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet"
          href="../assets/css/login.css?v=101">

</head>

<body>

<div class="decor decor-one"></div>
<div class="decor decor-two"></div>
<div class="decor decor-three"></div>
<div class="decor decor-four"></div>

<div class="login-container">

    <div class="left-panel">

        <div class="brand">

            <div class="brand-icon">

                <i class="fa-solid fa-book-open"></i>

            </div>

            <span>Buku Tamu Digital SMK Pelita</span>

        </div>

        <div class="illustration-wrapper">

            <img
                src="../assets/images/login-book.png?v=102"
                alt="Ilustrasi Buku Tamu Digital SMK Pelita"
                class="illustration"
            >

        </div>

        <div class="welcome-content">

            <h2>Selamat Datang</h2>

            <p>
                Kelola data tamu dengan lebih cepat, aman dan modern
                melalui <strong>Buku Tamu Digital SMK Pelita.</strong>
            </p>

        </div>

        <div class="features">

            <div class="feature">

                <div class="feature-icon">

                    <i class="fa-solid fa-shield-halved"></i>

                </div>

                <div class="feature-text">

                    <strong>Aman</strong>

                    <small>
                        Data tamu tersimpan dengan aman.
                    </small>

                </div>

            </div>

            <div class="feature">

                <div class="feature-icon">

                    <i class="fa-solid fa-bolt"></i>

                </div>

                <div class="feature-text">

                    <strong>Cepat</strong>

                    <small>
                        Pendataan tamu lebih praktis.
                    </small>

                </div>

            </div>

            <div class="feature">

                <div class="feature-icon">

                    <i class="fa-solid fa-chart-simple"></i>

                </div>

                <div class="feature-text">

                    <strong>Modern</strong>

                    <small>
                        Mudah digunakan dan sederhana.
                    </small>

                </div>

            </div>

        </div>

    </div>

    <div class="right-panel">

        <div class="login-header">

            <h1>
                Hello <span>👋</span>
            </h1>

            <h2>
                Welcome Back
            </h2>

            <p>
                Silakan login untuk melanjutkan.
            </p>

        </div>

        <?php if (isset($_GET['pesan'])): ?>

            <?php

            $jenis = $_GET['jenis'] ?? 'error';

            $pesan = htmlspecialchars(
                $_GET['pesan'],
                ENT_QUOTES,
                'UTF-8'
            );

            ?>

            <div class="alert <?= $jenis === 'success' ? 'alert-success' : 'alert-error'; ?>">

                <div class="alert-content">

                    <strong>
                        <?= $jenis === 'success' ? 'Berhasil' : 'Perhatian'; ?>
                    </strong>

                    <span>
                        <?= $pesan; ?>
                    </span>

                </div>

            </div>

        <?php endif; ?>

        <form
            action="proses_login.php"
            method="POST"
            autocomplete="off"
        >

            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <div class="input-box">

                    <i class="fa-solid fa-user"></i>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                    >

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
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="lihatPassword()"
                        aria-label="Tampilkan password"
                    >

                        <i
                            id="eye"
                            class="fa-solid fa-eye"
                        ></i>

                    </button>

                </div>

            </div>

            <div class="remember">

                <label>

                    <input
                        type="checkbox"
                        name="remember"
                    >

                    <span>
                        Remember Me
                    </span>

                </label>

            </div>

            <button
                type="submit"
                class="btn-login"
            >

                <i class="fa-solid fa-right-to-bracket"></i>

                <span>
                    Login
                </span>

            </button>

            <div class="register">

                <span>
                    Belum punya akun?
                </span>

                <a href="register.php">
                    Daftar Sekarang
                </a>

            </div>

        </form>

        <div class="footer">

            <i class="fa-solid fa-shield-halved"></i>

            © <?= date("Y"); ?> Buku Tamu Digital SMK Pelita

        </div>

    </div>

</div>

<script>

function lihatPassword() {

    const password =
        document.getElementById("password");

    const eye =
        document.getElementById("eye");

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

</script>

</body>

</html>