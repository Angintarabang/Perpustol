<?php
include 'koneksi.php';
$tgl = date('Y-m-d');
session_start();

if (isset($_SESSION['sesi'])) {
?>
<!doctype html>
<html>
<head>
    <title>KERAJAAN YOHANES</title>
    <!-- Pastikan file style.css ada dan sesuai -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div id="container">

        <!-- HEADER -->
        <div id="header">
            <div id="logo-perpustakaan-container">
                <!-- Pastikan path gambar logo benar -->
                <img id="logo-perpustakaan" src="images/logo-perpustakaan3.png" style="border:0;">
            </div>
            <div id="nama-alamat-perpustakaan-container">
                <div class="nama-alamat-perpustakaan">
                    <h1> PERPUSTAKAAN CHAOS YOHANES </h1>
                </div>
                <div class="nama-alamat-perpustakaan">
                    <h4>jl simpang karue rumah yohanes</h4>
                </div>
            </div>
        </div>

        <!-- HEADER 2 -->
        <div id="header2">
            <div id="nama-user">Hai <?php echo $_SESSION['sesi']; ?>!</div>
        </div>

        <!-- SIDEBAR -->
        <div id="sidebar">

            <a href="index.php?p=beranda">Beranda</a>

            <p class="label-navigasi">Data Anggota & Buku</p>
            <ul>
                <li><a href="index.php?p=anggota">Data Anggota</a></li>
                <li><a href="index.php?p=buku">Data Buku</a></li>
            </ul>

            <p class="label-navigasi">Data Transaksi</p>
            <ul>
                <!-- Link untuk INPUT Peminjaman (sesuai kebutuhan Anda) -->
                <li><a href="index.php?p=transaksi-peminjaman-input">Transaksi Peminjaman</a></li>
                <!-- Link untuk DAFTAR Pengembalian -->
                <li><a href="index.php?p=transaksi-pengembalian">Transaksi Pengembalian</a></li>
            </ul>

            <p class="label-navigasi">Laporan & Denda</p>
            <ul>
                <li><a href="index.php?p=laporan-transaksi">Laporan Transaksi</a></li>
                <li><a href="index.php?p=denda">Manajemen Denda</a></li>
            </ul>
            
            <p class="label-navigasi">ADMIN</p>

            <a href="logout.php">Logout</a>
        </div>

        <!-- CONTENT -->
        <div id="content-container">

            <?php
            $pages_dir = 'pages';

            if (!empty($_GET['p'])) {
                $pages = scandir($pages_dir, 0);
                unset($pages[0], $pages[1]);
                $p = $_GET['p'];

                // Kita tambahkan penanganan untuk alur input transaksi
                if ($p == 'transaksi-peminjaman') {
                    // Jika diklik dari menu, arahkan ke input peminjaman
                    $file_to_include = 'transaksi-peminjaman-input.php';
                } elseif ($p == 'transaksi-pengembalian') {
                    // Jika diklik dari menu, arahkan ke daftar pengembalian (report)
                    $file_to_include = 'transaksi-pengembalian.php';
                } elseif ($p == 'transaksi-peminjaman-input') {
                    // Jika dari halaman lain ingin langsung ke input
                    $file_to_include = 'transaksi-peminjaman-input.php';
                } elseif ($p == 'transaksi-pengembalian-input') {
                    // Jika dari halaman lain ingin langsung ke input pengembalian
                    $file_to_include = 'transaksi-pengembalian-input.php';
                } elseif (in_array($p.'.php', $pages)) {
                    // Untuk semua halaman lainnya
                    $file_to_include = $p.'.php';
                } else {
                    $file_to_include = null;
                    echo 'Halaman Tidak Ditemukan';
                }
                
                // Pengecekan akhir dan eksekusi include
                if ($file_to_include && file_exists($pages_dir.'/'.$file_to_include)) {
                    include($pages_dir.'/'.$file_to_include);
                } elseif($file_to_include) {
                    // Jika file_to_include sudah ditentukan tapi file tidak ada (misal: denda.php belum dibuat)
                    echo "File {$file_to_include} Belum Dibuat!";
                }

            } else {
                include($pages_dir.'/beranda.php');
            }
            ?>

        </div>

        <div id="footer">
            <h3> WEB PROJECT SBD| Praktikum</h3>
        </div>

    </div>
</body>
</html>

<?php
} else {
    echo "<script>alert('Anda Harus Login Dahulu!');</script>";
    header('location:login.php');
}
?>