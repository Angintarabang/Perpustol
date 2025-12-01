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

// Fungsi bantu untuk menghindari Undefined index/key warnings
function get_data($array, $key, $default = '-') {
<<<<<<< HEAD
=======
    // Menggunakan Null Coalescing Operator (PHP 7.0+) atau isset()
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
    return isset($array[$key]) ? $array[$key] : $default;
}

// Ambil aturan denda HANYA SEKALI
<<<<<<< HEAD
$set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting = 1");
if (!$set || mysqli_num_rows($set) == 0) {
    $d = ['denda_per_hari' => 5000, 'maks_denda' => 50000];
} else {
    $d = mysqli_fetch_array($set);
}

// HITUNG DENDA OTOMATIS untuk transaksi yang statusnya 'Dipinjam'
$hitung_denda_query = "
    UPDATE tbtransaksi 
    SET denda = 
        CASE 
            WHEN DATEDIFF(CURDATE(), tglkembali) > 0 AND status_pengembalian = 'Dipinjam' THEN
                LEAST(DATEDIFF(CURDATE(), tglkembali) * {$d['denda_per_hari']}, {$d['maks_denda']})
            ELSE 0
        END
    WHERE status_pengembalian = 'Dipinjam'
";

if (!mysqli_query($db, $hitung_denda_query)) {
    // Error handling jika update gagal
}
=======
$set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting=1");
$d = mysqli_fetch_array($set);
$maks_pinjam = $d['maks_hari_pinjam'] ?? 7; 
$denda_per_hari = $d['denda_per_hari'] ?? 5000;
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
?>

<div id="label-page"><h3>Laporan Anggota yang Melakukan Pengembalian</h3></div>

<div id="content">

<<<<<<< HEAD
<!-- Form Filter -->
<div style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;">
    <!-- ... (Form Filter HTML tetap sama) ... -->
    <h3>Filter Laporan</h3>
    <form method="get">
        <input type="hidden" name="p" value="laporan-transaksi">
        <table id="tabel-input">
            <tr>
                <td class="label-formulir">Status</td>
                <td class="isian-formulir">
                    <select name="status" class="isian-formulir isian-formulir-border">
                        <option value="">Semua Status</option>
                        <option value="Dipinjam" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Dipinjam') ? 'selected' : ''; ?>>Belum Kembali</option>
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
=======
<?php
// Query 1: Statistik Pengembalian Anggota (Menggunakan idanggota sebagai kunci)
// Kunci Statistik menggunakan idanggota saja karena ini adalah agregasi
$sql = "SELECT 
        a.idanggota,
        a.nama,
        a.jeniskelamin,
        a.alamat,
        COUNT(p.idpengembalian) as total_pengembalian,
        SUM(p.denda) as total_denda,
        MAX(p.tglkembali) as terakhir_kembali
        FROM tbanggota a
        LEFT JOIN tbpengembalian p ON a.idanggota = p.idanggota
        GROUP BY a.idanggota
        HAVING total_pengembalian > 0
        ORDER BY total_pengembalian DESC";
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286

$query = mysqli_query($db, $sql);
if (!debug_query($db, $query, "Statistik Pengembalian")) { $query = false; }


if($query && mysqli_num_rows($query) > 0) {
?>
<table id="tabel-tampil">
    <tr>
        <th>No</th>
        <th>ID Anggota</th>
        <th>Nama</th>
        <th>Jenis Kelamin</th>
        <th>Alamat</th>
        <th>Total Pengembalian</th>
        <th>Total Denda</th>
        <th>Terakhir Kembali</th>
    </tr>
    
    <?php
    $nomor = 1;
    $total_keseluruhan = 0;
    $total_denda_keseluruhan = 0;
    
    while ($data = mysqli_fetch_array($query)) {
        $total_keseluruhan += $data['total_pengembalian'];
        $total_denda_keseluruhan += $data['total_denda'];
    ?>
    <tr>
        <td><?= $nomor++; ?></td>
        <td><?= htmlspecialchars($data['idanggota']); ?></td>
        <td><?= htmlspecialchars($data['nama']); ?></td>
        <td><?= htmlspecialchars($data['jeniskelamin']); ?></td>
        <td><?= htmlspecialchars($data['alamat']); ?></td>
        <td style="text-align: center; font-weight: bold;">
            <?= $data['total_pengembalian']; ?>
        </td>
        <td style="color: <?= $data['total_denda'] > 0 ? 'red' : 'green'; ?>; font-weight: bold;">
            Rp <?= number_format($data['total_denda'], 0, ',', '.'); ?>
        </td>
        <td><?= $data['terakhir_kembali'] ? htmlspecialchars($data['terakhir_kembali']) : '-'; ?></td>
    </tr>
    <?php } ?>
    
    <tr style="background-color: #f0f0f0; font-weight: bold;">
        <td colspan="5" style="text-align: right;">TOTAL:</td>
        <td style="text-align: center;"><?= $total_keseluruhan; ?></td>
        <td style="color: red;">Rp <?= number_format($total_denda_keseluruhan, 0, ',', '.'); ?></td>
        <td></td>
    </tr>
</table>

<?php
} else {
    echo "<p style='text-align:center; padding:20px;'>Belum ada anggota yang melakukan pengembalian</p>";
}
?>

<h3 style="margin-top: 30px;">Laporan Seluruh Transaksi Peminjaman dan Pengembalian</h3>

<?php
// Query 2: Laporan Seluruh Transaksi (SAFE VERSION + MENGAMBIL STATUS)
$sql_transaksi = "SELECT 
               t.idtransaksi,
               t.tglpinjam,
               t.tglkembali as batas_waktu, 
               t.status_pengembalian, -- DIKEMBALIKAN!
               a.nama as nama_anggota,
               b.judulbuku,
               p.tglkembali as tgl_dikembalikan, 
               p.denda
               FROM tbtransaksi t
               JOIN tbanggota a ON t.idanggota = a.idanggota
               JOIN tbbuku b ON t.idbuku = b.idbuku
               LEFT JOIN tbpengembalian p ON t.idtransaksi = p.idtransaksi
               ORDER BY t.tglpinjam DESC";

$query_transaksi = mysqli_query($db, $sql_transaksi);

if (!debug_query($db, $query_transaksi, "Laporan Transaksi Umum")) { $query_transaksi = false; }


if($query_transaksi && mysqli_num_rows($query_transaksi) > 0) {
?>

<table id="tabel-tampil">
    <tr>
        <th>No</th>
        <th>ID Transaksi</th>
        <th>Anggota</th>
        <th>Buku</th>
        <th>Tgl Pinjam</th>
        <th>Batas Waktu</th>
        <th>Tgl Kembali</th>
        <th>Status</th>
        <th>Denda</th>
    </tr>
<<<<<<< HEAD
    <?php
    // Build query dengan filter
    $where = "1=1";
    
    if(isset($_GET['status']) && $_GET['status'] != '') {
        $where .= " AND t.status_pengembalian = '".mysqli_real_escape_string($db, $_GET['status'])."'";
    }
    if(isset($_GET['tgl_mulai']) && $_GET['tgl_mulai'] != '') {
        $where .= " AND t.tglpinjam >= '".mysqli_real_escape_string($db, $_GET['tgl_mulai'])."'";
    }
    if(isset($_GET['tgl_sampai']) && $_GET['tgl_sampai'] != '') {
        $where .= " AND t.tglpinjam <= '".mysqli_real_escape_string($db, $_GET['tgl_sampai'])."'";
    }
    if(isset($_GET['ada_denda']) && $_GET['ada_denda'] != '') {
        if($_GET['ada_denda'] == 'ya') {
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
            p.tglkembali as tgl_dikembalikan_aktual -- Tgl kembali dari tabel pengembalian
        FROM tbtransaksi t 
        JOIN tbanggota a ON t.idanggota = a.idanggota 
        JOIN tbbuku b ON t.idbuku = b.idbuku 
        LEFT JOIN tbpengembalian p ON t.idtransaksi = p.idtransaksi
        WHERE $where
        ORDER BY t.tglpinjam DESC
    ");
    
    $total_denda = 0;
    while($data = mysqli_fetch_array($qry)) {
        
        $batas_waktu = $data['tglkembali']; // Batas Waktu Pinjam
        $denda = $data['denda']; 
        $tgl_kembali_aktual = $data['tgl_dikembalikan_aktual']; 
        
        $keterlambatan = '-';
        $tgl_display = ($tgl_kembali_aktual != null) ? $tgl_kembali_aktual : '-'; 
        
        // Hitung Keterlambatan
        if($data['status_pengembalian'] == 'Dipinjam') {
             // Hitung telat berdasarkan CURDATE (hari ini)
             if ($batas_waktu < date('Y-m-d')) {
                $telat_hari = date_diff(date_create($batas_waktu), date_create(date('Y-m-d')))->format('%a');
                $keterlambatan = $telat_hari . ' hari';
             }
        } elseif($data['status_pengembalian'] == 'Sudah Kembali') {
            // Hitung telat saat pengembalian dilakukan (Batas Waktu vs Tgl Kembali Aktual)
            if ($batas_waktu < $tgl_kembali_aktual) {
                $telat_hari = date_diff(date_create($batas_waktu), date_create($tgl_kembali_aktual))->format('%a');
                $keterlambatan = $telat_hari . ' hari';
            }
            $tgl_display = $tgl_kembali_aktual; // Jika sudah kembali, tampilkan tanggal aktual
        }
        
        $warna_status = ($data['status_pengembalian'] == 'Dipinjam') ? 'style="color: red; font-weight: bold;"' : 'style="color: green; font-weight: bold;"';
        $warna_denda = ($denda > 0) ? 'style="color: red; font-weight: bold;"' : '';
        
        $total_denda += $denda;
    ?>
    <tr>
        <td><?php echo $data['idtransaksi']; ?></td>
        <td><?php echo $data['nama_anggota']; ?></td>
        <td><?php echo $data['judulbuku']; ?></td>
        <td><?php echo $data['tglpinjam']; ?></td>
        <td><?php echo $batas_waktu; ?></td>
        <td><?php echo $tgl_display; ?></td>
        <td <?php echo $warna_status; ?>><?php echo $data['status_pengembalian']; ?></td>
        <td><?php echo $keterlambatan; ?></td>
        <td <?php echo $warna_denda; ?>>Rp <?php echo number_format($denda, 0, ',', '.'); ?></td>
=======
<?php
    $nomor_transaksi = 1;
    while ($data_transaksi = mysqli_fetch_array($query_transaksi)) {
        
        // Ambil status dari tbtransaksi
        $status_display = get_data($data_transaksi, 'status_pengembalian', 'Dipinjam'); 
        
        // Cek denda dari tbpengembalian (jika NULL, anggap 0)
        $denda_val = get_data($data_transaksi, 'denda', 0);
        $batas_waktu_val = get_data($data_transaksi, 'batas_waktu', '-');
        
        // Jika sudah dikembalikan, pakai tgl_dikembalikan, jika belum, pakai '-'
        $tgl_kembali_val = get_data($data_transaksi, 'tgl_dikembalikan', '-'); 
?>
    <tr>
        <td><?= $nomor_transaksi++; ?></td>
        <td><?= htmlspecialchars($data_transaksi['idtransaksi']); ?></td>
        <td><?= htmlspecialchars($data_transaksi['nama_anggota']); ?></td>
        <td><?= htmlspecialchars($data_transaksi['judulbuku']); ?></td>
        <td><?= htmlspecialchars($data_transaksi['tglpinjam']); ?></td>
        <td><?= htmlspecialchars($batas_waktu_val); ?></td>
        <td><?= htmlspecialchars($tgl_kembali_val); ?></td>
        <td><?= htmlspecialchars($status_display); ?></td>
        <td style="color: <?= $denda_val > 0 ? 'red' : 'green'; ?>;">
            Rp <?= number_format($denda_val, 0, ',', '.'); ?>
        </td>
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
    </tr>
<?php
    }
?>
</table>
<<<<<<< HEAD

<!-- Statistik -->
<div style="margin-top: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;">
    <h3>Statistik Transaksi</h3>
    <?php
    $total_transaksi = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) as total FROM tbtransaksi t WHERE $where"))['total'];
    $belum_kembali = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) as total FROM tbtransaksi t WHERE status_pengembalian = 'Dipinjam' AND $where"))['total'];
    $denda_aktif = mysqli_fetch_array(mysqli_query($db, "SELECT COALESCE(SUM(denda), 0) as total FROM tbtransaksi t WHERE status_pengembalian = 'Dipinjam' AND $where"))['total'];
    ?>
    <p>Total Transaksi: <strong><?php echo $total_transaksi; ?></strong></p>
    <p>Belum Dikembalikan: <strong style="color: red;"><?php echo $belum_kembali; ?></strong></p>
    <p>Total Denda Aktif (Belum Kembali): <strong style="color: red;">Rp <?php echo number_format($denda_aktif, 0, ',', '.'); ?></strong></p>
    <p>Total Denda (Semua Filter): <strong>Rp <?php echo number_format($total_denda, 0, ',', '.'); ?></strong></p>
</div>
=======
<?php
} else {
    echo "<p style='text-align:center; padding:20px;'>Tidak ada data transaksi</p>";
}
?>
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286

</div>