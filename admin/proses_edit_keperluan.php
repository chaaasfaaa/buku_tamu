<?php


include "../config/database.php";


$id=$_POST['id'];

$nama=$_POST['nama_keperluan'];



mysqli_query($conn,


"UPDATE keperluan SET

nama_keperluan='$nama'

WHERE id='$id'

"


);



header("location:keperluan.php");


?>