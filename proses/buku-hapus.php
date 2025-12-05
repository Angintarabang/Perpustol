<?php
// FILE: proses/buku-hapus.php
include "../koneksi.php";

// Cek apakah ada ID yang dikirim
if(isset($_GET['id'])){
    $id_buku = $_GET['id'];

    // QUERY HAPUS (INI YANG UDAH DIBENERIN, GAK ADA DOUBLE PETIK)
    $sql = "DELETE FROM tbbuku WHERE idbuku = '$id_buku'";
    $query = mysqli_query($db, $sql);

    if($query){
        // Berhasil -> Balik ke halaman buku
        header("Location: ../index.php?p=buku");
    } else {
        // Gagal
        echo "<h3>Gagal menghapus data!</h3>";
        echo "Error MySQL: " . mysqli_error($db);
        echo "<br><a href='../index.php?p=buku'>Kembali</a>";
    }
} else {
    // Kalau user akses file ini tanpa ID, tendang balik
    header("Location: ../index.php?p=buku");
}
?>