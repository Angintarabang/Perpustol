<?php
// Cek apakah tabel tbdenda exists
$cek_tabel = mysqli_query($db, "SHOW TABLES LIKE 'tbdenda'");
if(mysqli_num_rows($cek_tabel) == 0) {
    echo "<div style='background-color: #ffcccc; padding: 15px; margin: 10px; border: 1px solid red;'>
            <h3>Error: Tabel Denda Belum Dibuat</h3>
            <p>Silakan jalankan query berikut di phpMyAdmin:</p>
            <pre style='background: #f4f4f4; padding: 10px;'>
CREATE TABLE tbdenda (
  id_setting INT PRIMARY KEY AUTO_INCREMENT,
  denda_per_hari INT DEFAULT 5000,
  maks_hari_pinjam INT DEFAULT 7,
  maks_denda INT DEFAULT 50000
);

INSERT INTO tbdenda (denda_per_hari, maks_hari_pinjam, maks_denda) 
VALUES (5000, 7, 50000);
            </pre>
          </div>";
    exit();
}

// Cek apakah kolom status_pengembalian sudah ada di tbtransaksi
$cek_kolom = mysqli_query($db, "SHOW COLUMNS FROM tbtransaksi LIKE 'status_pengembalian'");
if(mysqli_num_rows($cek_kolom) == 0) {
    echo "<div style='background-color: #ffcccc; padding: 15px; margin: 10px; border: 1px solid red;'>
            <h3>Error: Kolom di tbtransaksi Belum Ditambahkan</h3>
            <p>Silakan jalankan query berikut di phpMyAdmin:</p>
            <pre style='background: #f4f4f4; padding: 10px;'>
ALTER TABLE tbtransaksi 
ADD COLUMN batas_waktu DATE AFTER tglpinjam,
ADD COLUMN status_pengembalian ENUM('Belum Kembali', 'Sudah Kembali') DEFAULT 'Belum Kembali' AFTER tglkembali,
ADD COLUMN denda INT DEFAULT 0 AFTER status_pengembalian;

UPDATE tbtransaksi SET 
batas_waktu = DATE_ADD(tglpinjam, INTERVAL 7 DAY),
status_pengembalian = CASE 
    WHEN tglkembali = '0000-00-00' THEN 'Belum Kembali' 
    ELSE 'Sudah Kembali' 
END;
            </pre>
          </div>";
    exit();
}

// Ambil setting denda
$q_setting = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting = 1");
$setting = mysqli_fetch_array($q_setting);

// Proses update setting denda
if(isset($_POST['update_setting'])) {
    $denda_per_hari = $_POST['denda_per_hari'];
    $maks_hari_pinjam = $_POST['maks_hari_pinjam'];
    $maks_denda = $_POST['maks_denda'];
    
    $update = mysqli_query($db, "UPDATE tbdenda SET 
        denda_per_hari = '$denda_per_hari',
        maks_hari_pinjam = '$maks_hari_pinjam',
        maks_denda = '$maks_denda'
        WHERE id_setting = 1");
    
    if($update) {
        echo "<script>alert('Setting denda berhasil diupdate');</script>";
        // Refresh setting
        $q_setting = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting = 1");
        $setting = mysqli_fetch_array($q_setting);
    } else {
        echo "<script>alert('Error update setting: ".mysqli_error($db)."');</script>";
    }
}

// Hitung denda otomatis untuk semua transaksi yang terlambat
if($setting) {
    $hitung_denda = mysqli_query($db, "
        UPDATE tbtransaksi 
        SET denda = 
            CASE 
                WHEN DATEDIFF(CURDATE(), batas_waktu) > 0 AND status_pengembalian = 'Belum Kembali' THEN
                    LEAST(DATEDIFF(CURDATE(), batas_waktu) * {$setting['denda_per_hari']}, {$setting['maks_denda']})
                ELSE 0
            END
        WHERE status_pengembalian = 'Belum Kembali'
    ");
}
?>

<div id="label-page"><h3>Manajemen Denda</h3></div>
<div id="content">

<?php if(!$setting): ?>
<div style="background-color: #ffcccc; padding: 15px; margin-bottom: 20px; border: 1px solid red;">
    <h3>Error: Setting Denda Tidak Ditemukan</h3>
    <p>Pastikan tabel tbdenda sudah dibuat dan ada data setting.</p>
</div>
<?php endif; ?>

<!-- Form Setting Denda -->
<div style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;">
    <h3>Setting Denda</h3>
    <form method="post">
        <table id="tabel-input">
            <tr>
                <td class="label-formulir">Denda per Hari</td>
                <td class="isian-formulir">
                    <input type="number" name="denda_per_hari" value="<?php echo $setting ? $setting['denda_per_hari'] : '5000'; ?>" class="isian-formulir isian-formulir-border" required>
                </td>
            </tr>
            <tr>
                <td class="label-formulir">Maksimal Hari Pinjam</td>
                <td class="isian-formulir">
                    <input type="number" name="maks_hari_pinjam" value="<?php echo $setting ? $setting['maks_hari_pinjam'] : '7'; ?>" class="isian-formulir isian-formulir-border" required>
                </td>
            </tr>
            <tr>
                <td class="label-formulir">Maksimal Denda</td>
                <td class="isian-formulir">
                    <input type="number" name="maks_denda" value="<?php echo $setting ? $setting['maks_denda'] : '50000'; ?>" class="isian-formulir isian-formulir-border" required>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="update_setting" value="Update Setting" class="tombol">
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- Daftar Transaksi dengan Denda -->
<h3>Daftar Transaksi dengan Denda</h3>
<table id="tabel-tampil">
    <tr>
        <th>ID Transaksi</th>
        <th>Anggota</th>
        <th>Buku</th>
        <th>Tgl Pinjam</th>
        <th>Batas Waktu</th>
        <th>Status</th>
        <th>Keterlambatan</th>
        <th>Denda</th>
    </tr>
    <?php
    $qry = mysqli_query($db, "
        SELECT t.*, a.nama as nama_anggota, b.judulbuku 
        FROM tbtransaksi t 
        JOIN tbanggota a ON t.idanggota = a.idanggota 
        JOIN tbbuku b ON t.idbuku = b.idbuku 
        WHERE (t.denda > 0 OR t.status_pengembalian = 'Belum Kembali')
        ORDER BY t.denda DESC
    ");
    
    if(mysqli_num_rows($qry) > 0) {
        while($data = mysqli_fetch_array($qry)) {
            $keterlambatan = '-';
            if($data['status_pengembalian'] == 'Belum Kembali' && $data['batas_waktu'] < date('Y-m-d')) {
                $hari_terlambat = date_diff(date_create($data['batas_waktu']), date_create(date('Y-m-d')))->format('%a');
                $keterlambatan = $hari_terlambat . ' hari';
            }
            
            $warna_baris = ($data['denda'] > 0) ? 'style="background-color: #ffcccc;"' : '';
    ?>
    <tr <?php echo $warna_baris; ?>>
        <td><?php echo $data['idtransaksi']; ?></td>
        <td><?php echo $data['nama_anggota']; ?></td>
        <td><?php echo $data['judulbuku']; ?></td>
        <td><?php echo $data['tglpinjam']; ?></td>
        <td><?php echo $data['batas_waktu']; ?></td>
        <td><?php echo $data['status_pengembalian']; ?></td>
        <td><?php echo $keterlambatan; ?></td>
        <td>Rp <?php echo number_format($data['denda'], 0, ',', '.'); ?></td>
    </tr>
    <?php } 
    } else {
        echo "<tr><td colspan='8'>Tidak ada data transaksi dengan denda</td></tr>";
    } ?>
</table>

</div>