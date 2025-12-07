<?php
include "../koneksi.php";
if(isset($_POST['simpan'])){
    $id_transaksi = $_POST['id_transaksi'];
    $id_anggota = $_POST['id_anggota'];
    $id_buku = $_POST['id_buku'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];

    $sql = "INSERT INTO tbtransaksi (idtransaksi, idanggota, idbuku, tglpinjam, tglkembali, status_pengembalian) 
            VALUES ('$id_transaksi', '$id_anggota', '$id_buku', '$tgl_pinjam', '$tgl_kembali', 'Dipinjam')";
    $query = mysqli_query($db, $sql);

    if($query){
        mysqli_query($db, "UPDATE tbbuku SET status='Dipinjam' WHERE idbuku='$id_buku'");
        mysqli_query($db, "UPDATE tbanggota SET status='Sedang Meminjam' WHERE idanggota='$id_anggota'");
        header("Location: ../index.php?p=transaksi-peminjaman");
    } else {
        echo "Gagal: " . mysqli_error($db);
    }
}
?>