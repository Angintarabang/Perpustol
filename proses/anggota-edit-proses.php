<?php
// FILE: proses/anggota-edit-proses.php
include "../koneksi.php";

if(isset($_POST['simpan'])){
    
    $id_anggota = $_POST['id_anggota'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    
    // Default Query (Tanpa Foto)
    $sql = "UPDATE tbanggota SET 
            nama='$nama', 
            jeniskelamin='$jenis_kelamin', 
            alamat='$alamat' 
            WHERE idanggota='$id_anggota'";

    // Cek apakah ada upload foto baru?
    if(isset($_FILES['foto']['name']) && $_FILES['foto']['name'] != ""){
        $foto_nama = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_baru = date('dmYHis').$foto_nama;
        $path = "../images/".$foto_baru;

        if(move_uploaded_file($foto_tmp, $path)){
            // Kalau upload sukses, timpa query jadi update foto juga
            $sql = "UPDATE tbanggota SET 
                    nama='$nama', 
                    jeniskelamin='$jenis_kelamin', 
                    alamat='$alamat', 
                    foto='$foto_baru' 
                    WHERE idanggota='$id_anggota'";
        }
    }

    // Eksekusi Query
    $query = mysqli_query($db, $sql);

    if($query){
        // SUKSES -> Pake JS biar gak white page
        echo "<script>window.location='../index.php?p=anggota';</script>";
    } else {
        // GAGAL
        echo "<h3>Gagal Update Data!</h3>";
        echo "Error: " . mysqli_error($db);
        echo "<br><a href='../index.php?p=anggota'>Kembali</a>";
    }
} else {
    // Kalau dibuka langsung tanpa tombol simpan
    echo "<script>window.location='../index.php?p=anggota';</script>";
}
?>