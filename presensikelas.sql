-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table presensikelas.ci_sessions
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table presensikelas.ci_sessions: ~4 rows (approximately)
INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
	('180nuaiav4j01knt81nm7gtlie8ththj', '127.0.0.1', 1779353565, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313737393335333535393b69647c733a313a2231223b757365726e616d657c733a353a2261646d696e223b6e616d615f6c656e676b61707c733a31333a2241646d696e6973747261746f72223b726f6c657c733a353a2261646d696e223b666f746f5f70726f66696c7c4e3b656d61696c7c733a31343a2261646d696e40736d702e74657374223b6c6f676765645f696e7c623a313b6c6173745f61637469766974797c693a313737393335333536353b),
	('mm9gbav0g1to7d7rfags1pi38i409e22', '127.0.0.1', 1779386800, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313737393338363736353b69647c733a313a2232223b757365726e616d657c733a363a226b657073656b223b6e616d615f6c656e676b61707c733a31343a224b6570616c612053656b6f6c6168223b726f6c657c733a363a226b657073656b223b666f746f5f70726f66696c7c4e3b656d61696c7c733a31353a226b657073656b40736d702e74657374223b6c6f676765645f696e7c623a313b6c6173745f61637469766974797c693a313737393338363830303b),
	('ov7esooelcpfgrgrse5k5t89v3i837of', '127.0.0.1', 1779426837, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313737393432363638343b69647c733a313a2231223b757365726e616d657c733a353a2261646d696e223b6e616d615f6c656e676b61707c733a31333a2241646d696e6973747261746f72223b726f6c657c733a353a2261646d696e223b666f746f5f70726f66696c7c4e3b656d61696c7c733a31343a2261646d696e40736d702e74657374223b6c6f676765645f696e7c623a313b6c6173745f61637469766974797c693a313737393432363833373b),
	('l63ta4jirnjc6jp6foqprb5j3h4k04n6', '127.0.0.1', 1779447351, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313737393434373331303b69647c733a313a2233223b757365726e616d657c733a373a2267757275303031223b6e616d615f6c656e676b61707c733a31323a22427564692053616e746f736f223b726f6c657c733a343a2267757275223b666f746f5f70726f66696c7c4e3b656d61696c7c733a31363a226775727530303140736d702e74657374223b6c6f676765645f696e7c623a313b6c6173745f61637469766974797c693a313737393434373335313b);

-- Dumping structure for table presensikelas.tb_approval
CREATE TABLE IF NOT EXISTS `tb_approval` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_presensi` int unsigned NOT NULL,
  `id_siswa` int unsigned NOT NULL,
  `id_guru` int unsigned NOT NULL,
  `id_approver` int unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status_asli` enum('Izin','Sakit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_approval` enum('pending','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan_penolakan` text COLLATE utf8mb4_unicode_ci,
  `tanggal_approval` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_approval_presensi` (`id_presensi`),
  KEY `fk_approval_siswa` (`id_siswa`),
  KEY `fk_approval_guru` (`id_guru`),
  KEY `fk_approval_approver` (`id_approver`),
  CONSTRAINT `fk_approval_approver` FOREIGN KEY (`id_approver`) REFERENCES `tb_user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_approval_guru` FOREIGN KEY (`id_guru`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_approval_presensi` FOREIGN KEY (`id_presensi`) REFERENCES `tb_presensi` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_approval_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_approval: ~0 rows (approximately)

-- Dumping structure for table presensikelas.tb_guru
CREATE TABLE IF NOT EXISTS `tb_guru` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int unsigned DEFAULT NULL,
  `nip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `status_aktif` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nip` (`nip`),
  KEY `fk_guru_user` (`id_user`),
  CONSTRAINT `fk_guru_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_guru: ~5 rows (approximately)
INSERT INTO `tb_guru` (`id`, `id_user`, `nip`, `nama_lengkap`, `jenis_kelamin`, `no_hp`, `alamat`, `status_aktif`, `created_at`, `updated_at`) VALUES
	(1, 3, '198501012010011001', 'Budi Santoso', 'L', '081234567890', 'Jl. Pendidikan No. 1, Ubung', 1, '2026-05-20 16:48:58', '2026-05-20 16:48:58'),
	(4, 9, '44444', 'Idung', 'L', 'sdsad', 'asasa', 1, '2026-05-21 08:37:51', '2026-05-21 09:19:24'),
	(5, 10, '21212', 'weew', 'L', 'sdsad', 'Denpasar', 1, '2026-05-21 08:58:24', '2026-05-21 08:58:24'),
	(6, 20, '198501012010011000', 'Drs. John Doe, M.Pd', 'L', '081234567890', 'Jl. Pendidikan No. 45', 1, '2026-05-22 15:55:44', '2026-05-22 15:55:44'),
	(7, 21, '198602022011012000', 'Dra. Jane Smith, M.Pd', 'P', '081234567891', 'Jl. Guru No. 12', 1, '2026-05-22 15:55:45', '2026-05-22 15:55:45');

-- Dumping structure for table presensikelas.tb_jadwal
CREATE TABLE IF NOT EXISTS `tb_jadwal` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_guru` int unsigned NOT NULL,
  `id_kelas` int unsigned NOT NULL,
  `id_mapel` int unsigned NOT NULL,
  `id_tahun_ajaran` int unsigned NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `ruangan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_jadwal_guru` (`id_guru`),
  KEY `fk_jadwal_kelas` (`id_kelas`),
  KEY `fk_jadwal_mapel` (`id_mapel`),
  KEY `fk_jadwal_tahun` (`id_tahun_ajaran`),
  KEY `idx_jadwal_guru` (`id_guru`),
  KEY `idx_jadwal_kelas` (`id_kelas`),
  KEY `idx_jadwal_hari` (`hari`),
  CONSTRAINT `fk_jadwal_guru` FOREIGN KEY (`id_guru`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_jadwal_mapel` FOREIGN KEY (`id_mapel`) REFERENCES `tb_mata_pelajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_jadwal_tahun` FOREIGN KEY (`id_tahun_ajaran`) REFERENCES `tb_tahun_ajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_jadwal: ~6 rows (approximately)
INSERT INTO `tb_jadwal` (`id`, `id_guru`, `id_kelas`, `id_mapel`, `id_tahun_ajaran`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `status_aktif`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 1, 'Senin', '07:00:00', '08:30:00', 'Ruang 1', 1, '2026-05-20 16:48:58', '2026-05-20 16:48:58'),
	(2, 1, 1, 2, 1, 'Selasa', '07:00:00', '08:30:00', 'Ruang 1', 1, '2026-05-20 16:48:58', '2026-05-20 16:48:58'),
	(3, 1, 1, 3, 1, 'Rabu', '07:00:00', '08:30:00', 'Ruang 1', 1, '2026-05-20 16:48:58', '2026-05-20 16:48:58'),
	(4, 1, 1, 8, 1, 'Kamis', '07:00:00', '08:30:00', 'Lapangan', 1, '2026-05-21 21:41:58', '2026-05-22 17:55:01'),
	(5, 1, 1, 9, 1, 'Jumat', '07:00:00', '08:30:00', 'Ruang 7A', 1, '2026-05-22 11:25:37', '2026-05-22 11:25:37'),
	(6, 1, 1, 4, 1, 'Sabtu', '07:00:00', '08:30:00', 'Ruang 7A', 1, '2026-05-22 17:54:47', '2026-05-22 17:54:47');

-- Dumping structure for table presensikelas.tb_kelas
CREATE TABLE IF NOT EXISTS `tb_kelas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contoh: VII-A',
  `id_wali_kelas` int unsigned DEFAULT NULL,
  `id_tahun_ajaran` int unsigned NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_kelas_wali` (`id_wali_kelas`),
  KEY `fk_kelas_tahun` (`id_tahun_ajaran`),
  CONSTRAINT `fk_kelas_tahun` FOREIGN KEY (`id_tahun_ajaran`) REFERENCES `tb_tahun_ajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_kelas_wali` FOREIGN KEY (`id_wali_kelas`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_kelas: ~1 rows (approximately)
INSERT INTO `tb_kelas` (`id`, `nama_kelas`, `id_wali_kelas`, `id_tahun_ajaran`, `status_aktif`, `created_at`, `updated_at`) VALUES
	(1, 'VII-A', 1, 1, 1, '2026-05-20 16:48:58', '2026-05-21 01:14:19');

-- Dumping structure for table presensikelas.tb_kepala_sekolah
CREATE TABLE IF NOT EXISTS `tb_kepala_sekolah` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int unsigned DEFAULT NULL,
  `nip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nip_kepsek` (`nip`),
  KEY `fk_kepsek_user` (`id_user`),
  CONSTRAINT `fk_kepsek_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_kepala_sekolah: ~0 rows (approximately)

-- Dumping structure for table presensikelas.tb_log_aktivitas
CREATE TABLE IF NOT EXISTS `tb_log_aktivitas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int unsigned DEFAULT NULL,
  `aksi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabel` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_data` int unsigned DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_log_user` (`id_user`),
  KEY `idx_log_activity_created` (`created_at`),
  KEY `idx_log_activity_user` (`id_user`),
  CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_log_aktivitas: ~131 rows (approximately)
INSERT INTO `tb_log_aktivitas` (`id`, `id_user`, `aksi`, `tabel`, `id_data`, `keterangan`, `ip_address`, `user_agent`, `created_at`) VALUES
	(1, 1, 'insert', 'tb_tahun_ajaran', 0, 'Tambah tahun ajaran 2025/2026', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-20 23:49:28'),
	(2, 1, 'insert', 'tb_tahun_ajaran', 0, 'Tambah tahun ajaran 2025/2026', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:02:21'),
	(3, 1, 'insert', 'tb_tahun_ajaran', 2, 'Tambah tahun ajaran 2025/2026', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:10:59'),
	(4, 1, 'update', 'tb_tahun_ajaran', 0, 'Update tahun ajaran 2025/2026', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:11:11'),
	(5, 1, 'update', 'tb_tahun_ajaran', 0, 'Update tahun ajaran 2025/2026', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:17:37'),
	(6, 1, 'update', 'tb_tahun_ajaran', 0, 'Update tahun ajaran 2025/2026', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:25:01'),
	(7, 1, 'update', 'tb_tahun_ajaran', 0, 'Update tahun ajaran 2025/2026 ok', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:25:14'),
	(8, 1, 'update', 'tb_tahun_ajaran', 0, 'Update tahun ajaran 2025/2027', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:25:25'),
	(9, 1, 'update', 'tb_tahun_ajaran', 0, 'Update tahun ajaran 2025/2026', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:25:40'),
	(10, 1, 'insert', 'tb_tahun_ajaran', 3, 'Tambah tahun ajaran 2026/2027', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:26:00'),
	(11, 1, 'delete', 'tb_tahun_ajaran', 3, 'Hapus tahun ajaran', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:26:07'),
	(12, 1, 'update', 'tb_tahun_ajaran', 0, 'Update tahun ajaran 2026/2027', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:26:57'),
	(13, 1, 'update', 'tb_tahun_ajaran', 2, 'Update tahun ajaran 2025/2026', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:29:43'),
	(14, 1, 'update', 'tb_tahun_ajaran', 2, 'Update tahun ajaran 2026/2027', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:30:12'),
	(15, 1, 'update', 'tb_tahun_ajaran', 2, 'Update tahun ajaran 2025/2026', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:30:25'),
	(16, 1, 'update', 'tb_tahun_ajaran', 2, 'Update tahun ajaran 2026/2027', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 00:30:32'),
	(17, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 01:08:17'),
	(18, 1, 'UPDATE', 'tb_kelas', 1, 'Update kelas 7-A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 01:13:54'),
	(19, 1, 'INSERT', 'tb_kelas', 2, 'Tambah kelas 7 A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 01:14:04'),
	(20, 1, 'DELETE', 'tb_kelas', 2, 'Hapus kelas', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 01:14:09'),
	(21, 1, 'UPDATE', 'tb_kelas', 1, 'Update kelas VII-A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 01:14:20'),
	(22, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 02:15:47'),
	(23, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 02:15:53'),
	(24, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 02:17:52'),
	(25, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 02:18:02'),
	(26, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 02:22:39'),
	(27, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 02:23:06'),
	(28, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 02:23:33'),
	(29, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 02:29:46'),
	(30, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 02:30:30'),
	(31, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:01:05'),
	(32, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:01:21'),
	(33, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:02:20'),
	(34, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:02:31'),
	(35, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:02:36'),
	(36, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:02:40'),
	(37, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:07:03'),
	(38, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:07:08'),
	(39, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:07:45'),
	(40, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:15:55'),
	(41, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:18:50'),
	(42, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:19:28'),
	(43, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:22:30'),
	(44, 1, 'UPDATE', 'tb_guru', 0, 'Update guru Budi Santoso', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:23:10'),
	(45, 1, 'INSERT', 'tb_guru', 2, 'Tambah guru 222', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:24:27'),
	(46, 1, 'insert', 'tb_tahun_ajaran', 4, 'Tambah tahun ajaran 2025/2026 ok', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:26:03'),
	(47, 1, 'INSERT', 'tb_guru', 3, 'Tambah guru sfdsf', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:29:34'),
	(48, 1, 'delete', 'tb_tahun_ajaran', 4, 'Hapus tahun ajaran', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:30:41'),
	(49, 1, 'UPDATE', 'tb_kelas', 1, 'Update kelas VII-A', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:31:04'),
	(50, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa ', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 03:31:17'),
	(51, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 07:59:28'),
	(52, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:23:19'),
	(53, 1, 'logout', 'tb_user', 1, 'User logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:28:41'),
	(54, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:29:05'),
	(55, 1, 'logout', 'tb_user', 1, 'User logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:29:14'),
	(56, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:29:36'),
	(57, 1, 'DELETE', 'tb_guru', 3, 'Hapus guru', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:37:18'),
	(58, 1, 'UPDATE', 'tb_guru', 0, 'Update guru 222', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:37:27'),
	(59, 1, 'INSERT', 'tb_guru', 4, 'Tambah guru sdsad', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:37:51'),
	(60, 1, 'DELETE', 'tb_guru', 2, 'Hapus guru', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:49:41'),
	(61, 1, 'INSERT', 'tb_guru', 5, 'Tambah guru weew', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:58:24'),
	(62, 1, 'UPDATE', 'tb_guru', 0, 'Update guru sdsad', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 08:58:55'),
	(63, 1, 'UPDATE', 'tb_guru', 0, 'Update guru sdsad', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 09:04:02'),
	(64, 1, 'UPDATE', 'tb_guru', 0, 'Update guru sdsad', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 09:04:06'),
	(65, 1, 'UPDATE', 'tb_guru', 0, 'Update guru weew', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 09:04:16'),
	(66, 1, 'UPDATE', 'tb_guru', 0, 'Update guru weew', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 09:04:50'),
	(67, 1, 'UPDATE', 'tb_guru', 4, 'Update guru sdsad', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 09:18:58'),
	(68, 1, 'UPDATE', 'tb_guru', 4, 'Update guru Idung', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 09:19:15'),
	(69, 1, 'UPDATE', 'tb_guru', 4, 'Update guru Idung', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 09:19:24'),
	(70, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa Ahmad Rizki', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 10:24:48'),
	(71, 1, 'INSERT', 'tb_siswa', 3, 'Tambah siswa dsfds', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 10:25:33'),
	(72, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa dsfds', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 10:30:24'),
	(73, 1, 'UPDATE', 'tb_siswa', 0, 'Update siswa dsfds', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 10:36:11'),
	(74, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 12:27:33'),
	(75, 1, 'DELETE', 'tb_siswa', 3, 'Hapus siswa dsfds', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 12:27:57'),
	(76, 1, 'UPDATE', 'tb_siswa', 2, 'Update siswa Ahmad Rizki', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 12:34:53'),
	(77, 1, 'UPDATE', 'tb_siswa', 2, 'Update siswa Ahmad Rizki', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 12:35:08'),
	(78, 1, 'UPDATE', 'tb_siswa', 2, 'Update siswa Siti Nurhaliza', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 12:37:10'),
	(79, 1, 'UPDATE', 'tb_siswa', 2, 'Update siswa Siti Nurhaliza', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 12:37:34'),
	(80, 1, 'update', 'tb_siswa', NULL, 'Penempatan 1 siswa ke kelas', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 13:12:56'),
	(81, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:24:20'),
	(82, 1, 'INSERT', 'tb_mata_pelajaran', 5, 'Tambah mapel Olahraga', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:24:45'),
	(83, 1, 'INSERT', 'tb_mata_pelajaran', 6, 'Tambah mapel Ilmu Pengetahuan Alam dan Sosial', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:31:45'),
	(84, 1, 'DELETE', 'tb_mata_pelajaran', 6, 'Hapus mapel', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:35:34'),
	(85, 1, 'UPDATE', 'tb_mata_pelajaran', 0, 'Update mapel Olahraga c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:35:43'),
	(86, 1, 'UPDATE', 'tb_mata_pelajaran', 0, 'Update mapel Olahraga x', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:36:01'),
	(87, 1, 'UPDATE', 'tb_mata_pelajaran', 0, 'Update mapel Olahraga x', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:40:07'),
	(88, 1, 'DELETE', 'tb_mata_pelajaran', 5, 'Hapus mapel', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:40:25'),
	(89, 1, 'INSERT', 'tb_mata_pelajaran', 7, 'Tambah mapel Olahraga', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:46:07'),
	(90, 1, 'DELETE', 'tb_mata_pelajaran', 7, 'Hapus mapel', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:46:24'),
	(91, 1, 'INSERT', 'tb_mata_pelajaran', 8, 'Tambah mapel Olahraga', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:53:24'),
	(92, 1, 'INSERT', 'tb_mata_pelajaran', 9, 'Tambah mapel Ilmu Pengetahuan Alam dan Sosial', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:53:33'),
	(93, 1, 'UPDATE', 'tb_mata_pelajaran', 8, 'Update mapel Olahraga x', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:53:42'),
	(94, 1, 'UPDATE', 'tb_mata_pelajaran', 8, 'Update mapel Olahraga', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:53:54'),
	(95, 1, 'UPDATE', 'tb_mata_pelajaran', 8, 'Update mapel Olahraga', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 14:54:01'),
	(96, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 20:28:12'),
	(97, 1, 'insert', 'tb_jadwal', 4, 'Tambah jadwal pelajaran', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 21:41:58'),
	(98, 1, 'update', 'tb_jadwal', 4, 'Update jadwal pelajaran', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 21:55:44'),
	(99, 1, 'update', 'tb_jadwal', 4, 'Update jadwal pelajaran', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 21:56:03'),
	(100, 1, 'logout', 'tb_user', 1, 'User logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 22:20:43'),
	(101, 2, 'login', 'tb_user', 2, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-21 22:21:00'),
	(102, 2, 'login', 'tb_user', 2, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 00:01:52'),
	(103, 3, 'login', 'tb_user', 3, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 07:11:18'),
	(104, 3, 'logout', 'tb_user', 3, 'User logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 09:08:37'),
	(105, 4, 'login', 'tb_user', 4, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 09:09:26'),
	(106, 4, 'login', 'tb_user', 4, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 10:37:08'),
	(107, 4, 'logout', 'tb_user', 4, 'User logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 10:38:17'),
	(108, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 10:38:28'),
	(109, 1, 'update', 'tb_siswa', 1, 'Hapus siswa dari kelas', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 11:07:47'),
	(110, 1, 'update', 'tb_siswa', NULL, 'Penempatan 1 siswa ke kelas', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 11:08:02'),
	(111, 1, 'update', 'tb_siswa', NULL, 'Penempatan 1 siswa ke kelas', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 11:08:10'),
	(112, 1, 'update', 'tb_siswa', 1, 'Hapus siswa dari kelas', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 11:08:19'),
	(113, 1, 'update', 'tb_siswa', 2, 'Hapus siswa dari kelas', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 11:08:23'),
	(114, 1, 'update', 'tb_siswa', NULL, 'Penempatan 2 siswa ke kelas', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 11:08:34'),
	(115, 1, 'insert', 'tb_jadwal', 5, 'Tambah jadwal pelajaran', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 11:25:37'),
	(116, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 15:31:38'),
	(117, 1, 'import', 'tb_siswa', NULL, 'Import siswa: 8 berhasil, 1 gagal, 0 duplikat', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 15:38:59'),
	(118, 1, 'import', 'tb_siswa', NULL, 'Import siswa: 0 berhasil, 0 gagal, 2 duplikat', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 15:54:41'),
	(119, 1, 'import', 'tb_guru', NULL, 'Import guru: 2 berhasil, 0 gagal, 0 duplikat', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 15:55:45'),
	(120, 1, 'logout', 'tb_user', 1, 'User logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 15:57:29'),
	(121, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 15:59:02'),
	(122, 1, 'update', 'tb_siswa', NULL, 'Penempatan 2 siswa ke kelas', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 16:00:21'),
	(123, 1, 'logout', 'tb_user', 1, 'User logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 16:00:42'),
	(124, 3, 'login', 'tb_user', 3, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 16:00:50'),
	(125, 3, 'login', 'tb_user', 3, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 16:37:39'),
	(126, 3, 'logout', 'tb_user', 3, 'User logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 17:53:53'),
	(127, 1, 'login', 'tb_user', 1, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 17:54:05'),
	(128, 1, 'insert', 'tb_jadwal', 6, 'Tambah jadwal pelajaran', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 17:54:47'),
	(129, 1, 'update', 'tb_jadwal', 4, 'Update jadwal pelajaran', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 17:55:01'),
	(130, 1, 'logout', 'tb_user', 1, 'User logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 17:55:09'),
	(131, 3, 'login', 'tb_user', 3, 'User login berhasil', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-22 17:55:20');

-- Dumping structure for table presensikelas.tb_mata_pelajaran
CREATE TABLE IF NOT EXISTS `tb_mata_pelajaran` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mapel` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('wajib','muatan_lokal','pilihan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wajib',
  `status_aktif` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_kode_mapel` (`kode_mapel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_mata_pelajaran: ~6 rows (approximately)
INSERT INTO `tb_mata_pelajaran` (`id`, `kode_mapel`, `nama_mapel`, `kategori`, `status_aktif`, `created_at`, `updated_at`) VALUES
	(1, 'MTK', 'Matematika', 'wajib', 1, '2026-05-20 16:48:58', '2026-05-20 16:48:58'),
	(2, 'BHS', 'Bahasa Indonesia', 'wajib', 1, '2026-05-20 16:48:58', '2026-05-20 16:48:58'),
	(3, 'IPA', 'Ilmu Pengetahuan Alam', 'wajib', 1, '2026-05-20 16:48:58', '2026-05-20 16:48:58'),
	(4, 'ING', 'Bahasa Inggris', 'wajib', 1, '2026-05-20 16:48:58', '2026-05-20 16:48:58'),
	(8, 'OR', 'Olahraga', 'wajib', 1, '2026-05-21 14:53:24', '2026-05-21 14:54:01'),
	(9, 'IPAS', 'Ilmu Pengetahuan Alam dan Sosial', 'wajib', 1, '2026-05-21 14:53:33', '2026-05-21 14:53:33');

-- Dumping structure for table presensikelas.tb_presensi
CREATE TABLE IF NOT EXISTS `tb_presensi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_jadwal` int unsigned NOT NULL,
  `id_siswa` int unsigned NOT NULL,
  `id_guru` int unsigned NOT NULL,
  `id_guru_pengganti` int unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `metode` enum('web','manual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `waktu_input` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_presensi_jadwal` (`id_jadwal`),
  KEY `fk_presensi_siswa` (`id_siswa`),
  KEY `fk_presensi_guru` (`id_guru`),
  KEY `fk_presensi_guru_pengganti` (`id_guru_pengganti`),
  KEY `idx_presensi_tanggal` (`tanggal`),
  KEY `idx_presensi_siswa` (`id_siswa`),
  CONSTRAINT `fk_presensi_guru` FOREIGN KEY (`id_guru`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_presensi_guru_pengganti` FOREIGN KEY (`id_guru_pengganti`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_presensi_jadwal` FOREIGN KEY (`id_jadwal`) REFERENCES `tb_jadwal` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_presensi_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_presensi: ~0 rows (approximately)

-- Dumping structure for table presensikelas.tb_riwayat_kelas
CREATE TABLE IF NOT EXISTS `tb_riwayat_kelas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_siswa` int unsigned NOT NULL,
  `id_kelas` int unsigned NOT NULL,
  `id_tahun_ajaran` int unsigned NOT NULL,
  `status` enum('naik','tetap','turun') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'naik',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_riwayat_siswa` (`id_siswa`),
  KEY `fk_riwayat_kelas` (`id_kelas`),
  KEY `fk_riwayat_tahun` (`id_tahun_ajaran`),
  CONSTRAINT `fk_riwayat_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_riwayat_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_riwayat_tahun` FOREIGN KEY (`id_tahun_ajaran`) REFERENCES `tb_tahun_ajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_riwayat_kelas: ~7 rows (approximately)
INSERT INTO `tb_riwayat_kelas` (`id`, `id_siswa`, `id_kelas`, `id_tahun_ajaran`, `status`, `keterangan`, `created_at`) VALUES
	(2, 1, 1, 1, 'naik', NULL, '2026-05-21 13:12:55'),
	(3, 2, 1, 1, 'naik', NULL, '2026-05-22 11:08:01'),
	(4, 1, 1, 1, 'naik', NULL, '2026-05-22 11:08:10'),
	(5, 1, 1, 1, 'naik', NULL, '2026-05-22 11:08:34'),
	(6, 2, 1, 1, 'naik', NULL, '2026-05-22 11:08:34'),
	(7, 5, 1, 1, 'naik', NULL, '2026-05-22 16:00:21'),
	(8, 6, 1, 1, 'naik', NULL, '2026-05-22 16:00:21');

-- Dumping structure for table presensikelas.tb_siswa
CREATE TABLE IF NOT EXISTS `tb_siswa` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int unsigned DEFAULT NULL,
  `nis` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nama_ortu` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp_ortu` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_kelas` int unsigned DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nis` (`nis`),
  KEY `fk_siswa_user` (`id_user`),
  KEY `fk_siswa_kelas` (`id_kelas`),
  KEY `idx_siswa_kelas` (`id_kelas`),
  CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_siswa_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_siswa: ~4 rows (approximately)
INSERT INTO `tb_siswa` (`id`, `id_user`, `nis`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `nama_ortu`, `no_hp_ortu`, `id_kelas`, `status_aktif`, `created_at`, `updated_at`) VALUES
	(1, 4, '12345', 'Ahmad Rizki', 'L', 'Denpasar', '2012-05-15', 'Jl. Mawar No. 10', 'Rizki Pratama', '081234567891', 1, 1, '2026-05-20 16:48:58', '2026-05-22 11:08:34'),
	(2, 5, '12346', 'Siti Nurhaliza Cantika', 'P', 'Badung', '2012-08-20', 'Jl. Melati No. 20', 'Nurhaliza Ahmad', '081234567892', 1, 1, '2026-05-20 16:48:58', '2026-05-22 11:08:34'),
	(5, 13, '456789', 'Ahmad Santoso', 'L', 'Ubung', '2013-01-15', 'Jl. Raya Ubung No. 123', 'Budi Santoso', '081234567890', 1, 1, '2026-05-22 15:38:57', '2026-05-22 16:00:21'),
	(6, 14, '543210', 'Siti Aminah', 'P', 'Denpasar', '2013-03-20', 'Jl. Merdeka No. 45', 'Hasan Abdullah', '081234567891', 1, 1, '2026-05-22 15:38:58', '2026-05-22 16:00:21');

-- Dumping structure for table presensikelas.tb_tahun_ajaran
CREATE TABLE IF NOT EXISTS `tb_tahun_ajaran` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Format: 2025/2026',
  `semester` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT '0' COMMENT '1=Aktif, 0=Tidak Aktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tahun_semester` (`tahun_ajaran`,`semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_tahun_ajaran: ~2 rows (approximately)
INSERT INTO `tb_tahun_ajaran` (`id`, `tahun_ajaran`, `semester`, `status_aktif`, `created_at`, `updated_at`) VALUES
	(1, '2025/2026', '1', 1, '2026-05-20 16:48:58', '2026-05-20 16:48:58'),
	(2, '2026/2027', '2', 0, '2026-05-21 00:10:59', '2026-05-21 00:30:32');

-- Dumping structure for table presensikelas.tb_user
CREATE TABLE IF NOT EXISTS `tb_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','guru','siswa','kepsek') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`),
  KEY `idx_user_role` (`role`),
  KEY `idx_user_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table presensikelas.tb_user: ~12 rows (approximately)
INSERT INTO `tb_user` (`id`, `username`, `password`, `role`, `nama_lengkap`, `email`, `no_hp`, `foto_profil`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
	(1, 'admin', '$2b$10$G9rtb1rTdDYTpgjNtvu2YOUy3E2M.OwWqEeFNcjPZIXuK/9A0cena', 'admin', 'Administrator', 'admin@smp.test', NULL, NULL, 'aktif', '2026-05-22 17:54:05', '2026-05-20 16:48:58', '2026-05-22 17:54:05'),
	(2, 'kepsek', '$2b$10$4tM/hpNixUFgd.OpPgtWiOKjDstRDyTVb2HQ5ixvdak2WIUHlvqQy', 'kepsek', 'Kepala Sekolah', 'kepsek@smp.test', NULL, NULL, 'aktif', '2026-05-22 00:01:51', '2026-05-20 16:48:58', '2026-05-22 00:01:51'),
	(3, 'guru001', '$2b$10$5mzn3dGsaQl8tanvILlmYuqsWgEbn7MK0/14yJftECM0/m/XQnTK.', 'guru', 'Budi Santoso', 'guru001@smp.test', NULL, NULL, 'aktif', '2026-05-22 17:55:20', '2026-05-20 16:48:58', '2026-05-22 17:55:20'),
	(4, '12345', '$2b$10$gcLukpznSAlFLaNXUPReieD0WVG50AEFaEsss15ZlOccMXtZv4wmC', 'siswa', 'Ahmad Rizki', 'ahmad@test.com', '081234567893', NULL, 'aktif', '2026-05-22 10:37:08', '2026-05-20 16:48:58', '2026-05-22 10:37:08'),
	(5, '12346', '$2b$10$gcLukpznSAlFLaNXUPReieD0WVG50AEFaEsss15ZlOccMXtZv4wmC', 'siswa', 'Siti Nurhaliza Cantika', 'siti@test.com', '081234567893', NULL, 'aktif', NULL, '2026-05-20 16:48:58', '2026-05-21 12:37:34'),
	(6, '3333', '$2y$10$vrSXV2rzCCmSvjX7ccEvc./L0s6Q2dv9JMXp1SQYbj65jEKmAoeYS', 'guru', 'aaaa', 'aanterpercaya@gmail.com', '333', NULL, 'aktif', NULL, '2026-05-21 02:56:37', '2026-05-21 02:56:37'),
	(9, '4444', '$2y$10$x.5/vLmf.uv6BzFX33Ku3.PPOGHRV8MNhGYjv7dYboPx5089vVypO', 'guru', 'Idung', 'asas@asas.ss', 'sdsad', NULL, 'aktif', NULL, '2026-05-21 08:37:51', '2026-05-21 09:19:14'),
	(10, '21212', '$2y$10$owSUbNMteikxNrOzpM1IqupeMMGI6emVzIAQv3bAKvZszJ21JQNle', 'guru', 'weew', 'asas@asas.ss', 'sdsad', NULL, 'aktif', NULL, '2026-05-21 08:58:24', '2026-05-21 08:58:24'),
	(13, '456789', '$2y$10$jCyGLf.bliFiN.sM/dO0/eApMbpjrX9zi1FCWnvi5tTT1FQoz5S2q', 'siswa', 'Ahmad Santoso', NULL, NULL, NULL, 'aktif', NULL, '2026-05-22 15:38:57', '2026-05-22 15:38:57'),
	(14, '543210', '$2y$10$2l6XZ91WcYJ4auj2W2ocBeIKuIN.cegXNGt2eA1DI66K0jHJEYYde', 'siswa', 'Siti Aminah', NULL, NULL, NULL, 'aktif', NULL, '2026-05-22 15:38:58', '2026-05-22 15:38:58'),
	(20, '198501012010011000', '$2y$10$MQQ7fiDOhRLrxuAICT15weQDNqsomB4B4eiMZ7iaAKnBjwVXUJjBq', 'guru', 'Drs. John Doe, M.Pd', NULL, NULL, NULL, 'aktif', NULL, '2026-05-22 15:55:44', '2026-05-22 15:55:44'),
	(21, '198602022011012000', '$2y$10$GHjn2ritDfyV2YozRxGa2eJhOUSYKgUA5KG8c5JEZz3AShuNuKwTe', 'guru', 'Dra. Jane Smith, M.Pd', NULL, NULL, NULL, 'aktif', NULL, '2026-05-22 15:55:45', '2026-05-22 15:55:45');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
