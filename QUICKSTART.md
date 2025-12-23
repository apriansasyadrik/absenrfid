# 🚀 Quick Start Guide

Get your RFID Attendance System up and running in 10 minutes!

## TL;DR

```bash
# 1. Clone & Install CodeIgniter
git clone https://github.com/apriansasyadrik/absenrfid.git
cd absenrfid
composer create-project codeigniter/framework:^3.1 ci3-temp && cp -r ci3-temp/system . && rm -rf ci3-temp

# 2. Install Dependencies
composer install

# 3. Setup Database
mysql -u root -p
CREATE DATABASE absensi_rfid CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
mysql -u root -p absensi_rfid < database.sql

# 4. Configure
cp application/config/database.php.sample application/config/database.php
# Edit database credentials in application/config/database.php

# 5. Set Permissions
chmod -R 755 assets/uploads application/logs application/cache

# 6. Access
# Open browser: http://localhost/absenrfid/
# Login: admin / admin123
```

## What You Get

✅ **Complete RFID Attendance System** with:
- 📱 Real-time attendance display
- 👥 Student & Teacher management
- 📊 Reports & Analytics
- 💬 WhatsApp notifications
- 🎯 BK (Counselor) monitoring
- 📅 Class scheduling
- 📖 Teacher journal system

## Default Credentials

| Role  | Username | Password |
|-------|----------|----------|
| Admin | admin    | admin123 |

⚠️ **Change the password immediately after first login!**

## Key URLs

- **Admin Dashboard**: `http://localhost/absenrfid/`
- **RFID Display**: `http://localhost/absenrfid/absensi` (No login required)
- **API Endpoint**: `http://localhost/absenrfid/api/rfid/scan`

## Testing RFID Without Hardware

Open browser console on the RFID display page and run:

```javascript
// Simulate RFID scan
simulateScan('test_rfid_uid_001');
```

## Quick Configuration

### 1. School Settings
Go to: **Pengaturan → Sekolah**

Update:
- School name
- Address  
- Principal name
- Upload logo

### 2. Add Your First Student

Go to: **Data Master → Siswa → Add**

Fill in:
- NIS
- Name
- RFID UID (important!)
- Class
- Parent phone number (for WhatsApp)

### 3. Test Attendance

Method 1: Via Browser Console
```javascript
simulateScan('your_student_rfid_uid');
```

Method 2: Via cURL
```bash
curl -X POST http://localhost/absenrfid/api/rfid/scan \
  -d "rfid_uid=your_student_rfid_uid"
```

## Cron Jobs (Optional - for Production)

```bash
crontab -e
```

Add:
```cron
# WhatsApp queue processor
* * * * * php /path/to/absenrfid/index.php api/waqueue/process

# Absence reminder (9 AM)
0 9 * * 1-6 php /path/to/absenrfid/index.php api/cron/check_belum_absen

# BK monitoring (11 PM)
0 23 * * * php /path/to/absenrfid/index.php api/cron/update_monitoring_bk
```

## Common Issues

### 404 Error
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Permission Denied
```bash
chmod -R 755 assets/uploads
chmod -R 755 application/logs
chmod -R 755 application/cache
```

### Database Connection Failed
Edit `application/config/database.php` with correct credentials

## Need Help?

- 📖 Full guide: [INSTALL.md](INSTALL.md)
- 💻 Developer guide: [IMPLEMENTATION_GUIDE.md](IMPLEMENTATION_GUIDE.md)
- 📚 Main docs: [README.md](README.md)

## Next Steps

1. ✅ Login and change admin password
2. ✅ Configure school settings
3. ✅ Setup work hours
4. ✅ Add classes
5. ✅ Add teachers with RFID
6. ✅ Add students with RFID
7. ✅ Connect RFID hardware
8. ✅ Test complete flow
9. ✅ Setup WhatsApp (optional)
10. ✅ Configure cron jobs (production)

---

**🎉 You're all set! Start tracking attendance with RFID!**
