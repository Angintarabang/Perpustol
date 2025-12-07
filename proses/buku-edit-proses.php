<?php
// FILE: proses/buku-edit-proses.php
include "../koneksi.php";

if(isset($_POST['simpan'])){
    
    $id_buku = $_POST['id_buku'];
    $judul_buku = $_POST['judul_buku'];
    $kategori = $_POST['kategori'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];

    // Query Update Buku
    $sql = "UPDATE tbbuku SET 
            judulbuku='$judul_buku', 
            kategori='$kategori', 
            pengarang='$pengarang', 
            penerbit='$penerbit' 
            WHERE idbuku='$id_buku'";

    $query = mysqli_query($db, $sql);

    if($query){
        // SUKSES -> Pake JS Redirect (Lebih Aman)
        echo "<script>window.location='../index.php?p=buku';</script>";
    } else {
        // GAGAL
        echo "<h3>Gagal Update Data Buku!</h3>";
        echo "Error MySQL: " . mysqli_error($db);
        echo "<br><a href='../index.php?p=buku'>Kembali</a>";
    }
} else {
    // Kalau user iseng buka file ini langsung
    echo "<script>window.location='../index.php?p=buku';</script>";
}
?>