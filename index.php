<?php
session_start();
if(!isset($_SESSION['sesi'])){
    header("Location:login.php");
    exit;
}
include "koneksi.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Perpustakaan Chaos Yohanes</title>
    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="container">
		<div id="header">
            <!-- LOGO LAMA DIHAPUS, DIGANTI JUDUL SAJA -->
			<div id="nama-alamat-perpustakaan-container">
				<div class="nama-alamat-perpustakaan">
					<h1> PERPUSTAKAAN CHAOS YOHANES </h1>
                    <!-- ALAMAT LAMA SUDAH DIHAPUS -->
				</div>
			</div>
		</div>

		<div id="header2">
			<div id="nama-user">
                <!-- NAMA ANDI DIHAPUS, GANTI JADI STATUS ADMIN -->
                Status: Administrator Mode
            </div>
		</div>

		<div id="sidebar">
			<a href="index.php?p=beranda" class="<?php echo (!isset($_GET['p']) || $_GET['p']=='beranda')?'active':''; ?>">Beranda</a>
			
            <p class="label-navigasi">DATA ANGGOTA & BUKU</p>
			<a href="index.php?p=anggota" class="<?php echo (isset($_GET['p']) && $_GET['p']=='anggota')?'active':''; ?>">Data Anggota</a>
			<a href="index.php?p=buku" class="<?php echo (isset($_GET['p']) && $_GET['p']=='buku')?'active':''; ?>">Data Buku</a>
			
            <p class="label-navigasi">DATA TRANSAKSI</p>
			<a href="index.php?p=transaksi-peminjaman" class="<?php echo (isset($_GET['p']) && $_GET['p']=='transaksi-peminjaman')?'active':''; ?>">Transaksi Peminjaman</a>
            <a href="index.php?p=transaksi-pengembalian" class="<?php echo (isset($_GET['p']) && $_GET['p']=='transaksi-pengembalian')?'active':''; ?>">Transaksi Pengembalian</a>
            
            <p class="label-navigasi">LAPORAN & DENDA</p>
            <a href="index.php?p=laporan-transaksi" class="<?php echo (isset($_GET['p']) && $_GET['p']=='laporan-transaksi')?'active':''; ?>">Laporan Transaksi</a>
            <a href="index.php?p=denda" class="<?php echo (isset($_GET['p']) && $_GET['p']=='denda')?'active':''; ?>">Manajemen Denda</a>

            <p class="label-navigasi">ADMIN</p>
            <a href="logout.php">Logout</a>
		</div>

		<div id="content-container">
		    <?php
				$pages_dir='pages';
				if(!empty($_GET['p'])){
					$pages = scandir($pages_dir, 0);
					unset($pages[0], $pages[1]);
					$p = $_GET['p'];
					if(in_array($p.'.php', $pages)){
						include($pages_dir.'/'.$p.'.php');
					} else {
						echo '<div id="content"><h3>Halaman tidak ditemukan!</h3></div>';
					}
				} else {
					include($pages_dir.'/beranda.php');
				}
			?>
		</div>
		
        <!-- FOOTER -->
		<div id="footer">
            © <?php echo date('Y'); ?> Perpustakaan Chaos Yohanes - Developed by The Chaos Team
        </div>
	</div>
</body>
</html>