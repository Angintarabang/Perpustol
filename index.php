<?php
include 'koneksi.php';
$tgl = date('Y-m-d');
session_start();

if (isset($_SESSION['sesi'])) {
?>
<!doctype html>
<html>
<head>
    <title>Sistem Informasi Perpustakaan</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div id="container">

        <!-- HEADER -->
        <div id="header">
            <div id="logo-perpustakaan-container">
                <img id="logo-perpustakaan" src="images/logo-perpustakaan3.png" style="border:0;">
            </div>
            <div id="nama-alamat-perpustakaan-container">
                <div class="nama-alamat-perpustakaan">
                    <h1> PERPUSTAKAAN UMUM </h1>
                </div>
                <div class="nama-alamat-perpustakaan">
                    <h4>Jl. Lembah Abang No 11, Telp: (021)55555555</h4>
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
                <li><a href="index.php?p=transaksi-peminjaman">Transaksi Peminjaman</a></li>
                <li><a href="index.php?p=transaksi-pengembalian">Transaksi Pengembalian</a></li>
            </ul>

            <p class="label-navigasi">Laporan & Denda</p>
            <ul>
                <li><a href="index.php?p=laporan-transaksi">Laporan Transaksi</a></li>
                <li><a href="index.php?p=denda">Manajemen Denda</a></li>
            </ul>

            <!-- LABEL SAJA, TIDAK BISA DIKLIK -->
            <p class="label-navigasi">Keluar</p>

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

                if (in_array($p.'.php', $pages)) {
                    include($pages_dir.'/'.$p.'.php');
                } else {
                    echo 'Halaman Tidak Ditemukan';
                }
            } else {
                include($pages_dir.'/beranda.php');
            }
            ?>

        </div>

        <div id="footer">
            <h3>Sistem Informasi Perpustakaan (sipus) | Praktikum</h3>
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
