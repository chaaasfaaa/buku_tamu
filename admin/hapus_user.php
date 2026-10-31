<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
    header("Location:../auth/login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location:user.php");
    exit;
}

$id = $_GET['id'];

// Cegah admin menghapus akun sendiri
if($id == $_SESSION['id']){

    echo "
    <script>
    alert('Akun yang sedang digunakan tidak boleh dihapus!');
    window.location='user.php';
    </script>
    ";

    exit;
}

$hapus = mysqli_query($conn,"DELETE FROM users WHERE id='$id'");

if($hapus){

    echo "
    <script>
    alert('User berhasil dihapus');
    window.location='user.php';
    </script>
    ";

}else{

    echo "
    <script>
    alert('User gagal dihapus');
    window.location='user.php';
    </script>
    ";

}
?>