# Fix for PHP GD Extension

## Step 1: Enable GD Extension in php.ini

1. Open `C:\xampp\php\php.ini` in a text editor (as Administrator)

2. Find this line (around line 900-950):
   ```
   ;extension=gd
   ```

3. Remove the semicolon to enable it:
   ```
   extension=gd
   ```

4. Save the file

5. Restart Apache/PHP service:
   - Open XAMPP Control Panel
   - Stop Apache
   - Start Apache again

## Step 2: Verify GD is Enabled

Run this command:
```bash
php -m | findstr gd
```

You should see "gd" in the output.

## Alternative: Install Without GD (Temporary)

If you can't enable GD right now, you can install without it temporarily:

```bash
composer install --ignore-platform-req=ext-gd
```

Note: QR code and some image features won't work without GD extension.

## Step 3: After Enabling GD, Run

```bash
composer install
```
