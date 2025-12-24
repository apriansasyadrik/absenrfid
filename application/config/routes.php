<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Routes Configuration - RFID Attendance System
|--------------------------------------------------------------------------
*/

$route['default_controller'] = 'auth/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// ============ AUTH ROUTES ============
$route['login'] = 'Auth/login';
$route['logout'] = 'Auth/logout';
$route['auth'] = 'Auth';
$route['auth/(:any)'] = 'Auth/$1';

// ============ DASHBOARD ROUTES ============
$route['dashboard'] = 'Dashboard';
$route['admin/dashboard'] = 'Dashboard';

// ============ ADMIN ROUTES ============
// Settings
$route['admin/settings'] = 'admin/Settings';
$route['admin/settings/(:any)'] = 'admin/Settings/$1';

// Jam Kerja
$route['admin/jam-kerja'] = 'admin/JamKerja';
$route['admin/jam-kerja/(:any)'] = 'admin/JamKerja/$1';
$route['admin/jamkerja'] = 'admin/JamKerja';
$route['admin/jamkerja/(:any)'] = 'admin/JamKerja/$1';

// Hari Libur
$route['admin/hari-libur'] = 'admin/HariLibur';
$route['admin/hari-libur/(:any)'] = 'admin/HariLibur/$1';
$route['admin/harilibur'] = 'admin/HariLibur';
$route['admin/harilibur/(:any)'] = 'admin/HariLibur/$1';

// Tahun Ajaran
$route['admin/tahun-ajaran'] = 'admin/TahunAjaran';
$route['admin/tahun-ajaran/(:any)'] = 'admin/TahunAjaran/$1';
$route['admin/tahunajaran'] = 'admin/TahunAjaran';
$route['admin/tahunajaran/(:any)'] = 'admin/TahunAjaran/$1';

// Semester
$route['admin/semester'] = 'admin/Semester';
$route['admin/semester/(:any)'] = 'admin/Semester/$1';

// Kelas
$route['admin/kelas'] = 'admin/Kelas';
$route['admin/kelas/(:any)'] = 'admin/Kelas/$1';

// Siswa
$route['admin/siswa'] = 'admin/Siswa';
$route['admin/siswa/(:any)'] = 'admin/Siswa/$1';

// Guru
$route['admin/guru'] = 'admin/Guru';
$route['admin/guru/(:any)'] = 'admin/Guru/$1';

// Mata Pelajaran
$route['admin/mapel'] = 'admin/Mapel';
$route['admin/mapel/(:any)'] = 'admin/Mapel/$1';

// Jadwal
$route['admin/jadwal'] = 'admin/Jadwal';
$route['admin/jadwal/(:any)'] = 'admin/Jadwal/$1';

// WhatsApp Settings
$route['admin/wa-settings'] = 'admin/WaSettings';
$route['admin/wa-settings/(:any)'] = 'admin/WaSettings/$1';
$route['admin/wasettings'] = 'admin/WaSettings';
$route['admin/wasettings/(:any)'] = 'admin/WaSettings/$1';

// Laporan Siswa
$route['admin/laporan-siswa'] = 'admin/LaporanSiswa';
$route['admin/laporan-siswa/(:any)'] = 'admin/LaporanSiswa/$1';
$route['admin/laporansiswa'] = 'admin/LaporanSiswa';
$route['admin/laporansiswa/(:any)'] = 'admin/LaporanSiswa/$1';

// Laporan Guru
$route['admin/laporan-guru'] = 'admin/LaporanGuru';
$route['admin/laporan-guru/(:any)'] = 'admin/LaporanGuru/$1';
$route['admin/laporanguru'] = 'admin/LaporanGuru';
$route['admin/laporanguru/(:any)'] = 'admin/LaporanGuru/$1';

// Rekap Laporan
$route['admin/rekap-laporan'] = 'admin/RekapLaporan';
$route['admin/rekap-laporan/(:any)'] = 'admin/RekapLaporan/$1';
$route['admin/rekaplaporan'] = 'admin/RekapLaporan';
$route['admin/rekaplaporan/(:any)'] = 'admin/RekapLaporan/$1';

// ============ GURU ROUTES ============
$route['guru'] = 'guru/Dashboard';
$route['guru/dashboard'] = 'guru/Dashboard';
$route['guru/jurnal'] = 'guru/Jurnal';
$route['guru/jurnal/(:any)'] = 'guru/Jurnal/$1';
$route['guru/jadwal'] = 'guru/Jadwal';
$route['guru/jadwal/(:any)'] = 'guru/Jadwal/$1';
$route['guru/laporan'] = 'guru/Laporan';
$route['guru/laporan/(:any)'] = 'guru/Laporan/$1';
$route['guru/rekap'] = 'guru/Rekap';
$route['guru/rekap/(:any)'] = 'guru/Rekap/$1';
$route['guru/profile'] = 'guru/Profile';
$route['guru/profile/(:any)'] = 'guru/Profile/$1';

// ============ WALIKELAS ROUTES ============
$route['walikelas'] = 'guru/Dashboard';
$route['walikelas/dashboard'] = 'guru/Dashboard';
$route['walikelas/izin-siswa'] = 'walikelas/IzinSiswa';
$route['walikelas/izin-siswa/(:any)'] = 'walikelas/IzinSiswa/$1';
$route['walikelas/izinsiswa'] = 'walikelas/IzinSiswa';
$route['walikelas/izinsiswa/(:any)'] = 'walikelas/IzinSiswa/$1';
// Walikelas juga bisa akses menu guru
$route['walikelas/jurnal'] = 'guru/Jurnal';
$route['walikelas/jurnal/(:any)'] = 'guru/Jurnal/$1';
$route['walikelas/jadwal'] = 'guru/Jadwal';
$route['walikelas/jadwal/(:any)'] = 'guru/Jadwal/$1';
$route['walikelas/laporan'] = 'guru/Laporan';
$route['walikelas/laporan/(:any)'] = 'guru/Laporan/$1';
$route['walikelas/profile'] = 'guru/Profile';
$route['walikelas/profile/(:any)'] = 'guru/Profile/$1';

// ============ PIKET ROUTES ============
$route['piket'] = 'guru/Dashboard';
$route['piket/dashboard'] = 'guru/Dashboard';
$route['piket/izin-kbm'] = 'piket/IzinKBM';
$route['piket/izin-kbm/(:any)'] = 'piket/IzinKBM/$1';
$route['piket/izinkbm'] = 'piket/IzinKBM';
$route['piket/izinkbm/(:any)'] = 'piket/IzinKBM/$1';
$route['piket/rekap'] = 'piket/Rekap';
$route['piket/rekap/(:any)'] = 'piket/Rekap/$1';
// Piket juga bisa akses menu guru
$route['piket/jurnal'] = 'guru/Jurnal';
$route['piket/jurnal/(:any)'] = 'guru/Jurnal/$1';
$route['piket/jadwal'] = 'guru/Jadwal';
$route['piket/jadwal/(:any)'] = 'guru/Jadwal/$1';
$route['piket/laporan'] = 'guru/Laporan';
$route['piket/laporan/(:any)'] = 'guru/Laporan/$1';
$route['piket/profile'] = 'guru/Profile';
$route['piket/profile/(:any)'] = 'guru/Profile/$1';

// ============ BK ROUTES ============
$route['bk'] = 'bk/Dashboard';
$route['bk/dashboard'] = 'bk/Dashboard';
$route['bk/monitoring'] = 'bk/Monitoring';
$route['bk/monitoring/(:any)'] = 'bk/Monitoring/$1';
$route['bk/surat'] = 'bk/Surat';
$route['bk/surat/(:any)'] = 'bk/Surat/$1';
$route['bk/profile'] = 'bk/Profile';
$route['bk/profile/(:any)'] = 'bk/Profile/$1';

// ============ ABSENSI RFID (TANPA LOGIN) ============
$route['absensi'] = 'Absensi';
$route['absensi/display'] = 'Absensi/display';
$route['absensi/scan'] = 'Absensi/scan';
$route['absensi/(:any)'] = 'Absensi/$1';

// ============ API ROUTES ============
// RFID API
$route['api/rfid'] = 'api/Rfid';
$route['api/rfid/scan'] = 'api/Rfid/scan';
$route['api/rfid/(:any)'] = 'api/Rfid/$1';

// WhatsApp Queue API
$route['api/waqueue'] = 'api/WaQueue';
$route['api/waqueue/process'] = 'api/WaQueue/process';
$route['api/waqueue/(:any)'] = 'api/WaQueue/$1';

// Cron Jobs API
$route['api/cron/check_belum_absen'] = 'api/Cron/check_belum_absen';
$route['api/cron/update_monitoring_bk'] = 'api/Cron/update_monitoring_bk';
$route['api/cron/(:any)'] = 'api/Cron/$1';

// AJAX API untuk DataTables dan form
$route['api/siswa/get_by_kelas/(:num)'] = 'api/Siswa/get_by_kelas/$1';
$route['api/jadwal/get_by_tanggal'] = 'api/Jadwal/get_by_tanggal';
$route['api/absensi/get_today'] = 'api/Absensi/get_today';
