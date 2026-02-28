@echo off
echo ============================================
echo Vehicle Inspection System - Setup Helper
echo ============================================
echo.

echo Checking PHP version...
php -v
echo.

echo Checking if GD extension is enabled...
php -m | findstr gd
if %errorlevel% equ 0 (
    echo [OK] GD extension is enabled!
    echo.
    echo Running composer install...
    composer install
) else (
    echo [ERROR] GD extension is NOT enabled!
    echo.
    echo Please follow these steps:
    echo 1. Open C:\xampp\php\php.ini
    echo 2. Find: ;extension=gd
    echo 3. Change to: extension=gd
    echo 4. Save file
    echo 5. Restart Apache in XAMPP
    echo 6. Run this script again
    echo.
    echo OR run: composer install --ignore-platform-req=ext-gd
    echo (Note: QR codes won't work without GD)
)

echo.
pause
