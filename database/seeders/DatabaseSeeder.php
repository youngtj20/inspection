<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        echo "Seeding database...\n\n";

        // Create default department
        echo "[1/5] Creating department...\n";
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
        echo "   Department created (ID: {$deptId})\n\n";

        // Create roles
        echo "[2/5] Creating roles...\n";
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

        $viewerRoleId = DB::table('sys_role')->insertGetId([
            'title' => 'Viewer',
            'name' => 'viewer',
            'status' => 1,
            'create_date' => now(),
        ]);
        echo "   4 roles created\n\n";

        // Create users
        echo "[3/5] Creating users...\n";
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

        $viewerId = DB::table('sys_user')->insertGetId([
            'username' => 'viewer',
            'nickname' => 'Viewer One',
            'password' => Hash::make('password'),
            'email' => 'viewer@inspection.ng',
            'phone' => '+234-XXX-XXXX',
            'dept_id' => $deptId,
            'sex' => 1,
            'status' => 1,
            'create_date' => now(),
        ]);
        echo "   3 users created\n\n";

        // Assign roles
        echo "[4/5] Assigning roles to users...\n";
        DB::table('sys_user_role')->insert([
            ['user_id' => $superAdminId, 'role_id' => $superAdminRoleId],
            ['user_id' => $inspectorId, 'role_id' => $inspectorRoleId],
            ['user_id' => $viewerId, 'role_id' => $viewerRoleId],
        ]);
        echo "   Roles assigned\n\n";

        // Create sample menus
        echo "[5/5] Creating menu structure...\n";
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

        $vehiclesMenuId = DB::table('sys_menu')->insertGetId([
            'title' => 'Vehicles',
            'pid' => 0,
            'pids' => '[0]',
            'url' => '/vehicles',
            'perms' => 'vehicles.view',
            'icon' => 'fa-car',
            'type' => 1,
            'sort' => 3,
            'status' => 1,
            'create_date' => now(),
        ]);

        $reportsMenuId = DB::table('sys_menu')->insertGetId([
            'title' => 'Reports',
            'pid' => 0,
            'pids' => '[0]',
            'url' => '/reports',
            'perms' => 'reports.view',
            'icon' => 'fa-chart-bar',
            'type' => 1,
            'sort' => 4,
            'status' => 1,
            'create_date' => now(),
        ]);

        // Assign menus to roles
        DB::table('sys_role_menu')->insert([
            // Super Admin - all menus
            ['role_id' => $superAdminRoleId, 'menu_id' => $dashboardMenuId],
            ['role_id' => $superAdminRoleId, 'menu_id' => $inspectionsMenuId],
            ['role_id' => $superAdminRoleId, 'menu_id' => $vehiclesMenuId],
            ['role_id' => $superAdminRoleId, 'menu_id' => $reportsMenuId],
            // Inspector - dashboard, inspections, vehicles
            ['role_id' => $inspectorRoleId, 'menu_id' => $dashboardMenuId],
            ['role_id' => $inspectorRoleId, 'menu_id' => $inspectionsMenuId],
            ['role_id' => $inspectorRoleId, 'menu_id' => $vehiclesMenuId],
            // Viewer - dashboard and reports only
            ['role_id' => $viewerRoleId, 'menu_id' => $dashboardMenuId],
            ['role_id' => $viewerRoleId, 'menu_id' => $reportsMenuId],
        ]);
        echo "   Menu structure created\n\n";

        echo "========================================================\n";
        echo "DATABASE SEEDED SUCCESSFULLY!\n";
        echo "========================================================\n\n";
        echo "Login Credentials:\n\n";
        echo "Super Admin:\n";
        echo "  Email: admin@inspection.ng\n";
        echo "  Password: password\n\n";
        echo "Inspector:\n";
        echo "  Email: inspector@inspection.ng\n";
        echo "  Password: password\n\n";
        echo "Viewer:\n";
        echo "  Email: viewer@inspection.ng\n";
        echo "  Password: password\n\n";
        echo "⚠️  IMPORTANT: Change these passwords after first login!\n\n";
        echo "========================================================\n";
    }
}
