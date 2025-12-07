<?php
include "../koneksi.php";

if(isset($_POST['simpan'])){
    
    // Tangkap data dari form
    $id_buku = $_POST['id_buku'];
    $judul_buku = $_POST['judul_buku'];
    $kategori = $_POST['kategori'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $status = "Tersedia"; // Default status buku baru pasti Tersedia

    // Query Insert
    $sql = "INSERT INTO tbbuku (idbuku, judulbuku, kategori, pengarang, penerbit, status)
            VALUES ('$id_buku', '$judul_buku', '$kategori', '$pengarang', '$penerbit', '$status')";

    $query = mysqli_query($db, $sql);

    if($query){
        // Berhasil -> Balik ke halaman buku
        header("Location: ../index.php?p=buku");
    } else {
        // Gagal (Biasanya karena ID Kembar)
        echo "<div style='background: #121212; color: #ff4d4d; padding: 20px; font-family: sans-serif; border: 1px solid #ff4d4d;'>";
        echo "<h3>GAGAL MENYIMPAN DATA!</h3>";
        echo "<p>Error MySQL: " . mysqli_error($db) . "</p>";
        echo "<p>Kemungkinan ID Buku <b>'$id_buku'</b> sudah ada.</p>";
        echo "<br><a href='../index.php?p=buku-input' style='color: #ffda47;'>Kembali ke Form</a>";
        echo "</div>";
    }
}
?>