<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
    header("Location:../auth/login.php");
    exit;
}

$id = $_GET['id'];

$data = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
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

<h1>Edit User</h1>

<form action="proses_edit_user.php" method="POST">

<input type="hidden" name="id" value="<?= $user['id']; ?>">

<label>Nama Lengkap</label>
<input type="text" name="nama_lengkap" value="<?= $user['nama_lengkap']; ?>" required>

<label>Username</label>
<input type="text" name="username" value="<?= $user['username']; ?>" required>

<label>Password Baru</label>
<input type="password" name="password" placeholder="Kosongkan jika tidak diubah">

<label>Role</label>
<select name="role">
    <option value="admin" <?= $user['role']=="admin"?"selected":""; ?>>Admin</option>
    <option value="user" <?= $user['role']=="user"?"selected":""; ?>>User</option>
</select>

<button type="submit" class="btn">Simpan Perubahan</button>

</form>

</div>

</body>
</html>