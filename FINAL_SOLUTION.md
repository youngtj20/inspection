# 🎯 FINAL SOLUTION - Enable Extensions and Install

## What You Need to Do (3 Simple Steps)

### ✅ STEP 1: Enable Extensions in php.ini

**Open:** `C:\xampp\php\php.ini` (with Notepad as Administrator)

**Find these TWO lines and remove the semicolons:**

```ini
BEFORE:
;extension=gd
;extension=zip

AFTER:
extension=gd
extension=zip
```

**Save** (Ctrl+S) and **close** Notepad.

---

### ✅ STEP 2: Restart Apache

1. Open **XAMPP Control Panel**
2. **Stop** Apache
3. Wait 3 seconds
4. **Start** Apache

---

### ✅ STEP 3: Install the System

Open PowerShell or Command Prompt:

```powershell
cd C:\Users\talk2\OneDrive\Desktop\inspection
composer install
```

**That's it!** ✅

---

## 🚀 After Installation Completes

```powershell
# 1. Generate application key
php artisan key:generate

# 2. Copy environment file (if not exists)
copy .env.example .env

# 3. Edit .env and set database:
#    DB_DATABASE=timo
#    DB_USERNAME=root
#    DB_PASSWORD=

# 4. Seed database with initial data
php artisan db:seed

# 5. Start the server
php artisan serve
```

**Visit:** http://localhost:8000

**Login:**
- Email: `admin@inspection.ng`
- Password: `password`

---

## 🆘 Alternative: Install Without Extensions

If you absolutely cannot enable extensions right now:

```powershell
composer install --ignore-platform-req=ext-gd --ignore-platform-req=ext-zip
```

**⚠️ Limitations:**
- ❌ QR codes won't work
- ❌ Excel export won't work
- ✅ Everything else will work

---

## 🔍 Verify Extensions Are Enabled

Run this to check:

```powershell
php -m
```

You should see both `gd` and `zip` in the list.

---

## 📁 Quick Reference

| File | Purpose |
|------|---------|
| `check-extensions.bat` | **Run this** to check your extensions |
| `ENABLE_EXTENSIONS.md` | Detailed guide for enabling extensions |
| `ACTION_REQUIRED.md` | Step-by-step instructions |
| `install.bat` | Automated installation script |

---

## ⏱️ Total Time: 5 Minutes

1. Edit php.ini: **1 minute**
2. Restart Apache: **30 seconds**
3. Run composer install: **3 minutes**
4. Complete setup: **1 minute**

---

## 🎉 You're Almost There!

Just enable those two extensions and you'll have a fully functional Vehicle Inspection Management System!

**Next command:**
```powershell
composer install
```
