-- Skema tabel session untuk CodeIgniter 3
-- Jalankan script ini di database Anda untuk mengaktifkan session berbasis database

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Penjelasan:
-- id: Session ID unik
-- ip_address: IP pengguna (untuk keamanan)
-- timestamp: Waktu aktivitas terakhir (digunakan untuk garbage collection)
-- data: Data session yang disimpan dalam format serialized
