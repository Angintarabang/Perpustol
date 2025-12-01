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

// Ambil aturan denda HANYA SEKALI
$set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting=1");
$d = mysqli_fetch_array($set);
$maks_pinjam = $d['maks_hari_pinjam'] ?? 7; 
$denda_per_hari = $d['denda_per_hari'] ?? 5000;
?>

<div id="label-page"><h3>Transaksi Pengembalian Buku</h3></div>

<div id="content">

<div class="tombol-tambah-container">
    <a href="index.php?p=transaksi-pengembalian-input" class="tombol">Tambah Pengembalian</a>
</div>

<!-- Form Pencarian -->
<form method="post" style="margin-bottom: 20px;">
    <input type="text" name="pencarian" placeholder="Cari ID/Anggota/Buku">
    <input type="submit" name="search" value="Cari" class="tombol">
</form>

<table id="tabel-tampil">
<tr>
    <th>No</th>
    <th>ID Pengembalian</th>
    <th>Anggota</th>
    <th>Buku</th>
    <th>Tanggal Pinjam</th>
    <th>Tanggal Kembali</th>
    <th>Terlambat</th>
    <th>Denda</th>
    <th>Opsi</th>
</tr>

<?php
$query_search = "";
if (isset($_POST['search'])) {
    $pencarian = mysqli_real_escape_string($db, $_POST['pencarian']);
    // Filter berdasarkan ID Transaksi, Nama Anggota, atau Judul Buku
    $query_search = "
        AND (
            p.idpengembalian LIKE '%$pencarian%' OR
            a.nama LIKE '%$pencarian%' OR
            b.judulbuku LIKE '%$pencarian%'
        )
    ";
}

// Query Utama: Mengambil semua data dalam satu kali JOIN
$sql = "SELECT 
            p.*, 
            a.nama as nama_anggota, 
            b.judulbuku, 
            t.tglpinjam -- Ambil tglpinjam dari tabel transaksi
        FROM tbpengembalian p
        LEFT JOIN tbanggota a ON p.idanggota = a.idanggota
        LEFT JOIN tbbuku b ON p.idbuku = b.idbuku
        LEFT JOIN tbtransaksi t ON p.idtransaksi = t.idtransaksi -- JOIN PENTING
        WHERE 1=1 
        {$query_search}
        ORDER BY p.idpengembalian DESC";

$query = mysqli_query($db, $sql);

if (!debug_query($db, $query, "Data Pengembalian")) {
    // Jika query gagal, hentikan dan tampilkan pesan error
    echo "</table></div>";
    return;
}

$nomor = 1;

if (mysqli_num_rows($query) > 0) {
    while ($data = mysqli_fetch_array($query)) {
        
        $tgl_pinjam = $data['tglpinjam'] ?? "-";
        $tgl_kembali = $data['tglkembali'];
        $telat = 0;
        
        // Hitung Keterlambatan dan Denda
        if ($tgl_pinjam != "-") {
            $date_pinjam = strtotime($tgl_pinjam);
            $date_kembali = strtotime($tgl_kembali);
            
            $selisih_hari = ($date_kembali - $date_pinjam) / (60*60*24);
            
            // Logika Telat: Selisih Hari dikurangi batas pinjam
            $telat = max(0, $selisih_hari - $maks_pinjam); 
            
            // Denda (Jika denda tidak tersimpan di DB, hitung ulang)
            // Jika denda sudah tersimpan di p.denda, gunakan itu, atau hitung ulang
            $denda_hitung = $telat * $denda_per_hari; 
            
            // Kita gunakan data denda dari database (p.denda) jika ada, jika tidak, pakai hitungan
            $denda_tampil = $data['denda'] > 0 ? $data['denda'] : $denda_hitung;

        } else {
            $denda_tampil = 0;
        }

?>
<tr>
    <td><?= $nomor++; ?></td>
    <td><?= htmlspecialchars($data['idpengembalian']); ?></td>
    <td>
        <strong><?= htmlspecialchars($data['idanggota']); ?></strong><br>
        <small><?= htmlspecialchars($data['nama_anggota'] ?? '-'); ?></small>
    </td>
    <td>
        <strong><?= htmlspecialchars($data['idbuku']); ?></strong><br>
        <small><?= htmlspecialchars($data['judulbuku'] ?? '-'); ?></small>
    </td>
    <td><?= htmlspecialchars($tgl_pinjam); ?></td>
    <td><?= htmlspecialchars($tgl_kembali); ?></td>
    <td style="color: <?= $telat > 0 ? 'red' : 'green'; ?>; font-weight: bold;">
        <?= $telat; ?> hari
    </td>
    <td style="color: <?= $denda_tampil > 0 ? 'red' : 'green'; ?>; font-weight: bold;">
        Rp <?= number_format($denda_tampil, 0, ',', '.'); ?>
    </td>
    <td>
        <a class="tombol" 
           href="pages/transaksi-pengembalian-hapus.php?id=<?= $data['idpengembalian']; ?>" 
           onclick="return confirm('Hapus data ini?')">
           Hapus
        </a>
    </td>
</tr>
<?php } 
} else {
    echo "<tr><td colspan='9' style='text-align:center; padding:20px;'>Tidak ada data pengembalian</td></tr>";
}
?>
</table>

</div>