<?php
include "koneksi.php";
$id = $_GET['id'];
mysqli_query($db, "DELETE FROM tbbuku WHERE idbuku='$id'");
header("location: ../index.php?p=buku");
?>
