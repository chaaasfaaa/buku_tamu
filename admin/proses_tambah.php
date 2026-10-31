<?php

include "../config/database.php";

$nama = $_POST['nama_tamu'];
$jk = $_POST['jenis_kelamin'];
$instansi = $_POST['instansi'];
$no = $_POST['no_hp'];
$keperluan = $_POST['keperluan'];
$tujuan = $_POST['tujuan'];

$tanggal = date("Y-m-d");
$jam = date("H:i:s");

$query = mysqli_query($conn, "
INSERT INTO tamu
(
id_user,
nama_tamu,
jenis_kelamin,
instansi,
no_hp,
keperluan,
tujuan,
tanggal,
jam_masuk,
created_at
)
VALUES
(
1,
'$nama',
'$jk',
'$instansi',
'$no',
'$keperluan',
'$tujuan',
'$tanggal',
'$jam',
NOW()
)
");

if($query){
    header("Location: data_tamu.php");
}else{
    echo mysqli_error($conn);
}

?>