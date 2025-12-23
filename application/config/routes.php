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
$route['walikelas/dashboard'] = 'guru/dashboard';
$route['piket/dashboard'] = 'guru/dashboard';
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

// Walikelas routes
$route['walikelas/izin-siswa'] = 'walikelas/izinsiswa';

// Piket routes
$route['piket/izin-kbm'] = 'piket/izinkbm';
$route['piket/rekap'] = 'piket/rekap';

// BK routes
$route['bk/monitoring'] = 'bk/monitoring';
$route['bk/surat'] = 'bk/surat';
$route['bk/profile'] = 'bk/profile';

// Absensi RFID (tanpa login)
$route['absensi'] = 'absensi/index';
$route['absensi/display'] = 'absensi/display';

// API routes
$route['api/rfid/scan'] = 'api/rfid/scan';
$route['api/waqueue/process'] = 'api/waqueue/process';
$route['api/cron/check_belum_absen'] = 'api/cron/check_belum_absen';
$route['api/cron/update_monitoring_bk'] = 'api/cron/update_monitoring_bk';
