<?php


include "../config/database.php";


$nama=$_POST['nama_keperluan'];



mysqli_query($conn,


"INSERT INTO keperluan

VALUES

(NULL,'$nama')

"


);



header("location:keperluan.php");


?>