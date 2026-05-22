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
ERROR - 2026-05-21 01:21:45 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 185
ERROR - 2026-05-21 01:30:15 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 221
ERROR - 2026-05-21 01:30:20 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 221
ERROR - 2026-05-21 01:33:28 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 221
ERROR - 2026-05-21 01:33:35 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 221
ERROR - 2026-05-21 01:34:35 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 01:35:11 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 44
ERROR - 2026-05-21 01:35:11 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 44
ERROR - 2026-05-21 01:35:36 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 44
ERROR - 2026-05-21 01:35:36 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 44
ERROR - 2026-05-21 01:37:08 --> Severity: error --> Exception: syntax error, unexpected 'exit' (T_EXIT) C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 26
ERROR - 2026-05-21 01:38:16 --> Severity: error --> Exception: syntax error, unexpected 'exit' (T_EXIT) C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 27
ERROR - 2026-05-21 01:38:47 --> Severity: error --> Exception: syntax error, unexpected 'exit' (T_EXIT) C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 27
ERROR - 2026-05-21 01:39:03 --> Severity: error --> Exception: syntax error, unexpected 'exit' (T_EXIT) C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 27
ERROR - 2026-05-21 01:39:17 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 47
ERROR - 2026-05-21 01:39:17 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 47
ERROR - 2026-05-21 01:50:49 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\kelassiswa.php 5
ERROR - 2026-05-21 02:09:24 --> Query error: Unknown column 'no_hp_orang_tua' in 'field list' - Invalid query: UPDATE `tb_siswa` SET `nis` = '12345', `id_kelas` = '1', `nama_lengkap` = 'Ahmad Rizki', `jenis_kelamin` = 'L', `tempat_lahir` = 'Denpasar', `tanggal_lahir` = '2012-05-15', `alamat` = 'Jl. Mawar No. 10', `nama_ortu` = 'Rizki Pratama', `no_hp_orang_tua` = '081234567891'
WHERE `id` = 0
ERROR - 2026-05-21 02:12:20 --> Query error: Unknown column 'no_hp_orang_tua' in 'field list' - Invalid query: UPDATE `tb_siswa` SET `nis` = '12345', `id_kelas` = '1', `nama_lengkap` = 'Ahmad Rizki', `jenis_kelamin` = 'L', `tempat_lahir` = 'Denpasar', `tanggal_lahir` = '2012-05-15', `alamat` = 'Jl. Mawar No. 10', `nama_ortu` = 'Rizki Pratama', `no_hp_orang_tua` = '081234567891'
WHERE `id` = 0
ERROR - 2026-05-21 02:15:46 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 177
ERROR - 2026-05-21 02:15:47 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 182
ERROR - 2026-05-21 02:15:53 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 177
ERROR - 2026-05-21 02:15:53 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 182
ERROR - 2026-05-21 02:17:52 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 177
ERROR - 2026-05-21 02:17:52 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 182
ERROR - 2026-05-21 02:18:02 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 177
ERROR - 2026-05-21 02:18:02 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 182
ERROR - 2026-05-21 02:22:39 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 177
ERROR - 2026-05-21 02:22:39 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 182
ERROR - 2026-05-21 02:23:06 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 177
ERROR - 2026-05-21 02:23:06 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 182
ERROR - 2026-05-21 02:23:33 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 177
ERROR - 2026-05-21 02:23:33 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 182
ERROR - 2026-05-21 02:29:46 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 177
ERROR - 2026-05-21 02:29:46 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 182
ERROR - 2026-05-21 02:30:30 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 177
ERROR - 2026-05-21 02:30:30 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 182
ERROR - 2026-05-21 02:37:02 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 37
ERROR - 2026-05-21 02:37:35 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 37
ERROR - 2026-05-21 02:38:13 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 37
ERROR - 2026-05-21 02:38:24 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 37
ERROR - 2026-05-21 02:56:18 --> Query error: Unknown column 'nama' in 'field list' - Invalid query: UPDATE `tb_guru` SET `nip` = '198501012010011001', `nama` = 'Budi Santoso', `jenis_kelamin` = 'L', `alamat` = 'Jl. Pendidikan No. 1, Ubung', `no_hp` = '081234567890'
WHERE `id` = 0
ERROR - 2026-05-21 02:56:37 --> Query error: Unknown column 'nama' in 'field list' - Invalid query: INSERT INTO `tb_guru` (`nip`, `id_user`, `nama`, `jenis_kelamin`, `alamat`, `no_hp`) VALUES ('3333', 6, 'aaaa', 'P', 'asasas', '333')
ERROR - 2026-05-21 02:56:49 --> Query error: Unknown column 'nama' in 'field list' - Invalid query: UPDATE `tb_guru` SET `nip` = '198501012010011001', `nama` = 'Budi Santoso', `jenis_kelamin` = 'L', `alamat` = 'Jl. Pendidikan No. 1, Ubung', `no_hp` = '081234567890'
WHERE `id` = 0
ERROR - 2026-05-21 02:57:53 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\kelassiswa.php 5
ERROR - 2026-05-21 03:01:05 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 143
ERROR - 2026-05-21 03:01:21 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 143
ERROR - 2026-05-21 03:02:20 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 143
ERROR - 2026-05-21 03:02:31 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 143
ERROR - 2026-05-21 03:02:36 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 143
ERROR - 2026-05-21 03:02:40 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 143
ERROR - 2026-05-21 03:07:03 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 151
ERROR - 2026-05-21 03:07:08 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 151
ERROR - 2026-05-21 03:07:45 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 151
ERROR - 2026-05-21 03:10:35 --> Query error: Unknown table 'g' - Invalid query: SELECT `g`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:10:37 --> Query error: Unknown table 'g' - Invalid query: SELECT `g`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:10:38 --> Query error: Unknown table 'g' - Invalid query: SELECT `g`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:10:39 --> Query error: Unknown table 'g' - Invalid query: SELECT `g`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:10:44 --> Query error: Unknown table 'g' - Invalid query: SELECT `g`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:11:19 --> Query error: Column 'id' in where clause is ambiguous - Invalid query: SELECT `tb_guru`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `tb_guru`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:11:20 --> Query error: Column 'id' in where clause is ambiguous - Invalid query: SELECT `tb_guru`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `tb_guru`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:11:21 --> Query error: Column 'id' in where clause is ambiguous - Invalid query: SELECT `tb_guru`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `tb_guru`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:11:21 --> Query error: Column 'id' in where clause is ambiguous - Invalid query: SELECT `tb_guru`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `tb_guru`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:11:21 --> Query error: Column 'id' in where clause is ambiguous - Invalid query: SELECT `tb_guru`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `tb_guru`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:11:22 --> Query error: Column 'id' in where clause is ambiguous - Invalid query: SELECT `tb_guru`.*, `u`.`username`, `u`.`nama_lengkap` as `user_nama`, `u`.`status` as `user_status`
FROM `tb_guru`
JOIN `tb_user` `u` ON `u`.`id` = `tb_guru`.`id_user`
WHERE `id` = 1
ERROR - 2026-05-21 03:14:47 --> Severity: error --> Exception: syntax error, unexpected '$this' (T_VARIABLE) C:\laragon\www\presensikelas\application\models\admin\M_guru.php 26
ERROR - 2026-05-21 03:15:55 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 151
ERROR - 2026-05-21 03:18:50 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 151
ERROR - 2026-05-21 03:19:28 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 151
ERROR - 2026-05-21 03:22:29 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 151
ERROR - 2026-05-21 03:23:10 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 151
ERROR - 2026-05-21 03:30:24 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 03:30:26 --> Severity: error --> Exception: Call to undefined method M_jadwal::get_datatables() C:\laragon\www\presensikelas\application\controllers\admin\Jadwal.php 51
ERROR - 2026-05-21 03:31:17 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 177
ERROR - 2026-05-21 03:31:17 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 182
ERROR - 2026-05-21 08:30:50 --> Severity: error --> Exception: Too few arguments to function Guru::ajax_delete(), 0 passed in C:\laragon\www\presensikelas\system\core\CodeIgniter.php on line 532 and exactly 1 expected C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 163
ERROR - 2026-05-21 08:37:27 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 178
ERROR - 2026-05-21 08:58:55 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 192
ERROR - 2026-05-21 09:04:02 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 192
ERROR - 2026-05-21 09:04:06 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 192
ERROR - 2026-05-21 09:04:16 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 192
ERROR - 2026-05-21 09:04:50 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 192
ERROR - 2026-05-21 09:12:25 --> Severity: error --> Exception: syntax error, unexpected '+' C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 278
ERROR - 2026-05-21 09:12:43 --> Severity: error --> Exception: syntax error, unexpected '+' C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 277
ERROR - 2026-05-21 09:13:01 --> Severity: error --> Exception: syntax error, unexpected '<', expecting end of file C:\laragon\www\presensikelas\application\controllers\admin\Guru.php 278
ERROR - 2026-05-21 09:21:45 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\kelassiswa.php 5
ERROR - 2026-05-21 09:31:33 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:31:34 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:31:41 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:31:41 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:31:46 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:31:47 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:40:27 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:40:27 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:40:33 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:40:34 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:40:38 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 09:40:40 --> Severity: error --> Exception: Call to undefined method M_jadwal::get_datatables() C:\laragon\www\presensikelas\application\controllers\admin\Jadwal.php 51
ERROR - 2026-05-21 09:40:54 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 09:40:56 --> Severity: error --> Exception: Call to undefined method M_jadwal::get_datatables() C:\laragon\www\presensikelas\application\controllers\admin\Jadwal.php 51
ERROR - 2026-05-21 09:41:04 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:41:04 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:41:11 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:41:11 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:47:46 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:47:46 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:48:06 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:48:07 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:49:05 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:49:05 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:49:15 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:49:15 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:49:18 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\kelassiswa.php 5
ERROR - 2026-05-21 09:49:25 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:49:25 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:52:12 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 23
ERROR - 2026-05-21 09:53:18 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:53:24 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:53:46 --> Query error: Unknown column 'nama' in 'field list' - Invalid query: UPDATE `tb_siswa` SET `nis` = '12346', `id_kelas` = NULL, `nama` = 'Siti Nurhaliza', `jenis_kelamin` = 'P', `tempat_lahir` = 'Badung', `tanggal_lahir` = '2012-08-20', `alamat` = 'Jl. Melati No. 20', `nama_ortu` = 'Nurhaliza Ahmad', `no_hp_ortu` = '081234567892'
WHERE `id` = 0
ERROR - 2026-05-21 09:54:19 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\kelassiswa.php 5
ERROR - 2026-05-21 09:54:25 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 09:55:21 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\kelassiswa.php 5
ERROR - 2026-05-21 09:55:27 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 227
ERROR - 2026-05-21 10:01:05 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 236
ERROR - 2026-05-21 10:01:35 --> 404 Page Not Found: admin/Import/siswa
ERROR - 2026-05-21 10:01:35 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 10:01:44 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 236
ERROR - 2026-05-21 10:02:25 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 236
ERROR - 2026-05-21 10:02:57 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 236
ERROR - 2026-05-21 10:04:16 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 236
ERROR - 2026-05-21 10:10:55 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 236
ERROR - 2026-05-21 10:10:57 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 59
ERROR - 2026-05-21 10:10:57 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 59
ERROR - 2026-05-21 10:11:06 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 236
ERROR - 2026-05-21 10:11:07 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 59
ERROR - 2026-05-21 10:11:07 --> Severity: Notice --> Undefined index: nama C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 59
ERROR - 2026-05-21 10:11:33 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 236
ERROR - 2026-05-21 10:13:34 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 236
ERROR - 2026-05-21 10:15:21 --> Severity: error --> Exception: Cannot use object of type stdClass as array C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 236
ERROR - 2026-05-21 10:24:48 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 186
ERROR - 2026-05-21 10:30:24 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 190
ERROR - 2026-05-21 10:30:34 --> Severity: error --> Exception: Too few arguments to function Siswa::ajax_delete(), 0 passed in C:\laragon\www\presensikelas\system\core\CodeIgniter.php on line 532 and exactly 1 expected C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 206
ERROR - 2026-05-21 10:35:58 --> Severity: error --> Exception: Too few arguments to function Siswa::ajax_delete(), 0 passed in C:\laragon\www\presensikelas\system\core\CodeIgniter.php on line 532 and exactly 1 expected C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 206
ERROR - 2026-05-21 10:36:04 --> Severity: error --> Exception: Too few arguments to function Siswa::ajax_delete(), 0 passed in C:\laragon\www\presensikelas\system\core\CodeIgniter.php on line 532 and exactly 1 expected C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 206
ERROR - 2026-05-21 10:36:11 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Siswa.php 190
ERROR - 2026-05-21 12:37:46 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\kelassiswa.php 5
ERROR - 2026-05-21 13:06:07 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:06:08 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:06:44 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:06:44 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:09:19 --> Severity: error --> Exception: Object of class stdClass could not be converted to string C:\laragon\www\presensikelas\system\database\DB_driver.php 1471
ERROR - 2026-05-21 13:09:44 --> Severity: error --> Exception: Object of class stdClass could not be converted to string C:\laragon\www\presensikelas\system\database\DB_driver.php 1471
ERROR - 2026-05-21 13:10:04 --> Severity: error --> Exception: Object of class stdClass could not be converted to string C:\laragon\www\presensikelas\system\database\DB_driver.php 1471
ERROR - 2026-05-21 13:10:11 --> Severity: error --> Exception: Object of class stdClass could not be converted to string C:\laragon\www\presensikelas\system\database\DB_driver.php 1471
ERROR - 2026-05-21 13:10:25 --> Severity: error --> Exception: Object of class stdClass could not be converted to string C:\laragon\www\presensikelas\system\database\DB_driver.php 1471
ERROR - 2026-05-21 13:13:02 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:13:14 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:13:21 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:13:33 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:14:24 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:15:11 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:15:19 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:16:09 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 13:22:23 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Matapelajaran.php 26
ERROR - 2026-05-21 13:22:43 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Matapelajaran.php 26
ERROR - 2026-05-21 13:24:47 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Matapelajaran.php 26
ERROR - 2026-05-21 13:24:52 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Matapelajaran.php 26
ERROR - 2026-05-21 13:28:49 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Matapelajaran.php 26
ERROR - 2026-05-21 13:28:54 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Matapelajaran.php 26
ERROR - 2026-05-21 13:28:59 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Matapelajaran.php 26
ERROR - 2026-05-21 13:29:22 --> Severity: Notice --> Trying to access array offset on value of type null C:\laragon\www\presensikelas\application\controllers\admin\Matapelajaran.php 26
ERROR - 2026-05-21 14:25:09 --> Severity: error --> Exception: Too few arguments to function Matapelajaran::ajax_delete(), 0 passed in C:\laragon\www\presensikelas\system\core\CodeIgniter.php on line 532 and exactly 1 expected C:\laragon\www\presensikelas\application\controllers\admin\Matapelajaran.php 130
ERROR - 2026-05-21 14:31:16 --> Severity: error --> Exception: Too few arguments to function Matapelajaran::ajax_delete(), 0 passed in C:\laragon\www\presensikelas\system\core\CodeIgniter.php on line 532 and exactly 1 expected C:\laragon\www\presensikelas\application\controllers\admin\Matapelajaran.php 130
ERROR - 2026-05-21 14:43:29 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 14:43:31 --> Severity: error --> Exception: Call to undefined method M_jadwal::get_datatables() C:\laragon\www\presensikelas\application\controllers\admin\Jadwal.php 51
ERROR - 2026-05-21 14:54:08 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 14:54:10 --> Severity: error --> Exception: Call to undefined method M_jadwal::get_datatables() C:\laragon\www\presensikelas\application\controllers\admin\Jadwal.php 51
ERROR - 2026-05-21 14:59:14 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 14:59:16 --> Query error: Unknown column 't.nama_tahun_ajaran' in 'field list' - Invalid query: SELECT `j`.*, `k`.`nama_kelas`, `u`.`nama_lengkap` as `nama_guru`, `m`.`nama_mapel`, `t`.`nama_tahun_ajaran` as `nama_tahun_ajaran`
FROM `tb_jadwal` `j`
JOIN `tb_kelas` `k` ON `k`.`id` = `j`.`id_kelas`
JOIN `tb_guru` `g` ON `g`.`id` = `j`.`id_guru`
JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_mata_pelajaran` `m` ON `m`.`id` = `j`.`id_mapel`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `j`.`id_tahun_ajaran`
ORDER BY `j`.`hari` ASC
 LIMIT 10
ERROR - 2026-05-21 14:59:20 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 14:59:22 --> Query error: Unknown column 't.nama_tahun_ajaran' in 'field list' - Invalid query: SELECT `j`.*, `k`.`nama_kelas`, `u`.`nama_lengkap` as `nama_guru`, `m`.`nama_mapel`, `t`.`nama_tahun_ajaran` as `nama_tahun_ajaran`
FROM `tb_jadwal` `j`
JOIN `tb_kelas` `k` ON `k`.`id` = `j`.`id_kelas`
JOIN `tb_guru` `g` ON `g`.`id` = `j`.`id_guru`
JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_mata_pelajaran` `m` ON `m`.`id` = `j`.`id_mapel`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `j`.`id_tahun_ajaran`
ORDER BY `j`.`hari` ASC
 LIMIT 10
ERROR - 2026-05-21 15:01:48 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 15:01:50 --> Query error: Unknown column 't.nama_tahun_ajaran' in 'field list' - Invalid query: SELECT `j`.*, `k`.`nama_kelas`, `u`.`nama_lengkap` as `nama_guru`, `m`.`nama_mapel`, `t`.`nama_tahun_ajaran` as `nama_tahun_ajaran`
FROM `tb_jadwal` `j`
JOIN `tb_kelas` `k` ON `k`.`id` = `j`.`id_kelas`
JOIN `tb_guru` `g` ON `g`.`id` = `j`.`id_guru`
JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_mata_pelajaran` `m` ON `m`.`id` = `j`.`id_mapel`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `j`.`id_tahun_ajaran`
ORDER BY `j`.`hari` ASC
 LIMIT 10
ERROR - 2026-05-21 15:02:22 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 15:02:24 --> Query error: Unknown column 't.nama_tahun_ajaran' in 'field list' - Invalid query: SELECT `j`.*, `k`.`nama_kelas`, `u`.`nama_lengkap` as `nama_guru`, `m`.`nama_mapel`, `t`.`nama_tahun_ajaran` as `nama_tahun_ajaran`
FROM `tb_jadwal` `j`
JOIN `tb_kelas` `k` ON `k`.`id` = `j`.`id_kelas`
JOIN `tb_guru` `g` ON `g`.`id` = `j`.`id_guru`
JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_mata_pelajaran` `m` ON `m`.`id` = `j`.`id_mapel`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `j`.`id_tahun_ajaran`
ORDER BY `j`.`hari` ASC
 LIMIT 10
ERROR - 2026-05-21 15:02:28 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 15:02:30 --> Query error: Unknown column 't.nama_tahun_ajaran' in 'field list' - Invalid query: SELECT `j`.*, `k`.`nama_kelas`, `u`.`nama_lengkap` as `nama_guru`, `m`.`nama_mapel`, `t`.`nama_tahun_ajaran` as `nama_tahun_ajaran`
FROM `tb_jadwal` `j`
JOIN `tb_kelas` `k` ON `k`.`id` = `j`.`id_kelas`
JOIN `tb_guru` `g` ON `g`.`id` = `j`.`id_guru`
JOIN `tb_user` `u` ON `u`.`id` = `g`.`id_user`
JOIN `tb_mata_pelajaran` `m` ON `m`.`id` = `j`.`id_mapel`
JOIN `tb_tahun_ajaran` `t` ON `t`.`id` = `j`.`id_tahun_ajaran`
ORDER BY `j`.`hari` ASC
 LIMIT 10
ERROR - 2026-05-21 15:03:22 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 15:03:24 --> Severity: Notice --> Undefined property: stdClass::$nama_tahun_ajaran C:\laragon\www\presensikelas\application\views\admin\jadwal.php 19
ERROR - 2026-05-21 15:08:21 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:08:21 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:08:23 --> Severity: error --> Exception: Object of class stdClass could not be converted to string C:\laragon\www\presensikelas\system\database\DB_query_builder.php 2442
ERROR - 2026-05-21 15:08:27 --> Severity: error --> Exception: Object of class stdClass could not be converted to string C:\laragon\www\presensikelas\system\database\DB_query_builder.php 2442
ERROR - 2026-05-21 15:10:41 --> Severity: error --> Exception: Object of class stdClass could not be converted to string C:\laragon\www\presensikelas\system\database\DB_query_builder.php 2442
ERROR - 2026-05-21 15:12:59 --> Severity: error --> Exception: Object of class stdClass could not be converted to string C:\laragon\www\presensikelas\system\database\DB_query_builder.php 2442
ERROR - 2026-05-21 15:13:09 --> Severity: error --> Exception: Object of class stdClass could not be converted to string C:\laragon\www\presensikelas\system\database\DB_query_builder.php 2442
ERROR - 2026-05-21 15:16:13 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:16:13 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:16:20 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:16:20 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:19:39 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:19:39 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:23:22 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:23:22 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:31:47 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:31:47 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:36:09 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:36:09 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:36:28 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:36:28 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:36:39 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:36:39 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:41:18 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:41:18 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:42:09 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:42:09 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:46:25 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:46:25 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 15:52:42 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 15:52:42 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 20:28:26 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 20:28:26 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 20:38:34 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 20:38:34 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 20:38:50 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 20:38:50 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 20:44:25 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 20:44:25 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 20:54:34 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 20:54:34 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 20:55:36 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 20:55:36 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 20:56:32 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 20:56:32 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:00:16 --> 404 Page Not Found: admin/M_guru/get_active_teachers
ERROR - 2026-05-21 21:00:16 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:00:25 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:00:25 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:02:58 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:02:58 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:03:03 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:03:03 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:10:17 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:10:17 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:11:34 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 6 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
ERROR - 2026-05-21 21:11:43 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 6 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
ERROR - 2026-05-21 21:11:51 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 6 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
ERROR - 2026-05-21 21:13:19 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:13:19 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:13:56 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 6 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
ERROR - 2026-05-21 21:13:59 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 6 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
ERROR - 2026-05-21 21:18:36 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:18:36 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:19:09 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 7 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
AND `status_aktif` = 1
ERROR - 2026-05-21 21:19:12 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 7 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
AND `status_aktif` = 1
ERROR - 2026-05-21 21:19:20 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:19:20 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:19:47 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 7 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
AND `status_aktif` = 1
ERROR - 2026-05-21 21:19:50 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 7 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
AND `status_aktif` = 1
ERROR - 2026-05-21 21:22:11 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:22:11 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:22:40 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 7 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
AND `status_aktif` = 1
ERROR - 2026-05-21 21:29:28 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:29:28 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:32:20 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:32:20 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:32:24 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:32:24 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:32:52 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 7 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
AND `status_aktif` = 1
ERROR - 2026-05-21 21:32:58 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 7 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
AND `status_aktif` = 1
ERROR - 2026-05-21 21:33:05 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 7 - Invalid query: SELECT *
FROM `tb_jadwal`
WHERE `id_kelas` = '1'
AND `hari` = 'Kamis'
AND (`jam_mulai` < '08:30'
AND `jam_selesai` > '07:00)'
AND `status_aktif` = 1
ERROR - 2026-05-21 21:40:12 --> Severity: Compile Error --> Cannot redeclare Jadwal::ajax_update() C:\laragon\www\presensikelas\application\controllers\admin\Jadwal.php 352
ERROR - 2026-05-21 21:40:32 --> Severity: Compile Error --> Cannot redeclare Jadwal::ajax_update() C:\laragon\www\presensikelas\application\controllers\admin\Jadwal.php 352
ERROR - 2026-05-21 21:41:37 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:41:37 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:41:59 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:41:59 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:42:00 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:42:00 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:51:16 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:51:16 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:55:27 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:55:27 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:55:45 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:55:45 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:55:45 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:55:45 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:56:05 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:56:05 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:56:05 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 21:56:05 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 21:56:19 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 21:56:36 --> Severity: Warning --> require_once(C:\laragon\www\presensikelas\application\third_party/phpspreadsheet/vendor/autoload.php): failed to open stream: No such file or directory C:\laragon\www\presensikelas\application\libraries\Excel_import.php 8
ERROR - 2026-05-21 21:56:36 --> Severity: Compile Error --> require_once(): Failed opening required 'C:\laragon\www\presensikelas\application\third_party/phpspreadsheet/vendor/autoload.php' (include_path='.;C:/laragon/etc/php/pear') C:\laragon\www\presensikelas\application\libraries\Excel_import.php 8
ERROR - 2026-05-21 21:57:31 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 21:58:00 --> Severity: Notice --> Undefined property: stdClass::$id_siswa C:\laragon\www\presensikelas\application\controllers\admin\Kelassiswa.php 98
ERROR - 2026-05-21 22:05:34 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'ASC, s.nama_lengkap) ASC' at line 4 - Invalid query: SELECT `s`.`id`, `s`.`nis`, COALESCE(u.nama_lengkap, s.nama_lengkap) as nama_siswa, `s`.`jenis_kelamin`
FROM `tb_siswa` `s`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `s`.`id_user`
ORDER BY COALESCE(u.nama_lengkap ASC, s.nama_lengkap) ASC
ERROR - 2026-05-21 22:05:58 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'ASC, s.nama_lengkap) ASC' at line 4 - Invalid query: SELECT `s`.`id`, `s`.`nis`, COALESCE(u.nama_lengkap, s.nama_lengkap) as nama_siswa, `s`.`jenis_kelamin`
FROM `tb_siswa` `s`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `s`.`id_user`
ORDER BY COALESCE(u.nama_lengkap ASC, s.nama_lengkap) ASC
ERROR - 2026-05-21 22:07:16 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'ASC, s.nama_lengkap) ASC' at line 4 - Invalid query: SELECT `s`.`id`, `s`.`nis`, COALESCE(u.nama_lengkap, s.nama_lengkap) as nama_siswa, `s`.`jenis_kelamin`
FROM `tb_siswa` `s`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `s`.`id_user`
ORDER BY COALESCE(u.nama_lengkap ASC, s.nama_lengkap) ASC
ERROR - 2026-05-21 22:09:27 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'ASC, s.nama_lengkap) ASC' at line 4 - Invalid query: SELECT `s`.`id`, `s`.`nis`, COALESCE(u.nama_lengkap, s.nama_lengkap) as nama_siswa, `s`.`jenis_kelamin`
FROM `tb_siswa` `s`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `s`.`id_user`
ORDER BY COALESCE(u.nama_lengkap ASC, s.nama_lengkap) ASC
ERROR - 2026-05-21 22:09:43 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'ASC, s.nama_lengkap) ASC' at line 4 - Invalid query: SELECT `s`.`id`, `s`.`nis`, COALESCE(u.nama_lengkap, s.nama_lengkap) as nama_siswa, `s`.`jenis_kelamin`
FROM `tb_siswa` `s`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `s`.`id_user`
ORDER BY COALESCE(u.nama_lengkap ASC, s.nama_lengkap) ASC
ERROR - 2026-05-21 22:14:52 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'ASC, s.nama_lengkap) ASC' at line 4 - Invalid query: SELECT `s`.`id`, `s`.`nis`, COALESCE(u.nama_lengkap, s.nama_lengkap) as nama_siswa, `s`.`jenis_kelamin`
FROM `tb_siswa` `s`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `s`.`id_user`
ORDER BY COALESCE(u.nama_lengkap ASC, s.nama_lengkap) ASC
ERROR - 2026-05-21 22:15:44 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'ASC, s.nama_lengkap) ASC' at line 4 - Invalid query: SELECT `s`.`id`, `s`.`nis`, COALESCE(u.nama_lengkap, s.nama_lengkap) as nama_siswa, `s`.`jenis_kelamin`
FROM `tb_siswa` `s`
LEFT JOIN `tb_user` `u` ON `u`.`id` = `s`.`id_user`
ORDER BY COALESCE(u.nama_lengkap ASC, s.nama_lengkap) ASC
ERROR - 2026-05-21 22:18:55 --> 404 Page Not Found: Auth/get_csrf_hash
ERROR - 2026-05-21 22:18:55 --> Severity: error --> Exception: Call to undefined function site_url() C:\laragon\www\presensikelas\application\views\errors\html\error_404.php 69
ERROR - 2026-05-21 22:20:17 --> Severity: Warning --> require_once(C:\laragon\www\presensikelas\application\third_party/phpspreadsheet/vendor/autoload.php): failed to open stream: No such file or directory C:\laragon\www\presensikelas\application\libraries\Excel_import.php 8
ERROR - 2026-05-21 22:20:17 --> Severity: Compile Error --> require_once(): Failed opening required 'C:\laragon\www\presensikelas\application\third_party/phpspreadsheet/vendor/autoload.php' (include_path='.;C:/laragon/etc/php/pear') C:\laragon\www\presensikelas\application\libraries\Excel_import.php 8
ERROR - 2026-05-21 22:31:06 --> Severity: Notice --> Undefined variable: csrf_name C:\laragon\www\presensikelas\application\views\templates\footer.php 68
ERROR - 2026-05-21 22:31:06 --> Severity: Notice --> Undefined variable: csrf_hash C:\laragon\www\presensikelas\application\views\templates\footer.php 69
