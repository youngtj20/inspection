# Quick Start Guide - Vehicle Inspection Management System

## Getting Started in 5 Minutes

### Step 1: Install Laravel (if not already installed)

```bash
# Navigate to project directory
cd c:\Users\talk2\OneDrive\Desktop\inspection

# Install Composer dependencies
composer install

# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 2: Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=timo
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 3: Install Required Packages

```bash
# Install Laravel packages
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
composer require simplesoftwareio/simple-qrcode
composer require spatie/laravel-permission
composer require spatie/laravel-activitylog
```

### Step 4: Publish Package Configurations

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
```

### Step 5: Create Database Seeder

Create `database/seeders/DatabaseSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create default department
        $deptId = DB::table('sys_dept')->insertGetId([
            'title' => 'Main Inspection Center',
            'deptno' => '001',
            'pid' => 0,
            'pids' => '[0]',
            'sort' => 1,
            'address' => 'Lagos, Nigeria',
            'state' => 'Lagos',
            'contactnumber' => '+234-XXX-XXXX',
            'contacts' => 'Admin',
            'employees' => 10,
            'status' => 1,
            'create_date' => now(),
        ]);

        // Create roles
        $superAdminRoleId = DB::table('sys_role')->insertGetId([
            'title' => 'Super Administrator',
            'name' => 'super-admin',
            'status' => 1,
            'create_date' => now(),
        ]);

        $adminRoleId = DB::table('sys_role')->insertGetId([
            'title' => 'Administrator',
            'name' => 'admin',
            'status' => 1,
            'create_date' => now(),
        ]);

        $inspectorRoleId = DB::table('sys_role')->insertGetId([
            'title' => 'Inspector',
            'name' => 'inspector',
            'status' => 1,
            'create_date' => now(),
        ]);

        // Create users
        $superAdminId = DB::table('sys_user')->insertGetId([
            'username' => 'admin',
            'nickname' => 'Super Admin',
            'password' => Hash::make('password'),
            'email' => 'admin@inspection.ng',
            'phone' => '+234-XXX-XXXX',
            'dept_id' => $deptId,
            'sex' => 1,
            'status' => 1,
            'create_date' => now(),
        ]);

        $inspectorId = DB::table('sys_user')->insertGetId([
            'username' => 'inspector',
            'nickname' => 'Inspector One',
            'password' => Hash::make('password'),
            'email' => 'inspector@inspection.ng',
            'phone' => '+234-XXX-XXXX',
            'dept_id' => $deptId,
            'sex' => 1,
            'status' => 1,
            'create_date' => now(),
        ]);

        // Assign roles
        DB::table('sys_user_role')->insert([
            ['user_id' => $superAdminId, 'role_id' => $superAdminRoleId],
            ['user_id' => $inspectorId, 'role_id' => $inspectorRoleId],
        ]);

        // Create sample menus
        $dashboardMenuId = DB::table('sys_menu')->insertGetId([
            'title' => 'Dashboard',
            'pid' => 0,
            'pids' => '[0]',
            'url' => '/dashboard',
            'perms' => 'dashboard.view',
            'icon' => 'fa-home',
            'type' => 1,
            'sort' => 1,
            'status' => 1,
            'create_date' => now(),
        ]);

        $inspectionsMenuId = DB::table('sys_menu')->insertGetId([
            'title' => 'Inspections',
            'pid' => 0,
            'pids' => '[0]',
            'url' => '/inspections',
            'perms' => 'inspections.view',
            'icon' => 'fa-clipboard-check',
            'type' => 1,
            'sort' => 2,
            'status' => 1,
            'create_date' => now(),
        ]);

        // Assign menus to roles
        DB::table('sys_role_menu')->insert([
            ['role_id' => $superAdminRoleId, 'menu_id' => $dashboardMenuId],
            ['role_id' => $superAdminRoleId, 'menu_id' => $inspectionsMenuId],
            ['role_id' => $inspectorRoleId, 'menu_id' => $dashboardMenuId],
            ['role_id' => $inspectorRoleId, 'menu_id' => $inspectionsMenuId],
        ]);

        echo "Database seeded successfully!\n";
        echo "Login credentials:\n";
        echo "Super Admin - Email: admin@inspection.ng, Password: password\n";
        echo "Inspector - Email: inspector@inspection.ng, Password: password\n";
    }
}
```

### Step 6: Run Seeder

```bash
php artisan db:seed
```

### Step 7: Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## Default Login Credentials

**Super Admin:**
- Email: `admin@inspection.ng`
- Password: `password`

**Inspector:**
- Email: `inspector@inspection.ng`
- Password: `password`

**⚠️ IMPORTANT: Change these passwords immediately after first login!**

---

## Quick Tour

### 1. Dashboard
- View real-time statistics
- See recent inspections
- Check vehicles due for inspection
- Access quick actions

### 2. Create New Inspection
1. Click "New Inspection" button
2. Enter vehicle plate number
3. Select inspection type
4. System auto-populates vehicle details
5. Proceed with tests

### 3. Conduct Tests
- **Brake Test**: Enter axle loads and brake forces
- **Emission Test**: Record HC, CO, and other emissions
- **Headlamp Test**: Measure light intensity and alignment
- **Visual Inspection**: Record any defects found

### 4. Finalize Inspection
1. Review all test results
2. Click "Finalize Inspection"
3. System automatically determines pass/fail
4. Generate certificate (if passed)

### 5. Print Report
1. Go to inspection details
2. Click "Print Report" or "Download PDF"
3. Report includes barcode and QR code
4. Print or email to vehicle owner

---

## Common Tasks

### Register a New Vehicle
```
1. Navigate to Vehicles → Add New
2. Fill in vehicle details
3. Enter owner information
4. Save vehicle
```

### Search for a Vehicle
```
1. Use search bar in top navigation
2. Enter plate number, owner name, or engine number
3. Select from results
```

### Generate Daily Report
```
1. Go to Reports → Daily Report
2. Select date
3. Choose department (if applicable)
4. Click "Generate Report"
5. Download PDF or Excel
```

### View Inspection History
```
1. Go to Vehicles
2. Click on vehicle
3. View "Inspection History" tab
4. See all past inspections
```

---

## Keyboard Shortcuts

- `Ctrl + K`: Global search
- `Ctrl + N`: New inspection
- `Ctrl + P`: Print current page
- `Esc`: Close modals/dropdowns

---

## Troubleshooting

### Cannot Login
- Check database connection in `.env`
- Verify user exists in `sys_user` table
- Ensure password is correct
- Check if user status is active (status = 1)

### Database Connection Error
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Missing Dependencies
```bash
# Reinstall dependencies
composer install
npm install
```

### Permission Errors
```bash
# Fix storage permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache

# Windows: Run as Administrator
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Next Steps

1. **Customize Settings**
   - Update department information
   - Configure email settings
   - Set inspection parameters

2. **Add Users**
   - Create inspector accounts
   - Assign roles and permissions
   - Set up departments

3. **Import Vehicles**
   - Prepare CSV file with vehicle data
   - Use bulk import feature
   - Verify imported data

4. **Configure Reports**
   - Customize report templates
   - Set up automated reports
   - Configure email distribution

5. **Train Staff**
   - Conduct user training
   - Provide documentation
   - Set up support channels

---

## Support

### Documentation
- Full documentation: `FEATURES.md`
- Installation guide: `INSTALLATION.md`
- Database structure: `DATABASE_ANALYSIS.md`

### Getting Help
- Check documentation first
- Review error logs in `storage/logs/laravel.log`
- Contact system administrator

### Reporting Issues
When reporting issues, include:
- Error message
- Steps to reproduce
- Browser/system information
- Screenshots (if applicable)

---

## Security Best Practices

1. **Change Default Passwords**
   ```
   Go to Profile → Change Password
   Use strong passwords (min 8 characters, mixed case, numbers, symbols)
   ```

2. **Regular Backups**
   ```bash
   # Backup database daily
   mysqldump -u root -p timo > backup_$(date +%Y%m%d).sql
   ```

3. **Update Regularly**
   ```bash
   # Keep Laravel and packages updated
   composer update
   php artisan optimize
   ```

4. **Monitor Logs**
   ```
   Check storage/logs/laravel.log regularly
   Review sys_action_log table for suspicious activity
   ```

5. **Secure Environment**
   ```
   Set APP_DEBUG=false in production
   Use HTTPS
   Configure firewall
   Restrict database access
   ```

---

## Performance Tips

1. **Enable Caching**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Optimize Database**
   ```sql
   OPTIMIZE TABLE i_data_base;
   ANALYZE TABLE i_data_base;
   ```

3. **Use Queue for Heavy Tasks**
   ```bash
   php artisan queue:work
   ```

4. **Monitor Performance**
   - Check slow query log
   - Monitor server resources
   - Use Laravel Telescope (development)

---

## Frequently Asked Questions

**Q: How do I reset a user's password?**
A: Go to Users → Select User → Reset Password

**Q: Can I customize the inspection report?**
A: Yes, edit `resources/views/reports/inspection-pdf.blade.php`

**Q: How long are inspection certificates valid?**
A: Default is 365 days, configurable in `.env`

**Q: Can I add custom vehicle types?**
A: Yes, add entries to `sys_dict` table with type for vehicle types

**Q: How do I backup the system?**
A: Backup database and `storage` folder regularly

**Q: Is there a mobile app?**
A: The web interface is mobile-responsive. Native app planned for future.

---

## Conclusion

You're now ready to use the Vehicle Inspection Management System! Start by logging in with the default credentials and exploring the dashboard.

For detailed information about specific features, refer to `FEATURES.md`.

For installation and deployment, see `INSTALLATION.md`.

**Happy Inspecting! 🚗✅**
