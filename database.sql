-- =====================================================
-- Database: absensi_rfid
-- Sistem Absensi RFID untuk Siswa dan Guru
-- =====================================================

CREATE DATABASE IF NOT EXISTS `absensi_rfid` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `absensi_rfid`;

-- =====================================================
-- 1. TABEL SETTINGS - Pengaturan Sekolah
-- =====================================================
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_sekolah` varchar(200) NOT NULL,
  `alamat` text NOT NULL,
  `kepala_sekolah` varchar(200) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default data
INSERT INTO `settings` (`nama_sekolah`, `alamat`, `kepala_sekolah`, `logo`) VALUES
('SMA Negeri 1 Contoh', 'Jl. Pendidikan No. 123, Jakarta', 'Drs. Ahmad Santoso, M.Pd', NULL);

-- =====================================================
-- 2. TABEL JAM_KERJA - Pengaturan Jam Masuk/Pulang
-- =====================================================
CREATE TABLE `jam_kerja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `is_kerja` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=hari kerja, 0=libur',
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `toleransi_keterlambatan` int(11) NOT NULL DEFAULT 15 COMMENT 'dalam menit',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hari` (`hari`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default data
INSERT INTO `jam_kerja` (`hari`, `is_kerja`, `jam_masuk`, `jam_pulang`, `toleransi_keterlambatan`) VALUES
('Senin', 1, '07:00:00', '15:00:00', 15),
('Selasa', 1, '07:00:00', '15:00:00', 15),
('Rabu', 1, '07:00:00', '15:00:00', 15),
('Kamis', 1, '07:00:00', '15:00:00', 15),
('Jumat', 1, '07:00:00', '11:30:00', 15),
('Sabtu', 1, '07:00:00', '13:00:00', 15),
('Minggu', 0, '07:00:00', '15:00:00', 15);

-- =====================================================
-- 3. TABEL HARI_LIBUR - Hari Libur Nasional
-- =====================================================
CREATE TABLE `hari_libur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tanggal` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. TABEL TAHUN_AJARAN
-- =====================================================
CREATE TABLE `tahun_ajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` varchar(20) NOT NULL COMMENT 'Format: 2023/2024',
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tahun_ajaran` (`tahun_ajaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default data
INSERT INTO `tahun_ajaran` (`tahun_ajaran`, `tanggal_mulai`, `tanggal_selesai`, `is_active`) VALUES
('2023/2024', '2023-07-01', '2024-06-30', 1);

-- =====================================================
-- 5. TABEL SEMESTER
-- =====================================================
CREATE TABLE `semester` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_ajaran_id` int(11) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  CONSTRAINT `fk_semester_tahun_ajaran` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default data
INSERT INTO `semester` (`tahun_ajaran_id`, `semester`, `tanggal_mulai`, `tanggal_selesai`, `is_active`) VALUES
(1, 'Ganjil', '2023-07-01', '2023-12-31', 1);

-- =====================================================
-- 6. TABEL KELAS
-- =====================================================
CREATE TABLE `kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) NOT NULL COMMENT 'Contoh: X IPA 1, XI IPS 2',
  `tingkat` int(11) NOT NULL COMMENT '10, 11, 12',
  `jurusan` varchar(50) DEFAULT NULL COMMENT 'IPA, IPS, dll',
  `walikelas_id` int(11) DEFAULT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `walikelas_id` (`walikelas_id`),
  KEY `tahun_ajaran_id` (`tahun_ajaran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 7. TABEL USERS - User Login
-- =====================================================
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru','walikelas','guru_piket','bk') NOT NULL,
  `guru_id` int(11) DEFAULT NULL COMMENT 'Jika role guru/walikelas/guru_piket/bk',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `guru_id` (`guru_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin user (password: admin123)
INSERT INTO `users` (`username`, `password`, `role`, `is_active`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- =====================================================
-- 8. TABEL GURU - Data Guru dan Staff
-- =====================================================
CREATE TABLE `guru` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(50) DEFAULT NULL,
  `rfid_uid` varchar(50) DEFAULT NULL COMMENT 'UID kartu RFID',
  `nama` varchar(200) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL COMMENT 'Guru, Staff, dll',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nip` (`nip`),
  UNIQUE KEY `rfid_uid` (`rfid_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 9. TABEL SISWA - Data Siswa
-- =====================================================
CREATE TABLE `siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(50) NOT NULL,
  `nisn` varchar(50) DEFAULT NULL,
  `rfid_uid` varchar(50) DEFAULT NULL COMMENT 'UID kartu RFID',
  `nama` varchar(200) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `no_hp_siswa` varchar(20) DEFAULT NULL,
  `nama_ortu` varchar(200) DEFAULT NULL,
  `no_hp_ortu` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nis` (`nis`),
  UNIQUE KEY `rfid_uid` (`rfid_uid`),
  KEY `kelas_id` (`kelas_id`),
  CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 10. TABEL MATA_PELAJARAN
-- =====================================================
CREATE TABLE `mata_pelajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(20) NOT NULL,
  `nama_mapel` varchar(200) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_mapel` (`kode_mapel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 11. TABEL JADWAL_PELAJARAN
-- =====================================================
CREATE TABLE `jadwal_pelajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kelas_id` int(11) NOT NULL,
  `mata_pelajaran_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kelas_id` (`kelas_id`),
  KEY `mata_pelajaran_id` (`mata_pelajaran_id`),
  KEY `guru_id` (`guru_id`),
  KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  KEY `semester_id` (`semester_id`),
  CONSTRAINT `fk_jadwal_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jadwal_mapel` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jadwal_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jadwal_tahun_ajaran` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jadwal_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 12. TABEL ABSENSI_HARIAN - Absensi RFID Masuk/Pulang
-- =====================================================
CREATE TABLE `absensi_harian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis` enum('siswa','guru') NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'ID siswa atau guru',
  `waktu_masuk` datetime DEFAULT NULL,
  `waktu_pulang` datetime DEFAULT NULL,
  `status_masuk` enum('tepat_waktu','terlambat') DEFAULT NULL,
  `keterlambatan_menit` int(11) DEFAULT 0,
  `keterangan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tanggal_jenis` (`tanggal`, `jenis`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 13. TABEL JURNAL_MENGAJAR
-- =====================================================
CREATE TABLE `jurnal_mengajar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `materi` text NOT NULL,
  `kegiatan` text,
  `hambatan` text,
  `guru_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `jadwal_id` (`jadwal_id`),
  KEY `guru_id` (`guru_id`),
  KEY `idx_tanggal` (`tanggal`),
  CONSTRAINT `fk_jurnal_jadwal` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_jurnal_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 14. TABEL ABSENSI_MAPEL - Absensi Per Mata Pelajaran
-- =====================================================
CREATE TABLE `absensi_mapel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jurnal_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `status` enum('H','S','I','A') NOT NULL COMMENT 'H=Hadir, S=Sakit, I=Izin, A=Alpha',
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `jurnal_id` (`jurnal_id`),
  KEY `siswa_id` (`siswa_id`),
  CONSTRAINT `fk_absensi_mapel_jurnal` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal_mengajar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_absensi_mapel_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 15. TABEL IZIN_SISWA - Izin dari Walikelas
-- =====================================================
CREATE TABLE `izin_siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('Sakit','Izin') NOT NULL,
  `keterangan` text,
  `guru_id` int(11) NOT NULL COMMENT 'Guru yang input izin',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `siswa_id` (`siswa_id`),
  KEY `guru_id` (`guru_id`),
  KEY `idx_tanggal` (`tanggal`),
  CONSTRAINT `fk_izin_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_izin_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 16. TABEL WA_SETTINGS - Pengaturan WhatsApp API
-- =====================================================
CREATE TABLE `wa_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_url` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 17. TABEL WA_TEMPLATES - Template Pesan WhatsApp
-- =====================================================
CREATE TABLE `wa_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe` enum('absen_masuk','absen_pulang','notif_walikelas','lainnya') NOT NULL,
  `nama_template` varchar(100) NOT NULL,
  `template` text NOT NULL COMMENT 'Support variable: {nama}, {kelas}, {waktu}, {status}, {terlambat}',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default templates
INSERT INTO `wa_templates` (`tipe`, `nama_template`, `template`, `is_active`) VALUES
('absen_masuk', 'Notifikasi Masuk', 'Assalamualaikum. Ananda *{nama}* kelas *{kelas}* telah absen masuk pada pukul *{waktu}*. Status: *{status}*. {terlambat}', 1),
('absen_pulang', 'Notifikasi Pulang', 'Assalamualaikum. Ananda *{nama}* kelas *{kelas}* telah absen pulang pada pukul *{waktu}*.', 1),
('notif_walikelas', 'Notifikasi Belum Absen', 'Assalamualaikum Bapak/Ibu Walikelas *{kelas}*. Sampai pukul 09:00, terdapat *{jumlah}* siswa yang belum absen: {daftar_siswa}', 1);

-- =====================================================
-- 18. TABEL WA_KELAS_AKTIF - Kelas yang Aktif Notifikasi
-- =====================================================
CREATE TABLE `wa_kelas_aktif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kelas_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kelas_id` (`kelas_id`),
  CONSTRAINT `fk_wa_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 19. TABEL WA_QUEUE - Antrian Notifikasi WhatsApp
-- =====================================================
CREATE TABLE `wa_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_hp` varchar(20) NOT NULL,
  `pesan` text NOT NULL,
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `attempt` int(11) NOT NULL DEFAULT 0,
  `max_attempt` int(11) NOT NULL DEFAULT 3,
  `response` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `sent_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 20. TABEL MONITORING_BK - Monitoring Siswa Bermasalah
-- =====================================================
CREATE TABLE `monitoring_bk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `bulan` int(11) NOT NULL COMMENT '1-12',
  `tahun` int(11) NOT NULL,
  `jumlah_alpha` int(11) NOT NULL DEFAULT 0,
  `jumlah_terlambat` int(11) NOT NULL DEFAULT 0,
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=perlu perhatian',
  `catatan_bk` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_siswa_bulan_tahun` (`siswa_id`, `bulan`, `tahun`),
  KEY `siswa_id` (`siswa_id`),
  CONSTRAINT `fk_monitoring_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 21. TABEL SURAT_BK - Surat Panggilan BK
-- =====================================================
CREATE TABLE `surat_bk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_surat` varchar(100) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `hari` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `perihal` text NOT NULL,
  `bk_id` int(11) NOT NULL COMMENT 'User BK yang buat surat',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_surat` (`nomor_surat`),
  KEY `siswa_id` (`siswa_id`),
  KEY `bk_id` (`bk_id`),
  CONSTRAINT `fk_surat_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_surat_bk` FOREIGN KEY (`bk_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 22. TABEL RIWAYAT_KELAS - Riwayat Naik Kelas
-- =====================================================
CREATE TABLE `riwayat_kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `siswa_id` (`siswa_id`),
  KEY `kelas_id` (`kelas_id`),
  KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  CONSTRAINT `fk_riwayat_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_riwayat_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_riwayat_tahun` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 23. TABEL RFID_LOG - Log Aktivitas RFID
-- =====================================================
CREATE TABLE `rfid_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rfid_uid` varchar(50) NOT NULL,
  `jenis` enum('siswa','guru','unknown') NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aksi` enum('tap_masuk','tap_pulang','unknown') NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` datetime NOT NULL,
  `status` enum('success','failed') NOT NULL,
  `keterangan` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_rfid_uid` (`rfid_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL IZIN_KBM - Izin Siswa Saat KBM (Guru Piket)
-- =====================================================
CREATE TABLE `izin_kbm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('Masuk Terlambat','Keluar Awal','Tidak Masuk') NOT NULL,
  `jam_izin` time NOT NULL,
  `jam_kembali` time DEFAULT NULL,
  `alasan` text NOT NULL,
  `guru_piket_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `siswa_id` (`siswa_id`),
  KEY `guru_piket_id` (`guru_piket_id`),
  KEY `idx_tanggal` (`tanggal`),
  CONSTRAINT `fk_izin_kbm_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_izin_kbm_guru` FOREIGN KEY (`guru_piket_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL SURAT_PANGGILAN - Surat Panggilan Orang Tua (BK)
-- =====================================================
CREATE TABLE `surat_panggilan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siswa_id` int(11) NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `waktu_panggilan` datetime NOT NULL,
  `perihal` text NOT NULL,
  `keterangan` text,
  `status` enum('Belum Dipanggil','Sudah Dipanggil','Hadir','Tidak Hadir') DEFAULT 'Belum Dipanggil',
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `siswa_id` (`siswa_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_tanggal` (`tanggal_surat`),
  CONSTRAINT `fk_surat_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_surat_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INDEXES untuk optimasi
-- =====================================================
ALTER TABLE `kelas` ADD CONSTRAINT `fk_kelas_walikelas` FOREIGN KEY (`walikelas_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL;
ALTER TABLE `kelas` ADD CONSTRAINT `fk_kelas_tahun_ajaran` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE;
ALTER TABLE `users` ADD CONSTRAINT `fk_users_guru` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE;

-- =====================================================
-- END OF DATABASE SCHEMA
-- =====================================================
