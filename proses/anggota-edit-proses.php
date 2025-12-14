<?php
include "../koneksi.php";

// Cek apakah form dikirim (lewat input rahasia 'simpan')
if(isset($_POST['simpan'])){
    
    $id_anggota = $_POST['id_anggota'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];

    // --- CEK FOTO ---
    if(!empty($_FILES['foto']['name'])){
        
        $foto_nama = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        
        // Buat folder jika belum ada
        if (!file_exists("../images/")) { mkdir("../images/", 0777, true); }

        $foto_baru = date('dmYHis') . "_" . basename($foto_nama);
        $path = "../images/" . $foto_baru;

        if(move_uploaded_file($foto_tmp, $path)){
            
            // Hapus foto lama biar bersih
            $q = mysqli_query($db, "SELECT foto FROM tbanggota WHERE idanggota='$id_anggota'");
            $d = mysqli_fetch_array($q);
            if($d['foto'] != 'avatar-default.png' && file_exists("../images/".$d['foto'])){
                unlink("../images/".$d['foto']);
            }

            // Update dengan Foto
            $sql = "UPDATE tbanggota SET 
                    nama='$nama', jeniskelamin='$jenis_kelamin', alamat='$alamat', foto='$foto_baru' 
                    WHERE idanggota='$id_anggota'";
        } else {
            // Kalau gagal upload (misal permission), update data aja tanpa foto
            $sql = "UPDATE tbanggota SET nama='$nama', jeniskelamin='$jenis_kelamin', alamat='$alamat' WHERE idanggota='$id_anggota'";
        }

    } else {
        // Update Tanpa Foto
        $sql = "UPDATE tbanggota SET nama='$nama', jeniskelamin='$jenis_kelamin', alamat='$alamat' WHERE idanggota='$id_anggota'";
    }

    // --- EKSEKUSI ---
    $query = mysqli_query($db, $sql);

    if($query){
        echo "<script>window.location='../index.php?p=anggota';</script>";
    } else {
        echo "<h1>GAGAL UPDATE!</h1> Error: " . mysqli_error($db);
        echo "<br><a href='../index.php?p=anggota'>Kembali</a>";
    }

} else {
    // Kalau script ini dibuka langsung tanpa lewat form
    echo "Akses Ditolak. Form tidak terdeteksi.";
}
?>