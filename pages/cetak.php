<?php
include "../koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Anggota - Chaos Library</title>
    <style type="text/css">
        body { font-family: Arial, sans-serif; }
        h3 { text-align: center; text-transform: uppercase; margin-bottom: 5px; }
        p { text-align: center; margin-top: 0; font-size: 12px; color: #555; }
        
        table {
            border-collapse: collapse; /* BIAR GARIS NYAMBUNG RAPI */
            width: 100%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th {
            background-color: #ddd;
            padding: 10px;
            text-align: center;
        }
        td {
            padding: 8px;
            vertical-align: middle;
        }
        .center { text-align: center; }
        
        /* Foto agar pas di kertas */
        .foto-cetak {
            width: 50px; 
            height: 50px; 
            object-fit: cover; 
            border-radius: 50%;
            border: 1px solid #333;
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