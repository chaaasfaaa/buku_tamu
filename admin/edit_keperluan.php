<?php


include "../config/database.php";


$id=$_GET['id'];


$data=mysqli_query($conn,


"SELECT * FROM keperluan WHERE id='$id'"

);


$d=mysqli_fetch_assoc($data);


?>


<!DOCTYPE html>
<html>


<head>

<title>
Edit Keperluan
</title>


<link rel="stylesheet" href="../assets/css/style.css">


</head>


<body>


<div class="content">


<h1>
Edit Keperluan
</h1>


<form action="proses_edit_keperluan.php" method="POST">


<input type="hidden"

name="id"

value="<?= $d['id']; ?>">



<input type="text"

name="nama_keperluan"

value="<?= $d['nama_keperluan']; ?>"

required>


<br><br>


<button class="btn">

Update

</button>



</form>



</div>


</body>


</html>