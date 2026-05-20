<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-05-21 00:02:21 --> Query error: Column 'status_aktif' cannot be null - Invalid query: INSERT INTO `tb_tahun_ajaran` (`tahun_ajaran`, `semester`, `status_aktif`, `created_at`) VALUES ('2025/2026', '2', NULL, '2026-05-21 00:02:21')
ERROR - 2026-05-21 00:29:43 --> Query error: Duplicate entry '2025/2026-1' for key 'tb_tahun_ajaran.unique_tahun_semester' - Invalid query: UPDATE `tb_tahun_ajaran` SET `tahun_ajaran` = '2025/2026', `semester` = '1', `status_aktif` = 0, `updated_at` = '2026-05-21 00:29:43'
WHERE `id` = 2
ERROR - 2026-05-21 00:30:25 --> Query error: Duplicate entry '2025/2026-1' for key 'tb_tahun_ajaran.unique_tahun_semester' - Invalid query: UPDATE `tb_tahun_ajaran` SET `tahun_ajaran` = '2025/2026', `semester` = '1', `status_aktif` = 0, `updated_at` = '2026-05-21 00:30:25'
WHERE `id` = 2
ERROR - 2026-05-21 00:34:25 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:34:25 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:34:33 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:34:33 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:35:30 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:35:30 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:37:45 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:37:45 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:37:51 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:37:51 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:37:54 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:37:54 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:38:06 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:38:06 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:38:43 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:38:43 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:38:50 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:38:50 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:38:55 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:38:55 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:39:32 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:39:32 --> Severity: error --> Exception: Call to a member function result_array() on bool C:\laragon\www\presensikelas\application\models\admin\M_kelas.php 23
ERROR - 2026-05-21 00:40:33 --> Query error: Unknown column 't.tahun' in 'field list' - Invalid query: SELECT `k`.*, `u`.`nama_lengkap` as `wali_nama`, `t`.`tahun`, `t`.`semester`
FROM `tb_kelas` `k`
LEFT JOIN `tb_guru` `g` ON `g`.`id` = `k`.`id_wali_kelas`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `k`.`id_tahun_ajaran`
WHERE `k`.`id_tahun_ajaran` = '1'
ERROR - 2026-05-21 00:41:57 --> Severity: Notice --> Undefined index: tahun C:\laragon\www\presensikelas\application\controllers\admin\Kelas.php 44
ERROR - 2026-05-21 00:42:00 --> Severity: Notice --> Undefined index: tahun C:\laragon\www\presensikelas\application\controllers\admin\Kelas.php 44
