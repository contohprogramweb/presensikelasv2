<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Default Controller
|--------------------------------------------------------------------------
*/
$route['default_controller'] = 'auth';

/*
|--------------------------------------------------------------------------
| Error Routes
|--------------------------------------------------------------------------
*/
$route['403'] = 'error/error_403';
$route['404'] = 'error/error_404';
$route['500'] = 'error/error_500';

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
$route['admin/kelas'] = 'admin/Kelas/index';
$route['admin/matapelajaran'] = 'admin/Matapelajaran/index';
$route['admin/siswa'] = 'admin/Siswa/index';
$route['admin/guru'] = 'admin/Guru/index';
$route['admin/jadwal'] = 'admin/Jadwal/index';
$route['admin/kelassiswa'] = 'admin/Kelassiswa/index';
$route['admin/import'] = 'admin/Import/index';

/*
|--------------------------------------------------------------------------
| Guru Routes
|--------------------------------------------------------------------------
*/
$route['guru/jadwal'] = 'guru/Jadwal/index';
$route['guru/presensi'] = 'guru/Presensi/index';
$route['guru/rekap'] = 'guru/Rekap/index';

/*
|--------------------------------------------------------------------------
| Kepala Sekolah Routes
|--------------------------------------------------------------------------
*/
$route['kepsek/approval'] = 'kepsek/Approval/index';
$route['kepsek/laporan'] = 'kepsek/Laporan/index';
$route['kepsek/logapproval'] = 'kepsek/Logapproval/index';

/*
|--------------------------------------------------------------------------
| Siswa Routes
|--------------------------------------------------------------------------
*/
$route['siswa/jadwal'] = 'siswa/Jadwal/index';
$route['siswa/riwayat'] = 'siswa/Riwayat/index';

/*
|--------------------------------------------------------------------------
| 404 Override
|--------------------------------------------------------------------------
*/
$route['404_override'] = 'error/error_404';

/*
|--------------------------------------------------------------------------
| Translate dashes to underscores
|--------------------------------------------------------------------------
*/
$route['translate_uri_dashes'] = FALSE;
