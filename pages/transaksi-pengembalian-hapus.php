<?php
include "../koneksi.php";

$id = $_GET['id'];

mysqli_query($db, "DELETE FROM tbpengembalian WHERE idpengembalian='$id'");

header("location: ../index.php?p=transaksi-pengembalian");
?>
