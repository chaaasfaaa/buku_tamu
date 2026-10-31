<?php

include "../config/database.php";

$id = $_POST['id'];
$nama = $_POST['nama_tamu'];
$jk = $_POST['jenis_kelamin'];
$instansi = $_POST['instansi'];
$no = $_POST['no_hp'];
$keperluan = $_POST['keperluan'];
$tujuan = $_POST['tujuan'];

$query = mysqli_query($conn, "
UPDATE tamu SET
nama_tamu='$nama',
jenis_kelamin='$jk',
instansi='$instansi',
no_hp='$no',
keperluan='$keperluan',
tujuan='$tujuan'
WHERE id='$id'
");

if($query){
    header("Location: data_tamu.php");
}else{
    echo mysqli_error($conn);
}

?>