<?php
include "koneksi.php";
?>

<div id="label-page"><h3>Input Transaksi Pengembalian</h3></div>

<div id="content">
<form action="" method="post">

<table id="tabel-input">
    <tr>
        <td>ID Pengembalian</td>
        <td><input type="text" name="idpengembalian" required></td>
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
                $buku = mysqli_query($db, "SELECT * FROM tbbuku WHERE status='Dipinjam'");
                while($b = mysqli_fetch_array($buku)){
                ?>
                <option value="<?= $b['idbuku']; ?>">
                    <?= $b['idbuku']; ?> - <?= $b['judulbuku']; ?>
                </option>
                <?php } ?>
            </select>
        </td>
    </tr>

    <tr>
        <td>Tanggal Pengembalian</td>
        <td><input type="date" name="tglkembali" required></td>
    </tr>
</table>

<input type="submit" name="simpan" value="Simpan Pengembalian" class="tombol">

</form>

<?php
if (isset($_POST['simpan'])) {

    $idpengembalian = $_POST['idpengembalian'];
    $idanggota      = $_POST['idanggota'];
    $idbuku         = $_POST['idbuku'];
    $tglkembali     = $_POST['tglkembali'];

    // 1. Ambil tanggal pinjam dari tbtransaksi
    $q = mysqli_query($db, 
        "SELECT tglpinjam FROM tbtransaksi 
         WHERE idanggota='$idanggota' AND idbuku='$idbuku'"
    );
    $trx = mysqli_fetch_array($q);

    if (!$trx) {
        echo "<script>alert('Transaksi peminjaman tidak ditemukan!');</script>";
        exit;
    }

    $tglpinjam = $trx['tglpinjam'];

    // 2. Ambil pengaturan denda
    $set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting = 1");
    $d = mysqli_fetch_array($set);

    $maks_hari    = $d['maks_hari_pinjam'];
    $denda_harian = $d['denda_per_hari'];
    $maks_denda   = $d['maks_denda'];

    // 3. Hitung selisih hari pinjam - kembali
    $start  = strtotime($tglpinjam);
    $end    = strtotime($tglkembali);
    $selisih = floor(($end - $start) / (60 * 60 * 24));

    // 4. Hitung keterlambatan
    if ($selisih > $maks_hari) {
        $telat = $selisih - $maks_hari;
    } else {
        $telat = 0;
    }

    // 5. Hitung total denda
    $total_denda = $telat * $denda_harian;

    if ($total_denda > $maks_denda) {
        $total_denda = $maks_denda;
    }

    // 6. Simpan pengembalian ke tbpengembalian
    mysqli_query($db, 
        "INSERT INTO tbpengembalian (idpengembalian, idanggota, idbuku, tglkembali, denda)
         VALUES ('$idpengembalian', '$idanggota', '$idbuku', '$tglkembali', '$total_denda')"
    );

    // 7. Hapus transaksi dari tbtransaksi
    mysqli_query($db, 
        "DELETE FROM tbtransaksi 
         WHERE idanggota='$idanggota' AND idbuku='$idbuku'"
    );

    // 8. Ubah status buku
    mysqli_query($db, 
        "UPDATE tbbuku SET status='Tersedia' WHERE idbuku='$idbuku'"
    );

    echo "<script>
        alert('Pengembalian berhasil! Total denda: Rp $total_denda');
        document.location='index.php?p=transaksi-pengembalian';
    </script>";
}
?>
</div>
