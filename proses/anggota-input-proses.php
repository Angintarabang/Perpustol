<?php
// FILE: proses/anggota-input-proses.php
include "../koneksi.php"; // PENTING: Panggil koneksi database

// Cek apakah tombol simpan ditekan
if(isset($_POST['simpan'])){
    
    // Ambil data dari form
    $id_anggota = $_POST['id_anggota'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $status = "Tidak Meminjam"; // Default status

    // Proses Upload Foto
    if(isset($_FILES['foto']['name']) && $_FILES['foto']['name'] != "") {
        $foto_nama = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        // Pastikan nama foto unik biar gak bentrok (pake tanggal jam detik)
        $foto_baru = date('dmYHis').$foto_nama;
        $path = "../images/".$foto_baru; 

        // Cek folder images ada atau ngga, kalau ngga ada jangan upload
        if(move_uploaded_file($foto_tmp, $path)){
            $foto_fix = $foto_baru;
        } else {
            $foto_fix = "avatar-default.png"; 
        }
    } else {
        $foto_fix = "avatar-default.png"; 
    }

    // QUERY INSERT
    $sql = "INSERT INTO tbanggota (idanggota, nama, jeniskelamin, alamat, status, foto) 
            VALUES ('$id_anggota', '$nama', '$jenis_kelamin', '$alamat', '$status', '$foto_fix')";

    $query = mysqli_query($db, $sql);

    if($query){
        // Berhasil -> Balik ke halaman data anggota
        header("Location: ../index.php?p=anggota");
    } else {
        // Gagal (Tampilkan Error)
        echo "<div style='background:red; color:white; padding:20px; font-family:sans-serif;'>";
        echo "<h3>GAGAL MENYIMPAN DATA!</h3>";
        echo "<p>Error MySQL: " . mysqli_error($db) . "</p>";
        echo "<p>Query: " . $sql . "</p>";
        echo "<br><a href='../index.php?p=anggota-input' style='color:yellow; font-weight:bold;'>Kembali ke Form</a>";
        echo "</div>";
    }
}
?>