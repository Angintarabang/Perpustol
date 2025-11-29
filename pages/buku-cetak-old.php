<?php
include "../koneksi.php";
?>

<h2>Laporan Data Buku</h2>
<table border="1" cellspacing="0" cellpadding="5">
<tr>
    <th>ID Buku</th>
    <th>Judul</th>
    <th>Kategori</th>
    <th>Pengarang</th>
    <th>Penerbit</th>
</tr>

<?php
$q = mysqli_query($db, "SELECT * FROM tbbuku");
while($r = mysqli_fetch_array($q)){
?>
<tr>
    <td><?= $r['idbuku']; ?></td>
    <td><?= $r['judulbuku']; ?></td>
    <td><?= $r['kategori']; ?></td>
    <td><?= $r['pengarang']; ?></td>
    <td><?= $r['penerbit']; ?></td>
</tr>
<?php } ?>
</table>

<script>window.print();</script>
