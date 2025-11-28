<?php
// Hitung denda otomatis
$setting = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting = 1"));
$hitung_denda = mysqli_query($db, "
    UPDATE tbtransaksi 
    SET denda = 
        CASE 
            WHEN DATEDIFF(CURDATE(), batas_waktu) > 0 AND status_pengembalian = 'Belum Kembali' THEN
                LEAST(DATEDIFF(CURDATE(), batas_waktu) * $setting[denda_per_hari], $setting[maks_denda])
            ELSE 0
        END
    WHERE status_pengembalian = 'Belum Kembali'
");
?>

<div id="label-page"><h3>Laporan Transaksi dengan Denda</h3></div>
<div id="content">

<!-- Form Filter -->
<div style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;">
    <h3>Filter Laporan</h3>
    <form method="get">
        <input type="hidden" name="p" value="laporan-transaksi">
        <table id="tabel-input">
            <tr>
                <td class="label-formulir">Status</td>
                <td class="isian-formulir">
                    <select name="status" class="isian-formulir isian-formulir-border">
                        <option value="">Semua Status</option>
                        <option value="Belum Kembali" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Belum Kembali') ? 'selected' : ''; ?>>Belum Kembali</option>
                        <option value="Sudah Kembali" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Sudah Kembali') ? 'selected' : ''; ?>>Sudah Kembali</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label-formulir">Tanggal Mulai</td>
                <td class="isian-formulir">
                    <input type="date" name="tgl_mulai" value="<?php echo isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : ''; ?>" class="isian-formulir isian-formulir-border">
                </td>
            </tr>
            <tr>
                <td class="label-formulir">Tanggal Sampai</td>
                <td class="isian-formulir">
                    <input type="date" name="tgl_sampai" value="<?php echo isset($_GET['tgl_sampai']) ? $_GET['tgl_sampai'] : ''; ?>" class="isian-formulir isian-formulir-border">
                </td>
            </tr>
            <tr>
                <td class="label-formulir">Ada Denda</td>
                <td class="isian-formulir">
                    <select name="ada_denda" class="isian-formulir isian-formulir-border">
                        <option value="">Semua</option>
                        <option value="ya" <?php echo (isset($_GET['ada_denda']) && $_GET['ada_denda'] == 'ya') ? 'selected' : ''; ?>>Ada Denda</option>
                        <option value="tidak" <?php echo (isset($_GET['ada_denda']) && $_GET['ada_denda'] == 'tidak') ? 'selected' : ''; ?>>Tidak Ada Denda</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" value="Filter" class="tombol">
                    <a href="index.php?p=laporan-transaksi" class="tombol">Reset</a>
                    <a href="cetak/cetak-laporan-transaksi.php?<?php echo http_build_query($_GET); ?>" target="_blank" class="tombol">Cetak Laporan</a>
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- Tabel Laporan -->
<table id="tabel-tampil">
    <tr>
        <th>ID Transaksi</th>
        <th>Anggota</th>
        <th>Buku</th>
        <th>Tgl Pinjam</th>
        <th>Batas Waktu</th>
        <th>Tgl Kembali</th>
        <th>Status</th>
        <th>Keterlambatan</th>
        <th>Denda</th>
    </tr>
    <?php
    // Build query dengan filter
    $where = "1=1";
    if(isset($_GET['status']) && $_GET['status'] != '') {
        $where .= " AND t.status_pengembalian = '$_GET[status]'";
    }
    if(isset($_GET['tgl_mulai']) && $_GET['tgl_mulai'] != '') {
        $where .= " AND t.tglpinjam >= '$_GET[tgl_mulai]'";
    }
    if(isset($_GET['tgl_sampai']) && $_GET['tgl_sampai'] != '') {
        $where .= " AND t.tglpinjam <= '$_GET[tgl_sampai]'";
    }
    if(isset($_GET['ada_denda']) && $_GET['ada_denda'] != '') {
        if($_GET['ada_denda'] == 'ya') {
            $where .= " AND t.denda > 0";
        } else {
            $where .= " AND t.denda = 0";
        }
    }
    
    $qry = mysqli_query($db, "
        SELECT t.*, a.nama as nama_anggota, b.judulbuku 
        FROM tbtransaksi t 
        JOIN tbanggota a ON t.idanggota = a.idanggota 
        JOIN tbbuku b ON t.idbuku = b.idbuku 
        WHERE $where
        ORDER BY t.tglpinjam DESC
    ");
    
    $total_denda = 0;
    while($data = mysqli_fetch_array($qry)) {
        $keterlambatan = '-';
        if($data['status_pengembalian'] == 'Belum Kembali' && $data['batas_waktu'] < date('Y-m-d')) {
            $keterlambatan = date_diff(date_create($data['batas_waktu']), date_create(date('Y-m-d')))->format('%a hari');
        } elseif($data['status_pengembalian'] == 'Sudah Kembali' && $data['batas_waktu'] < $data['tglkembali']) {
            $keterlambatan = date_diff(date_create($data['batas_waktu']), date_create($data['tglkembali']))->format('%a hari');
        }
        
        $warna_status = ($data['status_pengembalian'] == 'Belum Kembali') ? 'style="color: red; font-weight: bold;"' : 'style="color: green; font-weight: bold;"';
        $warna_denda = ($data['denda'] > 0) ? 'style="color: red; font-weight: bold;"' : '';
        
        $total_denda += $data['denda'];
    ?>
    <tr>
        <td><?php echo $data['idtransaksi']; ?></td>
        <td><?php echo $data['nama_anggota']; ?></td>
        <td><?php echo $data['judulbuku']; ?></td>
        <td><?php echo $data['tglpinjam']; ?></td>
        <td><?php echo $data['batas_waktu']; ?></td>
        <td><?php echo ($data['tglkembali'] == '0000-00-00') ? '-' : $data['tglkembali']; ?></td>
        <td <?php echo $warna_status; ?>><?php echo $data['status_pengembalian']; ?></td>
        <td><?php echo $keterlambatan; ?></td>
        <td <?php echo $warna_denda; ?>>Rp <?php echo number_format($data['denda'], 0, ',', '.'); ?></td>
    </tr>
    <?php } ?>
</table>

<!-- Statistik -->
<div style="margin-top: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;">
    <h3>Statistik Transaksi</h3>
    <?php
    $total_transaksi = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) as total FROM tbtransaksi WHERE $where"))['total'];
    $belum_kembali = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) as total FROM tbtransaksi WHERE status_pengembalian = 'Belum Kembali' AND $where"))['total'];
    $denda_aktif = mysqli_fetch_array(mysqli_query($db, "SELECT COALESCE(SUM(denda), 0) as total FROM tbtransaksi WHERE status_pengembalian = 'Belum Kembali' AND $where"))['total'];
    ?>
    <p>Total Transaksi: <strong><?php echo $total_transaksi; ?></strong></p>
    <p>Belum Dikembalikan: <strong style="color: red;"><?php echo $belum_kembali; ?></strong></p>
    <p>Total Denda Aktif: <strong style="color: red;">Rp <?php echo number_format($denda_aktif, 0, ',', '.'); ?></strong></p>
    <p>Total Denda (Semua): <strong>Rp <?php echo number_format($total_denda, 0, ',', '.'); ?></strong></p>
</div>

</div>