<?php
include "koneksi.php";

// ambil data denda
$q = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting = 1");
$data = mysqli_fetch_array($q);
?>

<div id="label-page"><h3>Edit Pengaturan Denda</h3></div>

<div id="content">

<form method="post">
<table id="tabel-input">

    <tr>
        <td>Denda Per Hari</td>
        <td><input type="number" name="denda_per_hari"
                   value="<?= $data['denda_per_hari']; ?>" required></td>
    </tr>

    <tr>
        <td>Maksimal Hari Pinjam</td>
        <td><input type="number" name="maks_hari_pinjam"
                   value="<?= $data['maks_hari_pinjam']; ?>" required></td>
    </tr>

    <tr>
        <td>Maksimal Denda</td>
        <td><input type="number" name="maks_denda"
                   value="<?= $data['maks_denda']; ?>" required></td>
    </tr>

</table>

<br>

<input type="submit" name="simpan" value="Simpan Perubahan" class="tombol">

</form>

<?php
if (isset($_POST['simpan'])) {

    mysqli_query($db, "UPDATE tbdenda SET
        denda_per_hari = '$_POST[denda_per_hari]',
        maks_hari_pinjam = '$_POST[maks_hari_pinjam]',
        maks_denda = '$_POST[maks_denda]'
        WHERE id_setting = 1
    ");

    echo "<script>alert('Pengaturan berhasil diperbarui!'); document.location='index.php?p=denda';</script>";
}
?>

</div>
