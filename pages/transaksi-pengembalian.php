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
    <th>ID Transaksi</th>
    <th>Anggota</th>
    <th>Buku</th>
    <th>Tgl Kembali (Nyata)</th>
    <th>Denda</th>
    <th>Opsi</th>
</tr>

<?php
// UPGRADED QUERY: Mengambil data pengembalian dengan detail transaksi
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
?>
<tr>
    <td><?= $nomor++; ?></td>
    <td><?= htmlspecialchars($data['idpengembalian']); ?></td>
    <td><?= htmlspecialchars($data['idtransaksi']); ?></td>
    <td>
        <strong><?= htmlspecialchars($data['idanggota']); ?></strong><br>
        <?= htmlspecialchars($data['nama_anggota']); ?>
    </td>
    <td>
        <strong><?= htmlspecialchars($data['idbuku']); ?></strong><br>
        <?= htmlspecialchars($data['judulbuku']); ?>
    </td>
    <td>
        Tgl Pinjam: <?= htmlspecialchars($data['tglpinjam']); ?><br>
        Rencana: <small style="color: #666;"><?= htmlspecialchars($tgl_rencana); ?></small><br>
        Nyata: <strong><?= htmlspecialchars($tgl_kembali_nyata); ?></strong>
    </td>
    <td style="color: <?= $data['denda'] > 0 ? 'red' : 'green'; ?>; font-weight: bold;">
        Keterlambatan: <?= $telat; ?> hari<br>
        Denda: Rp <?= number_format($data['denda'], 0, ',', '.'); ?>
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
    echo "<tr><td colspan='8' style='text-align:center; padding:20px;'>Tidak ada data pengembalian</td></tr>";
}
?>
</table>

</div>