@echo off
color 0E
title Extension Checker - Vehicle Inspection System

echo.
echo ========================================================
echo    EXTENSION CHECKER
echo    Vehicle Inspection Management System
echo ========================================================
echo.

echo Checking required PHP extensions...
echo.

set MISSING=0

echo [1] Checking GD extension...
php -m | findstr /C:"gd" >nul 2>&1
if %errorlevel% equ 0 (
    echo     [OK] GD extension is ENABLED
) else (
    echo     [X] GD extension is MISSING
    set MISSING=1
)

echo [2] Checking ZIP extension...
php -m | findstr /C:"zip" >nul 2>&1
if %errorlevel% equ 0 (
    echo     [OK] ZIP extension is ENABLED
) else (
    echo     [X] ZIP extension is MISSING
    set MISSING=1
)

echo [3] Checking MySQLi extension...
php -m | findstr /C:"mysqli" >nul 2>&1
if %errorlevel% equ 0 (
    echo     [OK] MySQLi extension is ENABLED
) else (
    echo     [X] MySQLi extension is MISSING
    set MISSING=1
)

echo [4] Checking PDO MySQL extension...
php -m | findstr /C:"pdo_mysql" >nul 2>&1
if %errorlevel% equ 0 (
    echo     [OK] PDO MySQL extension is ENABLED
) else (
    echo     [X] PDO MySQL extension is MISSING
    set MISSING=1
)

echo [5] Checking MBString extension...
php -m | findstr /C:"mbstring" >nul 2>&1
if %errorlevel% equ 0 (
    echo     [OK] MBString extension is ENABLED
) else (
    echo     [X] MBString extension is MISSING
    set MISSING=1
)

echo.
echo ========================================================

if %MISSING% equ 0 (
    echo.
    echo     ALL REQUIRED EXTENSIONS ARE ENABLED!
    echo.
    echo ========================================================
    echo.
    echo You can now run: composer install
    echo.
    choice /C YN /M "Do you want to run composer install now"
    if errorlevel 2 goto end
    echo.
    echo Running composer install...
    composer install
    goto end
) else (
    echo.
    echo     SOME EXTENSIONS ARE MISSING!
    echo.
    echo ========================================================
    echo.
    echo To enable missing extensions:
    echo.
    echo 1. Open: C:\xampp\php\php.ini
    echo 2. Find lines like: ;extension=gd
    echo 3. Remove semicolon: extension=gd
    echo 4. Save file
    echo 5. Restart Apache in XAMPP
    echo 6. Run this script again
    echo.
    echo OR
    echo.
    echo Install without missing extensions (limited functionality):
    echo composer install --ignore-platform-req=ext-gd --ignore-platform-req=ext-zip
    echo.
    echo ========================================================
    echo.
    echo Opening php.ini location guide...
    echo.
    php --ini
    echo.
    echo Look for "Loaded Configuration File" above
    echo That's the file you need to edit!
    echo.
)

:end
echo.
pause
