<?php
include "koneksi.php";

// --- 1. Ambil Pengaturan Denda (Hanya dilakukan SEKALI) ---
$set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting=1");

if (!$set) {
    die("Query Setting Denda Gagal: " . mysqli_error($db));
}

$d = mysqli_fetch_array($set);
// Ambil maks hari pinjam, jika tidak ada, defaultkan ke 7 hari
$maks_hari = $d['maks_hari_pinjam'] ?? 7; 
// Ambil denda per hari (jika dibutuhkan)
$denda_per_hari = $d['denda_per_hari'] ?? 5000; 
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
// --- 2. Query Utama Menggunakan JOIN untuk Efisiensi ---
$sql = "
    SELECT 
        P.*, 
        T.tglpinjam,
        T.idanggota,
        T.idbuku
    FROM 
        tbpengembalian P
    JOIN 
        tbtransaksi T ON P.idtransaksi = T.idtransaksi 
    ORDER BY 
        P.idpengembalian DESC
";
$query = mysqli_query($db, $sql);

// --- Pengecekan Query Utama ---
if (!$query) {
    // Jika query gagal (misal tabel tidak ada), tampilkan pesan error SQL
    die("Query Data Pengembalian Gagal: " . mysqli_error($db));
}

$nomor = 1;

while ($data = mysqli_fetch_array($query)) {
    // Data diambil langsung dari hasil JOIN
    $idanggota  = $data['idanggota'];
    $idbuku     = $data['idbuku'];
    $tglkembali = $data['tglkembali'];
    $tglpinjam  = $data['tglpinjam'];

    // Inisialisasi telat
    $telat = 0;

    // Hitung keterlambatan (pastikan tanggal valid)
    if ($tglpinjam != "0000-00-00" && $tglkembali != "0000-00-00") {
        $start = strtotime($tglpinjam);
        $end   = strtotime($tglkembali);

        // Pastikan konversi tanggal berhasil
        if ($start !== false && $end !== false) {
            $selisih = floor(($end - $start) / (60*60*24));

            if ($selisih > $maks_hari) {
                $telat = $selisih - $maks_hari;
            } else {
                $telat = 0;
            }
        }
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