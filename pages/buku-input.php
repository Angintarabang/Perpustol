<div id="label-page"><h3>Input Data Buku</h3></div>

<div id="content">
<form action="index.php?p=buku-input" method="post">
<table id="tabel-input">
    <tr>
        <td>ID Buku</td>
        <td><input type="text" name="idbuku" required></td>
    </tr>
    <tr>
        <td>Judul</td>
        <td><input type="text" name="judulbuku" required></td>
    </tr>
    <tr>
        <td>Kategori</td>
        <td><input type="text" name="kategori"></td>
    </tr>
    <tr>
        <td>Pengarang</td>
        <td><input type="text" name="pengarang"></td>
    </tr>
    <tr>
        <td>Penerbit</td>
        <td><input type="text" name="penerbit"></td>
    </tr>
    <tr>
        <td>Status</td>
        <td>
            <select name="status">
                <option>Tersedia</option>
                <option>Dipinjam</option>
            </select>
        </td>
    </tr>
</table>

<input type="submit" name="simpan" value="Simpan" class="tombol">
</form>

<?php
if(isset($_POST['simpan'])){
    include "koneksi.php";

    $query = mysqli_query($db, "INSERT INTO tbbuku VALUES(
        '$_POST[idbuku]',
        '$_POST[judulbuku]',
        '$_POST[kategori]',
        '$_POST[pengarang]',
        '$_POST[penerbit]',
        '$_POST[status]'
    )");

    echo "<script>alert('Data berhasil disimpan'); document.location='index.php?p=buku';</script>";
}
?>
</div>
