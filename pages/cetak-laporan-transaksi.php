<?php
include "../koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi - Chaos Library</title>
    <style>
        /* --- CSS SAKTI BUAT CETAK --- */
        body { 
            font-family: 'Times New Roman', serif; 
            font-size: 11pt; 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
        }

        table {
            width: 100%;
            border-collapse: collapse; 
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black; 
        }

        th {
            background-color: #ddd !important; 
            font-weight: bold;
            text-align: center; 
            padding: 10px;
        }

        td {
            padding: 8px;
            text-align: center; 
            vertical-align: middle;
        }

        h3, p { text-align: center; margin: 5px 0; }
        
    </style> <!-- <--- INI DIA YANG TADI ILANG BRO! -->
</head>
<body>

    <h3>Laporan Transaksi Perpustakaan Chaos Yohanes</h3>
    <p style="text-align:center;">Dicetak: <?php echo date("d-m-Y H:i:s"); ?></p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>ID Transaksi</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil semua data transaksi (Join 3 tabel)
            $sql = "SELECT t.*, a.nama, b.judulbuku 
                    FROM tbtransaksi t
                    JOIN tbanggota a ON t.idanggota = a.idanggota
                    JOIN tbbuku b ON t.idbuku = b.idbuku
                    ORDER BY t.tglpinjam DESC";
            
            $query = mysqli_query($db, $sql);
            $no = 1;
            $total_denda = 0;

            while($r = mysqli_fetch_array($query)){
                $total_denda += $r['denda'];
            ?>
            <tr>
                <td class="center"><?php echo $no++; ?></td>
                <td class="center"><?php echo $r['idtransaksi']; ?></td>
                <td><?php echo $r['nama']; ?></td>
                <td><?php echo $r['judulbuku']; ?></td>
                <td class="center"><?php echo $r['tglpinjam']; ?></td>
                <td class="center"><?php echo $r['tglkembali']; ?></td>
                <td class="center"><?php echo $r['status_pengembalian']; ?></td>
                <td>Rp <?php echo number_format($r['denda'], 0, ',', '.'); ?></td>
            </tr>
            <?php } ?>
            
            <!-- Baris Total -->
            <tr>
                <td colspan="7" style="text-align: right; font-weight: bold;">TOTAL DENDA</td>
                <td style="font-weight: bold;">Rp <?php echo number_format($total_denda, 0, ',', '.'); ?></td>
            </tr>
        </tbody>
    </table>

    <script>window.print();</script>
</body>
</html>