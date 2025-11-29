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
    <th>ID Anggota</th>
    <th>ID Buku</th>
    <th>Tanggal Pinjam</th>
    <th>Tanggal Kembali</th>
    <th>Terlambat</th>
    <th>Denda</th>
    <th>Opsi</th>
</tr>

<?php
$sql = "SELECT * FROM tbpengembalian ORDER BY idpengembalian DESC";
$query = mysqli_query($db, $sql);

$nomor = 1;

while ($data = mysqli_fetch_array($query)) {

    $idanggota  = $data['idanggota'];
    $idbuku     = $data['idbuku'];
    $tglkembali = $data['tglkembali'];

    // Ambil tanggal pinjam dari tbtransaksi
    $queryPinjam = mysqli_query($db,
        "SELECT tglpinjam FROM tbtransaksi 
         WHERE idanggota='$idanggota' AND idbuku='$idbuku'"
    );

    $pinjam = mysqli_fetch_array($queryPinjam);
    $tglpinjam = $pinjam['tglpinjam'] ?? "-";

    // Hitung keterlambatan
    if ($tglpinjam != "-") {
        $start = strtotime($tglpinjam);
        $end   = strtotime($tglkembali);
        $selisih = floor(($end - $start) / (60*60*24));

        // ambil pengaturan denda
        $set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting=1");
        $d = mysqli_fetch_array($set);

        $maks_hari = $d['maks_hari_pinjam'];

        if ($selisih > $maks_hari) {
            $telat = $selisih - $maks_hari;
        } else {
            $telat = 0;
        }
    } else {
        $telat = 0;
    }
?>
<tr>
    <td><?= $nomor++; ?></td>
    <td><?= $data['idpengembalian']; ?></td>
    <td><?= $idanggota; ?></td>
    <td><?= $idbuku; ?></td>
    <td><?= $tglpinjam; ?></td>
    <td><?= $tglkembali; ?></td>
    <td><?= $telat; ?> hari</td>
    <td>Rp <?= number_format($data['denda'], 0, ',', '.'); ?></td>

    <td>
        <a class="tombol" 
        href="pages/transaksi-pengembalian-hapus.php?id=<?= $data['idpengembalian']; ?>" 
        onclick="return confirm('Hapus data ini?')">
        Hapus</a>
    </td>
</tr>

<?php } ?>
</table>

</div>
