<?php

// Performance optimization script for Vehicle Inspection System
// Run this script to add performance indexes to your database

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Starting Performance Optimization...\n\n";

// Check if we can connect to database
try {
    DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Performance indexes to add
$indexes = [
    // i_data_base table indexes
    'CREATE INDEX IF NOT EXISTS idx_data_base_month_dept ON i_data_base (dept_id, inspectdate)',
    'CREATE INDEX IF NOT EXISTS idx_data_base_plate_type ON i_data_base (plateno, vehicletype)',
    'CREATE INDEX IF NOT EXISTS idx_data_base_series_times ON i_data_base (seriesno, inspecttimes)',
    'CREATE INDEX IF NOT EXISTS idx_data_base_testresult ON i_data_base (testresult)',
    'CREATE INDEX IF NOT EXISTS idx_data_base_createdate ON i_data_base (createDate)',
    
    // i_vehicle_register table indexes
    'CREATE INDEX IF NOT EXISTS idx_vehicle_register_plate_type ON i_vehicle_register (plateno, vehicletype)',
    'CREATE INDEX IF NOT EXISTS idx_vehicle_register_series_times ON i_vehicle_register (seriesno, inspecttimes)',
    
    // sys_dept table indexes
    'CREATE INDEX IF NOT EXISTS idx_sys_dept_status ON sys_dept (status)',
    
    // Brake tables indexes
    'CREATE INDEX IF NOT EXISTS idx_brake_front_series ON i_data_brake_front (seriesno, inspecttimes)',
    'CREATE INDEX IF NOT EXISTS idx_brake_rear_series ON i_data_brake_rear (seriesno, inspecttimes)',
    'CREATE INDEX IF NOT EXISTS idx_brake_summary_series ON i_data_brake_summary (seriesno, inspecttimes)',
    
    // Gas table index
    'CREATE INDEX IF NOT EXISTS idx_gas_series ON i_data_gas (seriesno, inspecttimes)',
    
    // Headlamp tables indexes
    'CREATE INDEX IF NOT EXISTS idx_headlamp_left_series ON i_data_headlamp_left (seriesno, inspecttimes)',
    'CREATE INDEX IF NOT EXISTS idx_headlamp_right_series ON i_data_headlamp_right (seriesno, inspecttimes)',
    
    // Visual and Pit inspection indexes
    'CREATE INDEX IF NOT EXISTS idx_visual_series ON i_data_visual (seriesno)',
    'CREATE INDEX IF NOT EXISTS idx_pit_series ON i_data_pit (seriesno)',
    
    // User and role indexes
    'CREATE INDEX IF NOT EXISTS idx_sys_user_status ON sys_user (status)',
    'CREATE INDEX IF NOT EXISTS idx_sys_user_dept ON sys_user (dept_id)',
];

$successCount = 0;
$errorCount = 0;

foreach ($indexes as $indexSql) {
    try {
        DB::statement($indexSql);
        echo "✅ Added index: " . substr($indexSql, 0, 50) . "...\n";
        $successCount++;
    } catch (Exception $e) {
        echo "⚠️  Index may already exist or error: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n📊 Performance Optimization Summary:\n";
echo "✅ Successfully added: {$successCount} indexes\n";
echo "⚠️  Skipped/Errors: {$errorCount} indexes\n";

// Clear cache
echo "\n🧹 Clearing application cache...\n";
Artisan::call('cache:clear');
Artisan::call('config:clear');
Artisan::call('route:clear');
Artisan::call('view:clear');

echo "✅ Cache cleared successfully\n";

// Optimize database
echo "\n⚡ Optimizing database tables...\n";
try {
    DB::statement('OPTIMIZE TABLE i_data_base');
    DB::statement('OPTIMIZE TABLE i_vehicle_register');
    DB::statement('OPTIMIZE TABLE sys_user');
    DB::statement('OPTIMIZE TABLE sys_dept');
    echo "✅ Database optimization completed\n";
} catch (Exception $e) {
    echo "⚠️  Database optimization warning: " . $e->getMessage() . "\n";
}

echo "\n🎉 Performance optimization completed successfully!\n";
echo "Your application should now load faster and respond more quickly.\n";