# Vehicle Inspection Management System - Installation Guide

## Prerequisites

- PHP >= 8.1
- MySQL >= 8.0
- Composer
- Node.js & NPM (for asset compilation)
- Git

## Step-by-Step Installation

### 1. Clone or Setup Project

```bash
cd c:\Users\talk2\OneDrive\Desktop\inspection
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

Copy the `.env.example` to `.env`:

```bash
copy .env.example .env
```

Edit `.env` file and configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=timo
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Database Setup

The system uses your existing `timo` database. No migrations needed as tables already exist.

### 6. Create Storage Link

```bash
php artisan storage:link
```

### 7. Seed Initial Data (Optional)

Create a database seeder for initial users and roles:

```bash
php artisan db:seed
```

### 8. Compile Assets

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 9. Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Default Login Credentials

After seeding, use these credentials:

**Super Admin:**
- Email: admin@inspection.ng
- Password: password

**Inspector:**
- Email: inspector@inspection.ng
- Password: password

**Viewer:**
- Email: viewer@inspection.ng
- Password: password

## Post-Installation Configuration

### 1. Configure Permissions

```bash
php artisan permission:cache-reset
```

### 2. Optimize Application

```bash
php artisan optimize
```

### 3. Set Up Queue Worker (Optional)

For background jobs:

```bash
php artisan queue:work
```

### 4. Set Up Task Scheduler (Optional)

Add to crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Features Configuration

### PDF Generation

The system uses DomPDF for PDF generation. Configuration in `config/dompdf.php`.

### Barcode/QR Code

QR codes are generated using SimpleSoftwareIO/simple-qrcode package.

### File Uploads

Configure max upload size in `.env`:

```env
MAX_UPLOAD_SIZE=10240
```

### Inspection Settings

```env
INSPECTION_CERTIFICATE_VALIDITY_DAYS=365
INSPECTION_REMINDER_DAYS=30
BARCODE_TYPE=CODE128
```

## Troubleshooting

### Permission Issues

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Database Connection Issues

1. Verify MySQL is running
2. Check database credentials in `.env`
3. Ensure database `timo` exists
4. Test connection: `php artisan tinker` then `DB::connection()->getPdo();`

## Production Deployment

### 1. Environment

Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`

### 2. Optimize

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Security

- Change all default passwords
- Set strong `APP_KEY`
- Configure HTTPS
- Set up firewall rules
- Regular backups

### 4. Web Server Configuration

#### Apache

```apache
<VirtualHost *:80>
    ServerName inspection.example.com
    DocumentRoot /path/to/inspection/public

    <Directory /path/to/inspection/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name inspection.example.com;
    root /path/to/inspection/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Backup Strategy

### Database Backup

```bash
mysqldump -u root -p timo > backup_$(date +%Y%m%d).sql
```

### Full Backup

```bash
tar -czf inspection_backup_$(date +%Y%m%d).tar.gz /path/to/inspection
```

## Monitoring

### Application Logs

Located in `storage/logs/laravel.log`

### Database Logs

Check MySQL slow query log and error log

### Performance Monitoring

Consider using:
- Laravel Telescope (development)
- New Relic or Datadog (production)

## Support

For issues or questions:
- Check documentation
- Review logs
- Contact system administrator

## License

MIT License - See LICENSE file for details
