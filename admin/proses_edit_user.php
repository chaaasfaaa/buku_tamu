<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
    header("Location:../auth/login.php");
    exit;
}

$id = $_POST['id'];
$nama = $_POST['nama_lengkap'];
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

if($password==""){

    mysqli_query($conn,"
    UPDATE users SET
    nama_lengkap='$nama',
    username='$username',
    role='$role'
    WHERE id='$id'
    ");

}else{

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn,"
    UPDATE users SET
    nama_lengkap='$nama',
    username='$username',
    password='$password',
    role='$role'
    WHERE id='$id'
    ");

}

echo "
<script>
alert('Data user berhasil diubah');
window.location='user.php';
</script>
";
?>