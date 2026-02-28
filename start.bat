@echo off
color 0A
title Vehicle Inspection System - Quick Start

echo.
echo ========================================================
echo    VEHICLE INSPECTION SYSTEM - QUICK START
echo ========================================================
echo.

echo [1/3] Seeding database...
echo.
php artisan db:seed
if %errorlevel% neq 0 (
    echo.
    echo [ERROR] Database seeding failed!
    echo.
    echo Please check:
    echo 1. MySQL is running in XAMPP
    echo 2. Database 'timo' exists
    echo 3. Database credentials in .env are correct
    echo.
    pause
    exit /b 1
)

echo.
echo [2/3] Clearing caches...
php artisan cache:clear >nul 2>&1
php artisan config:clear >nul 2>&1
php artisan route:clear >nul 2>&1
echo [OK] Caches cleared
echo.

echo [3/3] Starting development server...
echo.
echo ========================================================
echo    SERVER STARTING
echo ========================================================
echo.
echo Visit: http://localhost:8000
echo.
echo Login Credentials:
echo   Email: admin@inspection.ng
echo   Password: password
echo.
echo Press Ctrl+C to stop the server
echo ========================================================
echo.

php artisan serve
