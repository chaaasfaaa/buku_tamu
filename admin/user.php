<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
    header("Location:../auth/login.php");
    exit;
}

$data=mysqli_query($conn,"SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>

<title>Kelola User</title>

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

<h1>Kelola User</h1>

<a href="tambah_user.php" class="btn">
+ Tambah User
</a>

<table>

<tr>

<th>No</th>
<th>Nama</th>
<th>Username</th>
<th>Role</th>
<th>Aksi</th>

</tr>

<?php

$no=1;

while($d=mysqli_fetch_assoc($data)){

?>

<tr>

<td><?= $no++; ?></td>

<td><?= $d['nama_lengkap']; ?></td>

<td><?= $d['username']; ?></td>

<td><?= ucfirst($d['role']); ?></td>

<td>

<a href="edit_user.php?id=<?= $d['id']; ?>">Edit</a>

|

<a href="hapus_user.php?id=<?= $d['id']; ?>"
onclick="return confirm('Hapus user?')">

Hapus

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>