<?php
include "koneksi.php";

// Ambil data denda dari tabel
$q = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting = 1");
$data = mysqli_fetch_array($q);
?>

<div id="label-page"><h3>Pengaturan Denda Perpustakaan</h3></div>

<div id="content">

<table id="tabel-input">
    <tr>
        <td width="200px">Denda per hari</td>
        <td>: Rp <?= number_format($data['denda_per_hari'], 0, ',', '.'); ?></td>
    </tr>

    <tr>
        <td>Maksimal Hari Pinjam</td>
        <td>: <?= $data['maks_hari_pinjam']; ?> hari</td>
    </tr>

    <tr>
        <td>Maksimal Denda</td>
        <td>: Rp <?= number_format($data['maks_denda'], 0, ',', '.'); ?></td>
    </tr>
</table>

<br>

<a class="tombol" href="index.php?p=denda-edit">Edit Pengaturan</a>

</div>
