<?php


$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_buku_tamu";


$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);



if(!$conn){

    die("Database gagal terkoneksi : ".mysqli_connect_error());

}


?>