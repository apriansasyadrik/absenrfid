# 🎓 Sistem Absensi RFID - Siswa dan Guru

Aplikasi **Sistem Absensi Siswa dan Guru berbasis Kartu RFID** menggunakan **CodeIgniter 3** dan **Tailwind CSS Modern**. Aplikasi ini memiliki fitur absensi masuk/pulang menggunakan RFID dan absensi per mata pelajaran dengan metode input H/S/I/A melalui jurnal guru.

## 🔧 Tech Stack

- **Backend**: CodeIgniter 3
- **Frontend**: Tailwind CSS Modern (CDN)
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **PHP**: 7.2 atau lebih tinggi
- **Export**: PhpSpreadsheet untuk Excel (.xlsx), TCPDF untuk PDF
- **RFID**: Integrasi dengan hardware RFID reader
- **WhatsApp API**: Untuk notifikasi otomatis

## 📋 Fitur Utama

### 👤 Role dan Hak Akses

1. **Admin** - Akses penuh ke semua fitur sistem
2. **Guru** - Jurnal mengajar, absensi siswa per mapel
3. **Walikelas** - Fitur guru + input izin siswa
4. **Guru Piket** - Fitur guru + izin siswa saat KBM
5. **BK (Bimbingan Konseling)** - Monitoring siswa, cetak surat panggilan

### 🎯 Fitur Lengkap

#### Modul Admin
- Dashboard dengan statistik dan grafik
- Pengaturan sekolah (nama, alamat, kepala sekolah, logo)
- Pengaturan jam kerja dan hari libur
- Master data: Tahun Ajaran, Semester, Kelas, Siswa, Guru
- Import/Export Excel untuk data siswa dan guru
- Mata pelajaran dan jadwal pelajaran
- Pengaturan WhatsApp notifikasi
- Laporan dan rekap absensi (PDF/Excel)

#### Modul Guru
- Dashboard jadwal dan jurnal
- Isi jurnal mengajar
- Input absensi siswa per mapel (H/S/I/A)
- Rekap jurnal dan absensi
- Profile management

#### Modul Walikelas
- Semua fitur guru
- Input izin sakit/izin untuk siswa di kelasnya

#### Modul Guru Piket
- Input izin siswa masuk/keluar saat KBM
- Rekap izin harian

#### Modul BK
- Dashboard monitoring siswa bermasalah
- Auto-flag siswa (Alpha ≥3 atau Terlambat ≥5 per bulan)
- Cetak surat panggilan
- Catatan konseling

#### Halaman Absensi RFID (Tanpa Login)
- Akses langsung tanpa login
- Display full screen untuk monitor absensi
- Real-time scanning RFID
- Tampil foto, nama, kelas/jabatan, status, waktu
- List absensi hari ini dengan auto-refresh
- **WhatsApp Queue System** - Notifikasi tidak blocking

## 📦 Instalasi

### 1. Requirement

```bash
- PHP 7.2 atau lebih tinggi
- MySQL 5.7+ atau MariaDB 10.3+
- Apache/Nginx dengan mod_rewrite
- Composer (untuk install dependencies)
- Extension PHP: mysqli, gd, mbstring, xml
```

### 2. Clone Repository

```bash
git clone https://github.com/apriansasyadrik/absenrfid.git
cd absenrfid
```

### 3. Install CodeIgniter 3

Download CodeIgniter 3 dari [https://codeigniter.com/download](https://codeigniter.com/download) dan extract folder `system` ke root project.

Atau gunakan composer:

```bash
composer create-project codeigniter/framework:^3.1 ci3-temp
cp -r ci3-temp/system .
rm -rf ci3-temp
```

### 4. Install Dependencies

```bash
composer require phpoffice/phpspreadsheet
composer require tecnickcom/tcpdf
```

### 5. Konfigurasi Database

Buat database MySQL:

```bash
mysql -u root -p
CREATE DATABASE absensi_rfid CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

Import database schema:

```bash
mysql -u root -p absensi_rfid < database.sql
```

### 6. Konfigurasi CodeIgniter

Edit file `application/config/database.php`:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => 'your_password',
    'database' => 'absensi_rfid',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
);
```

Edit file `application/config/config.php`:

```php
$config['base_url'] = 'http://localhost/absenrfid/';
$config['encryption_key'] = 'your_random_32_character_key_here';
```

### 7. Set Permission Folder

```bash
chmod -R 755 assets/uploads
chmod -R 755 application/logs
chmod -R 755 application/cache
```

### 8. Setup Cron Jobs (Production)

Tambahkan ke crontab:

```bash
crontab -e
```

Tambahkan baris berikut:

```cron
# Notifikasi siswa belum absen (jam 09:00)
0 9 * * 1-6 /usr/bin/php /path/to/absenrfid/index.php api/cron/check_belum_absen

# Process WhatsApp queue (setiap 1 menit)
* * * * * /usr/bin/php /path/to/absenrfid/index.php api/waqueue/process

# Update monitoring BK (jam 23:00)
0 23 * * * /usr/bin/php /path/to/absenrfid/index.php api/cron/update_monitoring_bk
```

### 9. Akses Aplikasi

Buka browser dan akses:

```
http://localhost/absenrfid/
```

**Default Login Admin:**
- Username: `admin`
- Password: `admin123`

**Halaman Absensi RFID (tanpa login):**
```
http://localhost/absenrfid/absensi
```

## 📱 Integrasi RFID Hardware

Aplikasi ini dirancang untuk menerima input RFID melalui endpoint API:

```
POST /api/rfid/scan
Parameters:
- rfid_uid: UID dari kartu RFID
- timestamp: Waktu scan (optional)
```

Contoh integrasi dengan Arduino/ESP32:

```cpp
// Arduino/ESP32 Code Example
#include <WiFi.h>
#include <HTTPClient.h>

void sendRFID(String uid) {
  HTTPClient http;
  http.begin("http://your-server/absenrfid/api/rfid/scan");
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  
  String postData = "rfid_uid=" + uid;
  int httpCode = http.POST(postData);
  
  if (httpCode > 0) {
    String response = http.getString();
    Serial.println(response);
  }
  
  http.end();
}
```

## 📊 Database Schema

Aplikasi menggunakan 24 tabel utama:

1. **settings** - Pengaturan sekolah
2. **jam_kerja** - Jam masuk/pulang per hari
3. **hari_libur** - Hari libur nasional
4. **tahun_ajaran** - Data tahun ajaran
5. **semester** - Data semester
6. **kelas** - Data kelas dengan walikelas
7. **users** - User login (role-based)
8. **guru** - Data guru dan staff dengan RFID UID
9. **siswa** - Data siswa dengan RFID UID
10. **mata_pelajaran** - Data mata pelajaran
11. **jadwal_pelajaran** - Jadwal pelajaran
12. **absensi_harian** - Absensi RFID masuk/pulang
13. **jurnal_mengajar** - Jurnal guru
14. **absensi_mapel** - Absensi per mapel (H/S/I/A)
15. **izin_siswa** - Izin dari walikelas
16. **izin_kbm** - Izin dari guru piket
17. **wa_settings** - Pengaturan WhatsApp API
18. **wa_templates** - Template pesan WA
19. **wa_kelas_aktif** - Kelas yang aktif notifikasi
20. **wa_queue** - Antrian notifikasi WA
21. **monitoring_bk** - Monitoring siswa bermasalah
22. **surat_bk** - Surat panggilan BK
23. **riwayat_kelas** - Riwayat naik kelas
24. **rfid_log** - Log aktivitas RFID

## 🔗 Integrasi WhatsApp

### Setup WhatsApp API

1. Login sebagai Admin
2. Menu **Pengaturan WA Notifikasi**
3. Isi konfigurasi:
   - URL API (contoh: https://api.whatsapp.com/send)
   - API Key
   - Sender Number
   - Link URL (optional)
4. Edit template pesan sesuai kebutuhan
5. Pilih kelas yang akan menerima notifikasi
6. Test kirim pesan

### WhatsApp Queue System

Aplikasi menggunakan sistem queue untuk mengirim WhatsApp:
- Saat siswa/guru tap RFID, data langsung tampil di layar
- Notifikasi masuk ke `wa_queue` table
- Cron job memproses queue setiap 1 menit
- Retry mechanism otomatis (max 3 attempts)
- Tidak blocking proses absensi

## 📄 Export Laporan

### PDF
- Kop surat otomatis (logo, nama sekolah, alamat)
- Header dan footer dengan tanggal cetak
- Tabel data terstruktur

### Excel (.xlsx)
- Format rapi dengan header
- Auto-width kolom
- Support filter dan sort

## 🎨 Design & UI

- **Tailwind CSS Modern** - Utility-first CSS framework
- **Responsive Design** - Mobile, tablet, desktop friendly
- **SweetAlert2** - Konfirmasi dan notifikasi cantik
- **DataTables** - Tabel interaktif dengan search, sort, pagination
- **Heroicons/Font Awesome** - Icon set modern
- **Loading States** - UX yang smooth

## 🔐 Security

✅ Password hashing dengan bcrypt  
✅ Session management yang aman  
✅ CSRF protection  
✅ XSS filtering  
✅ SQL injection prevention (Query Builder)  
✅ Role-based access control  
✅ Input validation dan sanitization  

## 📁 Struktur Folder

```
absenrfid/
├── application/
│   ├── config/          # Konfigurasi
│   ├── controllers/     # Controllers (Admin, Guru, Walikelas, Piket, BK, Api)
│   ├── models/          # Models
│   ├── views/           # Views (templates, pages)
│   ├── libraries/       # Custom libraries (Excel, PDF, WhatsApp)
│   └── helpers/         # Custom helpers
├── system/              # CodeIgniter 3 core files
├── assets/
│   ├── css/            # Tailwind CSS
│   ├── js/             # JavaScript files
│   ├── images/         # Images
│   └── uploads/        # Upload files (logo, foto, imports)
├── vendor/             # Composer dependencies
├── database.sql        # Database schema
├── index.php           # Entry point
├── .htaccess           # Apache rewrite rules
├── composer.json       # Composer config
└── README.md           # This file
```

## 🐛 Troubleshooting

### Error 404 Page Not Found

Pastikan `.htaccess` sudah benar dan `mod_rewrite` aktif:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

Enable `mod_rewrite`:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Error Database Connection

- Cek credential di `application/config/database.php`
- Pastikan MySQL service running
- Pastikan database sudah dibuat dan diimport

### Error Permission Denied

```bash
chmod -R 755 assets/uploads
chmod -R 755 application/logs
chmod -R 755 application/cache
```

### Composer Dependencies Not Found

```bash
composer install
```

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

## 📝 License

This project is open source and available under the MIT License.

## 👨‍💻 Author

**Aprian Sasyadrik**

## 📞 Support

Jika ada pertanyaan atau butuh bantuan, silakan buat issue di repository ini.

---

**Dibuat dengan ❤️ menggunakan CodeIgniter 3 dan Tailwind CSS**
