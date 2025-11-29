<?php
include "koneksi.php";
$id = $_GET['id'];
$data = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM tbbuku WHERE idbuku='$id'"));
?>
<div id="label-page"><h3>Edit Data Buku</h3></div>

<div id="content">
<form action="" method="post">
<table id="tabel-input">
    <tr><td>ID Buku</td><td><input value="<?= $data['idbuku']; ?>" type="text" readonly></td></tr>
    <tr><td>Judul</td><td><input value="<?= $data['judulbuku']; ?>" type="text" name="judulbuku"></td></tr>
    <tr><td>Kategori</td><td><input value="<?= $data['kategori']; ?>" type="text" name="kategori"></td></tr>
    <tr><td>Pengarang</td><td><input value="<?= $data['pengarang']; ?>" type="text" name="pengarang"></td></tr>
    <tr><td>Penerbit</td><td><input value="<?= $data['penerbit']; ?>" type="text" name="penerbit"></td></tr>
    <tr>
        <td>Status</td>
        <td>
            <select name="status">
                <option <?= $data['status']=="Tersedia"?"selected":""; ?>>Tersedia</option>
                <option <?= $data['status']=="Dipinjam"?"selected":""; ?>>Dipinjam</option>
            </select>
        </td>
    </tr>
</table>

<input type="submit" name="edit" value="Simpan Perubahan" class="tombol">
</form>

<?php
if(isset($_POST['edit'])){
    mysqli_query($db, "UPDATE tbbuku SET
        judulbuku='$_POST[judulbuku]',
        kategori='$_POST[kategori]',
        pengarang='$_POST[pengarang]',
        penerbit='$_POST[penerbit]',
        status='$_POST[status]'
    WHERE idbuku='$id'");

    echo "<script>alert('Data berhasil diperbarui'); document.location='index.php?p=buku';</script>";
}
?>
</div>
