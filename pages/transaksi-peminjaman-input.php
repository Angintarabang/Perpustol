<?php
include "koneksi.php";

// Fungsi untuk mendapatkan ID Transaksi baru secara otomatis
function generate_id_transaksi($db) {
    $q = mysqli_query($db, "SELECT MAX(SUBSTRING(idtransaksi, 3)) AS last_id FROM tbtransaksi");
    $data = mysqli_fetch_assoc($q);
    $last_id = (int)($data['last_id'] ?? 0);
    $next_id = $last_id + 1;
    return 'TR' . sprintf('%03s', $next_id); 
}

$next_id_transaksi = generate_id_transaksi($db);
?>

<div id="label-page"><h3>Input Transaksi Peminjaman</h3></div>

<div id="content">
<form action="" method="post">

<table id="tabel-input">
    <tr>
        <td>ID Transaksi</td>
        <td><input type="text" name="idtransaksi" value="<?= $next_id_transaksi; ?>"required></td>
    </tr>
    <tr>
        <td>ID Anggota</td>
        <td>
            <select name="idanggota" required>
                <option value="">Pilih Anggota</option>
                <?php
                // Tampilkan hanya anggota yang statusnya 'Tidak Meminjam' atau 'Sedang Meminjam' (jika batas pinjam lebih dari 1)
                // Default: hanya tampilkan yang 'Tidak Meminjam' untuk mencegah double pinjam jika sistem hanya membolehkan 1 buku per anggota.
                $anggota = mysqli_query($db, "SELECT * FROM tbanggota WHERE status = 'Tidak Meminjam' OR status = 'Sedang Meminjam' ORDER BY nama ASC");
                while($a = mysqli_fetch_array($anggota)){
                ?>
                <option value="<?= $a['idanggota']; ?>"><?= htmlspecialchars($a['idanggota']) ; ?> - <?= htmlspecialchars($a['nama']); ?></option>
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
                // Tampilkan hanya buku yang 'Tersedia'
                $buku = mysqli_query($db, "SELECT * FROM tbbuku WHERE status = 'Tersedia' ORDER BY judulbuku ASC");
                while($b = mysqli_fetch_array($buku)){
                ?>
                <option value="<?= $b['idbuku']; ?>"><?= htmlspecialchars($b['idbuku']); ?> - <?= htmlspecialchars($b['judulbuku']); ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>

    <tr>
        <td>Tanggal Pinjam</td>
        <td><input type="date" name="tglpinjam" value="<?= date('Y-m-d'); ?>" required></td>
    </tr>

    <tr>
        <td>Batas Kembali</td>
        <td>
            <?php
            // Ambil maksimal hari pinjam dari tbdenda
            $set = mysqli_query($db, "SELECT maks_hari_pinjam FROM tbdenda WHERE id_setting=1");
            $d = mysqli_fetch_array($set);
            $maks_hari = $d['maks_hari_pinjam'] ?? 7;
            
            // Hitung tanggal default kembali
            $default_kembali = date('Y-m-d', strtotime('+' . $maks_hari . ' days'));
            ?>
            <input type="date" name="tglkembali" value="<?= $default_kembali; ?>" required>
        </td>
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
<<<<<<< HEAD
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
=======
    // Tgl kembali di sini adalah TGL RENCANA KEMBALI
    $tglkembali  = mysqli_real_escape_string($db, $_POST['tglkembali']); 

    // Cek apakah ID Transaksi sudah ada
    $check_query = mysqli_query($db, "SELECT idtransaksi FROM tbtransaksi WHERE idtransaksi = '$idtransaksi'");
    if (mysqli_num_rows($check_query) > 0) {
        // Jika ID transaksi sudah ada (kemungkinan tombol submit ditekan dua kali)
        echo "<script>alert('ID Transaksi sudah terdaftar. Silakan coba lagi.');</script>";
    } else {
        // INSERT INTO tbtransaksi
        $insert_query = "INSERT INTO tbtransaksi (
                            idtransaksi, idanggota, idbuku, tglpinjam, tglkembali, status_pengembalian, denda
                        ) VALUES (
                            '$idtransaksi',
                            '$idanggota',
                            '$idbuku',
                            '$tglpinjam',
                            '$tglkembali', 
                            'Dipinjam',
                            0
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
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
    }
}
?>

</div>