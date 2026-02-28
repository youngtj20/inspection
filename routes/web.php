<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ReportController;
// use App\Http\Controllers\DepartmentController;  // not yet built
// use App\Http\Controllers\UserController;         // not yet built
// use App\Http\Controllers\EquipmentController;    // not yet built
// use App\Http\Controllers\PersonnelController;    // not yet built

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (no prefix — login is at /login or /admin/login)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Also accept /admin/login for users who type it directly
Route::get('/admin/login', [LoginController::class, 'showLoginForm']);
Route::post('/admin/login', [LoginController::class, 'login']);

// Protected Routes — all under /admin prefix
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/charts', [DashboardController::class, 'getChartData'])->name('dashboard.charts');

    // Vehicles
    Route::resource('vehicles', VehicleController::class);
    Route::get('vehicles/{vehicle}/history', [VehicleController::class, 'history'])->name('vehicles.history');
    Route::post('vehicles/import', [VehicleController::class, 'import'])->name('vehicles.import');
    Route::get('vehicles/export', [VehicleController::class, 'export'])->name('vehicles.export');

    // Inspections
    Route::resource('inspections', InspectionController::class);
    Route::get('inspections/{inspection}/details', [InspectionController::class, 'details'])->name('inspections.details');
    Route::post('inspections/{inspection}/register', [InspectionController::class, 'register'])->name('inspections.register');
    Route::post('inspections/{inspection}/brake-test', [InspectionController::class, 'saveBrakeTest'])->name('inspections.brake-test');
    Route::post('inspections/{inspection}/emission-test', [InspectionController::class, 'saveEmissionTest'])->name('inspections.emission-test');
    Route::post('inspections/{inspection}/headlamp-test', [InspectionController::class, 'saveHeadlampTest'])->name('inspections.headlamp-test');
    Route::post('inspections/{inspection}/suspension-test', [InspectionController::class, 'saveSuspensionTest'])->name('inspections.suspension-test');
    Route::post('inspections/{inspection}/visual-inspection', [InspectionController::class, 'saveVisualInspection'])->name('inspections.visual');
    Route::post('inspections/{inspection}/finalize', [InspectionController::class, 'finalize'])->name('inspections.finalize');
    Route::get('inspections/{inspection}/certificate', [InspectionController::class, 'certificate'])->name('inspections.certificate');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/inspection/{inspection}', [ReportController::class, 'inspectionReport'])->name('reports.inspection');
    Route::get('reports/inspection/{inspection}/pdf', [ReportController::class, 'inspectionPDF'])->name('reports.inspection.pdf');
    Route::get('reports/daily', [ReportController::class, 'dailyReport'])->name('reports.daily');
    Route::get('reports/monthly', [ReportController::class, 'monthlyReport'])->name('reports.monthly');
    Route::get('reports/department', [ReportController::class, 'departmentReport'])->name('reports.department');
    Route::get('reports/vehicle-history/{vehicle}', [ReportController::class, 'vehicleHistory'])->name('reports.vehicle-history');
    Route::post('reports/custom', [ReportController::class, 'customReport'])->name('reports.custom');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Departments, Users, Equipment, Personnel — controllers not yet built; stub routes keep layout from crashing
    Route::get('departments', fn() => redirect()->route('dashboard'))->name('departments.index');
    Route::get('equipment', fn() => redirect()->route('dashboard'))->name('equipment.index');
    Route::get('personnel', fn() => redirect()->route('dashboard'))->name('personnel.index');
    Route::get('users', fn() => redirect()->route('dashboard'))->name('users.index');

    // Search & Filter
    Route::get('search', [DashboardController::class, 'search'])->name('search');
    Route::get('filter/inspections', [InspectionController::class, 'filter'])->name('filter.inspections');
    Route::get('filter/vehicles', [VehicleController::class, 'filter'])->name('filter.vehicles');

    // Activity Log
    Route::get('activity-log', [DashboardController::class, 'activityLog'])->name('activity-log');

    // Profile — stub until UserController is built
    Route::get('profile', fn() => redirect()->route('dashboard'))->name('profile');
});
