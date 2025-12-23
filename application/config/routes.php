<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/
$route['default_controller'] = 'auth/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth routes
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['auth/do_login'] = 'auth/do_login';

// Dashboard routes
$route['dashboard'] = 'dashboard';
$route['admin/dashboard'] = 'dashboard';
$route['guru/dashboard'] = 'guru/dashboard';
$route['walikelas/dashboard'] = 'walikelas/dashboard';
$route['piket/dashboard'] = 'piket/dashboard';
$route['bk/dashboard'] = 'bk/dashboard';

// Admin routes
$route['admin/settings'] = 'admin/settings';
$route['admin/jam-kerja'] = 'admin/jamkerja';
$route['admin/hari-libur'] = 'admin/harilibur';
$route['admin/tahun-ajaran'] = 'admin/tahunajaran';
$route['admin/semester'] = 'admin/semester';
$route['admin/kelas'] = 'admin/kelas';
$route['admin/siswa'] = 'admin/siswa';
$route['admin/guru'] = 'admin/guru';
$route['admin/mapel'] = 'admin/mapel';
$route['admin/jadwal'] = 'admin/jadwal';
$route['admin/wa-settings'] = 'admin/wasettings';
$route['admin/laporan-siswa'] = 'admin/laporansiswa';
$route['admin/laporan-guru'] = 'admin/laporanguru';
$route['admin/rekap-laporan'] = 'admin/rekaplaporan';

// Guru routes
$route['guru/jurnal'] = 'guru/jurnal';
$route['guru/jadwal'] = 'guru/jadwal';
$route['guru/laporan'] = 'guru/laporan';
$route['guru/rekap'] = 'guru/rekap';
$route['guru/profile'] = 'guru/profile';

// ============ WALIKELAS ROUTES ============
$route['walikelas'] = 'walikelas/dashboard';
$route['walikelas/dashboard'] = 'walikelas/dashboard';
$route['walikelas/izin'] = 'walikelas/izinsiswa';
$route['walikelas/izin/tambah'] = 'walikelas/izinsiswa/tambah';
$route['walikelas/izin/edit/(:num)'] = 'walikelas/izinsiswa/edit/$1';
$route['walikelas/izin/hapus/(:num)'] = 'walikelas/izinsiswa/hapus/$1';
$route['walikelas/izin/(:any)'] = 'walikelas/izinsiswa/$1';

// ============ PIKET ROUTES ============
$route['piket'] = 'piket/dashboard';
$route['piket/dashboard'] = 'piket/dashboard';
$route['piket/izin'] = 'piket/izinkbm';
$route['piket/izin/tambah'] = 'piket/izinkbm/tambah';
$route['piket/izin/edit/(:num)'] = 'piket/izinkbm/edit/$1';
$route['piket/izin/hapus/(:num)'] = 'piket/izinkbm/hapus/$1';
$route['piket/izin/(:any)'] = 'piket/izinkbm/$1';
$route['piket/rekap'] = 'piket/rekap';
$route['piket/rekap/export'] = 'piket/rekap/export';
$route['piket/rekap/(:any)'] = 'piket/rekap/$1';

// ============ BK ROUTES ============
$route['bk'] = 'bk/dashboard';
$route['bk/dashboard'] = 'bk/dashboard';
$route['bk/monitoring'] = 'bk/monitoring';
$route['bk/monitoring/detail/(:num)'] = 'bk/monitoring/detail/$1';
$route['bk/monitoring/catatan/(:num)'] = 'bk/monitoring/catatan/$1';
$route['bk/monitoring/(:any)'] = 'bk/monitoring/$1';
$route['bk/surat'] = 'bk/surat';
$route['bk/surat/tambah'] = 'bk/surat/tambah';
$route['bk/surat/edit/(:num)'] = 'bk/surat/edit/$1';
$route['bk/surat/cetak/(:num)'] = 'bk/surat/cetak/$1';
$route['bk/surat/hapus/(:num)'] = 'bk/surat/hapus/$1';
$route['bk/surat/(:any)'] = 'bk/surat/$1';
$route['bk/profile'] = 'bk/profile';

// Absensi RFID (tanpa login)
$route['absensi'] = 'absensi/index';
$route['absensi/display'] = 'absensi/display';

// API routes
$route['api/rfid/scan'] = 'api/rfid/scan';
$route['api/waqueue/process'] = 'api/waqueue/process';
$route['api/cron/check_belum_absen'] = 'api/cron/check_belum_absen';
$route['api/cron/update_monitoring_bk'] = 'api/cron/update_monitoring_bk';
