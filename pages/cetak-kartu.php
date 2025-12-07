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
        /* RESET & PRINT SETTINGS */
        body { 
            margin: 0; padding: 0; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0; /* Background layar abu biar kartu kelihatan */
            -webkit-print-color-adjust: exact;
        }
        
        /* CARD CONTAINER (UKURAN ID CARD STANDAR: 8.6cm x 5.4cm) */
        .id-card {
            width: 8.6cm;
            height: 5.4cm;
            background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden;
            color: #fff;
            margin: 50px auto; /* Tengah di layar */
            border: 1px solid #ffd700; /* Border Emas */
        }

        /* WATERMARK / PATTERN BACKGROUND */
        .id-card::before {
            content: 'CHAOS LIBRARY';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 2em;
            color: rgba(255, 215, 0, 0.05);
            font-weight: 900;
            white-space: nowrap;
            pointer-events: none;
        }

        /* HEADER KARTU */
        .header {
            background: linear-gradient(90deg, #ffd700, #b8860b);
            height: 1.2cm;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header h2 {
            margin: 0;
            color: #000;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
        }

        /* ISI KARTU */
        .content {
            display: flex;
            padding: 10px;
            height: calc(100% - 1.2cm);
            align-items: center;
        }

        /* FOTO */
        .photo-area {
            width: 2.5cm;
            height: 3cm;
            background: #fff;
            border: 2px solid #ffd700;
            border-radius: 5px;
            overflow: hidden;
            margin-right: 15px;
        }
        .photo-area img {
            width: 100%; height: 100%; object-fit: cover;
        }

        /* DATA DIRI */
        .data-area {
            flex: 1;
            font-size: 8pt;
            line-height: 1.4;
        }
        .data-row {
            margin-bottom: 2px;
        }
        .label {
            color: #aaa;
            font-size: 7pt;
            display: block;
        }
        .value {
            font-weight: bold;
            color: #fff;
            font-size: 9pt;
            text-transform: uppercase;
        }
        .id-number {
            color: #ffd700;
            font-size: 10pt;
            margin-bottom: 5px;
            display: block;
            letter-spacing: 1px;
        }

        /* STYLE KHUSUS PRINT */
        @media print {
            body { background: none; margin: 0; }
            .id-card { margin: 0; box-shadow: none; page-break-inside: avoid; }
        }
    </style>
</head>
<body>

    <div class="id-card">
        <div class="header">
            <h2>MEMBER CARD</h2>
        </div>
        <div class="content">
            <div class="photo-area">
                <img src="<?php echo $foto; ?>" onerror="this.src='../images/avatar-default.png'">
            </div>
            <div class="data-area">
                <span class="label">ID MEMBER</span>
                <span class="value id-number"><?php echo $r_tampil_anggota['idanggota']; ?></span>
                
                <div class="data-row">
                    <span class="label">NAMA LENGKAP</span>
                    <span class="value"><?php echo $r_tampil_anggota['nama']; ?></span>
                </div>
                <div class="data-row">
                    <span class="label">ALAMAT</span>
                    <span class="value" style="font-size: 7pt;"><?php echo substr($r_tampil_anggota['alamat'], 0, 30); ?></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>