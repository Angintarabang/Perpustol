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

// --- Pengecekan Query Utama ---
if (!$query) {
    // Jika query gagal, tampilkan pesan error SQL
    die("Query Gagal: " . mysqli_error($db));
}

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

    // --- Pengecekan Query Pinjam ---
    if (!$queryPinjam) {
        $tglpinjam = "Error mengambil data pinjam";
        $telat = 0;
        $pinjam = false;
    } else {
        $pinjam = mysqli_fetch_array($queryPinjam);
        $tglpinjam = $pinjam['tglpinjam'] ?? "-";
    }
    
    // Inisialisasi variabel telat dan denda sementara
    $telat = 0;
    
    // Hitung keterlambatan (hanya jika data pinjam berhasil diambil)
    if ($tglpinjam != "-" && $pinjam !== false) {
        $start = strtotime($tglpinjam);
        $end   = strtotime($tglkembali);
        // Pastikan konversi tanggal berhasil (jika salah satu tanggal invalid, strtotime bisa mengembalikan false)
        if ($start !== false && $end !== false) {
            $selisih = floor(($end - $start) / (60*60*24));

            // ambil pengaturan denda
            $set = mysqli_query($db, "SELECT * FROM tbdenda WHERE id_setting=1");
            
            // --- Pengecekan Query Denda ---
            if (!$set) {
                 // Jika gagal ambil setting denda
                 $maks_hari = 7; // nilai default jika query gagal
            } else {
                 $d = mysqli_fetch_array($set);
                 $maks_hari = $d['maks_hari_pinjam'] ?? 7; // default 7 jika kolom tidak ada
            }


            if ($selisih > $maks_hari) {
                $telat = $selisih - $maks_hari;
            } else {
                $telat = 0;
            }
        } else {
            // Jika konversi tanggal gagal
            $telat = 0;
            $tglpinjam = "Tgl Invalid";
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