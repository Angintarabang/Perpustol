<?php
include "../koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Anggota - Chaos Library</title>
    <style type="text/css">
       /* --- CSS SAKTI BUAT CETAK --- */
body { 
    font-family: 'Times New Roman', serif; 
    font-size: 11pt; /* Ukuran font standar */
    -webkit-print-color-adjust: exact; /* Chrome/Edge */
    print-color-adjust: exact; /* Firefox */
}

table {
    width: 100%;
    border-collapse: collapse; /* Biar garis nyatu */
    margin-top: 20px;
}

table, th, td {
    border: 1px solid black; /* Garis hitam tegas */
}

th {
    background-color: #ddd !important; /* Paksa warna abu keluar */
    font-weight: bold;
    text-align: center; /* Judul Kolom Rata Tengah */
    padding: 10px;
}

td {
    padding: 8px;
    text-align: center; /* ISI TABEL RATA TENGAH SEMUA */
    vertical-align: middle;
}

/* Khusus kolom Nama/Judul biar rata kiri aja (opsional, kalau mau tengah semua hapus ini) */
/* td:nth-child(3) { text-align: left; padding-left: 10px; } */

h3, p { text-align: center; margin: 5px 0; }
/* TAMBAHAN WAJIB BIAR FOTO GAK RAKSASA */
.foto-cetak {
    width: 60px;       /* Lebar Foto Pas */
    height: 60px;      /* Tinggi Foto Pas */
    object-fit: cover; /* Biar foto gak gepeng */
    border-radius: 5px; /* Biar gak tajem sudutnya */
    border: 1px solid #000;
    display: block;
    margin: 0 auto;    /* Tengahin foto */
}
    </style>
</head>
<body>

    <h3>Laporan Data Anggota Perpustakaan Yohanes</h3>
    <p>Dicetak pada: <?php echo date("d-m-Y H:i:s"); ?></p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>ID Anggota</th>
                <th>Nama</th>
                <th>Foto</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM tbanggota ORDER BY idanggota ASC";
            $query = mysqli_query($db, $sql);
            $nomor = 1;
            while($r = mysqli_fetch_array($query)){
                
                // LOGIKA CEK FOTO BIAR GAK PECAH DI PRINT
                // Kita harus mundur satu folder (../) untuk akses images dari folder pages
                $path_foto = "../images/" . $r['foto'];
                
                if(!empty($r['foto']) && file_exists($path_foto)) {
                    $img_src = $path_foto;
                } else {
                    $img_src = "../images/avatar-default.png"; // Pastikan ada gambar default
                }
            ?>
            <tr>
                <td class="center"><?php echo $nomor++; ?></td>
                <td class="center"><?php echo $r['idanggota']; ?></td>
                <td><?php echo $r['nama']; ?></td>
                <td class="center">
                    <!-- Tampilkan foto -->
                    <img src="<?php echo $img_src; ?>" class="foto-cetak" onerror="this.style.display='none'"> 
                </td>
                <td class="center"><?php echo $r['jeniskelamin']; ?></td>
                <td><?php echo $r['alamat']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>