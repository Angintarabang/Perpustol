<?php
include "../koneksi.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan Data Buku</title>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 25px;
    color: #000;
}

/* Header */
.header {
    text-align: center;
    margin-bottom: 20px;
}

.header h2 {
    margin: 0;
    font-size: 20px;
    text-transform: uppercase;
}

.header p {
    margin: 2px;
    font-size: 12px;
}

/* Garis tebal */
.line {
    border-bottom: 2px solid #000;
    margin: 15px 0;
}

/* Tabel */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

table, th, td {
    border: 1px solid #000;
}

th {
    background: #eaeaea;
    font-size: 13px;
    padding: 8px;
}

td {
    font-size: 12px;
    padding: 6px;
}

/* Footer */
.footer {
    margin-top: 40px;
    width: 100%;
    font-size: 12px;
}

.footer .ttd {
    float: right;
    text-align: center;
    margin-right: 40px;
}

/* Print setting */
@media print {
    body {
        margin: 0;
    }

    .no-print {
        display: none;
    }
}
</style>
</head>

<body>

<div class="header">
    <h2>PERPUSTAKAAN UMUM</h2>
    <p>Jl. Lembah Abang No 11, Telp: (021) 5555555</p>
    <div class="line"></div>
    <h3>LAPORAN DATA BUKU</h3>
</div>

<table>
    <tr>
        <th width="10%">ID Buku</th>
        <th width="25%">Judul Buku</th>
        <th width="15%">Kategori</th>
        <th width="15%">Pengarang</th>
        <th width="15%">Penerbit</th>
        <th width="10%">Status</th>
    </tr>

    <?php
    $data = mysqli_query($db, "SELECT * FROM tbbuku ORDER BY idbuku ASC");
    while ($row = mysqli_fetch_array($data)) {
    ?>
    <tr>
        <td><?= $row['idbuku']; ?></td>
        <td><?= $row['judulbuku']; ?></td>
        <td><?= $row['kategori']; ?></td>
        <td><?= $row['pengarang']; ?></td>
        <td><?= $row['penerbit']; ?></td>
        <td><?= $row['status']; ?></td>
    </tr>
    <?php } ?>
</table>

<div class="footer">
    <div class="ttd">
        <p>Palangkaraya, ............ 20....</p>
        <br><br><br>
        <p><u><b>Kepala Perpustakaan</b></u></p>
    </div>
</div>

<script>
window.print();
</script>

</body>
</html>
