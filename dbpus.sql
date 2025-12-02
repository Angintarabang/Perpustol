-- SQL Dump Final: dbpus
-- Struktur telah diperbaiki untuk mendukung transaksi dan laporan yang kompleks.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Pastikan database yang benar digunakan (HANYA JIKA ANDA TIDAK MENGIMPOR LANGSUNG)
-- USE dbpus; 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Table structure for table `tbanggota` (UPGRADED: Menambahkan nohp)
--

CREATE TABLE `tbanggota` (
  `idanggota` varchar(5) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `jeniskelamin` varchar(10) NOT NULL,
  `alamat` varchar(40) NOT NULL,

  `nohp` varchar(15) DEFAULT NULL, -- DITAMBAHKAN
=======
  `nohp` varchar(15) DEFAULT NULL, -- KOLOM TAMBAHAN UNTUK nohp
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
  `status` varchar(20) NOT NULL,
  `foto` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbanggota`
--

INSERT INTO `tbanggota` (`idanggota`, `nama`, `jeniskelamin`, `alamat`, `nohp`, `status`, `foto`) VALUES
('AG002', 'Aini Rahmawati', 'Wanita', 'Jl.Anggrek No 45', NULL, 'Sedang Meminjam', 'AG002.jpg'),
('AG003', 'Rudi Hartono', 'Pria', 'Jl.Manggis 98', NULL, 'Sedang Meminjam', ''),
('AG004', 'Dino Riano', 'Pria', 'Jl.Melon No 33', NULL, 'Sedang Meminjam', ''),
('AG005', 'Agus Wardoyo', 'Pria', 'Jl.Cempedak No 88', NULL, 'Tidak Meminjam', ''),
('AG006', 'Shinta Riani', 'Wanita', 'JL.Jeruk No 1', NULL, 'Sedang Meminjam', ''),
('AG007', 'Irwan Hakim', 'Pria', 'Jl.Salak No 34', NULL, 'Tidak Meminjam', ''),
('AG008', 'Indah Dian', 'Wanita', 'Jl.Semangka No 23', NULL, 'Tidak Meminjam', ''),
('AG009', 'Rina Auliah', 'Wanita', 'Jl.Merpati No 44', NULL, 'Tidak Meminjam', ''),
('AG010', 'Septi Putri', 'Wanita', 'Jl.Beringin No 2', NULL, 'Tidak Meminjam', ''),
('AG011', 'Herkules', 'Pria', 'Test', NULL, 'Tidak Meminjam', 'AG011.jpg'),
('AG014', 'Rangga', 'Pria', 'Jl.Manggis No 41', NULL, 'Tidak Meminjam', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

--
-- Table structure for table `tbtransaksi` (UPGRADED: Menambahkan status_pengembalian & denda)
--

CREATE TABLE `tbtransaksi` (
  `idtransaksi` varchar(5) NOT NULL,
  `idanggota` varchar(5) NOT NULL,
  `idbuku` varchar(5) NOT NULL,
  `tglpinjam` date NOT NULL,
  `tglkembali` date NOT NULL,

  `status_pengembalian` varchar(20) NOT NULL DEFAULT 'Dipinjam', -- DITAMBAHKAN
  `denda` int(11) NOT NULL DEFAULT 0                         -- DITAMBAHKAN
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `tbtransaksi` (`idtransaksi`, `idanggota`, `idbuku`, `tglpinjam`, `tglkembali`, `status_pengembalian`, `denda`) VALUES
('TR001', 'AG002', 'BK002', '2016-11-03', '0000-00-00', 'Dipinjam', 0),
('TR002', 'AG003', 'BK003', '2016-11-04', '2016-11-04', 'Sudah Kembali', 0),
('TR003', 'AG001', 'BK001', '2016-11-04', '2021-02-23', 'Sudah Kembali', 0),
('TR004', 'AG003', 'BK003', '2016-11-04', '2016-11-04', 'Sudah Kembali', 0),
('TR005', 'AG006', 'BK004', '2016-11-04', '2021-02-23', 'Sudah Kembali', 0),
('TR006', 'AG003', 'BK005', '2016-11-05', '2016-11-05', 'Sudah Kembali', 0),
('TR007', 'AG008', 'BK013', '2016-11-05', '2021-02-23', 'Sudah Kembali', 0),
('TR031', 'AG010', 'BK003', '2017-01-22', '2021-02-23', 'Sudah Kembali', 0);
=======
  `status_pengembalian` varchar(20) NOT NULL DEFAULT 'Dipinjam' -- KOLOM PENTING DITAMBAHKAN
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbtransaksi` (Data disesuaikan dengan status baru)
--

INSERT INTO `tbtransaksi` (`idtransaksi`, `idanggota`, `idbuku`, `tglpinjam`, `tglkembali`, `status_pengembalian`) VALUES
('TR001', 'AG002', 'BK002', '2016-11-03', '0000-00-00', 'Dipinjam'),
('TR002', 'AG003', 'BK003', '2016-11-04', '2016-11-04', 'Sudah Kembali'),
('TR003', 'AG001', 'BK001', '2016-11-04', '2021-02-23', 'Sudah Kembali'),
('TR004', 'AG003', 'BK003', '2016-11-04', '2016-11-04', 'Sudah Kembali'),
('TR005', 'AG006', 'BK004', '2016-11-04', '2021-02-23', 'Sudah Kembali'),
('TR006', 'AG003', 'BK005', '2016-11-05', '2016-11-05', 'Sudah Kembali'),
('TR007', 'AG008', 'BK013', '2016-11-05', '2021-02-23', 'Sudah Kembali'),
('TR031', 'AG010', 'BK003', '2017-01-22', '2021-02-23', 'Sudah Kembali');
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286

--
-- Table structure for table `tbuser`
--

CREATE TABLE `tbuser` (
  `iduser` varchar(5) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `alamat` varchar(40) NOT NULL,
  `password` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `tbuser` (`iduser`, `nama`, `alamat`, `password`) VALUES
('jwd', 'Andi Rahman Hakim', 'Jl.Pramuka No 9', '1234');

--
-- Table structure for table `tbpengembalian` (UPGRADED: Menambahkan idtransaksi)
--

CREATE TABLE `tbpengembalian` (
  `idpengembalian` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `idanggota` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `idbuku` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,

  `idtransaksi` varchar(5) DEFAULT NULL, -- DITAMBAHKAN
=======
  `idtransaksi` varchar(5) DEFAULT NULL, -- KOLOM PENTING DITAMBAHKAN UNTUK JOIN
>>>>>>> c15bd4d57d295ed13a547d4a807210918722a286
  `tglkembali` date NOT NULL,
  `denda` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `tbdenda` (Setting denda)
--

CREATE TABLE IF NOT EXISTS tbdenda (
  id_setting INT PRIMARY KEY AUTO_INCREMENT,
  denda_per_hari INT DEFAULT 5000,
  maks_hari_pinjam INT DEFAULT 7,
  maks_denda INT DEFAULT 50000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tbdenda (denda_per_hari, maks_hari_pinjam, maks_denda) 
VALUES (5000, 7, 50000);

--
-- Indexes for dumped tables
--
ALTER TABLE `tbanggota`
  ADD PRIMARY KEY (`idanggota`);

ALTER TABLE `tbbuku`
  ADD PRIMARY KEY (`idbuku`);

ALTER TABLE `tbtransaksi`
  ADD PRIMARY KEY (`idtransaksi`);

ALTER TABLE `tbuser`
  ADD PRIMARY KEY (`iduser`);

ALTER TABLE `tbpengembalian`
  ADD PRIMARY KEY (`idpengembalian`);

COMMIT;