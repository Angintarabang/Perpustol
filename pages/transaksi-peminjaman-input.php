<?php
include "koneksi.php";
?>

<div id="label-page"><h3>Input Transaksi Peminjaman</h3></div>

<div id="content">
<form action="" method="post">

<table id="tabel-input">
    <tr>
        <td>ID Transaksi</td>
        <td><input type="text" name="idtransaksi" required></td>
    </tr>
    <tr>
        <td>ID Anggota</td>
        <td>
            <select name="idanggota" required>
                <option value="">Pilih Anggota</option>
                <?php
                $anggota = mysqli_query($db, "SELECT * FROM tbanggota");
                while($a = mysqli_fetch_array($anggota)){
                ?>
                <option value="<?= $a['idanggota']; ?>"><?= $a['idanggota']; ?> - <?= $a['nama']; ?></option>
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
                $buku = mysqli_query($db, "SELECT * FROM tbbuku WHERE status = 'Tersedia'");
                while($b = mysqli_fetch_array($buku)){
                ?>
                <option value="<?= $b['idbuku']; ?>"><?= $b['idbuku']; ?> - <?= $b['judulbuku']; ?></option>
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

    // Amankan data input
    $idtransaksi = mysqli_real_escape_string($db, $_POST['idtransaksi']);
    $idanggota   = mysqli_real_escape_string($db, $_POST['idanggota']);
    $idbuku      = mysqli_real_escape_string($db, $_POST['idbuku']);
    $tglpinjam   = mysqli_real_escape_string($db, $_POST['tglpinjam']);
    $tglkembali  = mysqli_real_escape_string($db, $_POST['tglkembali']);

    // INSERT INTO tbtransaksi
    $insert_query = "INSERT INTO tbtransaksi (
                        idtransaksi, idanggota, idbuku, tglpinjam, tglkembali, status_pengembalian, denda
                    ) VALUES (
                        '$idtransaksi',
                        '$idanggota',
                        '$idbuku',
                        '$tglpinjam',
                        '$tglkembali',
                        'Dipinjam', -- STATUS DEFAULT
                        0          -- DENDA DEFAULT
                    )";
    
    $insert_result = mysqli_query($db, $insert_query);

    if ($insert_result) {
        // Update status buku menjadi Dipinjam
        mysqli_query($db, "UPDATE tbbuku SET status='Dipinjam' WHERE idbuku='$idbuku'");

        // Update status anggota menjadi Sedang Meminjam
        mysqli_query($db, "UPDATE tbanggota SET status='Sedang Meminjam' WHERE idanggota='$idanggota'");

        echo "<script>alert('Transaksi berhasil disimpan!'); document.location='index.php?p=transaksi-peminjaman';</script>";
    } else {
        // Tampilkan error MySQL jika gagal
        echo "<script>alert('Gagal menyimpan transaksi! MySQL Error: ".mysqli_error($db)."');</script>";
    }
}
?>

</div>
