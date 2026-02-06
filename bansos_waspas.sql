-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2026 at 07:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bansos_waspas`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_kriteria`
--

CREATE TABLE `data_kriteria` (
  `id` int(11) NOT NULL,
  `penerima_id` int(11) NOT NULL,
  `c1` decimal(5,2) DEFAULT 0.00 COMMENT 'Nilai desimal: 0.33, 0.50, 1.00',
  `c2` decimal(5,2) DEFAULT 0.00,
  `c3` decimal(5,2) DEFAULT 0.00,
  `c4` decimal(5,2) DEFAULT 0.00,
  `c5` decimal(5,2) DEFAULT 0.00,
  `c6` decimal(5,2) DEFAULT 0.00,
  `c7` decimal(5,2) DEFAULT 0.00,
  `c8` decimal(5,2) DEFAULT 0.00,
  `c9` decimal(5,2) DEFAULT 0.00,
  `c10` decimal(5,2) DEFAULT 0.00,
  `c11` decimal(5,2) DEFAULT 0.00,
  `c12` decimal(5,2) DEFAULT 0.00,
  `c13` decimal(5,2) DEFAULT 0.00,
  `c14` decimal(5,2) DEFAULT 0.00,
  `total_bobot` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_kriteria`
--

INSERT INTO `data_kriteria` (`id`, `penerima_id`, `c1`, `c2`, `c3`, `c4`, `c5`, `c6`, `c7`, `c8`, `c9`, `c10`, `c11`, `c12`, `c13`, `c14`, `total_bobot`, `created_at`) VALUES
(2, 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 16:49:14'),
(3, 2, 0.50, 0.50, 0.50, 0.50, 0.50, 1.00, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 16:51:45'),
(4, 3, 1.00, 1.00, 1.00, 1.00, 0.50, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 16:53:11'),
(5, 4, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 17:05:20'),
(6, 5, 1.00, 1.00, 1.00, 1.00, 0.50, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 17:06:58'),
(7, 6, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 17:09:53'),
(8, 7, 1.00, 1.00, 1.00, 1.00, 0.50, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 17:13:06'),
(9, 8, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 17:14:28'),
(10, 9, 1.00, 1.00, 1.00, 1.00, 0.50, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 17:15:59'),
(11, 10, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 17:22:06'),
(12, 11, 1.00, 1.00, 1.00, 1.00, 0.50, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 17:24:07'),
(13, 12, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 17:26:55'),
(14, 13, 1.00, 1.00, 1.00, 1.00, 0.50, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 17:34:04'),
(15, 14, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 17:35:26'),
(16, 15, 1.00, 1.00, 1.00, 1.00, 0.50, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 17:36:20'),
(17, 26, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 17:39:56'),
(18, 16, 0.33, 0.33, 0.33, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 1.00, 1.00, 0.33, 0.33, 1.00, 0.00, '2026-02-02 17:41:33'),
(19, 17, 1.00, 1.00, 1.00, 1.00, 0.50, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 18:02:37'),
(20, 18, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 18:03:27'),
(21, 19, 1.00, 1.00, 1.00, 1.00, 0.50, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 18:04:15'),
(22, 20, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 18:05:25'),
(23, 21, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 18:06:11'),
(24, 22, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.50, 0.67, 0.67, 0.67, 0.67, 0.50, 0.50, 0.67, 0.00, '2026-02-02 18:06:51'),
(25, 23, 1.00, 1.00, 1.00, 1.00, 0.50, 1.00, 1.00, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 0.33, 0.00, '2026-02-02 18:07:27'),
(26, 24, 0.33, 0.33, 0.33, 0.33, 0.33, 0.33, 0.33, 1.00, 1.00, 1.00, 1.00, 0.33, 0.33, 1.00, 0.00, '2026-02-02 18:08:24');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int(11) NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `bobot` decimal(5,2) NOT NULL,
  `jenis` enum('benefit','cost') DEFAULT 'benefit',
  `keterangan` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `kode`, `nama`, `bobot`, `jenis`, `keterangan`, `status`) VALUES
(1, 'C1', 'Luas lantai per kapita', 8.00, 'cost', 'Kriteria Keluarga Miskin', 'aktif'),
(2, 'C2', 'Jenis lantai', 6.00, 'cost', 'Kriteria Keluarga Miskin', 'aktif'),
(3, 'C3', 'Jenis dinding', 6.00, 'cost', 'Kriteria Keluarga Miskin', 'aktif'),
(4, 'C4', 'Akses fasilitas buang air', 7.00, 'cost', 'Kriteria Keluarga Miskin', 'aktif'),
(5, 'C5', 'Sumber penerangan', 6.00, 'cost', 'Kriteria Keluarga Miskin', 'aktif'),
(6, 'C6', 'Sumber air minum', 6.00, 'cost', 'Kriteria Keluarga Miskin', 'aktif'),
(7, 'C7', 'Bahan bakar memasak', 5.00, 'cost', 'Kriteria Keluarga Miskin', 'aktif'),
(8, 'C8', 'Konsumsi protein mingguan', 6.00, 'benefit', 'Kriteria Keluarga Miskin', 'aktif'),
(9, 'C9', 'Kepemilikan pakaian tahunan', 4.00, 'benefit', 'Kriteria Keluarga Miskin', 'aktif'),
(10, 'C10', 'Frekuensi makan harian', 7.00, 'benefit', 'Kriteria Keluarga Miskin', 'aktif'),
(11, 'C11', 'Kemampuan berobat', 7.00, 'benefit', 'Kriteria Keluarga Miskin', 'aktif'),
(12, 'C12', 'Sumber penghasilan & besaran', 12.00, 'cost', 'Kriteria Keluarga Miskin', 'aktif'),
(13, 'C13', 'Pendidikan kepala keluarga', 10.00, 'cost', 'Kriteria Keluarga Miskin', 'aktif'),
(14, 'C14', 'Aset/tabungan likuid', 10.00, 'benefit', 'Kriteria Keluarga Miskin', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `penerima_bantuan`
--

CREATE TABLE `penerima_bantuan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `nama_bank` varchar(50) DEFAULT NULL,
  `jml_penghuni` int(11) DEFAULT NULL,
  `pkh` varchar(10) DEFAULT NULL,
  `bpnt` varchar(10) DEFAULT NULL,
  `kp` varchar(10) DEFAULT NULL,
  `kehilangan_pekerjaan` varchar(10) DEFAULT NULL,
  `tidak_terdata` varchar(10) DEFAULT NULL,
  `sakit_kronis` varchar(10) DEFAULT NULL,
  `rekomendasi` varchar(20) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penerima_bantuan`
--

INSERT INTO `penerima_bantuan` (`id`, `nama`, `nik`, `alamat`, `no_rekening`, `nama_bank`, `jml_penghuni`, `pkh`, `bpnt`, `kp`, `kehilangan_pekerjaan`, `tidak_terdata`, `sakit_kronis`, `rekomendasi`, `no_hp`, `keterangan`, `created_at`) VALUES
(1, 'HELMI DESWATI', '1306145607940001', 'TABEK LUBUAK JORONG SURAU PINANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '--', '-', '2026-01-29 22:47:00'),
(2, 'ZULFITRA YANTI', '1306074209610001', 'JORONG SURAU PINANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:29:24'),
(3, 'WISMAR', '1306071506470001', 'TABEK LUBUAK JORONG SURAU PINANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '--', '-', '2026-01-30 10:30:09'),
(4, 'ASMAINI', '1306075211430005', 'ANJUANG JORONG SURAU PINANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:30:45'),
(5, 'SYAFNIL', '1306072604520001', 'JORONG SURAU PINANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:31:12'),
(6, 'ARIADI', '1306070101600002', 'JORONG SURAU PINANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '--', '-', '2026-01-30 10:35:02'),
(7, 'ROSNIDA', '1306074106640004', 'JORONG SURAU PINANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:35:27'),
(8, 'FRIESTA AIDUL FITRIANI', '1306076911030001', 'JORONG SURAU PINAN', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:35:55'),
(9, 'MASNIDAR', '1306075509480001', 'PAKAN LADANG ATEH JORONG SURAU KAMBA', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:36:25'),
(10, 'DENI RAHMA', '1306074806830001', 'JIREK JORONG SURAU KAMBA', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '--', '-', '2026-01-30 10:36:48'),
(11, 'BASRI', '1306070803420002', 'PAKAN LADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:37:11'),
(12, 'DARMAINI D', '1306076505420002', 'MEJAN PATAH JORONG SURAU KAMBA', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:37:51'),
(13, 'DARMINI', '1306074707390004', 'MEJAN PATAH JORONG SURAU KAMBA', '--', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:38:10'),
(14, 'GADELIS', '1306076712640004', 'PAKAN LADANG ATEH JORONG SURAU KAMBA', '--', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:38:35'),
(15, 'ARMEN', '1306071901610001', 'MEJAN PATAH JORONG SURAU KAMBA', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:39:04'),
(16, 'PUTRI AMELIA WISZYAR', '3173046909990005', 'KAPALO KOTO JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:39:26'),
(17, 'INDRA YANI', '1306081007850001', 'TANGAH KOTO JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:39:47'),
(18, 'NURWITA', '1306075311820001', 'EKOR KOTO JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:40:44'),
(19, 'MASNIDAR', '1306075210450001', 'EKOR KOTO JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:41:03'),
(20, 'AMNA', '1306077112490002', 'AIR TABIT JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:41:27'),
(21, 'ZURTINI', '1306075208640001', 'EKOR KOTO JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:41:55'),
(22, 'HIDAYATI', '3172026402800001', 'JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:42:18'),
(23, 'RUDATIN', '1306074309530001', 'EKOR KOTO JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:42:38'),
(24, 'ANTON MULYADI', '1306070401780001', 'TANGAH KOTO JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:42:59'),
(25, 'MEN LEO GUCI', '1306072507710001', 'KAPALO KOTO JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-01-30 10:43:36'),
(26, 'DEZI SUSWITA', '1306076801740001', 'EKOR KOTO JORONG AMPANG GADANG', '-', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '-', '2026-02-02 17:37:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$Gdiww9FI.P0BcOpP6jNdj..L3czOua9x73g7VPegZVd3xbVNcY5lO', 'Administrator', 'admin@bansos.test', 'admin', '2026-01-27 18:57:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_kriteria`
--
ALTER TABLE `data_kriteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penerima_id` (`penerima_id`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `penerima_bantuan`
--
ALTER TABLE `penerima_bantuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_kriteria`
--
ALTER TABLE `data_kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `penerima_bantuan`
--
ALTER TABLE `penerima_bantuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_kriteria`
--
ALTER TABLE `data_kriteria`
  ADD CONSTRAINT `fk_data_penerima` FOREIGN KEY (`penerima_id`) REFERENCES `penerima_bantuan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
