<?php
include "../koneksi.php";
$id_anggota = $_GET['id'];
$q_tampil_anggota = mysqli_query($db, "SELECT * FROM tbanggota WHERE idanggota = '$id_anggota'");
$r_tampil_anggota = mysqli_fetch_array($q_tampil_anggota);

// Cek Foto
$foto = "../images/" . $r_tampil_anggota['foto'];
if(empty($r_tampil_anggota['foto']) || !file_exists($foto)) {
    $foto = "../images/avatar-default.png"; 
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kartu Anggota - <?php echo $r_tampil_anggota['nama']; ?></title>
    <style>
        /* RESET KHUSUS CETAK */
        body { 
            margin: 0; padding: 20px; 
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff;
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
        }
        
        /* CONTAINER KARTU (UKURAN KTP: 8.6cm x 5.4cm) */
        .id-card {
            width: 8.6cm;
            height: 5.4cm;
            background: #111; /* Hitam Pekat */
            border: 2px solid #ffd700; /* Border Emas */
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            color: #fff;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 10px rgba(0,0,0,0.5); /* Shadow cuma buat preview di layar */
        }

        /* HEADER EMAS */
        .header {
            background: linear-gradient(90deg, #b8860b, #ffd700);
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #fff;
        }
        .header h2 {
            margin: 0;
            color: #000;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ISI KARTU */
        .content {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background-image: linear-gradient(45deg, #1a1a1a 25%, #000 25%, #000 50%, #1a1a1a 50%, #1a1a1a 75%, #000 75%, #000 100%);
            background-size: 20px 20px; /* Pattern Background */
        }

        /* FOTO KIRI */
        .photo-area {
            width: 80px;
            height: 100px;
            border: 2px solid #ffd700;
            border-radius: 5px;
            overflow: hidden;
            margin-right: 15px;
            background: #fff;
        }
        .photo-area img {
            width: 100%; height: 100%; object-fit: cover;
        }

        /* DATA KANAN */
        .data-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .label {
            color: #888;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        
        .value {
            font-size: 11px;
            font-weight: bold;
            color: #fff;
            margin-bottom: 8px;
            text-transform: uppercase;
            border-bottom: 1px solid #444;
            padding-bottom: 2px;
            display: inline-block;
            width: 100%;
        }

        /* NOMOR ID SPESIAL */
        .id-number {
            color: #ffd700;
            font-size: 14px;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        /* WATERMARK */
        .watermark {
            position: absolute;
            bottom: 5px; right: 10px;
            font-size: 8px;
            color: #444;
            font-style: italic;
        }

        /* CSS PRINT: HILANGKAN MARGIN/SHADOW SAAT DICETAK */
        @media print {
            body { margin: 0; padding: 0; }
            .id-card { 
                box-shadow: none; 
                margin: 0; 
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact; 
            }
        }
    </style>
</head>
<body>

    <div class="id-card">
        <!-- HEADER -->
        <div class="header">
            <h2>CHAOS MEMBER</h2>
        </div>

        <!-- ISI -->
        <div class="content">
            <!-- FOTO -->
            <div class="photo-area">
                <img src="<?php echo $foto; ?>" onerror="this.src='../images/avatar-default.png'">
            </div>

            <!-- DATA -->
            <div class="data-area">
                
                <span class="label">ID Member</span>
                <span class="value id-number"><?php echo $r_tampil_anggota['idanggota']; ?></span>

                <span class="label">Nama Lengkap</span>
                <span class="value"><?php echo $r_tampil_anggota['nama']; ?></span>

                <span class="label">Alamat</span>
                <span class="value" style="border: none; margin-bottom: 0;">
                    <?php echo substr($r_tampil_anggota['alamat'], 0, 25); ?>...
                </span>

            </div>
        </div>
        
        <div class="watermark">Valid Member of Chaos Library</div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>