<?php
include "koneksi.php";

// Fungsi untuk debugging MySQL
function debug_query($db, $query, $query_name = "Query") {
    if (!$query) {
        echo "<p style='color: red; font-weight: bold;'>!!! SQL ERROR ($query_name) !!!</p>";
        echo "<p>MySQL Error: " . mysqli_error($db) . "</p>";
        return false;
    }
    return true;
}

// Ambil aturan denda
$set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting = 1");
if (!$set || mysqli_num_rows($set) == 0) {
    $d = ['denda_per_hari' => 5000, 'maks_denda' => 50000, 'maks_hari_pinjam' => 7];
} else {
    $d = mysqli_fetch_array($set);
}

// CRITICAL FIX: HITUNG DENDA OTOMATIS (Hanya untuk transaksi yang BELUM KEMBALI dan sudah melewati batas waktu RENCANA)
// Ini harus dijalankan setiap kali laporan dibuka untuk menampilkan denda aktif
$hitung_denda_query = "
    UPDATE tbtransaksi 
    SET denda = 
        CASE 
            WHEN CURDATE() > tglkembali AND status_pengembalian = 'Dipinjam' THEN
                LEAST(
                    DATEDIFF(CURDATE(), tglkembali) * {$d['denda_per_hari']}, 
                    {$d['maks_denda']}
                )
            ELSE 0
        END
    WHERE status_pengembalian = 'Dipinjam'
";
mysqli_query($db, $hitung_denda_query);
?>

<div id="label-page"><h3>Laporan Seluruh Transaksi Peminjaman dan Pengembalian</h3></div>

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
                        <option value="Dipinjam" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Dipinjam') ? 'selected' : ''; ?>>Belum Kembali (Dipinjam)</option>
                        <option value="Sudah Kembali" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Sudah Kembali') ? 'selected' : ''; ?>>Sudah Kembali</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label-formulir">Tanggal Mulai (Pinjam)</td>
                <td class="isian-formulir">
                    <input type="date" name="tgl_mulai" value="<?php echo isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : ''; ?>" class="isian-formulir isian-formulir-border">
                </td>
            </tr>
            <tr>
                <td class="label-formulir">Tanggal Sampai (Pinjam)</td>
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

<!-- Logika Query Filter -->
<?php
$where = "1=1";
$filter_params = $_GET; 

if(isset($filter_params['status']) && $filter_params['status'] != '') {
    $where .= " AND t.status_pengembalian = '".mysqli_real_escape_string($db, $filter_params['status'])."'";
}
if(isset($filter_params['tgl_mulai']) && $filter_params['tgl_mulai'] != '') {
    $where .= " AND t.tglpinjam >= '".mysqli_real_escape_string($db, $filter_params['tgl_mulai'])."'";
}
if(isset($filter_params['tgl_sampai']) && $filter_params['tgl_sampai'] != '') {
    $where .= " AND t.tglpinjam <= '".mysqli_real_escape_string($db, $filter_params['tgl_sampai'])."'";
}
if(isset($filter_params['ada_denda']) && $filter_params['ada_denda'] != '') {
    if($filter_params['ada_denda'] == 'ya') {
        $where .= " AND t.denda > 0";
    } else {
        $where .= " AND t.denda = 0";
    }
}

// Query Utama: SELECT dari tbtransaksi
$qry = mysqli_query($db, "
    SELECT 
        t.*, 
        a.nama as nama_anggota, 
        b.judulbuku,
        p.tglkembali as tgl_dikembalikan_aktual 
    FROM tbtransaksi t 
    JOIN tbanggota a ON t.idanggota = a.idanggota 
    JOIN tbbuku b ON t.idbuku = b.idbuku 
    LEFT JOIN tbpengembalian p ON t.idtransaksi = p.idtransaksi
    WHERE $where
    ORDER BY t.tglpinjam DESC
");

if (!debug_query($db, $qry, "Laporan Transaksi Umum")) { $qry = false; }
?>

<table id="tabel-tampil">
    <tr>
        <th>No</th>
        <th>ID Transaksi</th>
        <th>Anggota</th>
        <th>Buku</th>
        <th>Tgl Pinjam</th>
        <th>Batas Waktu</th>
        <th>Tgl Kembali (Nyata)</th>
        <th>Status</th>
        <th>Terlambat (Hari)</th>
        <th>Denda</th>
    </tr>

    <?php
    $nomor = 1;
    $total_denda_laporan = 0;

    if($qry && mysqli_num_rows($qry) > 0) {
        while($data = mysqli_fetch_array($qry)) {
            
            $batas_waktu = $data['tglkembali']; // Tgl Rencana Kembali (dari tbtransaksi)
            $denda = $data['denda']; 
            $tgl_kembali_aktual = $data['tgl_dikembalikan_aktual']; 
            $tgl_pinjam = $data['tglpinjam'];
            
            $telat_display = '-';
            $tgl_display = ($tgl_kembali_aktual != null && $data['status_pengembalian'] == 'Sudah Kembali') ? $tgl_kembali_aktual : '-'; 
            
            // Logika Perhitungan Keterlambatan untuk Laporan
            if ($data['status_pengembalian'] == 'Dipinjam') {
                if (strtotime($batas_waktu) < strtotime(date('Y-m-d'))) {
                    $date_diff_obj = date_diff(date_create($batas_waktu), date_create(date('Y-m-d')));
                    $telat_display = $date_diff_obj->days . " (Aktif)";
                }
            } elseif ($data['status_pengembalian'] == 'Sudah Kembali' && $tgl_kembali_aktual) {
                if (strtotime($batas_waktu) < strtotime($tgl_kembali_aktual)) {
                    $date_diff_obj = date_diff(date_create($batas_waktu), date_create($tgl_kembali_aktual));
                    $telat_display = $date_diff_obj->days;
                }
            }
            
            $warna_status = ($data['status_pengembalian'] == 'Dipinjam') ? 'red' : 'green';
            $warna_denda = ($denda > 0) ? 'red' : 'green';
            
            $total_denda_laporan += $denda;
        ?>
        <tr>
            <td><?php echo $nomor++; ?></td>
            <td><?php echo htmlspecialchars($data['idtransaksi']); ?></td>
            <td><?php echo htmlspecialchars($data['nama_anggota']); ?></td>
            <td><?php echo htmlspecialchars($data['judulbuku']); ?></td>
            <td><?php echo htmlspecialchars($tgl_pinjam); ?></td>
            <td><?php echo htmlspecialchars($batas_waktu); ?></td>
            <td><?php echo $tgl_display; ?></td>
            <td style="color: <?php echo $warna_status; ?>; font-weight: bold;"><?php echo htmlspecialchars($data['status_pengembalian']); ?></td>
            <td><?php echo $telat_display; ?></td>
            <td style="color: <?php echo $warna_denda; ?>; font-weight: bold;">Rp <?php echo number_format($denda, 0, ',', '.'); ?></td>
        </tr>
        <?php
        }
    } else {
        echo "<tr><td colspan='10' style='text-align:center; padding:20px;'>Tidak ada data transaksi yang sesuai dengan filter.</td></tr>";
    }
    ?>
</table>

<!-- Statistik -->
<div style="margin-top: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;">
    <h3>Statistik Laporan</h3>
    <?php
    $total_transaksi = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) as total FROM tbtransaksi t WHERE $where"))['total'];
    $belum_kembali = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) as total FROM tbtransaksi t WHERE status_pengembalian = 'Dipinjam' AND $where"))['total'];
    
    $denda_aktif = mysqli_fetch_array(mysqli_query($db, "SELECT COALESCE(SUM(denda), 0) as total FROM tbtransaksi t WHERE status_pengembalian = 'Dipinjam' AND $where"))['total'];
    ?>
    <p>Total Transaksi dalam Filter: <strong><?php echo $total_transaksi; ?></strong></p>
    <p>Total Belum Dikembalikan dalam Filter: <strong style="color: red;"><?php echo $belum_kembali; ?></strong></p>
    <p>Total Denda (Keseluruhan Transaksi Tampil): <strong style="color: red;">Rp <?php echo number_format($total_denda_laporan, 0, ',', '.'); ?></strong></p>
    <p>Total Denda Aktif (Belum Kembali, Saat Ini): <strong style="color: red;">Rp <?php echo number_format($denda_aktif, 0, ',', '.'); ?></strong></p>
</div>


</div>