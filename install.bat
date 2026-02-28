@echo off
color 0A
title Vehicle Inspection System - Installation

echo.
echo ========================================================
echo    VEHICLE INSPECTION MANAGEMENT SYSTEM
echo    Installation Script for Windows
echo ========================================================
echo.

:check_php
echo [1/7] Checking PHP installation...
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP is not installed or not in PATH
    echo Please install PHP or add it to your system PATH
    pause
    exit /b 1
)
php -v | findstr /C:"PHP"
echo [OK] PHP is installed
echo.

:check_composer
echo [2/7] Checking Composer installation...
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composer is not installed or not in PATH
    echo Please install Composer from https://getcomposer.org/
    pause
    exit /b 1
)
composer --version | findstr /C:"Composer"
echo [OK] Composer is installed
echo.

:check_gd
echo [3/7] Checking GD extension...
php -m | findstr /C:"gd" >nul 2>&1
if %errorlevel% neq 0 (
    echo [WARNING] GD extension is NOT enabled!
    echo.
    echo GD extension is required for QR code generation.
    echo.
    echo To enable GD:
    echo 1. Open C:\xampp\php\php.ini
    echo 2. Find: ;extension=gd
    echo 3. Change to: extension=gd
    echo 4. Save and restart Apache
    echo.
    choice /C YN /M "Do you want to continue without GD (QR codes won't work)"
    if errorlevel 2 (
        echo Installation cancelled. Please enable GD and run again.
        pause
        exit /b 1
    )
    set IGNORE_GD=--ignore-platform-req=ext-gd
) else (
    echo [OK] GD extension is enabled
    set IGNORE_GD=
)
echo.

:install_dependencies
echo [4/7] Installing Composer dependencies...
echo This may take a few minutes...
composer install %IGNORE_GD%
if %errorlevel% neq 0 (
    echo [ERROR] Composer install failed
    echo Please check the error messages above
    pause
    exit /b 1
)
echo [OK] Dependencies installed
echo.

:setup_env
echo [5/7] Setting up environment file...
if not exist .env (
    if exist .env.example (
        copy .env.example .env >nul
        echo [OK] .env file created from .env.example
    ) else (
        echo [ERROR] .env.example not found
        pause
        exit /b 1
    )
) else (
    echo [OK] .env file already exists
)
echo.

:generate_key
echo [6/7] Generating application key...
php artisan key:generate
if %errorlevel% neq 0 (
    echo [ERROR] Failed to generate application key
    pause
    exit /b 1
)
echo [OK] Application key generated
echo.

:final_steps
echo [7/7] Final setup steps...
echo.
echo ========================================================
echo    INSTALLATION COMPLETE!
echo ========================================================
echo.
echo Next steps:
echo.
echo 1. Configure your database in .env file:
echo    - DB_DATABASE=timo
echo    - DB_USERNAME=root
echo    - DB_PASSWORD=your_password
echo.
echo 2. Run the database seeder:
echo    php artisan db:seed
echo.
echo 3. Start the development server:
echo    php artisan serve
echo.
echo 4. Open your browser and visit:
echo    http://localhost:8000
echo.
echo 5. Login with default credentials:
echo    Email: admin@inspection.ng
echo    Password: password
echo.
echo ========================================================
echo.
echo For detailed documentation, see:
echo - README.md
echo - INSTALLATION.md
echo - QUICKSTART.md
echo - FEATURES.md
echo.
echo ========================================================
echo.

choice /C YN /M "Do you want to start the development server now"
if errorlevel 2 goto end

echo.
echo Starting Laravel development server...
echo Press Ctrl+C to stop the server
echo.
php artisan serve

:end
echo.
echo Thank you for installing Vehicle Inspection System!
echo.
pause
