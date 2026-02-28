# 🚗 Vehicle Inspection Management System - Quick Setup

## ⚠️ IMPORTANT: Enable GD Extension First!

### Quick Fix (2 minutes):

1. **Open php.ini file:**
   - Location: `C:\xampp\php\php.ini`
   - Open with Notepad (as Administrator)

2. **Find and edit this line** (around line 900-950):
   ```ini
   ;extension=gd
   ```
   Change to:
   ```ini
   extension=gd
   ```

3. **Save the file** (Ctrl+S)

4. **Restart Apache:**
   - Open XAMPP Control Panel
   - Stop Apache
   - Start Apache

5. **Verify GD is enabled:**
   ```bash
   php -m | findstr gd
   ```
   You should see "gd" in the output.

---

## 🚀 Installation Options

### Option 1: Automated Installation (Recommended)

Double-click `install.bat` and follow the prompts.

### Option 2: Manual Installation

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
copy .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Configure database in .env file
# Edit DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 5. Run database seeder
php artisan db:seed

# 6. Start server
php artisan serve
```

### Option 3: Install Without GD (Temporary)

If you can't enable GD right now:

```bash
composer install --ignore-platform-req=ext-gd
```

**Note:** QR code generation won't work, but everything else will.

---

## 📝 After Installation

1. **Visit:** http://localhost:8000

2. **Login with:**
   - Email: `admin@inspection.ng`
   - Password: `password`

3. **⚠️ Change password immediately after first login!**

---

## 📚 Documentation

- **Quick Start:** `QUICKSTART.md`
- **Full Features:** `FEATURES.md`
- **Installation Guide:** `INSTALLATION.md`
- **Database Analysis:** `DATABASE_ANALYSIS.md`

---

## 🆘 Troubleshooting

### GD Extension Issues
See: `ENABLE_GD.md`

### Composer Errors
```bash
composer clear-cache
composer install
```

### Database Connection
1. Check MySQL is running in XAMPP
2. Verify credentials in `.env`
3. Ensure database `timo` exists

### Permission Errors
Run Command Prompt as Administrator

---

## 📞 Need Help?

Check the documentation files or review error logs in `storage/logs/laravel.log`

---

## ✅ System Requirements

- ✅ PHP 8.1+ (You have 8.2.12)
- ✅ MySQL 8.0+
- ✅ Composer
- ⚠️ GD Extension (needs to be enabled)

---

**Ready to start? Run `install.bat` or follow the manual steps above!**
