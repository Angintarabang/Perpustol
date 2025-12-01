<?php
include "koneksi.php";


// Fungsi bantu untuk menghindari Undefined index/key warnings
function get_data($array, $key, $default = '-') {
    return isset($array[$key]) ? $array[$key] : $default;
}
?>

<div id="label-page"><h3>Laporan Anggota yang Melakukan Pengembalian</h3></div>

<div id="content">

<?php
// Query 1: Statistik Pengembalian Anggota
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

$query = mysqli_query($db, $sql);

// PENTING: Cek hasil query untuk laporan pertama
if (!$query) {
    echo "<p style='color: red;'>ERROR SQL Laporan Anggota: " . mysqli_error($db) . "</p>";
    $query = false; // Set kembali ke false jika terjadi error, untuk mencegah crash
}


if($query && mysqli_num_rows($query) > 0) {
// ... (Bagian tabel Laporan Anggota tetap sama) ...
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

<!-- ========================================================================= -->
<!-- PERBAIKAN JOIN DAN PENANGANAN ERROR PADA LAPORAN TRANSAKSI -->
<!-- ========================================================================= -->

<h3 style="margin-top: 30px;">Laporan Seluruh Transaksi Peminjaman dan Pengembalian</h3>

<?php
// Query 2: Laporan Seluruh Transaksi
$sql_transaksi = "SELECT 
               t.idtransaksi,
               t.tglpinjam,
               t.tglkembali as batas_waktu,
               a.nama as nama_anggota,
               b.judulbuku,
               p.tglkembali as tgl_dikembalikan, 
               p.denda
               FROM tbtransaksi t
               JOIN tbanggota a ON t.idanggota = a.idanggota
               JOIN tbbuku b ON t.idbuku = b.idbuku
               
               -- PERBAIKAN JOIN: Menggunakan idanggota dan idbuku, BUKAN idtransaksi
               LEFT JOIN tbpengembalian p ON t.idanggota = p.idanggota AND t.idbuku = p.idbuku
               
               ORDER BY t.tglpinjam DESC";

$query_transaksi = mysqli_query($db, $sql_transaksi);

// PENTING: Cek hasil query untuk laporan kedua (INI YANG MENYEBABKAN FATAL ERROR)
if (!$query_transaksi) {
    echo "<p style='color: red; font-weight: bold;'>!!! FATAL SQL ERROR pada Laporan Transaksi !!!</p>";
    echo "<p>MySQL Error: " . mysqli_error($db) . "</p>";
    // Hentikan eksekusi kode di sini atau lanjutkan dengan asumsi tidak ada hasil
    $query_transaksi = false; 
}


// Perbaikan kondisi IF: Cek apakah $query_transaksi adalah objek hasil sebelum memanggil mysqli_num_rows
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
<?php
    $nomor_transaksi = 1;
    while ($data_transaksi = mysqli_fetch_array($query_transaksi)) {
        
        // Memastikan variabel diakses dengan aman
        $denda_val = get_data($data_transaksi, 'denda', 0);
        $status_val = get_data($data_transaksi, 'status_pengembalian', 'Dipinjam');
        $batas_waktu_val = get_data($data_transaksi, 'batas_waktu', '-');
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
        <td><?= htmlspecialchars($status_val); ?></td>
        <td style="color: <?= $denda_val > 0 ? 'red' : 'green'; ?>;">
            Rp <?= number_format($denda_val, 0, ',', '.'); ?>
        </td>
    </tr>
<?php
    }
?>
</table>
<?php
} else {
    echo "<p style='text-align:center; padding:20px;'>Tidak ada data transaksi</p>";
}
?>

</div>