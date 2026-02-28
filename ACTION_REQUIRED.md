# ⚠️ ACTION REQUIRED: Enable GD and ZIP Extensions

## Current Status
❌ GD extension is **NOT ENABLED** on your system
❌ ZIP extension is **NOT ENABLED** on your system
✅ PHP 8.2.12 is installed
✅ Composer is installed

## 🎯 What You Need to Do RIGHT NOW

### Step 1: Find Your php.ini File
Run this command to find the exact location:
```powershell
php --ini
```

Look for the line that says: **"Loaded Configuration File:"**

It's probably: `C:\xampp\php\php.ini`

### Step 2: Edit php.ini

1. **Open File Explorer**
2. Navigate to: `C:\xampp\php\`
3. **Right-click** on `php.ini`
4. Select **"Open with" → "Notepad"**
5. If prompted, click **"Run as administrator"**

### Step 3: Enable GD and ZIP Extensions

1. Press **Ctrl+F** to open Find
2. Type: `extension=gd`
3. You'll find a line like: `;extension=gd`
4. **Remove the semicolon** so it looks like: `extension=gd`
5. Press **Ctrl+F** again and search for: `extension=zip`
6. Find: `;extension=zip`
7. **Remove the semicolon** so it looks like: `extension=zip`
8. Press **Ctrl+S** to save
9. Close Notepad

### Step 4: Restart Apache

1. Open **XAMPP Control Panel**
2. Click **"Stop"** next to Apache (if running)
3. Wait 3 seconds
4. Click **"Start"** next to Apache
5. Apache should show **green "Running"** status

### Step 5: Verify GD is Enabled

Open PowerShell or Command Prompt and run:
```powershell
php -m
```

Look for `gd` in the list. If you see it, **SUCCESS!** ✅

### Step 6: Install the System

```powershell
cd C:\Users\talk2\OneDrive\Desktop\inspection
composer install
```

---

## 🚨 Can't Enable GD Right Now?

### Temporary Workaround

Install without GD (QR codes won't work, but everything else will):

```powershell
cd C:\Users\talk2\OneDrive\Desktop\inspection
composer install --ignore-platform-req=ext-gd
```

Then continue with:
```powershell
php artisan key:generate
```

---

## 📋 Complete Installation Checklist

- [ ] Enable GD extension in php.ini
- [ ] Restart Apache
- [ ] Verify GD with `php -m`
- [ ] Run `composer install`
- [ ] Copy `.env.example` to `.env`
- [ ] Run `php artisan key:generate`
- [ ] Configure database in `.env`
- [ ] Run `php artisan db:seed`
- [ ] Run `php artisan serve`
- [ ] Visit http://localhost:8000
- [ ] Login with admin@inspection.ng / password

---

## 🎬 Quick Video Tutorial (Text Version)

```
1. Open XAMPP folder → php folder
2. Find php.ini file
3. Open with Notepad (as admin)
4. Press Ctrl+F, search "extension=gd"
5. Remove semicolon from ";extension=gd"
6. Save file (Ctrl+S)
7. Open XAMPP Control Panel
8. Stop Apache
9. Start Apache
10. Done! ✅
```

---

## 🆘 Still Stuck?

### Option 1: Use the Automated Script
Double-click: `install.bat`

### Option 2: Manual Check
Run: `setup.bat`

### Option 3: Skip GD for Now
```powershell
composer install --ignore-platform-req=ext-gd
```

---

## 📞 What Happens After GD is Enabled?

1. Composer will install all packages successfully
2. You can generate QR codes for inspection reports
3. Image processing will work properly
4. PDF reports will include barcodes
5. Full system functionality will be available

---

## ⏱️ Time Required

- Enable GD: **2 minutes**
- Install dependencies: **3-5 minutes**
- Complete setup: **10 minutes total**

---

## 🎯 Your Next Command

After enabling GD, run:

```powershell
cd C:\Users\talk2\OneDrive\Desktop\inspection
composer install
```

---

**Let's get GD enabled and your system running! 🚀**
