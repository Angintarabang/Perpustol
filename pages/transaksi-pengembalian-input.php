<?php
include "koneksi.php";
?>

<div id="label-page"><h3>Input Transaksi Pengembalian</h3></div>

<div id="content">

<form action="" method="post">

<table id="tabel-input">

    <!-- PILIH ID TRANSAKSI -->
    <tr>
        <td>ID Transaksi</td>
        <td>
            <select name="idtransaksi" required onchange="this.form.submit()">
                <option value="">Pilih ID Transaksi</option>
                <?php
                $q = mysqli_query($db, "SELECT * FROM tbtransaksi ORDER BY idtransaksi ASC");
                while($t = mysqli_fetch_array($q)){
                    $selected = (isset($_POST['idtransaksi']) && $_POST['idtransaksi']==$t['idtransaksi']) ? "selected" : "";
                    echo "<option value='$t[idtransaksi]' $selected>
                          $t[idtransaksi] - $t[idanggota] - $t[idbuku] - $t[tglpinjam]
                          </option>";
                }
                ?>
            </select>
        </td>
    </tr>

    <?php 
    // Kalau ID dipilih, ambil data detail transaksi
    if(isset($_POST['idtransaksi'])){
        $idtrx = $_POST['idtransaksi'];
        $qtrx = mysqli_query($db, "SELECT * FROM tbtransaksi WHERE idtransaksi='$idtrx'");
        $trx  = mysqli_fetch_array($qtrx);
    ?>
    
    <tr>
        <td>ID Anggota</td>
        <td><input type="text" value="<?= $trx['idanggota']; ?>" name="idanggota" readonly></td>
    </tr>

    <tr>
        <td>ID Buku</td>
        <td><input type="text" value="<?= $trx['idbuku']; ?>" name="idbuku" readonly></td>
    </tr>

    <tr>
        <td>Tanggal Pinjam</td>
        <td><input type="text" value="<?= $trx['tglpinjam']; ?>" readonly></td>
    </tr>

    <tr>
        <td>Tanggal Kembali</td>
        <td><input type="date" name="tglkembali" required></td>
    </tr>

    <?php } ?>

</table>

<?php if(isset($_POST['idtransaksi'])){ ?>
<input type="submit" name="simpan" value="Simpan Pengembalian" class="tombol">
<?php } ?>

</form>

<?php
// PROSES SIMPAN
if (isset($_POST['simpan'])) {

    $idtrx      = $_POST['idtransaksi'];
    $idanggota  = $_POST['idanggota'];
    $idbuku     = $_POST['idbuku'];
    $tglkembali = $_POST['tglkembali'];

    // Ambil tgl pinjam
    $q = mysqli_query($db, "SELECT tglpinjam FROM tbtransaksi WHERE idtransaksi='$idtrx'");
    $trx = mysqli_fetch_array($q);
    $tglpinjam = $trx['tglpinjam'];

    // Ambil aturan denda
    $set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting=1");
    $d   = mysqli_fetch_array($set);

    $maks_hari    = $d['maks_hari_pinjam'];
    $denda_harian = $d['denda_per_hari'];
    $maks_denda   = $d['maks_denda'];

    // Hitung selisih
    $selisih = floor((strtotime($tglkembali) - strtotime($tglpinjam)) / 86400);

    // Hitung telat
    $telat = ($selisih > $maks_hari) ? $selisih - $maks_hari : 0;

    // Hitung total denda
    $total_denda = $telat * $denda_harian;
    if($total_denda > $maks_denda){ $total_denda = $maks_denda; }

    // Simpan ke tbpengembalian (idpengembalian = idtransaksi)
    mysqli_query($db, 
        "INSERT INTO tbpengembalian VALUES(
            '$idtrx', '$idanggota', '$idbuku', '$tglkembali', '$total_denda'
        )"
    );

    // Hapus transaksi peminjaman
    mysqli_query($db, "DELETE FROM tbtransaksi WHERE idtransaksi='$idtrx'");

    // Update status buku
    mysqli_query($db, "UPDATE tbbuku SET status='Tersedia' WHERE idbuku='$idbuku'");

    echo "<script>
            alert('Pengembalian berhasil! | Denda: Rp $total_denda');
            document.location='index.php?p=transaksi-pengembalian';
          </script>";
}
?>

</div>
