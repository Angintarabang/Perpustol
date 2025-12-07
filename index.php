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
	<!-- SweetAlert2 CDN (Wajib ada) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<div id="nama-alamat-perpustakaan-container" style="width: 100%; text-align: center;">
				<div class="nama-alamat-perpustakaan">
					<h1> PERPUSTAKAAN CHAOS YOHANES </h1>
				</div>
			</div>
		</div>

		<div id="header2">
			<div id="nama-user">
                STATUS: ADMINISTRATOR MODE
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
		
		<div id="footer">
            © <?php echo date('Y'); ?> Perpustakaan Chaos Yohanes - System by Chaos Team
        </div>
	</div>

    <!-- SCRIPT ALERT HAPUS (GLOBAL CHAOS THEME) - TARUH DISINI PALING BAWAH -->
    <script>
        function konfirmasiHapus(event, urlHapus, namaData) {
            event.preventDefault(); // Tahan dulu biar gak langsung pindah halaman

            Swal.fire({
                title: 'HAPUS DATA?',
                text: "Yakin mau menghapus " + namaData + "? Data yang hilang tidak bisa kembali!",
                icon: 'warning',
                background: '#121212', // Hitam
                color: '#f0f0f0',      // Putih
                showCancelButton: true,
                confirmButtonColor: '#d33', // Merah buat hapus
                cancelButtonColor: '#333',  // Abu buat batal
                confirmButtonText: 'YA, MUSNAHKAN!',
                cancelButtonText: 'BATAL'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = urlHapus; // Kalau Ya, baru pindah ke link hapus
                }
            })
        }
    </script>
	<script>
/* --- TAMBAHAN: ALERT KONFIRMASI SIMPAN (CHAOS THEME) --- */
function konfirmasiSimpan(event, idForm) {
    event.preventDefault(); // Tahan dulu, jangan langsung kirim
    const form = document.getElementById(idForm);

    // Cek apakah input wajib (required) sudah diisi?
    if(!form.checkValidity()) {
        form.reportValidity(); // Kalau belum, munculin bubble error bawaan browser
        return;
    }

    Swal.fire({
        title: 'SIMPAN PERUBAHAN?',
        text: "Pastikan data yang anda masukkan sudah benar.",
        icon: 'question',
        background: '#121212', // Hitam
        color: '#f0f0f0',      // Putih
        showCancelButton: true,
        confirmButtonColor: '#b8860b', // Emas Gelap
        cancelButtonColor: '#333',     // Abu
        confirmButtonText: 'YA, SIMPAN!',
        cancelButtonText: 'BATAL'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit(); // Kalau Yes, baru kirim data ke server
        }
    })
}
</script>
</body>
</html>