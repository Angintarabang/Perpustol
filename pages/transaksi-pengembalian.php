<?php
include "koneksi.php";
?>

<div id="label-page"><h3>Transaksi Pengembalian Buku</h3></div>

<div id="content">

<div class="tombol-tambah-container">
    <a href="index.php?p=transaksi-pengembalian-input" class="tombol">Tambah Pengembalian</a>
</div>

<form method="post">
    <input type="text" name="pencarian" placeholder="Cari ID/Anggota/Buku">
    <input type="submit" name="search" value="search" class="tombol">
</form>

<table id="tabel-tampil">
<tr>
    <th>No</th>
    <th>ID Pengembalian</th>
    <th>ID Anggota</th>
    <th>ID Buku</th>
    <th>Tanggal Pinjam</th>
    <th>Tanggal Kembali</th>
    <th>Terlambat</th>
    <th>Denda</th>
    <th>Opsi</th>
</tr>

<?php

// tampilkan data pengembalian
$sql = "SELECT * FROM tbpengembalian ORDER BY idpengembalian DESC";
$query = mysqli_query($db, $sql);

$nomor = 1;

while ($data = mysqli_fetch_array($query)) {

    // ambil data peminjaman dari tbtransaksi
    $qpinjam = mysqli_query($db, 
        "SELECT * FROM tbtransaksi 
         WHERE idanggota='$data[idanggota]' 
         AND idbuku='$data[idbuku]'"
    );

    $pinjam = mysqli_fetch_array($qpinjam);

    // Jika tidak ada data pinjam → tampil "-"
    $tgl_pinjam = $pinjam['tglpinjam'] ?? "-";
    $tgl_kembali = $data['tglkembali'];

    // Hitung keterlambatan hanya jika data pinjam ada
    if ($tgl_pinjam != "-") {
        $selisih_hari = (strtotime($tgl_kembali) - strtotime($tgl_pinjam)) / (60*60*24);

        // ambil aturan denda
        $set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting=1");
        $d = mysqli_fetch_array($set);

        $maks_pinjam = $d['maks_hari_pinjam'];

        $telat = max(0, $selisih_hari - $maks_pinjam);
    } else {
        $telat = 0;
    }
?>
<tr>
    <td><?= $nomor++; ?></td>
    <td><?= $data['idpengembalian']; ?></td>
    <td><?= $data['idanggota']; ?></td>
    <td><?= $data['idbuku']; ?></td>
    <td><?= $tgl_pinjam; ?></td>
    <td><?= $tgl_kembali; ?></td>
    <td><?= $telat; ?> hari</td>
    <td>Rp <?= number_format($data['denda'], 0, ',', '.'); ?></td>

    <td>
        <a class="tombol" 
           href="pages/transaksi-pengembalian-hapus.php?id=<?= $data['idpengembalian']; ?>" 
           onclick="return confirm('Hapus data ini?')">
           Hapus
        </a>
    </td>
</tr>
<?php } ?>
</table>

</div>
