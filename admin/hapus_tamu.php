<?php


include "../config/database.php";


$id=$_GET['id'];


mysqli_query($conn,

"DELETE FROM tamu WHERE id='$id'"

);



header("location:data_tamu.php");


?>