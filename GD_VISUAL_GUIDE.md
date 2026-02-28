# Visual Guide: Enable GD Extension in XAMPP

## Step-by-Step with Screenshots Description

### Step 1: Locate php.ini
```
📁 Open File Explorer
📂 Navigate to: C:\xampp\php\
📄 Find file: php.ini
🖱️ Right-click → Open with → Notepad (Run as Administrator)
```

### Step 2: Find the GD Extension Line
```
📝 In Notepad:
   Press Ctrl+F (Find)
   Type: extension=gd
   Click "Find Next"
```

You will see something like this:
```ini
;extension=exif      ; Must be after mbstring as it depends on it
;extension=ffi
;extension=ftp
;extension=gd        ← THIS LINE!
;extension=gettext
;extension=gmp
```

### Step 3: Enable GD Extension
```
Before:
;extension=gd

After (remove the semicolon):
extension=gd
```

### Step 4: Save the File
```
💾 Press Ctrl+S to save
✅ Close Notepad
```

### Step 5: Restart Apache
```
🔧 Open XAMPP Control Panel
🔴 Click "Stop" button next to Apache
⏳ Wait 2-3 seconds
🟢 Click "Start" button next to Apache
✅ Apache should show "Running" in green
```

### Step 6: Verify GD is Enabled
```
💻 Open Command Prompt (cmd)
📝 Type: php -m | findstr gd
⏎ Press Enter

Expected output:
gd

If you see "gd" - SUCCESS! ✅
If nothing appears - GD is still disabled ❌
```

### Step 7: Install Composer Dependencies
```
💻 In Command Prompt:
📂 cd C:\Users\talk2\OneDrive\Desktop\inspection
📦 composer install
⏳ Wait for installation to complete (2-5 minutes)
✅ Installation complete!
```

## Common Issues & Solutions

### Issue 1: "Access Denied" when saving php.ini
**Solution:** Open Notepad as Administrator
- Right-click Notepad
- Select "Run as administrator"
- Then open php.ini

### Issue 2: Multiple php.ini files
**Solution:** Find the correct one
```bash
php --ini
```
Look for "Loaded Configuration File:" and edit that file

### Issue 3: GD still not showing after restart
**Solution:** 
1. Check you edited the correct php.ini
2. Restart your computer
3. Check if there's a php.ini-development or php.ini-production file

### Issue 4: Apache won't start after changes
**Solution:**
1. Check for syntax errors in php.ini
2. Make sure you only removed the semicolon, nothing else
3. Check Apache error logs: C:\xampp\apache\logs\error.log

## Quick Test: Is GD Working?

Create a test file: `test-gd.php`
```php
<?php
if (extension_loaded('gd')) {
    echo "GD Extension is ENABLED ✅\n";
    echo "GD Version: " . GD_VERSION . "\n";
} else {
    echo "GD Extension is DISABLED ❌\n";
}
?>
```

Run it:
```bash
php test-gd.php
```

Expected output:
```
GD Extension is ENABLED ✅
GD Version: bundled (2.1.0 compatible)
```

## Alternative: Edit php.ini Directly via XAMPP

```
1. Open XAMPP Control Panel
2. Click "Config" button next to Apache
3. Select "PHP (php.ini)"
4. Find: ;extension=gd
5. Change to: extension=gd
6. Save and close
7. Restart Apache
```

## Still Having Issues?

### Check PHP Version
```bash
php -v
```

### Check All Loaded Extensions
```bash
php -m
```

### Check PHP Configuration
```bash
php --ini
```

### View PHP Info
Create `info.php`:
```php
<?php phpinfo(); ?>
```
Place in `C:\xampp\htdocs\info.php`
Visit: `http://localhost/info.php`
Search for "gd" on the page

---

## After GD is Enabled

Run the installation:
```bash
cd C:\Users\talk2\OneDrive\Desktop\inspection
composer install
php artisan key:generate
php artisan serve
```

Visit: http://localhost:8000

**You're all set! 🎉**
