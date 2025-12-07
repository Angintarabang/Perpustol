<?php
include "../koneksi.php";

$id = $_GET['id'];

// 1. Ambil data dulu (PENTING)
$cek = mysqli_query($db, "SELECT * FROM tbtransaksi WHERE idtransaksi='$id'");
$data = mysqli_fetch_array($cek);

if($data){
    $idbuku = $data['idbuku'];
    $idanggota = $data['idanggota']; // Ambil ID Anggota juga

    // 2. Balikin Status Buku -> Tersedia
    mysqli_query($db, "UPDATE tbbuku SET status='Tersedia' WHERE idbuku='$idbuku'");

    // 3. Balikin Status Anggota -> Tidak Meminjam (INI YANG KURANG TADI)
    mysqli_query($db, "UPDATE tbanggota SET status='Tidak Meminjam' WHERE idanggota='$idanggota'");
}

// 4. Baru Hapus Transaksinya
mysqli_query($db, "DELETE FROM tbtransaksi WHERE idtransaksi='$id'");

header("location: ../index.php?p=transaksi-peminjaman");
?>