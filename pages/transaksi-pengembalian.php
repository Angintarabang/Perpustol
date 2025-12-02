<?php
include "koneksi.php";
?>

<div id="label-page"><h3>Transaksi Pengembalian Buku</h3></div>

<div id="content">

    <!-- Tombol Tambah -->
    <div class="tombol-tambah-container" style="margin-bottom: 20px;">
        <a href="index.php?p=transaksi-pengembalian-input" class="tombol">Tambah Pengembalian</a>
    </div>

    <!-- WRAPPER BIAR AMAN & FULL WIDTH -->
    <div style="overflow-x: auto; width: 100%;">
        
        <!-- PAKSA WIDTH 100% DISINI -->
        <table id="tabel-tampil" style="width: 100%; min-width: 100%;">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>ID Kembali</th>
                    <th>ID Transaksi</th>
                    <th style="width: 20%;">Anggota</th>
                    <th style="width: 25%;">Buku</th>
                    <th style="width: 20%;">Detail Tanggal</th>
                    <th style="width: 15%;">Denda & Status</th>
                    <th style="width: 10%;">Opsi</th>
                </tr>
            </thead>
            <tbody>

            <?php
            // QUERY MANTAP - Mengambil data pengembalian dengan detail lengkap
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

            if(mysqli_num_rows($query) > 0) {
                $nomor = 1;

                while ($data = mysqli_fetch_array($query)) {
                    $tgl_rencana = $data['tgl_rencana'];
                    $tgl_kembali_nyata = $data['tglkembali'];
                    
                    $telat = 0;
                    if (strtotime($tgl_kembali_nyata) > strtotime($tgl_rencana)) {
                        $telat = floor((strtotime($tgl_kembali_nyata) - strtotime($tgl_rencana)) / (60*60*24));
                    }

                    // Warna Chaos Neon
                    $warna_denda = ($data['denda'] > 0) ? '#ff4d4d' : '#00ff80'; // Merah Neon vs Hijau Neon
                    $teks_denda = ($data['denda'] > 0) ? 'Terlambat' : 'Tepat Waktu';
            ?>
            <tr>
                <td style="text-align: center;"><?= $nomor++; ?></td>
                <td style="text-align: center;"><?= htmlspecialchars($data['idpengembalian']); ?></td>
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
                    Pinjam: <span style="color: #ccc;"><?= htmlspecialchars($data['tglpinjam']); ?></span><br>
                    Rencana: <span style="color: #999; font-style: italic;"><?= htmlspecialchars($tgl_rencana); ?></span><br>
                    Nyata: <strong style="color: #fff; border-bottom: 1px solid #555;"><?= htmlspecialchars($tgl_kembali_nyata); ?></strong>
                </td>
                <td style="color: <?= $warna_denda; ?>; font-weight: bold;">
                    <div style="margin-bottom: 3px;"><?= $teks_denda; ?>: <?= $telat; ?> hari</div>
                    Rp <?= number_format($data['denda'], 0, ',', '.'); ?>
                </td>
                <td style="text-align: center;">
                    <a class="tombol" 
                       style="background: linear-gradient(135deg, #500000, #300000); color: #ffcccc; padding: 8px 15px; font-size: 0.8em;"
                       href="pages/transaksi-pengembalian-hapus.php?id=<?= $data['idpengembalian']; ?>" 
                       onclick="return confirm('Hapus data pengembalian ini? Warning: Data akan hilang permanen dari sistem.')">
                       HAPUS
                    </a>
                </td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='8' style='text-align:center; padding:30px; font-style: italic; color: #777;'>Belum ada riwayat pengembalian buku.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>