<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Default Controller
|--------------------------------------------------------------------------
*/
$route['default_controller'] = 'auth/login';

 
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

/*
|--------------------------------------------------------------------------
| Dashboard Routes (role-based)
|--------------------------------------------------------------------------
*/
$route['dashboard'] = 'dashboard/index';

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
$route['profil'] = 'profil/index';
$route['profil/update'] = 'profil/update_profil';
$route['profil/password'] = 'profil/update_password';

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
$route['admin/tahunajaran'] = 'admin/Tahunajaran/index';
$route['admin/tahunajaran/ajax_list'] = 'admin/Tahunajaran/ajax_list';
$route['admin/tahunajaran/ajax_add'] = 'admin/Tahunajaran/ajax_add';
$route['admin/tahunajaran/ajax_edit/(:any)'] = 'admin/Tahunajaran/ajax_edit/$1';
$route['admin/tahunajaran/ajax_update'] = 'admin/Tahunajaran/ajax_update';
$route['admin/tahunajaran/ajax_delete/(:any)'] = 'admin/Tahunajaran/ajax_delete/$1';

$route['admin/kelas'] = 'admin/Kelas/index';
$route['admin/kelas/ajax_list'] = 'admin/Kelas/ajax_list';
$route['admin/kelas/ajax_add'] = 'admin/Kelas/ajax_add';
$route['admin/kelas/ajax_edit/(:any)'] = 'admin/Kelas/ajax_edit/$1';
$route['admin/kelas/ajax_update'] = 'admin/Kelas/ajax_update';
$route['admin/kelas/ajax_delete'] = 'admin/Kelas/ajax_delete';
$route['admin/kelas/ajax_list_guru_select'] = 'admin/Kelas/ajax_list_guru_select';

$route['admin/matapelajaran'] = 'admin/Matapelajaran/index';
$route['admin/matapelajaran/ajax_list'] = 'admin/Matapelajaran/ajax_list';
$route['admin/matapelajaran/ajax_add'] = 'admin/Matapelajaran/ajax_add';
$route['admin/matapelajaran/ajax_edit/(:any)'] = 'admin/Matapelajaran/ajax_edit/$1';
$route['admin/matapelajaran/ajax_update'] = 'admin/Matapelajaran/ajax_update';
$route['admin/matapelajaran/ajax_delete'] = 'admin/Matapelajaran/ajax_delete';

$route['admin/siswa'] = 'admin/Siswa/index';
$route['admin/siswa/ajax_list'] = 'admin/Siswa/ajax_list';
$route['admin/siswa/ajax_add'] = 'admin/Siswa/ajax_add';
$route['admin/siswa/ajax_edit/(:any)'] = 'admin/Siswa/ajax_edit/$1';
$route['admin/siswa/ajax_update'] = 'admin/Siswa/ajax_update';
$route['admin/siswa/ajax_delete'] = 'admin/Siswa/ajax_delete';
$route['admin/siswa/get_kelas_select'] = 'admin/Siswa/get_kelas_select';

$route['admin/guru'] = 'admin/Guru/index';
$route['admin/guru/ajax_list'] = 'admin/Guru/ajax_list';
$route['admin/guru/ajax_add'] = 'admin/Guru/ajax_add';
$route['admin/guru/ajax_edit/(:any)'] = 'admin/Guru/ajax_edit/$1';
$route['admin/guru/ajax_update'] = 'admin/Guru/ajax_update';
$route['admin/guru/ajax_delete'] = 'admin/Guru/ajax_delete';
$route['admin/guru/ajax_list_guru_select'] = 'admin/Guru/ajax_list_guru_select';

$route['admin/jadwal'] = 'admin/Jadwal/index';
$route['admin/jadwal/ajax_list'] = 'admin/Jadwal/ajax_list';
$route['admin/jadwal/ajax_add'] = 'admin/Jadwal/ajax_add';
$route['admin/jadwal/ajax_edit/(:any)'] = 'admin/Jadwal/ajax_edit/$1';
$route['admin/jadwal/ajax_update'] = 'admin/Jadwal/ajax_update';
$route['admin/jadwal/ajax_delete/(:any)'] = 'admin/Jadwal/ajax_delete/$1';
$route['admin/jadwal/ajax_get_dropdown'] = 'admin/Jadwal/ajax_get_dropdown';

$route['admin/kelassiswa'] = 'admin/Kelassiswa/index';
$route['admin/kelassiswa/ajax_list'] = 'admin/Kelassiswa/ajax_list';
$route['admin/kelassiswa/ajax_add'] = 'admin/Kelassiswa/ajax_add';
$route['admin/kelassiswa/ajax_delete'] = 'admin/Kelassiswa/ajax_delete';
$route['admin/kelassiswa/ajax_statistik'] = 'admin/Kelassiswa/ajax_statistik';
$route['admin/kelassiswa/ajax_siswa_belum_kelas'] = 'admin/Kelassiswa/ajax_siswa_belum_kelas';
$route['admin/kelassiswa/ajax_get_kelas'] = 'admin/Kelassiswa/ajax_get_kelas';
$route['admin/kelassiswa/ajax_hapus_siswa'] = 'admin/Kelassiswa/ajax_hapus_siswa';
$route['admin/kelassiswa/simpan_penempatan'] = 'admin/Kelassiswa/simpan_penempatan';

// Route untuk penempatan siswa dengan parameter
$route['admin/kelassiswa/ajax_get_siswa/(:any)'] = 'admin/Kelassiswa/ajax_get_siswa/$1';
$route['admin/kelassiswa/ajax_get_statistik'] = 'admin/Kelassiswa/ajax_get_statistik';

$route['admin/import'] = 'admin/Import/index';
$route['admin/import/siswa'] = 'admin/Import/siswa';
$route['admin/import/guru'] = 'admin/Import/guru';
$route['admin/import/proses_siswa'] = 'admin/Import/proses_siswa';
$route['admin/import/proses_guru'] = 'admin/Import/proses_guru';
$route['admin/import/template_siswa'] = 'admin/Import/template_siswa';
$route['admin/import/template_guru'] = 'admin/Import/template_guru';
$route['admin/import/proses'] = 'admin/Import/proses';
$route['admin/import/preview'] = 'admin/Import/preview';
$route['admin/import/download_template/(:any)'] = 'admin/Import/download_template/$1';

/*
|--------------------------------------------------------------------------
| Guru Routes
|--------------------------------------------------------------------------
*/
$route['guru/jadwal'] = 'guru/Jadwal/index';
$route['guru/presensi'] = 'guru/Presensi/index';
$route['guru/presensi/(:any)'] = 'guru/Presensi/index/$1';
$route['guru/rekap'] = 'guru/Rekap/index';
$route['guru/rekap/export_pdf'] = 'guru/Rekap/export_pdf';

/*
|--------------------------------------------------------------------------
| Kepala Sekolah Routes
|--------------------------------------------------------------------------
*/
$route['kepsek/approval'] = 'kepsek/Approval/index';
$route['kepsek/laporan'] = 'kepsek/Laporan/index';
$route['kepsek/laporan/export_pdf'] = 'kepsek/Laporan/export_pdf';
$route['kepsek/logapproval'] = 'kepsek/Logapproval/index';
$route['kepsek/logapproval/ajax_list'] = 'kepsek/Logapproval/ajax_list';
$route['kepsek/logapproval/export_excel'] = 'kepsek/Logapproval/export_excel';

/*
|--------------------------------------------------------------------------
| Siswa Routes
|--------------------------------------------------------------------------
*/
$route['siswa/jadwal'] = 'siswa/Jadwal/index';
$route['siswa/riwayat'] = 'siswa/Riwayat/index';
$route['siswa/riwayat/ajax_list'] = 'siswa/Riwayat/ajax_list';
$route['siswa/riwayat/ajax_statistik'] = 'siswa/Riwayat/ajax_statistik';

/*
|--------------------------------------------------------------------------
| Security / CSRF Routes
|--------------------------------------------------------------------------
*/
$route['security/get_csrf_hash'] = 'auth/get_csrf_hash';

/*
|--------------------------------------------------------------------------
| 404 Override
|--------------------------------------------------------------------------
*/
$route['404_override'] = '';

/*
|--------------------------------------------------------------------------
| Translate dashes to underscores
|--------------------------------------------------------------------------
*/
$route['translate_uri_dashes'] = FALSE;