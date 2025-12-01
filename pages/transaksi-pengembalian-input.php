<?php
include "koneksi.php";

// Fungsi untuk mendapatkan ID Pengembalian baru secara otomatis
function generate_id_pengembalian($db) {
<<<<<<< HEAD
    $q = mysqli_query($db, "SELECT MAX(SUBSTRING(idpengembalian, 2)) AS last_id FROM tbpengembalian");
    if (!$q || mysqli_num_rows($q) == 0) {
        $last_id = 0;
    } else {
        $data = mysqli_fetch_assoc($q);
        $last_id = (int)$data['last_id'];
    }
    $next_id = $last_id + 1;
    return 'P' . sprintf('%03s', $next_id); 
}
=======
    // Mengambil nilai numerik maksimum dari idpengembalian setelah karakter pertama ('P')
    $q = mysqli_query($db, "SELECT MAX(SUBSTRING(idpengembalian, 2)) AS last_id FROM tbpengembalian");
    $data = mysqli_fetch_assoc($q);
    
    // Menggunakan operator null coalescing (?? 0) untuk menghindari error jika tabel kosong
    $last_id = (int)($data['last_id'] ?? 0);
    $next_id = $last_id + 1;
    
    // Format menjadi P001, P002, dst.
    return 'P' . sprintf('%03s', $next_id); 
}

// Inisialisasi variabel
$trx = null;

// Jika user sudah memilih ID Transaksi, ambil data transaksi tersebut
if(isset($_POST['idtransaksi']) && !empty($_POST['idtransaksi'])){
    $idtrx = mysqli_real_escape_string($db, $_POST['idtransaksi']);
    $qtrx = mysqli_query($db, "
        SELECT t.*, a.nama as nama_anggota, b.judulbuku 
        FROM tbtransaksi t
        JOIN tbanggota a ON t.idanggota = a.idanggota
        JOIN tbbuku b ON t.idbuku = b.idbuku
        WHERE t.idtransaksi='$idtrx' AND t.status_pengembalian='Dipinjam'
    ");
    $trx  = mysqli_fetch_array($qtrx);
}

>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
?>

<div id="label-page"><h3>Input Transaksi Pengembalian</h3></div>

<div id="content">

<form action="" method="post">

<table id="tabel-input">

    <!-- PILIH ID TRANSAKSI (Display lebih informatif) -->
    <tr>
        <td>ID Transaksi</td>
        <td>
            <select name="idtransaksi" required onchange="this.form.submit()">
<<<<<<< HEAD
                <option value="">Pilih ID Transaksi</option>
=======
                <option value="">Pilih ID Transaksi (Sedang Dipinjam)</option>
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
                <?php
                // Query: Tampilkan transaksi yang statusnya 'Dipinjam'
                $q_trx_select = "
                    SELECT 
<<<<<<< HEAD
                        t.idtransaksi, t.idanggota, t.idbuku, t.tglpinjam,
                        a.nama as nama_anggota,
                        b.judulbuku
=======
                        t.idtransaksi, a.nama as nama_anggota, b.judulbuku, t.tglpinjam
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
                    FROM tbtransaksi t
                    JOIN tbanggota a ON t.idanggota = a.idanggota
                    JOIN tbbuku b ON t.idbuku = b.idbuku
                    WHERE t.status_pengembalian='Dipinjam'
                    ORDER BY t.idtransaksi ASC";
                
                $q = mysqli_query($db, $q_trx_select);
                
<<<<<<< HEAD
                if (!$q) {
                    echo "<option value=''>[SQL ERROR: ".mysqli_error($db)."]</option>";
                }

                while($t = mysqli_fetch_array($q)){
                    $selected = (isset($_POST['idtransaksi']) && $_POST['idtransaksi']==$t['idtransaksi']) ? "selected" : "";
                    
                    // Display yang informatif
=======
                while($t = mysqli_fetch_array($q)){
                    $selected = ($trx && $trx['idtransaksi'] == $t['idtransaksi']) ? "selected" : "";
                    
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
                    echo "<option value='".htmlspecialchars($t['idtransaksi'])."' $selected>
                          [".htmlspecialchars($t['idtransaksi'])."] ".htmlspecialchars($t['nama_anggota'])." - ".htmlspecialchars($t['judulbuku'])." (Pinjam: ".htmlspecialchars($t['tglpinjam']).")
                          </option>";
                }
                ?>
            </select>
        </td>
    </tr>

    <?php 
<<<<<<< HEAD
    if(isset($_POST['idtransaksi']) && !empty($_POST['idtransaksi'])){
        $idtrx = mysqli_real_escape_string($db, $_POST['idtransaksi']);
        $qtrx = mysqli_query($db, "SELECT * FROM tbtransaksi WHERE idtransaksi='$idtrx'");
        $trx  = mysqli_fetch_array($qtrx);

        if ($trx) {
=======
    // Tampilkan detail jika transaksi sudah dipilih
    if($trx){
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
    ?>
    
    <tr>
        <td>ID Anggota</td>
<<<<<<< HEAD
        <td><input type="text" value="<?= htmlspecialchars($trx['idanggota']); ?>" name="idanggota" readonly></td>
=======
        <td><input type="text" value="<?= htmlspecialchars($trx['idanggota']); ?> (<?= htmlspecialchars($trx['nama_anggota']); ?>)" name="idanggota_display" readonly>
            <input type="hidden" name="idanggota" value="<?= htmlspecialchars($trx['idanggota']); ?>">
        </td>
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
    </tr>

    <tr>
        <td>ID Buku</td>
<<<<<<< HEAD
        <td><input type="text" value="<?= htmlspecialchars($trx['idbuku']); ?>" name="idbuku" readonly></td>
=======
        <td><input type="text" value="<?= htmlspecialchars($trx['idbuku']); ?> (<?= htmlspecialchars($trx['judulbuku']); ?>)" name="idbuku_display" readonly>
            <input type="hidden" name="idbuku" value="<?= htmlspecialchars($trx['idbuku']); ?>">
        </td>
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
    </tr>

    <tr>
        <td>Tanggal Pinjam</td>
<<<<<<< HEAD
        <td><input type="text" value="<?= htmlspecialchars($trx['tglpinjam']); ?>" readonly></td>
    </tr>
    
    <tr>
        <td>Tanggal Kembali</td>
        <td><input type="date" name="tglkembali" required></td>
    </tr>

    <?php 
        } 
=======
        <td><input type="text" value="<?= htmlspecialchars($trx['tglpinjam']); ?>" name="tglpinjam" readonly></td>
    </tr>
    
    <tr>
        <td>Tanggal Kembali (Nyata)</td>
        <td><input type="date" name="tglkembali" value="<?= date('Y-m-d'); ?>" required></td>
    </tr>

    <?php 
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
    } 
    ?>

</table>

<?php 
<<<<<<< HEAD
if(isset($_POST['idtransaksi']) && isset($trx) && $trx){ 
=======
if($trx){ 
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
?>
<input type="submit" name="simpan" value="Simpan Pengembalian" class="tombol">
<?php 
} 
?>

</form>

<?php
// PROSES SIMPAN TRANSAKSI PENGEMBALIAN
if (isset($_POST['simpan'])) {

    $idtrx      = mysqli_real_escape_string($db, $_POST['idtransaksi']);
    $idanggota  = mysqli_real_escape_string($db, $_POST['idanggota']);
    $idbuku     = mysqli_real_escape_string($db, $_POST['idbuku']);
    $tglkembali = mysqli_real_escape_string($db, $_POST['tglkembali']);
<<<<<<< HEAD

    // 1. Ambil tgl pinjam
    $q = mysqli_query($db, "SELECT tglpinjam FROM tbtransaksi WHERE idtransaksi='$idtrx'");
    $trx_data = mysqli_fetch_array($q);
    $tglpinjam = $trx_data['tglpinjam'];
    
    // 2. Generate ID Pengembalian Baru
    $idpengembalian = generate_id_pengembalian($db);

    // 3. Ambil aturan denda
    $set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting=1");
    $d   = mysqli_fetch_array($set);

    $maks_hari    = $d['maks_hari_pinjam'] ?? 7;
    $denda_harian = $d['denda_per_hari'] ?? 5000;
    $maks_denda   = $d['maks_denda'] ?? 50000;

    // 4. Hitung telat dan denda
    $selisih = floor((strtotime($tglkembali) - strtotime($tglpinjam)) / 86400);
    $telat = ($selisih > $maks_hari) ? $selisih - $maks_hari : 0;
    $total_denda = min($telat * $denda_harian, $maks_denda);

    // 5. Simpan ke tbpengembalian (SAFE INSERT)
    $insert_query = "INSERT INTO tbpengembalian (
            idpengembalian, idanggota, idbuku, idtransaksi, tglkembali, denda
        ) VALUES (
            '$idpengembalian', '$idanggota', '$idbuku', '$idtrx', '$tglkembali', '$total_denda'
        )";
        
    $insert_result = mysqli_query($db, $insert_query);

    if ($insert_result) {
        
        // 6. UPDATE: Set status di tbtransaksi menjadi 'Sudah Kembali'
        mysqli_query($db, "UPDATE tbtransaksi SET status_pengembalian='Sudah Kembali', denda='$total_denda', tglkembali='$tglkembali' WHERE idtransaksi='$idtrx'");
        
        // 7. UPDATE: Set status buku
        mysqli_query($db, "UPDATE tbbuku SET status='Tersedia' WHERE idbuku='$idbuku'");
        
        echo "<script>
                alert('Pengembalian berhasil! | Denda: Rp ".number_format($total_denda, 0, ',', '.')."');
=======
    $tglpinjam  = mysqli_real_escape_string($db, $_POST['tglpinjam']);

    // Validasi Tanggal Kembali
    if (strtotime($tglkembali) < strtotime($tglpinjam)) {
        echo "<script>alert('Error! Tanggal kembali tidak boleh lebih awal dari tanggal pinjam (".htmlspecialchars($tglpinjam).").');</script>";
        exit;
    }

    // 1. Generate ID Pengembalian Baru
    $idpengembalian = generate_id_pengembalian($db);

    // 2. Ambil aturan denda
    $set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting=1");
    $d   = mysqli_fetch_array($set);

    $maks_hari    = $d['maks_hari_pinjam'] ?? 7;
    $denda_harian = $d['denda_per_hari'] ?? 5000;
    $maks_denda   = $d['maks_denda'] ?? 50000;

    // 3. Hitung telat dan denda
    $selisih = floor((strtotime($tglkembali) - strtotime($tglpinjam)) / 86400); // Selisih total hari pinjam
    
    $telat = max(0, $selisih - $maks_hari);
    $total_denda = min($telat * $denda_harian, $maks_denda);

    // 4. Simpan ke tbpengembalian (INSERT)
    $insert_query = "INSERT INTO tbpengembalian (
            idpengembalian, idanggota, idbuku, idtransaksi, tglkembali, denda
        ) VALUES (
            '$idpengembalian', '$idanggota', '$idbuku', '$idtrx', '$tglkembali', '$total_denda'
        )";
        
    $insert_result = mysqli_query($db, $insert_query);

    if ($insert_result) {
        
        // 5. UPDATE: Set status di tbtransaksi menjadi 'Sudah Kembali' dan update denda serta TGL KEMBALI NYATA
        mysqli_query($db, "UPDATE tbtransaksi SET status_pengembalian='Sudah Kembali', denda='$total_denda', tglkembali='$tglkembali' WHERE idtransaksi='$idtrx'");
        
        // 6. UPDATE: Set status buku
        mysqli_query($db, "UPDATE tbbuku SET status='Tersedia' WHERE idbuku='$idbuku'");
        
        // 7. Update status anggota: Cek apakah anggota masih punya pinjaman aktif
        $pinjaman_aktif = mysqli_query($db, "SELECT idtransaksi FROM tbtransaksi WHERE idanggota='$idanggota' AND status_pengembalian='Dipinjam'");
        if (mysqli_num_rows($pinjaman_aktif) == 0) {
            // Jika tidak ada pinjaman aktif, set status anggota menjadi 'Tidak Meminjam'
            mysqli_query($db, "UPDATE tbanggota SET status='Tidak Meminjam' WHERE idanggota='$idanggota'");
        }
        
        echo "<script>
                alert('Pengembalian berhasil! | Keterlambatan: $telat hari | Denda: Rp ".number_format($total_denda, 0, ',', '.')."');
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
                document.location='index.php?p=transaksi-pengembalian';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan data pengembalian! MySQL Error: ".mysqli_error($db)."');
              </script>";
    }
}
?>

</div>