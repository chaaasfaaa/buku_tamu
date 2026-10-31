<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
    header("Location:../auth/login.php");
    exit;
}

if(isset($_POST['simpan'])){

    $nama     = mysqli_real_escape_string($conn,$_POST['nama']);
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $cek=mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");

    if(mysqli_num_rows($cek)>0){

        echo "<script>
        alert('Username sudah digunakan!');
        window.location='tambah_user.php';
        </script>";

    }else{

        mysqli_query($conn,"INSERT INTO users(nama_lengkap,username,password,role)
        VALUES('$nama','$username','$password','$role')");

        echo "<script>
        alert('User berhasil ditambahkan');
        window.location='user.php';
        </script>";

    }

}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tambah User</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="sidebar">

<h2>📖 Buku Tamu</h2>

<a href="dashboard.php">🏠 Dashboard</a>
<a href="user.php">👤 Kelola User</a>
<a href="data_tamu.php">👥 Data Tamu</a>
<a href="keperluan.php">📋 Keperluan</a>
<a href="pencarian.php">🔍 Pencarian</a>
<a href="laporan.php">🖨 Laporan</a>
<a href="../auth/logout.php">🚪 Logout</a>

</div>

<div class="content">

<h1>Tambah User</h1>

<form method="POST">

<label>Nama Lengkap</label>

<input
type="text"
name="nama"
required>

<label>Username</label>

<input
type="text"
name="username"
required>

<label>Password</label>

<input
type="password"
name="password"
required>

<label>Role</label>

<select name="role">

<option value="admin">Admin</option>

<option value="user">User</option>

</select>

<button type="submit" name="simpan" class="btn">
💾 Simpan
</button>

<a href="user.php" class="btn"
style="margin-top:10px;background:#64748b;">
⬅ Kembali
</a>

</form>

</div>

</body>
</html>