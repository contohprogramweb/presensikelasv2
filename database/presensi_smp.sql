-- ============================================================
-- DATABASE: presensi_smp.sql
-- SISTEM INFORMASI PRESENSI SISWA SMP - SMPTK Galang Kasih Ubung
-- MySQL 5.7+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collate: utf8mb4_unicode_ci
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================================
-- 1. TABEL tb_tahun_ajaran
-- ============================================================
CREATE TABLE `tb_tahun_ajaran` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` VARCHAR(20) NOT NULL COMMENT 'Format: 2025/2026',
  `semester` ENUM('1','2') NOT NULL,
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=Aktif, 0=Tidak Aktif',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tahun_semester` (`tahun_ajaran`, `semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. TABEL tb_kelas
-- ============================================================
CREATE TABLE `tb_kelas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kelas` VARCHAR(50) NOT NULL COMMENT 'Contoh: VII-A',
  `id_wali_kelas` INT UNSIGNED DEFAULT NULL,
  `id_tahun_ajaran` INT UNSIGNED NOT NULL,
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_kelas_wali` (`id_wali_kelas`),
  KEY `fk_kelas_tahun` (`id_tahun_ajaran`),
  CONSTRAINT `fk_kelas_wali` FOREIGN KEY (`id_wali_kelas`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_kelas_tahun` FOREIGN KEY (`id_tahun_ajaran`) REFERENCES `tb_tahun_ajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. TABEL tb_mata_pelajaran
-- ============================================================
CREATE TABLE `tb_mata_pelajaran` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_mapel` VARCHAR(20) NOT NULL,
  `nama_mapel` VARCHAR(100) NOT NULL,
  `kategori` ENUM('wajib','muatan_lokal','pilihan') NOT NULL DEFAULT 'wajib',
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_kode_mapel` (`kode_mapel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. TABEL tb_user
-- ============================================================
CREATE TABLE `tb_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','guru','siswa','kepsek') NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `foto_profil` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `last_login` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. TABEL tb_siswa
-- ============================================================
CREATE TABLE `tb_siswa` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` INT UNSIGNED DEFAULT NULL,
  `nis` VARCHAR(20) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `jenis_kelamin` ENUM('L','P') NOT NULL,
  `tempat_lahir` VARCHAR(100) DEFAULT NULL,
  `tanggal_lahir` DATE DEFAULT NULL,
  `alamat` TEXT,
  `nama_ortu` VARCHAR(100) DEFAULT NULL,
  `no_hp_ortu` VARCHAR(20) DEFAULT NULL,
  `id_kelas` INT UNSIGNED DEFAULT NULL,
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nis` (`nis`),
  KEY `fk_siswa_user` (`id_user`),
  KEY `fk_siswa_kelas` (`id_kelas`),
  CONSTRAINT `fk_siswa_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. TABEL tb_riwayat_kelas
-- ============================================================
CREATE TABLE `tb_riwayat_kelas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_siswa` INT UNSIGNED NOT NULL,
  `id_kelas` INT UNSIGNED NOT NULL,
  `id_tahun_ajaran` INT UNSIGNED NOT NULL,
  `status` ENUM('naik','tetap','turun') NOT NULL DEFAULT 'naik',
  `keterangan` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_riwayat_siswa` (`id_siswa`),
  KEY `fk_riwayat_kelas` (`id_kelas`),
  KEY `fk_riwayat_tahun` (`id_tahun_ajaran`),
  CONSTRAINT `fk_riwayat_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_riwayat_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_riwayat_tahun` FOREIGN KEY (`id_tahun_ajaran`) REFERENCES `tb_tahun_ajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. TABEL tb_guru
-- ============================================================
CREATE TABLE `tb_guru` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` INT UNSIGNED DEFAULT NULL,
  `nip` VARCHAR(30) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `jenis_kelamin` ENUM('L','P') NOT NULL,
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `alamat` TEXT,
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nip` (`nip`),
  KEY `fk_guru_user` (`id_user`),
  CONSTRAINT `fk_guru_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Update FK di tb_kelas setelah tb_guru dibuat
ALTER TABLE `tb_kelas`
  DROP FOREIGN KEY `fk_kelas_wali`,
  ADD CONSTRAINT `fk_kelas_wali` FOREIGN KEY (`id_wali_kelas`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- ============================================================
-- 8. TABEL tb_kepala_sekolah
-- ============================================================
CREATE TABLE `tb_kepala_sekolah` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` INT UNSIGNED DEFAULT NULL,
  `nip` VARCHAR(30) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `tanggal_mulai` DATE DEFAULT NULL,
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nip_kepsek` (`nip`),
  KEY `fk_kepsek_user` (`id_user`),
  CONSTRAINT `fk_kepsek_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. TABEL tb_jadwal
-- ============================================================
CREATE TABLE `tb_jadwal` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_guru` INT UNSIGNED NOT NULL,
  `id_kelas` INT UNSIGNED NOT NULL,
  `id_mapel` INT UNSIGNED NOT NULL,
  `id_tahun_ajaran` INT UNSIGNED NOT NULL,
  `hari` ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` TIME NOT NULL,
  `jam_selesai` TIME NOT NULL,
  `ruangan` VARCHAR(50) DEFAULT NULL,
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_jadwal_guru` (`id_guru`),
  KEY `fk_jadwal_kelas` (`id_kelas`),
  KEY `fk_jadwal_mapel` (`id_mapel`),
  KEY `fk_jadwal_tahun` (`id_tahun_ajaran`),
  CONSTRAINT `fk_jadwal_guru` FOREIGN KEY (`id_guru`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_jadwal_mapel` FOREIGN KEY (`id_mapel`) REFERENCES `tb_mata_pelajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_jadwal_tahun` FOREIGN KEY (`id_tahun_ajaran`) REFERENCES `tb_tahun_ajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. TABEL tb_presensi
-- ============================================================
CREATE TABLE `tb_presensi` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_jadwal` INT UNSIGNED NOT NULL,
  `id_siswa` INT UNSIGNED NOT NULL,
  `id_guru` INT UNSIGNED NOT NULL,
  `id_guru_pengganti` INT UNSIGNED DEFAULT NULL,
  `tanggal` DATE NOT NULL,
  `status` ENUM('Hadir','Izin','Sakit','Alpa') NOT NULL,
  `keterangan` TEXT,
  `metode` ENUM('web','manual') NOT NULL DEFAULT 'web',
  `waktu_input` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_presensi_jadwal` (`id_jadwal`),
  KEY `fk_presensi_siswa` (`id_siswa`),
  KEY `fk_presensi_guru` (`id_guru`),
  KEY `fk_presensi_guru_pengganti` (`id_guru_pengganti`),
  CONSTRAINT `fk_presensi_jadwal` FOREIGN KEY (`id_jadwal`) REFERENCES `tb_jadwal` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_presensi_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_presensi_guru` FOREIGN KEY (`id_guru`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_presensi_guru_pengganti` FOREIGN KEY (`id_guru_pengganti`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 11. TABEL tb_approval
-- ============================================================
CREATE TABLE `tb_approval` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_presensi` INT UNSIGNED NOT NULL,
  `id_siswa` INT UNSIGNED NOT NULL,
  `id_guru` INT UNSIGNED NOT NULL,
  `id_approver` INT UNSIGNED DEFAULT NULL,
  `tanggal` DATE NOT NULL,
  `status_asli` ENUM('Izin','Sakit') NOT NULL,
  `status_approval` ENUM('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `catatan_penolakan` TEXT,
  `tanggal_approval` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_approval_presensi` (`id_presensi`),
  KEY `fk_approval_siswa` (`id_siswa`),
  KEY `fk_approval_guru` (`id_guru`),
  KEY `fk_approval_approver` (`id_approver`),
  CONSTRAINT `fk_approval_presensi` FOREIGN KEY (`id_presensi`) REFERENCES `tb_presensi` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_approval_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `tb_siswa` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_approval_guru` FOREIGN KEY (`id_guru`) REFERENCES `tb_guru` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_approval_approver` FOREIGN KEY (`id_approver`) REFERENCES `tb_user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 12. TABEL tb_log_aktivitas
-- ============================================================
CREATE TABLE `tb_log_aktivitas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` INT UNSIGNED DEFAULT NULL,
  `aksi` VARCHAR(100) NOT NULL,
  `tabel` VARCHAR(50) DEFAULT NULL,
  `id_data` INT UNSIGNED DEFAULT NULL,
  `keterangan` TEXT,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_log_user` (`id_user`),
  CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DATA DUMMY UNTUK TESTING
-- ============================================================

-- Password hash untuk: admin123, kepsek123, guru123, siswa123
-- Menggunakan PASSWORD_BCRYPT default cost

-- 1. Tahun Ajaran Aktif
INSERT INTO `tb_tahun_ajaran` (`tahun_ajaran`, `semester`, `status_aktif`) VALUES
('2025/2026', '1', 1);

-- 2. Mata Pelajaran
INSERT INTO `tb_mata_pelajaran` (`kode_mapel`, `nama_mapel`, `kategori`) VALUES
('MTK', 'Matematika', 'wajib'),
('BHS', 'Bahasa Indonesia', 'wajib'),
('IPA', 'Ilmu Pengetahuan Alam', 'wajib'),
('ING', 'Bahasa Inggris', 'wajib');

-- 3. User Admin
INSERT INTO `tb_user` (`username`, `password`, `role`, `nama_lengkap`, `email`, `status`) VALUES
('admin', '$2b$10$G9rtb1rTdDYTpgjNtvu2YOUy3E2M.OwWqEeFNcjPZIXuK/9A0cena', 'admin', 'Administrator', 'admin@smp.test', 'aktif');

-- 4. User Kepala Sekolah
INSERT INTO `tb_user` (`username`, `password`, `role`, `nama_lengkap`, `email`, `status`) VALUES
('kepsek', '$2b$10$4tM/hpNixUFgd.OpPgtWiOKjDstRDyTVb2HQ5ixvdak2WIUHlvqQy', 'kepsek', 'Kepala Sekolah', 'kepsek@smp.test', 'aktif');

-- 5. User Guru + Data Guru
INSERT INTO `tb_user` (`username`, `password`, `role`, `nama_lengkap`, `email`, `status`) VALUES
('guru001', '$2b$10$5mzn3dGsaQl8tanvILlmYuqsWgEbn7MK0/14yJftECM0/m/XQnTK.', 'guru', 'Budi Santoso', 'guru001@smp.test', 'aktif');

INSERT INTO `tb_guru` (`id_user`, `nip`, `nama_lengkap`, `jenis_kelamin`, `no_hp`, `alamat`) VALUES
(LAST_INSERT_ID(), '198501012010011001', 'Budi Santoso', 'L', '081234567890', 'Jl. Pendidikan No. 1, Ubung');

-- 6. Kelas VII-A dengan Wali Kelas
INSERT INTO `tb_kelas` (`nama_kelas`, `id_wali_kelas`, `id_tahun_ajaran`) VALUES
('VII-A', 1, 1);

-- 7. User Siswa + Data Siswa
INSERT INTO `tb_user` (`username`, `password`, `role`, `nama_lengkap`, `email`, `status`) VALUES
('12345', '$2b$10$gcLukpznSAlFLaNXUPReieD0WVG50AEFaEsss15ZlOccMXtZv4wmC', 'siswa', 'Ahmad Rizki', 'ahmad@test.com', 'aktif'),
('12346', '$2b$10$gcLukpznSAlFLaNXUPReieD0WVG50AEFaEsss15ZlOccMXtZv4wmC', 'siswa', 'Siti Nurhaliza', 'siti@test.com', 'aktif');

INSERT INTO `tb_siswa` (`id_user`, `nis`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `nama_ortu`, `no_hp_ortu`, `id_kelas`) VALUES
(3, '12345', 'Ahmad Rizki', 'L', 'Denpasar', '2012-05-15', 'Jl. Mawar No. 10', 'Rizki Pratama', '081234567891', 1),
(4, '12346', 'Siti Nurhaliza', 'P', 'Badung', '2012-08-20', 'Jl. Melati No. 20', 'Nurhaliza Ahmad', '081234567892', 1);

-- 8. Jadwal Pelajaran
INSERT INTO `tb_jadwal` (`id_guru`, `id_kelas`, `id_mapel`, `id_tahun_ajaran`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`) VALUES
(1, 1, 1, 1, 'Senin', '07:00:00', '08:30:00', 'Ruang 1'),
(1, 1, 2, 1, 'Selasa', '07:00:00', '08:30:00', 'Ruang 1'),
(1, 1, 3, 1, 'Rabu', '07:00:00', '08:30:00', 'Ruang 1');

-- ===========================================================
-- INDEX TAMBAHAN UNTUK PERFORMA (Optimasi Query)
-- ===========================================================
-- Index untuk pencarian presensi berdasarkan tanggal dan kelas
CREATE INDEX idx_presensi_tanggal ON tb_presensi(tanggal);
CREATE INDEX idx_presensi_kelas ON tb_presensi(id_kelas);
CREATE INDEX idx_presensi_siswa ON tb_presensi(id_siswa);

-- Index untuk pencarian riwayat presensi
CREATE INDEX idx_riwayat_presensi_tanggal ON tb_riwayat_presensi(tanggal);
CREATE INDEX idx_riwayat_presensi_siswa ON tb_riwayat_presensi(id_siswa);

-- Index untuk pencarian jadwal
CREATE INDEX idx_jadwal_guru ON tb_jadwal(id_guru);
CREATE INDEX idx_jadwal_kelas ON tb_jadwal(id_kelas);
CREATE INDEX idx_jadwal_hari ON tb_jadwal(hari);

-- Index untuk pencarian user berdasarkan role dan status
CREATE INDEX idx_user_role ON tb_user(role);
CREATE INDEX idx_user_status ON tb_user(status);

-- Index untuk pencarian siswa berdasarkan kelas
CREATE INDEX idx_siswa_kelas ON tb_siswa(id_kelas);

-- Index untuk log aktivitas
CREATE INDEX idx_log_activity_waktu ON tb_log_aktivitas(waktu);
CREATE INDEX idx_log_activity_user ON tb_log_aktivitas(id_user);

COMMIT;
