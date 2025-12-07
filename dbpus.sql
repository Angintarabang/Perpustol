-- SQL Dump Final: dbpus (CLEAN VERSION)
-- Sudah disesuaikan dengan fitur Chaos Library
-- No HP dihapus, Fitur Denda & Transaksi aktif.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `tbanggota`
-- (Kolom 'nohp' SUDAH DIHAPUS agar sesuai tampilan)
--

CREATE TABLE `tbanggota` (
  `idanggota` varchar(5) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `jeniskelamin` varchar(10) NOT NULL,
  `alamat` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Tidak Meminjam',
  `foto` varchar(255) NOT NULL DEFAULT 'avatar-default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbanggota`
--

INSERT INTO `tbanggota` (`idanggota`, `nama`, `jeniskelamin`, `alamat`, `status`, `foto`) VALUES
('AG001', 'Andi Rahman', 'Pria', 'Jl. Contoh No 1', 'Tidak Meminjam', 'avatar-default.png'),
('AG002', 'Aini Rahmawati', 'Wanita', 'Jl.Anggrek No 45', 'Sedang Meminjam', 'avatar-default.png'),
('AG003', 'Rudi Hartono', 'Pria', 'Jl.Manggis 98', 'Sedang Meminjam', 'avatar-default.png'),
('AG004', 'Dino Riano', 'Pria', 'Jl.Melon No 33', 'Sedang Meminjam', 'avatar-default.png'),
('AG005', 'Agus Wardoyo', 'Pria', 'Jl.Cempedak No 88', 'Tidak Meminjam', 'avatar-default.png'),
('AG006', 'Shinta Riani', 'Wanita', 'JL.Jeruk No 1', 'Sedang Meminjam', 'avatar-default.png'),
('AG007', 'Irwan Hakim', 'Pria', 'Jl.Salak No 34', 'Tidak Meminjam', 'avatar-default.png'),
('AG008', 'Indah Dian', 'Wanita', 'Jl.Semangka No 23', 'Tidak Meminjam', 'avatar-default.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbbuku`
--

CREATE TABLE `tbbuku` (
  `idbuku` varchar(5) NOT NULL,
  `judulbuku` varchar(50) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `pengarang` varchar(40) NOT NULL,
  `penerbit` varchar(40) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbbuku`
--

INSERT INTO `tbbuku` (`idbuku`, `judulbuku`, `kategori`, `pengarang`, `penerbit`, `status`) VALUES
('BK001', 'Belajar PHP', 'Ilmu Komputer', 'Candra', 'Media Baca', 'Dipinjam'),
('BK002', 'Belajar HTML', 'Ilmu Komputer', 'Rahmat Hakim', 'Media Baca', 'Dipinjam'),
('BK003', 'Kumpulan Puisi', 'Karya Sastra', 'Bejo', 'Media Kita', 'Tersedia'),
('BK004', 'Sejarah Islam', 'Ilmu Agama', 'Sutejo', 'Media Kita', 'Dipinjam'),
('BK005', 'Pintar CSS', 'Ilmu Komputer', 'Anton', 'Graha Buku', 'Tersedia'),
('BK006', 'Kumpulan Cerpen', 'Karya Sastra', 'Rudi', 'Media Aksara', 'Dipinjam'),
('BK007', 'Keamanan Data', 'Ilmu Komputer', 'Nusron', 'Media Cipta', 'Dipinjam'),
('BK008', 'Dasar-Dasar Database', 'Ilmu Komputer', 'Andi', 'Graha Media', 'Tersedia'),
('BK009', 'Kumpulan Cerpen 2', 'Karya Sastra', 'Sutejo', 'Media Cipta', 'Tersedia'),
('BK010', 'Peradaban Islam', 'Ilmu Agama', 'Aminnudin', 'Media Baca', 'Tersedia'),
('BK011', 'Kumpulan Cerpen 3', 'Karya Sastra', 'Rudi', 'Media Baca', 'Tersedia'),
('BK012', 'Teknologi Informasi', 'Ilmu Komputer', 'Andi A', 'Media Baca', 'Tersedia'),
('BK013', 'Dermaga Biru', 'Karya Sastra', 'Sutejo', 'Media Cipta', 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `tbtransaksi`
--

CREATE TABLE `tbtransaksi` (
  `idtransaksi` varchar(5) NOT NULL,
  `idanggota` varchar(5) NOT NULL,
  `idbuku` varchar(5) NOT NULL,
  `tglpinjam` date NOT NULL,
  `tglkembali` date NOT NULL,
  `status_pengembalian` varchar(20) NOT NULL DEFAULT 'Dipinjam',
  `denda` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbtransaksi`
--

INSERT INTO `tbtransaksi` (`idtransaksi`, `idanggota`, `idbuku`, `tglpinjam`, `tglkembali`, `status_pengembalian`, `denda`) VALUES
('TR001', 'AG002', 'BK002', '2016-11-03', '0000-00-00', 'Dipinjam', 0),
('TR002', 'AG003', 'BK003', '2016-11-04', '2016-11-04', 'Sudah Kembali', 0),
('TR003', 'AG001', 'BK001', '2016-11-04', '2021-02-23', 'Sudah Kembali', 0),
('TR004', 'AG003', 'BK003', '2016-11-04', '2016-11-04', 'Sudah Kembali', 0),
('TR005', 'AG006', 'BK004', '2016-11-04', '2021-02-23', 'Sudah Kembali', 0),
('TR006', 'AG003', 'BK005', '2016-11-05', '2016-11-05', 'Sudah Kembali', 0),
('TR007', 'AG008', 'BK013', '2016-11-05', '2021-02-23', 'Sudah Kembali', 0),
('TR031', 'AG010', 'BK003', '2017-01-22', '2021-02-23', 'Sudah Kembali', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbpengembalian`
--

CREATE TABLE `tbpengembalian` (
  `idpengembalian` int(11) NOT NULL AUTO_INCREMENT,
  `idtransaksi` varchar(5) DEFAULT NULL,
  `idanggota` varchar(20) NOT NULL,
  `idbuku` varchar(20) NOT NULL,
  `tglkembali` date NOT NULL,
  `denda` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idpengembalian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbdenda`
--

CREATE TABLE `tbdenda` (
  `id_setting` INT(11) NOT NULL AUTO_INCREMENT,
  `denda_per_hari` INT(11) DEFAULT 5000,
  `maks_hari_pinjam` INT(11) DEFAULT 7,
  `maks_denda` INT(11) DEFAULT 50000,
  PRIMARY KEY (`id_setting`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbdenda` (`id_setting`, `denda_per_hari`, `maks_hari_pinjam`, `maks_denda`) VALUES
(1, 5000, 7, 50000);

-- --------------------------------------------------------

--
-- Table structure for table `tbuser`
--

CREATE TABLE `tbuser` (
  `iduser` varchar(5) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `alamat` varchar(40) NOT NULL,
  `password` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ganti admin jadi Chaos Team (Opsional)
INSERT INTO `tbuser` (`iduser`, `nama`, `alamat`, `password`) VALUES
('bijikajung', 'Admin Chaos', 'Perpustakaan Pusat', 'woekerjaincoek');

--
-- Indexes for tables
--

ALTER TABLE `tbanggota`
  ADD PRIMARY KEY (`idanggota`);

ALTER TABLE `tbbuku`
  ADD PRIMARY KEY (`idbuku`);

ALTER TABLE `tbtransaksi`
  ADD PRIMARY KEY (`idtransaksi`);

ALTER TABLE `tbuser`
  ADD PRIMARY KEY (`iduser`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;