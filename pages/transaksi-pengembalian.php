<?php
include "koneksi.php";
?>

<div id="label-page"><h3>Transaksi Pengembalian Buku</h3></div>

<div id="content">

<div class="tombol-tambah-container">
    <a href="index.php?p=transaksi-pengembalian-input" class="tombol">Tambah Pengembalian</a>
</div>

<table id="tabel-tampil">
<tr>
    <th>No</th>
    <th>ID Pengembalian</th>
    <th>Anggota</th>
    <th>Buku</th>
    <th>Tanggal Pinjam</th>
    <th>Tanggal Kembali (Nyata)</th>
    <th>Terlambat</th>
    <th>Denda</th>
    <th>Opsi</th>
</tr>

<?php
// Ambil aturan denda global untuk perhitungan telat di laporan
$set_denda = mysqli_query($db, "SELECT maks_hari_pinjam FROM tbdenda WHERE id_setting=1");
// Cek jika query berhasil dan ada data
if (!$set_denda || mysqli_num_rows($set_denda) == 0) {
    // Nilai default jika tbdenda tidak ditemukan
    $maks_pinjam = 7; 
} else {
    $denda_data = mysqli_fetch_array($set_denda);
    $maks_pinjam = $denda_data['maks_hari_pinjam'];
}


// UPGRADED QUERY: JOIN harus menggunakan idtransaksi untuk keakuratan data tglpinjam
$sql = "SELECT 
            p.*, 
            a.nama as nama_anggota, 
            a.jeniskelamin, 
            b.judulbuku, 
            t.tglpinjam AS tgl_pinjam_transaksi
        FROM tbpengembalian p
        JOIN tbanggota a ON p.idanggota = a.idanggota
        JOIN tbbuku b ON p.idbuku = b.idbuku
        -- Penting: Pastikan JOIN ke tbtransaksi menggunakan ID transaksi pengembalian
        JOIN tbtransaksi t ON p.idtransaksi = t.idtransaksi 
        ORDER BY p.idpengembalian DESC";

$query = mysqli_query($db, $sql);

if (!$query) {
    echo "<tr><td colspan='9' style='text-align:center; color:red;'>SQL Error: ".mysqli_error($db)."</td></tr>";
}
else if(mysqli_num_rows($query) > 0) {
    $nomor = 1;

    while ($data = mysqli_fetch_array($query)) {
        $tgl_pinjam = $data['tgl_pinjam_transaksi'];
        $tgl_kembali = $data['tglkembali'];
        $telat = 0;

        // Hitung selisih hari jika data tanggal lengkap
        if($tgl_pinjam && $tgl_kembali) {
            // Selisih hari total pinjam (dibulatkan ke bawah)
            $selisih_hari = floor((strtotime($tgl_kembali) - strtotime($tgl_pinjam)) / (60*60*24)); 
            
            // Hitung keterlambatan (maks pinjam sudah diambil di awal)
            $telat = max(0, $selisih_hari - $maks_pinjam);
        }
?>
<tr>
    <td><?= $nomor++; ?></td>
    <td><?= htmlspecialchars($data['idpengembalian']); ?></td>
    <td>
        <strong><?= htmlspecialchars($data['idanggota']); ?></strong><br>
        <?= htmlspecialchars($data['nama_anggota']); ?><br>
        <small><?= htmlspecialchars($data['jeniskelamin']); ?></small>
    </td>
    <td>
        <strong><?= htmlspecialchars($data['idbuku']); ?></strong><br>
        <?= htmlspecialchars($data['judulbuku']); ?>
    </td>
    <td><?= $tgl_pinjam ? htmlspecialchars($tgl_pinjam) : '-'; ?></td>
    <td><?= htmlspecialchars($tgl_kembali); ?></td>
    <td style="color: <?= $telat > 0 ? 'red' : 'green'; ?>; font-weight: bold;">
        <?= $telat; ?> hari
    </td>
    <td style="color: <?= $data['denda'] > 0 ? 'red' : 'green'; ?>; font-weight: bold;">
        Rp <?= number_format($data['denda'], 0, ',', '.'); ?>
    </td>
    <td>
        <a class="tombol" 
           href="pages/transaksi-pengembalian-hapus.php?id=<?= $data['idpengembalian']; ?>" 
           onclick="return confirm('Hapus data pengembalian ini? Tindakan ini tidak akan mengembalikan status buku/anggota.')">
           Hapus
        </a>
    </td>
</tr>
<?php 
    }
} else {
    echo "<tr><td colspan='9' style='text-align:center; padding:20px;'>Tidak ada data pengembalian</td></tr>";
}
?>
</table>

</div>