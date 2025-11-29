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
    <th>Tanggal Kembali</th>
    <th>Terlambat</th>
    <th>Denda</th>
    <th>Opsi</th>
</tr>

<?php
// Query untuk mengambil data pengembalian dengan info anggota dan buku
$sql = "SELECT p.*, a.nama as nama_anggota, a.jeniskelamin, b.judulbuku, t.tglpinjam
        FROM tbpengembalian p
        JOIN tbanggota a ON p.idanggota = a.idanggota
        JOIN tbbuku b ON p.idbuku = b.idbuku
        LEFT JOIN tbtransaksi t ON p.idanggota = t.idanggota AND p.idbuku = t.idbuku
        ORDER BY p.idpengembalian DESC";

$query = mysqli_query($db, $sql);

if(mysqli_num_rows($query) > 0) {
    $nomor = 1;

    while ($data = mysqli_fetch_array($query)) {
        $tgl_pinjam = $data['tglpinjam'];
        $tgl_kembali = $data['tglkembali'];

        // Hitung selisih hari
        if($tgl_pinjam && $tgl_kembali) {
            $selisih_hari = (strtotime($tgl_kembali) - strtotime($tgl_pinjam)) / (60*60*24);
            
            // ambil aturan denda
            $set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting=1");
            if(mysqli_num_rows($set) > 0) {
                $d = mysqli_fetch_array($set);
                $maks_pinjam = $d['maks_hari_pinjam'];
                $denda_per_hari = $d['denda_per_hari'];
                
                // hitung keterlambatan
                $telat = max(0, $selisih_hari - $maks_pinjam);
            } else {
                $telat = 0;
            }
        } else {
            $telat = 0;
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
           onclick="return confirm('Hapus data pengembalian ini?')">
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

</div>s