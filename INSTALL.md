# 📦 Installation Guide - Sistem Absensi RFID

## Prerequisites

Before installing, ensure you have:

- ✅ PHP 7.2 or higher
- ✅ MySQL 5.7+ or MariaDB 10.3+
- ✅ Apache/Nginx web server with mod_rewrite enabled
- ✅ Composer installed
- ✅ PHP Extensions: mysqli, gd, mbstring, xml, curl

## Step-by-Step Installation

### 1. Clone Repository

```bash
git clone https://github.com/apriansasyadrik/absenrfid.git
cd absenrfid
```

### 2. Install CodeIgniter 3 Core

The repository doesn't include the CodeIgniter 3 `system` folder to keep it lightweight. Install it using one of these methods:

**Method A: Using Composer (Recommended)**

```bash
composer create-project codeigniter/framework:^3.1 ci3-temp
cp -r ci3-temp/system .
rm -rf ci3-temp
```

**Method B: Manual Download**

1. Download CodeIgniter 3 from: https://codeigniter.com/download
2. Extract the zip file
3. Copy only the `system` folder to your project root
4. Your structure should be: `absenrfid/system/`

### 3. Install PHP Dependencies

```bash
composer install
```

This will install:
- `phpoffice/phpspreadsheet` - For Excel import/export
- `tecnickcom/tcpdf` - For PDF generation

### 4. Create and Configure Database

**Create Database:**

```bash
mysql -u root -p
```

```sql
CREATE DATABASE absensi_rfid CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**Import Database Schema:**

```bash
mysql -u root -p absensi_rfid < database.sql
```

This will create:
- 24 tables with relationships
- Default admin account
- Sample settings
- Sample jam kerja data
- WhatsApp templates

### 5. Configure Application

**Edit Database Configuration:**

File: `application/config/database.php`

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',              // Your MySQL username
    'password' => 'your_password',     // Your MySQL password
    'database' => 'absensi_rfid',
    'dbdriver' => 'mysqli',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
);
```

**Edit Base URL and Encryption Key:**

File: `application/config/config.php`

```php
// Update base_url to match your installation
$config['base_url'] = 'http://localhost/absenrfid/';

// Generate a random 32-character encryption key
$config['encryption_key'] = 'your_random_32_character_key_here';
```

To generate a random encryption key:
```bash
php -r "echo bin2hex(random_bytes(16));"
```

### 6. Set Folder Permissions

```bash
# For Linux/Mac
chmod -R 755 assets/uploads
chmod -R 755 application/logs
chmod -R 755 application/cache

# Create log and cache directories if they don't exist
mkdir -p application/logs
mkdir -p application/cache
touch application/logs/index.html
touch application/cache/index.html
```

For Windows, ensure the web server user has write access to these folders.

### 7. Configure Apache (.htaccess)

The `.htaccess` file is already included, but ensure `mod_rewrite` is enabled:

```bash
# For Ubuntu/Debian
sudo a2enmod rewrite
sudo systemctl restart apache2

# For other systems, check Apache documentation
```

**Apache Virtual Host Configuration (Optional but recommended):**

```apache
<VirtualHost *:80>
    ServerName absensi.local
    DocumentRoot "/var/www/absenrfid"
    
    <Directory "/var/www/absenrfid">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/absensi-error.log
    CustomLog ${APACHE_LOG_DIR}/absensi-access.log combined
</VirtualHost>
```

Don't forget to update your `/etc/hosts` file:
```
127.0.0.1  absensi.local
```

### 8. Setup Cron Jobs (Production Only)

For production server, setup these cron jobs:

```bash
crontab -e
```

Add these lines:

```cron
# Process WhatsApp queue every minute
* * * * * /usr/bin/php /var/www/absenrfid/index.php api/waqueue/process >> /var/www/absenrfid/application/logs/cron-wa.log 2>&1

# Check students who haven't checked in (runs at 9:00 AM, Monday-Saturday)
0 9 * * 1-6 /usr/bin/php /var/www/absenrfid/index.php api/cron/check_belum_absen >> /var/www/absenrfid/application/logs/cron-absen.log 2>&1

# Update BK monitoring (runs at 11:00 PM daily)
0 23 * * * /usr/bin/php /var/www/absenrfid/index.php api/cron/update_monitoring_bk >> /var/www/absenrfid/application/logs/cron-bk.log 2>&1
```

### 9. Test the Installation

**Access the Application:**

Open your browser and navigate to:
```
http://localhost/absenrfid/
```

or

```
http://absensi.local/
```

**Default Login Credentials:**

- **Username:** `admin`
- **Password:** `admin123`

**⚠️ IMPORTANT:** Change the default password immediately after first login!

**Access RFID Display (No Login Required):**

```
http://localhost/absenrfid/absensi
```

This page should be displayed on a monitor/TV for real-time attendance display.

### 10. Configure WhatsApp Integration (Optional)

1. Login as admin
2. Navigate to **WA Notifikasi** menu
3. Configure your WhatsApp API settings:
   - API URL
   - API Key
   - Sender Number
4. Edit message templates
5. Select classes to receive notifications
6. Test send a message

## RFID Hardware Integration

### Arduino/ESP32 Integration

The system accepts RFID data via HTTP POST request to the API endpoint:

```
POST http://your-server/absenrfid/api/rfid/scan
Parameters:
  - rfid_uid: string (UID from RFID card)
  - timestamp: string (optional, format: Y-m-d H:i:s)
```

**Example Arduino/ESP32 Code:**

```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <MFRC522.h>

const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";
const char* serverUrl = "http://your-server/absenrfid/api/rfid/scan";

#define SS_PIN 5
#define RST_PIN 22

MFRC522 mfrc522(SS_PIN, RST_PIN);

void setup() {
  Serial.begin(115200);
  SPI.begin();
  mfrc522.PCD_Init();
  
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}

void loop() {
  // Look for new cards
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
    return;
  }
  
  // Get UID
  String uid = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    uid += String(mfrc522.uid.uidByte[i], HEX);
  }
  uid.toUpperCase();
  
  Serial.println("RFID UID: " + uid);
  
  // Send to server
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    String postData = "rfid_uid=" + uid;
    int httpCode = http.POST(postData);
    
    if (httpCode > 0) {
      String response = http.getString();
      Serial.println("Server response: " + response);
      
      // Parse JSON response and handle accordingly
      // (Show success/error on LCD or LED)
    }
    
    http.end();
  }
  
  // Halt PICC and stop encryption
  mfrc522.PICC_HaltA();
  mfrc522.PCD_StopCrypto1();
  
  delay(2000); // Prevent multiple reads
}
```

## Post-Installation Tasks

### 1. Change Default Password

Login and navigate to Profile → Change Password

### 2. Configure School Settings

Go to: **Pengaturan → Sekolah**

Update:
- School name
- Address
- Principal name
- Upload school logo

### 3. Setup Work Hours

Go to: **Pengaturan → Jam Kerja**

Configure:
- Work days (which days are working days)
- Check-in and check-out times per day
- Late tolerance (in minutes)

### 4. Add Holidays

Go to: **Pengaturan → Hari Libur**

Add national holidays and school holidays

### 5. Setup Master Data

In order:
1. **Tahun Ajaran** - Add academic years
2. **Semester** - Add semesters
3. **Kelas** - Add classes
4. **Guru & Staff** - Add teachers (with RFID UID)
5. **Siswa** - Add students (with RFID UID)

💡 **Tip:** Use Excel import for bulk adding students and teachers

### 6. Setup Mata Pelajaran & Jadwal

1. Add subjects
2. Create class schedules

## Troubleshooting

### Issue: 404 Page Not Found

**Solution:**
- Ensure `.htaccess` file exists in root directory
- Enable `mod_rewrite`: `sudo a2enmod rewrite`
- Check Apache configuration allows `.htaccess` override
- Restart Apache: `sudo systemctl restart apache2`

### Issue: Database Connection Error

**Solution:**
- Verify database credentials in `application/config/database.php`
- Ensure MySQL service is running: `sudo systemctl status mysql`
- Check database exists: `SHOW DATABASES;`
- Verify user has proper permissions

### Issue: Permission Denied Errors

**Solution:**
```bash
sudo chown -R www-data:www-data /var/www/absenrfid
chmod -R 755 /var/www/absenrfid
chmod -R 755 assets/uploads
chmod -R 755 application/logs
chmod -R 755 application/cache
```

### Issue: Composer Dependencies Not Found

**Solution:**
```bash
composer install
# or
composer update
```

### Issue: RFID API Not Working

**Solution:**
- Check if endpoint is accessible: `http://your-server/absenrfid/api/rfid/scan`
- Verify CSRF exception in `application/config/config.php`:
  ```php
  $config['csrf_exclude_uris'] = array('api/rfid/scan', 'api/waqueue/process', 'api/cron/*');
  ```
- Test with Postman or curl

### Issue: WhatsApp Not Sending

**Solution:**
- Verify WhatsApp API configuration
- Check queue table: `SELECT * FROM wa_queue WHERE status='failed'`
- Verify cron job is running
- Check API logs

## Security Recommendations

1. ✅ Change default admin password
2. ✅ Use strong database password
3. ✅ Generate unique encryption key
4. ✅ Enable HTTPS in production
5. ✅ Restrict database access
6. ✅ Regular backups
7. ✅ Keep CodeIgniter and dependencies updated
8. ✅ Review and limit CSRF exceptions
9. ✅ Set proper file permissions
10. ✅ Monitor error logs regularly

## Backup Strategy

### Database Backup

```bash
# Daily backup
mysqldump -u root -p absensi_rfid > backup_$(date +%Y%m%d).sql

# Automated backup script
0 2 * * * mysqldump -u root -pYOUR_PASSWORD absensi_rfid | gzip > /backups/absensi_$(date +\%Y\%m\%d).sql.gz
```

### File Backup

```bash
# Backup uploaded files
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz assets/uploads/

# Full application backup
tar -czf absensi_full_$(date +%Y%m%d).tar.gz /var/www/absenrfid --exclude='application/logs' --exclude='application/cache'
```

## Support

If you encounter any issues:

1. Check the logs: `application/logs/`
2. Review this guide
3. Check the IMPLEMENTATION_GUIDE.md
4. Create an issue on GitHub

## Next Steps

After installation:

1. ✅ Complete Post-Installation Tasks
2. ✅ Register all students and teachers with RFID cards
3. ✅ Setup RFID hardware
4. ✅ Test the complete flow
5. ✅ Train staff on using the system
6. ✅ Setup monitoring and backups

---

**🎉 Installation Complete! Your RFID Attendance System is ready to use.**
