<?php
include "../koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Buku - Chaos Library</title>
    <style type="text/css">
        /* Reset CSS khusus Print */
        body { 
            font-family: 'Times New Roman', serif; 
            font-size: 12pt; 
            color: #000;
        }
        
        h3 { 
            text-align: center; 
            text-transform: uppercase; 
            margin-bottom: 5px;
            font-size: 16pt;
        }
        
        p { 
            text-align: center; 
            margin-top: 0; 
            font-size: 10pt; 
            font-style: italic;
        }
        
        /* Tabel Garis Tegas (Excel Style) */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        
        table, th, td {
            border: 1px solid black;
        }
        
        th {
            background-color: #e0e0e0 !important; /* Abu muda saat print */
            padding: 8px;
            text-align: center;
            font-weight: bold;
            -webkit-print-color-adjust: exact; /* Paksa warna background keluar */
        }
        
        td {
            padding: 5px 8px;
            vertical-align: middle;
        }
        
        .center { text-align: center; }
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