<?php
include "../koneksi.php";

$id = $_GET['id'];

// ambil idbuku dari transaksi
$data = mysqli_fetch_array(mysqli_query($db, 
    "SELECT idbuku FROM tbtransaksi WHERE idtransaksi='$id'"
));

$idbuku = $data['idbuku'];

// hapus transaksi
mysqli_query($db, "DELETE FROM tbtransaksi WHERE idtransaksi='$id'");

// perbaiki status buku
mysqli_query($db, "UPDATE tbbuku SET status='Tersedia' WHERE idbuku='$idbuku'");

header("location: ../index.php?p=transaksi-peminjaman");
?>
