<?php
include 'koneksi.php';
$tgl = date('Y-m-d');
session_start();

if (isset($_SESSION['sesi'])) {

    // Helper untuk menentukan halaman aktif
    $current_page = isset($_GET['p']) ? $_GET['p'] : 'beranda';
?>
<!doctype html>
<html>
<head>
    <title>KERAJAAN YOHANES</title>
    <!-- Pastikan path CSS ini benar -->
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

        <!-- SIDEBAR - Sudah memiliki penanda ACTIVE class -->
        <div id="sidebar">

            <a href="index.php?p=beranda" class="<?php echo ($current_page == 'beranda') ? 'active' : ''; ?>">Beranda</a>

            <p class="label-navigasi">Data Anggota & Buku</p>
            <ul>
                <li><a href="index.php?p=anggota" class="<?php echo ($current_page == 'anggota') ? 'active' : ''; ?>">Data Anggota</a></li>
                <li><a href="index.php?p=buku" class="<?php echo ($current_page == 'buku') ? 'active' : ''; ?>">Data Buku</a></li>
            </ul>

            <p class="label-navigasi">Data Transaksi</p>
            <ul>
                <li><a href="index.php?p=transaksi-peminjaman-input" class="<?php echo ($current_page == 'transaksi-peminjaman-input') ? 'active' : ''; ?>">Transaksi Peminjaman</a></li>
                <!-- Transaksi Pengembalian aktif jika p=transaksi-pengembalian atau p=transaksi-pengembalian-input -->
                <li><a href="index.php?p=transaksi-pengembalian" class="<?php echo ($current_page == 'transaksi-pengembalian' || $current_page == 'transaksi-pengembalian-input') ? 'active' : ''; ?>">Transaksi Pengembalian</a></li>
            </ul>

            <p class="label-navigasi">Laporan & Denda</p>
            <ul>
                <li><a href="index.php?p=laporan-transaksi" class="<?php echo ($current_page == 'laporan-transaksi') ? 'active' : ''; ?>">Laporan Transaksi</a></li>
                <li><a href="index.php?p=denda" class="<?php echo ($current_page == 'denda') ? 'active' : ''; ?>">Manajemen Denda</a></li>
            </ul>
            
            <p class="label-navigasi">ADMIN</p>

            <a href="logout.php">Logout</a>
        </div>

        <!-- CONTENT CONTAINER - TEMPAT SEMUA KONTEN DINAMIS DIMUAT -->
        <div id="content-container">
            <?php
            $pages_dir = 'pages';
            $file_to_include = null;

            if (!empty($_GET['p'])) {
                $pages = scandir($pages_dir, 0);
                unset($pages[0], $pages[1]);
                $p = $_GET['p'];

                // Logic routing
                if (in_array($p.'.php', $pages)) {
                    $file_to_include = $p.'.php';
                } else {
                    // Penanganan khusus untuk nama file yang berbeda dari nama p
                    if ($p == 'transaksi-peminjaman-input') $file_to_include = 'transaksi-peminjaman-input.php';
                    elseif ($p == 'transaksi-pengembalian-input') $file_to_include = 'transaksi-pengembalian-input.php';
                    elseif ($p == 'transaksi-pengembalian') $file_to_include = 'transaksi-pengembalian.php';
                    else $file_to_include = null; 
                }
                
                // Pengecekan akhir dan eksekusi include
                if ($file_to_include && file_exists($pages_dir.'/'.$file_to_include)) {
                    include($pages_dir.'/'.$file_to_include);
                } elseif($file_to_include) {
                    echo "<div id='content'><p>File {$file_to_include} Belum Dibuat!</p></div>";
                } else {
                    echo "<div id='content'><p>Halaman Tidak Ditemukan (404).</p></div>";
                }

            } else {
                // Default ke Beranda jika tidak ada parameter 'p'
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