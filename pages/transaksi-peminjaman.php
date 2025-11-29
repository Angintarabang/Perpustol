<?php
include "koneksi.php";
?>

<div id="label-page"><h3>Transaksi Peminjaman</h3></div>

<div id="content">

<div class="tombol-tambah-container">
    <a href="index.php?p=transaksi-peminjaman-input" class="tombol">Tambah Peminjaman</a>
</div>

<form method="post">
    <input type="text" name="pencarian" placeholder="Cari ID/Anggota/Buku">
    <input type="submit" name="search" value="search" class="tombol">
</form>

<table id="tabel-tampil">
<tr>
    <th>No</th>
    <th>ID Transaksi</th>
    <th>ID Anggota</th>
    <th>ID Buku</th>
    <th>Tgl Pinjam</th>
    <th>Tgl Kembali</th>
    <th>Opsi</th>
</tr>

<?php
// tanpa pencarian
$sql = "SELECT * FROM tbtransaksi ORDER BY idtransaksi DESC";
$query = mysqli_query($db, $sql);

$nomor = 1;
while ($data = mysqli_fetch_array($query)) {
?>
<tr>
    <td><?= $nomor++; ?></td>
    <td><?= $data['idtransaksi']; ?></td>
    <td><?= $data['idanggota']; ?></td>
    <td><?= $data['idbuku']; ?></td>
    <td><?= $data['tglpinjam']; ?></td>
    <td><?= $data['tglkembali']; ?></td>

    <td>
        <a class="tombol" 
           href="pages/transaksi-peminjaman-hapus.php?id=<?= $data['idtransaksi']; ?>" 
           onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
           Hapus
        </a>
    </td>
</tr>
<?php } ?>
</table>

</div>
