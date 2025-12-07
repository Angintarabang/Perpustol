<?php
include "../koneksi.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Hapus data
    $query = mysqli_query($db, "DELETE FROM tbpengembalian WHERE idpengembalian='$id'");

    if($query){
        header("Location: ../index.php?p=transaksi-pengembalian");
    } else {
        echo "Gagal menghapus data! Error: " . mysqli_error($db);
    }
} else {
    header("Location: ../index.php?p=transaksi-pengembalian");
}
?>