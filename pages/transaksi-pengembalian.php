<?php
include "koneksi.php";
?>

<div id="label-page"><h3>Transaksi Pengembalian Buku</h3></div>

<div id="content">

    <div class="tombol-tambah-container" style="margin-bottom: 20px;">
        <a href="index.php?p=transaksi-pengembalian-input" class="tombol">Tambah Pengembalian</a>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table id="tabel-tampil" style="width: 100%; min-width: 100%;">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>ID Kembali</th>
                    <th>ID Transaksi</th>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Detail Tanggal</th>
                    <th>Denda & Status</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>

            <?php
            $sql = "SELECT 
                        p.*, 
                        a.nama as nama_anggota, 
                        b.judulbuku, 
                        t.tglpinjam, 
                        t.tglkembali AS tgl_rencana
                    FROM tbpengembalian p
                    JOIN tbanggota a ON p.idanggota = a.idanggota
                    JOIN tbbuku b ON p.idbuku = b.idbuku
                    JOIN tbtransaksi t ON p.idtransaksi = t.idtransaksi 
                    ORDER BY p.idpengembalian DESC";

            $query = mysqli_query($db, $sql);

            // Ambil Limit Denda
            $q_limit = mysqli_fetch_array(mysqli_query($db, "SELECT maks_denda, denda_per_hari FROM tbdenda LIMIT 1"));
            $limit_maksimal = $q_limit['maks_denda'];
            $tarif_harian = $q_limit['denda_per_hari'];

            if(mysqli_num_rows($query) > 0) {
                $nomor = 1;
                while ($data = mysqli_fetch_array($query)) {
                    
                    // Hitung Ulang Telat (Buat Display)
                    $telat = 0;
                    $time_rencana = strtotime($data['tgl_rencana']);
                    $time_nyata = strtotime($data['tglkembali']);
                    
                    if ($time_nyata > $time_rencana) {
                        $selisih = $time_nyata - $time_rencana;
                        $telat = floor($selisih / (60 * 60 * 24)); 
                    }

                    // Logika Capping Visual (Biar di tabel tetep keliatan max 100jt walau data lama error)
                    $denda_display = $data['denda'];
                    if($denda_display > $limit_maksimal){
                        $denda_display = $limit_maksimal; 
                    }

                    // Warna Warni
                    if ($telat > 0) {
                        $status_text = "Telat: " . $telat . " Hari";
                        $color_style = "color: #ff4d4d;";
                    } else {
                        $status_text = "Tepat Waktu";
                        $color_style = "color: #00ff80;";
                    }
            ?>
            <tr>
                <td style="text-align: center;"><?= $nomor++; ?></td>
                
                <!-- ID KEMBALI (AUTO INCREMENT) -->
                <td style="text-align: center; font-weight: bold; color: var(--gold-primary);">
                    #<?= $data['idpengembalian']; ?>
                </td>

                <td style="text-align: center;"><?= htmlspecialchars($data['idtransaksi']); ?></td>
                <td>
                    <div style="font-weight: bold; color: var(--gold-primary);"><?= htmlspecialchars($data['idanggota']); ?></div>
                    <?= htmlspecialchars($data['nama_anggota']); ?>
                </td>
                <td>
                    <div style="font-weight: bold; color: var(--gold-primary);"><?= htmlspecialchars($data['idbuku']); ?></div>
                    <?= htmlspecialchars($data['judulbuku']); ?>
                </td>
                <td style="font-size: 0.9em;">
                    Pinjam: <span style="color: #ccc;"><?= $data['tglpinjam']; ?></span><br>
                    Rencana: <span style="color: #ffff00; font-style: italic;"><?= $data['tgl_rencana']; ?></span><br>
                    Nyata: <strong style="color: #fff; border-bottom: 1px solid #777;"><?= $data['tglkembali']; ?></strong>
                </td>
                <td style="<?= $color_style; ?> font-weight: bold;">
                    <div style="margin-bottom: 3px;"><?= $status_text; ?></div>
                    Rp <?= number_format($denda_display, 0, ',', '.'); ?>
                </td>
                <td style="text-align: center;">
                    <a href="#" 
   onclick="konfirmasiHapus(event, 'proses/transaksi-pengembalian-hapus.php?id=<?= $data['idpengembalian']; ?>', 'Data Pengembalian #<?= $data['idpengembalian']; ?>')"
   class="tombol" style="background: linear-gradient(135deg, #500000, #300000); color: #ffcccc; padding: 8px 15px; font-size: 0.8em;">
   HAPUS
</a>
                </td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='8' style='text-align:center; padding:30px; font-style: italic; color: #ccc;'>Belum ada riwayat pengembalian.</td></tr>";
            } 
            ?>
            </tbody>
        </table>
    </div>
</div>