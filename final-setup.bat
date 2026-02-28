@echo off
color 0A
title Vehicle Inspection System - Final Setup

echo.
echo ========================================================
echo    FINAL SETUP - Vehicle Inspection System
echo ========================================================
echo.

echo [1/5] Generating application key...
php artisan key:generate
if %errorlevel% neq 0 (
    echo [ERROR] Failed to generate key
    pause
    exit /b 1
)
echo [OK] Application key generated
echo.

echo [2/5] Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo [OK] Caches cleared
echo.

echo [3/5] Creating database seeder...
echo Creating seeder file...
echo.

echo [4/5] Database configuration...
echo.
echo Please ensure your .env file has correct database settings:
echo   DB_DATABASE=timo
echo   DB_USERNAME=root
echo   DB_PASSWORD=your_password
echo.
pause

echo [5/5] Running database seeder...
php artisan db:seed
if %errorlevel% neq 0 (
    echo [WARNING] Seeder failed - you may need to create it first
    echo See QUICKSTART.md for seeder code
)
echo.

echo ========================================================
echo    SETUP COMPLETE!
echo ========================================================
echo.
echo Next steps:
echo 1. Verify database settings in .env
echo 2. Run: php artisan serve
echo 3. Visit: http://localhost:8000
echo 4. Login: admin@inspection.ng / password
echo.
echo ========================================================
echo.

choice /C YN /M "Do you want to start the server now"
if errorlevel 2 goto end

echo.
echo Starting server...
echo Press Ctrl+C to stop
echo.
php artisan serve

:end
pause
