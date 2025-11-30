<?php
include "koneksi.php";

// -------------------------
// Generate ID Transaksi Otomatis
// -------------------------
$query = mysqli_query($db, "SELECT MAX(idtransaksi) AS maxID FROM tbtransaksi");
$data = mysqli_fetch_array($query);
$lastID = $data['maxID'];  // contoh: TR031

if ($lastID) {
    $urutan = (int) substr($lastID, 2, 3); // ambil 031 → jadi 31
    $urutan++;                             // +1
    $idBaru = "TR" . sprintf("%03d", $urutan);
} else {
    // jika tabel masih kosong
    $idBaru = "TR001";
}
?>

<div id="label-page"><h3>Input Transaksi Peminjaman</h3></div>

<div id="content">
<form action="" method="post">

<table id="tabel-input">
    <tr>
        <td>ID Transaksi</td>
        <td><input type="text" name="idtransaksi" value="<?= $idBaru; ?>" readonly></td>
    </tr>

    <tr>
        <td>ID Anggota</td>
        <td>
            <select name="idanggota" required>
                <option value="">Pilih Anggota</option>
                <?php
                $anggota = mysqli_query($db, "SELECT * FROM tbanggota");
                while ($a = mysqli_fetch_array($anggota)) {
                ?>
                    <option value="<?= $a['idanggota']; ?>">
                        <?= $a['idanggota']; ?> - <?= $a['nama']; ?>
                    </option>
                <?php } ?>
            </select>
        </td>
    </tr>

    <tr>
        <td>ID Buku</td>
        <td>
            <select name="idbuku" required>
                <option value="">Pilih Buku</option>
                <?php
                $buku = mysqli_query($db, "SELECT * FROM tbbuku WHERE status='Tersedia'");
                while ($b = mysqli_fetch_array($buku)) {
                ?>
                    <option value="<?= $b['idbuku']; ?>">
                        <?= $b['idbuku']; ?> - <?= $b['judulbuku']; ?>
                    </option>
                <?php } ?>
            </select>
        </td>
    </tr>

    <tr>
        <td>Tanggal Pinjam</td>
        <td><input type="date" name="tglpinjam" required></td>
    </tr>

    <tr>
        <td>Tanggal Kembali</td>
        <td><input type="date" name="tglkembali" required></td>
    </tr>
</table>

<input type="submit" name="simpan" value="Simpan Peminjaman" class="tombol">

</form>

<?php
if (isset($_POST['simpan'])) {

    mysqli_query($db, "INSERT INTO tbtransaksi VALUES(
        '$_POST[idtransaksi]',
        '$_POST[idanggota]',
        '$_POST[idbuku]',
        '$_POST[tglpinjam]',
        '$_POST[tglkembali]',
        'Belum Kembali',        -- status default
        0,                      -- denda default
        DATE_ADD('$_POST[tglpinjam]', INTERVAL 7 DAY)  -- otomatis set batas waktu
    )");

    // Update status buku menjadi Dipinjam
    mysqli_query($db, "UPDATE tbbuku SET status='Dipinjam' WHERE idbuku='$_POST[idbuku]'");

    echo "<script>alert('Transaksi berhasil disimpan!'); document.location='index.php?p=transaksi-peminjaman';</script>";
}
?>

</div>
