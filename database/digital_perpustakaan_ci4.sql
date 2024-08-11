-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2024 at 12:04 AM
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
-- Database: `digital_perpustakaan_ci4`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(13, '2024-08-09-103831', 'App\\Database\\Migrations\\TbJabatan', 'default', 'App', 1723413332, 1),
(14, '2024-08-09-103833', 'App\\Database\\Migrations\\TbUser', 'default', 'App', 1723413332, 1),
(15, '2024-08-09-104310', 'App\\Database\\Migrations\\TbKategoriBuku', 'default', 'App', 1723413332, 1),
(16, '2024-08-09-104311', 'App\\Database\\Migrations\\TbBuku', 'default', 'App', 1723413332, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_buku`
--

CREATE TABLE `tb_buku` (
  `id_buku` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_kategori_buku` int(11) UNSIGNED NOT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `jumlah` varchar(255) NOT NULL,
  `file_cover_buku` varchar(255) DEFAULT NULL,
  `file_buku` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_jabatan`
--

CREATE TABLE `tb_jabatan` (
  `id_jabatan` int(11) UNSIGNED NOT NULL,
  `nama_jabatan` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_jabatan`
--

INSERT INTO `tb_jabatan` (`id_jabatan`, `nama_jabatan`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2024-08-11 21:55:37', '2024-08-11 21:55:37'),
(2, 'User', '2024-08-11 21:55:37', '2024-08-11 21:55:37');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori_buku`
--

CREATE TABLE `tb_kategori_buku` (
  `id_kategori_buku` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_jabatan` int(11) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_last_reset` datetime DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'aktif',
  `email` varchar(255) NOT NULL,
  `no_telepon` varchar(255) DEFAULT NULL,
  `file_profil` varchar(255) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `is_logged_in` tinyint(1) NOT NULL DEFAULT 0,
  `terakhir_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `id_jabatan`, `nama_lengkap`, `username`, `password`, `password_last_reset`, `status`, `email`, `no_telepon`, `file_profil`, `token`, `is_logged_in`, `terakhir_login`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin', 'admin', '$2y$10$oTPRLrviDxINXst6Bf6s6eYB876sziCuQIPEVu/mQ2bwTuGcdVg9i', NULL, 'aktif', 'jihadanbs11@gmail.com', '088215212122', 'admin.jpg', '', 0, '2024-08-11 21:55:37', '2024-08-11 21:55:37', '2024-08-11 21:55:37'),
(2, 2, 'Jihadan Beckhianosyuhada', 'jihadan', '$2y$10$tPRxA7LbHymcMDM.zzIP8.9OCOHqpuexHYvr/t00d3SuE9c1M64m2', NULL, 'aktif', 'jihadanb@gmail.com', '088212342233', 'user.jpg', '', 0, '2024-08-11 21:55:37', '2024-08-11 21:55:37', '2024-08-11 21:55:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_buku`
--
ALTER TABLE `tb_buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `tb_buku_id_kategori_buku_foreign` (`id_kategori_buku`),
  ADD KEY `tb_buku_id_user_foreign` (`id_user`);

--
-- Indexes for table `tb_jabatan`
--
ALTER TABLE `tb_jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `tb_kategori_buku`
--
ALTER TABLE `tb_kategori_buku`
  ADD PRIMARY KEY (`id_kategori_buku`),
  ADD KEY `tb_kategori_buku_id_user_foreign` (`id_user`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `tb_user_id_jabatan_foreign` (`id_jabatan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_buku`
--
ALTER TABLE `tb_buku`
  MODIFY `id_buku` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_jabatan`
--
ALTER TABLE `tb_jabatan`
  MODIFY `id_jabatan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_kategori_buku`
--
ALTER TABLE `tb_kategori_buku`
  MODIFY `id_kategori_buku` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_buku`
--
ALTER TABLE `tb_buku`
  ADD CONSTRAINT `tb_buku_id_kategori_buku_foreign` FOREIGN KEY (`id_kategori_buku`) REFERENCES `tb_kategori_buku` (`id_kategori_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_buku_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_kategori_buku`
--
ALTER TABLE `tb_kategori_buku`
  ADD CONSTRAINT `tb_kategori_buku_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `tb_user_id_jabatan_foreign` FOREIGN KEY (`id_jabatan`) REFERENCES `tb_jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
