<?php
include "../koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Buku - Chaos Library</title>
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
    </style>
</head>
<body>

    <h3>Laporan Data Buku Perpustakaan Chaos Yohanes</h3>
    <p>Dicetak pada tanggal: <?php echo date("d F Y"); ?></p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>ID Buku</th>
                <th>Judul Buku</th>
                <th>Kategori</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM tbbuku ORDER BY idbuku ASC";
            $query = mysqli_query($db, $sql);
            $nomor = 1;
            while($r = mysqli_fetch_array($query)){
            ?>
            <tr>
                <td class="center"><?php echo $nomor++; ?></td>
                <td class="center"><?php echo $r['idbuku']; ?></td>
                <td><?php echo $r['judulbuku']; ?></td>
                <td><?php echo $r['kategori']; ?></td>
                <td><?php echo $r['pengarang']; ?></td>
                <td><?php echo $r['penerbit']; ?></td>
                <td class="center"><?php echo $r['status']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>