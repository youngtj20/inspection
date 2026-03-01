<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use App\Traits\LoadsDepartments;

class ReportController extends Controller
{
    use LoadsDepartments;
    public function index()
    {
        $today     = Carbon::today()->format('Y-m-d');
        $thisMonth = Carbon::now()->startOfMonth()->format('Y-m-d');

        // Today's quick stats
        $todayRows = DB::table('i_data_base')
            ->whereBetween('inspectdate', [$today . ' 00:00:00', $today . ' 23:59:59'])
            ->get(['testresult']);

        $todayStats = [
            'total'  => $todayRows->count(),
            'passed' => $todayRows->whereIn('testresult', ['1', 'Y'])->count(),
            'failed' => $todayRows->whereIn('testresult', ['0', 'N'])->count(),
        ];

        // This month's quick stats
        $monthRows = DB::table('i_data_base')
            ->whereBetween('inspectdate', [$thisMonth . ' 00:00:00', $today . ' 23:59:59'])
            ->get(['testresult']);

        $monthStats = [
            'total'  => $monthRows->count(),
            'passed' => $monthRows->whereIn('testresult', ['1', 'Y'])->count(),
            'failed' => $monthRows->whereIn('testresult', ['0', 'N'])->count(),
        ];
        $monthComp = $monthStats['passed'] + $monthStats['failed'];
        $monthStats['pass_rate'] = $monthComp > 0 ? round($monthStats['passed'] / $monthComp * 100, 1) : 0;

        // Department count
        $deptCount = DB::table('sys_dept')->where('status', 1)->count();

        return view('reports.index', compact('todayStats', 'monthStats', 'deptCount', 'today'));
    }
    
    public function inspectionReport($id)
    {
        $inspection = $this->getInspectionDetails($id);
        
        if (!$inspection) {
            abort(404, 'Inspection not found');
        }
        
        return view('reports.inspection', compact('inspection'));
    }
    
    public function inspectionPDF($id)
    {
        $inspection = $this->getInspectionDetails($id);
        
        if (!$inspection) {
            abort(404, 'Inspection not found');
        }
        
        // Generate QR Code
        $qrCode = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate(route('inspections.show', $id)));
        
        // Generate Barcode
        $barcode = $this->generateBarcode($inspection->base->seriesno);
        
        $pdf = PDF::loadView('reports.inspection-pdf', compact('inspection', 'qrCode', 'barcode'));
        
        return $pdf->download('inspection-report-' . $inspection->base->seriesno . '.pdf');
    }
    
    public function dailyReport(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $deptId = $request->get('department');
        
        $query = DB::table('i_data_base')
            ->select(
                'i_data_base.*',
                'i_vehicle_register.makeofvehicle',
                'i_vehicle_register.model',
                'i_vehicle_register.owner',
                'sys_dept.title as department_name'
            )
            ->leftJoin('i_vehicle_register', function($join) {
                $join->on('i_data_base.plateno',      '=', 'i_vehicle_register.plateno')
                     ->on('i_data_base.vehicletype',  '=', 'i_vehicle_register.vehicletype')
                     ->on('i_data_base.seriesno',     '=', 'i_vehicle_register.seriesno')
                     ->on('i_data_base.inspecttimes', '=', 'i_vehicle_register.inspecttimes');
            })
            ->leftJoin('sys_dept', 'i_data_base.dept_id', '=', 'sys_dept.id')
            ->whereBetween('i_data_base.inspectdate', [$date . ' 00:00:00', $date . ' 23:59:59']);
        
        if ($deptId) {
            $resolvedIds = $this->resolveDeptFilter($deptId);
            if (!empty($resolvedIds)) {
                $query->whereIn('i_data_base.dept_id', $resolvedIds);
            }
        }

        $inspections = $query->get();
        
        $passed  = $inspections->whereIn('testresult', ['1', 'Y'])->count();
        $failed  = $inspections->whereIn('testresult', ['0', 'N'])->count();
        $total   = $inspections->count();
        $comp    = $passed + $failed;
        $stats = [
            'total'     => $total,
            'passed'    => $passed,
            'failed'    => $failed,
            'pending'   => $total - $passed - $failed,
            'pass_rate' => $comp > 0 ? round($passed / $comp * 100, 1) : 0,
        ];

        // Per-department breakdown for the day
        $deptBreakdown = $inspections->groupBy('dept_id')->map(function ($rows) {
            $p = $rows->whereIn('testresult', ['1', 'Y'])->count();
            $f = $rows->whereIn('testresult', ['0', 'N'])->count();
            $t = $rows->count();
            $c = $p + $f;
            return [
                'name'      => $rows->first()->department_name ?? 'Unassigned',
                'total'     => $t,
                'passed'    => $p,
                'failed'    => $f,
                'pending'   => $t - $p - $f,
                'pass_rate' => $c > 0 ? round($p / $c * 100, 1) : 0,
            ];
        })->sortByDesc('total')->values();

        $departments = $this->getGroupedDepartments();

        if ($request->get('format') === 'pdf') {
            $pdf = PDF::loadView('reports.daily-pdf', compact('inspections', 'stats', 'date', 'deptBreakdown'));
            return $pdf->download('daily-report-' . $date . '.pdf');
        }

        return view('reports.daily', compact('inspections', 'stats', 'date', 'departments', 'deptBreakdown'));
    }
    
    public function monthlyReport(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $deptId = $request->get('department');
        
        // Parse month to get year and month components
        $yearMonth = Carbon::parse($month . '-01');
        $year = $yearMonth->year;
        $monthNum = str_pad($yearMonth->month, 2, '0', STR_PAD_LEFT);
        
        // Use date range on inspectdate — uses idx_inspectdate index
        $monthStart = $year . '-' . $monthNum . '-01';
        $monthEnd   = $yearMonth->endOfMonth()->format('Y-m-d');

        $query = DB::table('i_data_base')
            ->whereBetween('inspectdate', [$monthStart, $monthEnd . ' 23:59:59']);

        if ($deptId) {
            $resolvedIds = $this->resolveDeptFilter($deptId);
            if (!empty($resolvedIds)) {
                $query->whereIn('dept_id', $resolvedIds);
            }
        }

        $inspections = $query->get();
        
        // Overall statistics from i_data_base table - count by test result
        // testresult can be: 'Y' or '1' = Passed, 'N' or '0' = Failed, NULL or '' = Pending
        $stats = [
            'total' => $inspections->count(),
            'passed' => $inspections->whereIn('testresult', ['1', 'Y'])->count(),
            'failed' => $inspections->whereIn('testresult', ['0', 'N'])->count(),
            'pending' => 0,
            'pass_rate' => 0,
        ];
        
        $stats['pending'] = $stats['total'] - $stats['passed'] - $stats['failed'];
        
        // Calculate pass rate
        $completed = $stats['passed'] + $stats['failed'];
        if ($completed > 0) {
            $stats['pass_rate'] = round(($stats['passed'] / $completed) * 100, 2);
        }
        
        // Group by date - count records per day
        $dailyStats = $inspections->groupBy(function($item) {
            if (empty($item->inspectdate)) {
                return 'Unknown';
            }
            try {
                return Carbon::parse($item->inspectdate)->format('Y-m-d');
            } catch (\Exception $e) {
                return 'Unknown';
            }
        })->map(function($group) {
            return [
                'total'  => $group->count(),
                'passed' => $group->whereIn('testresult', ['1', 'Y'])->count(),
                'failed' => $group->whereIn('testresult', ['0', 'N'])->count(),
            ];
        })->sortKeys();

        // Group by vehicle type - count records per vehicle type
        $vehicleTypeStats = $inspections->groupBy('vehicletype')->map(function($group) {
            return [
                'total'  => $group->count(),
                'passed' => $group->whereIn('testresult', ['1', 'Y'])->count(),
                'failed' => $group->whereIn('testresult', ['0', 'N'])->count(),
            ];
        })->sortKeys();
        
        // Group by test result - count records by test result
        $testResultStats = [
            'passed' => $inspections->whereIn('testresult', ['1', 'Y'])->count(),
            'failed' => $inspections->whereIn('testresult', ['0', 'N'])->count(),
            'pending' => 0,
        ];
        $testResultStats['pending'] = $inspections->count() - $testResultStats['passed'] - $testResultStats['failed'];
        
        // Group by department - count records per department using direct SQL
        $departmentStatsQuery = DB::table('i_data_base')
            ->select(
                'sys_dept.id',
                'sys_dept.title as department_name',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN i_data_base.testresult IN ("1", "Y") THEN 1 ELSE 0 END) as passed'),
                DB::raw('SUM(CASE WHEN i_data_base.testresult IN ("0", "N") THEN 1 ELSE 0 END) as failed'),
                DB::raw('SUM(CASE WHEN i_data_base.testresult NOT IN ("0", "N", "1", "Y") OR i_data_base.testresult IS NULL THEN 1 ELSE 0 END) as pending')
            )
            ->leftJoin('sys_dept', 'i_data_base.dept_id', '=', 'sys_dept.id')
            ->whereBetween('i_data_base.inspectdate', [$monthStart, $monthEnd . ' 23:59:59']);

        // Apply department filter with state→centers expansion
        if ($deptId) {
            $resolvedForStats = $this->resolveDeptFilter($deptId);
            if (!empty($resolvedForStats)) {
                $departmentStatsQuery->whereIn('i_data_base.dept_id', $resolvedForStats);
            }
        }

        $departmentStats = $departmentStatsQuery
            ->groupBy('sys_dept.id', 'sys_dept.title')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->get();
        
        // Inspector performance - count records per inspector
        $inspectorStats = $inspections->filter(function($item) {
            return !empty($item->inspector);
        })->groupBy('inspector')->map(function($group) {
            return [
                'total'  => $group->count(),
                'passed' => $group->whereIn('testresult', ['1', 'Y'])->count(),
                'failed' => $group->whereIn('testresult', ['0', 'N'])->count(),
            ];
        });

        // Group by inspection type - count records per inspection type
        $inspectionTypeStats = $inspections->groupBy('inspecttype')->map(function($group) {
            return [
                'total'  => $group->count(),
                'passed' => $group->whereIn('testresult', ['1', 'Y'])->count(),
                'failed' => $group->whereIn('testresult', ['0', 'N'])->count(),
            ];
        });
        
        $departments = $this->getGroupedDepartments();

        // Handle export formats
        if ($request->get('format') === 'pdf') {
            $pdf = PDF::loadView('reports.monthly-pdf', compact(
                'stats', 'dailyStats', 'vehicleTypeStats', 'inspectorStats', 
                'departmentStats', 'testResultStats', 'inspectionTypeStats', 'month'
            ));
            return $pdf->download('monthly-report-' . $month . '.pdf');
        }
        
        if ($request->get('format') === 'excel') {
            return $this->exportMonthlyToExcel($inspections, $month, $deptId);
        }
        
        if ($request->get('format') === 'details-pdf') {
            return $this->exportMonthlyDetailsPDF($inspections, $stats, $month, $deptId);
        }
        
        return view('reports.monthly', compact(
            'stats', 'dailyStats', 'vehicleTypeStats', 'inspectorStats', 
            'departmentStats', 'testResultStats', 'inspectionTypeStats', 'month', 'departments'
        ));
    }
    
    // Export monthly details to Excel
    private function exportMonthlyToExcel($inspections, $month, $deptId = null)
    {
        // Get unique plate numbers and vehicle types from inspections
        $plateNos = $inspections->pluck('plateno')->map(function($plate) {
            return trim($plate);
        })->unique()->values()->toArray();
        
        $vehicleTypes = $inspections->pluck('vehicletype')->map(function($type) {
            return trim($type);
        })->unique()->values()->toArray();
        
        // Fetch only the vehicle details we need (filtered by plate numbers and types)
        $vehicles = DB::table('i_vehicle_register')
            ->select('plateno', 'vehicletype', 'makeofvehicle', 'model', 'owner', 'address', 'phoneno', 'engineno', 'chassisno')
            ->whereIn('plateno', $plateNos)
            ->whereIn('vehicletype', $vehicleTypes)
            ->get()
            ->keyBy(function($item) {
                return trim($item->plateno) . '_' . trim($item->vehicletype);
            });
        
        // Fetch department names
        $deptIds = $inspections->pluck('dept_id')->unique()->filter()->toArray();
        $departments = DB::table('sys_dept')
            ->whereIn('id', $deptIds)
            ->get()
            ->keyBy('id');
        
        $csv = fopen('php://temp', 'r+');
        
        // Headers
        $headers = [
            'ID',
            'Plate No',
            'Vehicle Type',
            'License Type',
            'Series No',
            'Make of Vehicle',
            'Model',
            'Owner Name',
            'Owner Address',
            'Owner Phone',
            'Engine No',
            'Chassis No',
            'Inspect Date',
            'Inspect Times',
            'Inspect Type',
            'Test Result',
            'Inspector',
            'Appearance Inspector',
            'Pit Inspector',
            'Department',
            'Start Time',
            'End Time',
            'Conclusion'
        ];
        fputcsv($csv, $headers);
        
        // Data rows
        foreach ($inspections as $inspection) {
            $testResult = '';
            if (in_array($inspection->testresult, ['1', 'Y'])) {
                $testResult = 'Passed';
            } elseif (in_array($inspection->testresult, ['0', 'N'])) {
                $testResult = 'Failed';
            } else {
                $testResult = 'Pending';
            }
            
            // Get vehicle details - use trimmed keys for matching
            $vehicleKey = trim($inspection->plateno) . '_' . trim($inspection->vehicletype);
            $vehicle = $vehicles->get($vehicleKey);
            
            // Get department name
            $deptName = '';
            if ($inspection->dept_id && isset($departments[$inspection->dept_id])) {
                $deptName = $departments[$inspection->dept_id]->title;
            }
            
            $row = [
                $inspection->id,
                trim($inspection->plateno),
                trim($inspection->vehicletype),
                $inspection->licencetype ?? 'N/A',
                $inspection->seriesno ?? 'N/A',
                $vehicle ? ($vehicle->makeofvehicle ?? 'N/A') : 'N/A',
                $vehicle ? ($vehicle->model ?? 'N/A') : 'N/A',
                $vehicle ? ($vehicle->owner ?? $inspection->owner ?? 'N/A') : ($inspection->owner ?? 'N/A'),
                $vehicle ? ($vehicle->address ?? 'N/A') : 'N/A',
                $vehicle ? ($vehicle->phoneno ?? 'N/A') : 'N/A',
                $vehicle ? ($vehicle->engineno ?? 'N/A') : 'N/A',
                $vehicle ? ($vehicle->chassisno ?? 'N/A') : 'N/A',
                $inspection->inspectdate ?? 'N/A',
                $inspection->inspecttimes ?? 'N/A',
                $inspection->inspecttype ?? 'N/A',
                $testResult,
                $inspection->inspector ?? 'N/A',
                $inspection->appearanceinspector ?? 'N/A',
                $inspection->pitinspector ?? 'N/A',
                $deptName ?: 'N/A',
                $inspection->starttime ?? 'N/A',
                $inspection->endTime ?? 'N/A',
                $inspection->conclusion ?? 'N/A'
            ];
            fputcsv($csv, $row);
        }
        
        rewind($csv);
        $output = stream_get_contents($csv);
        fclose($csv);
        
        $filename = 'monthly-details-' . $month;
        if ($deptId) {
            $dept = DB::table('sys_dept')->find($deptId);
            if ($dept) {
                $filename .= '-' . str_replace(' ', '-', $dept->title);
            }
        }
        $filename .= '.csv';
        
        return response($output)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    // Export monthly details to PDF
    private function exportMonthlyDetailsPDF($inspections, $stats, $month, $deptId = null)
    {
        $departmentName = 'All Departments';
        if ($deptId) {
            $dept = DB::table('sys_dept')->find($deptId);
            if ($dept) {
                $departmentName = $dept->title;
            }
        }
        
        // Get unique plate numbers and vehicle types from inspections
        $plateNos = $inspections->pluck('plateno')->map(function($plate) {
            return trim($plate);
        })->unique()->values()->toArray();
        
        $vehicleTypes = $inspections->pluck('vehicletype')->map(function($type) {
            return trim($type);
        })->unique()->values()->toArray();
        
        // Fetch only the vehicle details we need (filtered by plate numbers and types)
        $vehicles = DB::table('i_vehicle_register')
            ->select('plateno', 'vehicletype', 'makeofvehicle', 'model', 'owner', 'address', 'phoneno', 'engineno', 'chassisno')
            ->whereIn('plateno', $plateNos)
            ->whereIn('vehicletype', $vehicleTypes)
            ->get()
            ->keyBy(function($item) {
                return trim($item->plateno) . '_' . trim($item->vehicletype);
            });
        
        // Fetch department names
        $deptIds = $inspections->pluck('dept_id')->unique()->filter()->toArray();
        $departments = DB::table('sys_dept')
            ->whereIn('id', $deptIds)
            ->get()
            ->keyBy('id');
        
        // Get detailed records with complete vehicle information
        $detailedRecords = collect($inspections)->map(function($inspection) use ($vehicles, $departments) {
            $testResult = '';
            if (in_array($inspection->testresult, ['1', 'Y'])) {
                $testResult = 'Passed';
            } elseif (in_array($inspection->testresult, ['0', 'N'])) {
                $testResult = 'Failed';
            } else {
                $testResult = 'Pending';
            }
            
            // Get vehicle details - try with trimmed keys
            $vehicleKey = trim($inspection->plateno) . '_' . trim($inspection->vehicletype);
            $vehicle = $vehicles->get($vehicleKey);
            
            // Get department name
            $deptName = '';
            if ($inspection->dept_id && isset($departments[$inspection->dept_id])) {
                $deptName = $departments[$inspection->dept_id]->title;
            }
            
            return (object)[
                'id' => $inspection->id,
                'plateno' => $inspection->plateno,
                'vehicletype' => $inspection->vehicletype,
                'licencetype' => $inspection->licencetype ?? '',
                'seriesno' => $inspection->seriesno,
                'makeofvehicle' => $vehicle ? ($vehicle->makeofvehicle ?? '') : '',
                'model' => $vehicle ? ($vehicle->model ?? '') : '',
                'owner' => $vehicle ? ($vehicle->owner ?? $inspection->owner ?? '') : ($inspection->owner ?? ''),
                'address' => $vehicle ? ($vehicle->address ?? '') : '',
                'phone' => $vehicle ? ($vehicle->phoneno ?? '') : '',
                'engineno' => $vehicle ? ($vehicle->engineno ?? '') : '',
                'chassisno' => $vehicle ? ($vehicle->chassisno ?? '') : '',
                'inspectdate' => $inspection->inspectdate,
                'inspecttimes' => $inspection->inspecttimes,
                'inspecttype' => $inspection->inspecttype,
                'testresult' => $testResult,
                'inspector' => $inspection->inspector ?? '',
                'appearanceinspector' => $inspection->appearanceinspector ?? '',
                'pitinspector' => $inspection->pitinspector ?? '',
                'department' => $deptName,
                'conclusion' => $inspection->conclusion ?? '',
            ];
        });
        
        $pdf = PDF::loadView('reports.monthly-details-pdf', compact(
            'detailedRecords', 'stats', 'month', 'departmentName'
        ));
        
        $filename = 'monthly-details-' . $month;
        if ($deptId) {
            $filename .= '-' . str_replace(' ', '-', $departmentName);
        }
        $filename .= '.pdf';
        
        return $pdf->download($filename);
    }
    
    public function departmentReport(Request $request)
    {
        $deptId   = $request->get('department');
        $dateFrom = $request->get('date_from', Carbon::now()->subMonth()->format('Y-m-d'));
        $dateTo   = $request->get('date_to',   Carbon::now()->format('Y-m-d'));

        $departments = $this->getGroupedDepartments();

        if (empty($deptId)) {
            return view('reports.department', compact('departments', 'dateFrom', 'dateTo'));
        }

        // Expand state → child centers, or use the single center directly
        $deptIds = $this->resolveDeptFilter($deptId);

        if (empty($deptIds)) {
            return view('reports.department', compact('departments', 'dateFrom', 'dateTo'));
        }

        $dateRange = [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'];

        // Flat id→title lookup for the resolved center IDs
        $flatDepts = DB::table('sys_dept')
            ->select('id', 'title')
            ->whereIn('id', $deptIds)
            ->where('status', 1)
            ->get()
            ->keyBy('id');

        // All inspections across resolved departments in one query
        $allInspections = DB::table('i_data_base')
            ->whereIn('dept_id', $deptIds)
            ->whereBetween('inspectdate', $dateRange)
            ->get();

        // Aggregate totals
        $totalPassed    = $allInspections->whereIn('testresult', ['1', 'Y'])->count();
        $totalFailed    = $allInspections->whereIn('testresult', ['0', 'N'])->count();
        $totalTotal     = $allInspections->count();
        $totalCompleted = $totalPassed + $totalFailed;

        $stats = [
            'total'     => $totalTotal,
            'passed'    => $totalPassed,
            'failed'    => $totalFailed,
            'pending'   => $totalTotal - $totalPassed - $totalFailed,
            'pass_rate' => $totalCompleted > 0 ? round(($totalPassed / $totalCompleted) * 100, 2) : 0,
        ];

        // Per-department breakdown (one row per resolved center)
        $deptBreakdown = [];
        foreach ($deptIds as $id) {
            $dept = $flatDepts->get($id);
            if (!$dept) continue;

            $rows   = $allInspections->where('dept_id', $id);
            $passed = $rows->whereIn('testresult', ['1', 'Y'])->count();
            $failed = $rows->whereIn('testresult', ['0', 'N'])->count();
            $total  = $rows->count();
            $comp   = $passed + $failed;

            $deptBreakdown[] = [
                'id'          => $dept->id,
                'title'       => $dept->title,
                'total'       => $total,
                'passed'      => $passed,
                'failed'      => $failed,
                'pending'     => $total - $passed - $failed,
                'pass_rate'   => $comp > 0 ? round(($passed / $comp) * 100, 2) : 0,
                'equipment'   => DB::table('f_equipment_files')->where('dept_id', $dept->id)->count(),
                'personnel'   => DB::table('f_personnel_files')->where('dept_id', $dept->id)->count(),
                'active_users'=> DB::table('sys_user')->where('dept_id', $dept->id)->where('status', 1)->count(),
            ];
        }

        // $department: the selected node (state or center) for the header label
        $selectedNode = DB::table('sys_dept')->select('id', 'title', 'pid')->where('id', $deptId)->first();
        $department   = count($deptBreakdown) === 1 ? $flatDepts->get($deptIds[0]) : $selectedNode;

        if ($request->get('format') === 'pdf') {
            $pdfName = 'department-report-' . str_replace(' ', '-', $department->title ?? 'report') . '.pdf';
            $pdf = PDF::loadView('reports.department-pdf', compact(
                'department', 'deptBreakdown', 'stats', 'dateFrom', 'dateTo'
            ));
            return $pdf->download($pdfName);
        }

        return view('reports.department', compact(
            'departments', 'department', 'deptBreakdown', 'stats',
            'dateFrom', 'dateTo', 'deptId'
        ));
    }
    
    public function vehicleHistory($vehicleId)
    {
        $vehicle = DB::table('i_vehicle_base')->find($vehicleId);
        
        if (!$vehicle) {
            abort(404, 'Vehicle not found');
        }
        
        $inspections = DB::table('i_data_base')
            ->where('plateno', $vehicle->plateno)
            ->where('vehicletype', $vehicle->vehicletype)
            ->orderBy('inspectdate', 'desc')
            ->get();
        
        $stats = [
            'total_inspections' => $inspections->count(),
            'passed' => $inspections->where('testresult', '1')->count(),
            'failed' => $inspections->where('testresult', '0')->count(),
            'last_inspection' => $inspections->first()?->inspectdate,
            'next_due' => $inspections->first() 
                ? Carbon::parse($inspections->first()->inspectdate)->addYear()->format('Y-m-d')
                : null,
        ];
        
        return view('reports.vehicle-history', compact('vehicle', 'inspections', 'stats'));
    }
    
    public function customReport(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'department' => 'nullable|exists:sys_dept,id',
            'vehicle_type' => 'nullable|string',
            'test_result' => 'nullable|in:0,1',
            'inspector' => 'nullable|string',
        ]);
        
        $query = DB::table('i_data_base')
            ->select(
                'i_data_base.*',
                'i_vehicle_base.makeofvehicle',
                'i_vehicle_base.model',
                'i_vehicle_base.owner',
                'sys_dept.title as department_name'
            )
            ->leftJoin('i_vehicle_base', function($join) {
                $join->on('i_data_base.plateno', '=', 'i_vehicle_base.plateno')
                     ->on('i_data_base.vehicletype', '=', 'i_vehicle_base.vehicletype');
            })
            ->leftJoin('sys_dept', 'i_data_base.dept_id', '=', 'sys_dept.id')
            ->whereBetween('i_data_base.inspectdate', [$validated['date_from'], $validated['date_to']]);
        
        if (!empty($validated['department'])) {
            $query->where('i_data_base.dept_id', $validated['department']);
        }
        
        if (!empty($validated['vehicle_type'])) {
            $query->where('i_data_base.vehicletype', $validated['vehicle_type']);
        }
        
        if (isset($validated['test_result'])) {
            $query->where('i_data_base.testresult', $validated['test_result']);
        }
        
        if (!empty($validated['inspector'])) {
            $query->where('i_data_base.inspector', 'LIKE', '%' . $validated['inspector'] . '%');
        }
        
        $inspections = $query->get();
        
        $stats = [
            'total' => $inspections->count(),
            'passed' => $inspections->where('testresult', '1')->count(),
            'failed' => $inspections->where('testresult', '0')->count(),
            'pending' => $inspections->whereNull('testresult')->count(),
        ];
        
        if ($request->get('format') === 'pdf') {
            $pdf = PDF::loadView('reports.custom-pdf', compact('inspections', 'stats', 'validated'));
            return $pdf->download('custom-report-' . date('Y-m-d') . '.pdf');
        }
        
        if ($request->get('format') === 'excel') {
            return $this->exportToExcel($inspections, 'custom-report');
        }
        
        return response()->json([
            'success' => true,
            'data' => $inspections,
            'stats' => $stats,
        ]);
    }
    
    public function export(Request $request)
    {
        $type = $request->get('type', 'inspections');
        $format = $request->get('format', 'excel');
        // Always require a date range — never dump the entire table
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->get('date_to',   Carbon::today()->format('Y-m-d'));

        switch ($type) {
            case 'inspections':
                $data = DB::table('i_data_base')
                    ->select('i_data_base.id', 'i_data_base.plateno', 'i_data_base.vehicletype',
                             'i_data_base.seriesno', 'i_data_base.inspectdate', 'i_data_base.testresult',
                             'i_data_base.inspector', 'i_data_base.createDate',
                             'i_vehicle_register.makeofvehicle', 'i_vehicle_register.model', 'i_vehicle_register.owner')
                    ->leftJoin('i_vehicle_register', function($join) {
                        $join->on('i_data_base.plateno', '=', 'i_vehicle_register.plateno')
                             ->on('i_data_base.vehicletype', '=', 'i_vehicle_register.vehicletype')
                             ->on('i_data_base.seriesno', '=', 'i_vehicle_register.seriesno')
                             ->on('i_data_base.inspecttimes', '=', 'i_vehicle_register.inspecttimes');
                    })
                    ->whereBetween('i_data_base.inspectdate', [$dateFrom, $dateTo])
                    ->orderByDesc('i_data_base.inspectdate')
                    ->limit(5000)
                    ->get();
                break;

            case 'vehicles':
                $data = DB::table('i_vehicle_register')
                    ->select('plateno', 'vehicletype', 'owner', 'makeofvehicle', 'model',
                             'engineno', 'chassisno', 'registerdate', 'createDate')
                    ->orderByDesc('createDate')
                    ->limit(5000)
                    ->get();
                break;

            default:
                return response()->json(['error' => 'Invalid export type'], 400);
        }

        if ($format === 'excel') {
            return $this->exportToExcel($data, $type);
        }

        return response()->json(['error' => 'Invalid format'], 400);
    }
    
    // Helper methods
    private function getInspectionDetails($id)
    {
        $base = DB::table('i_data_base')
            ->select(
                'i_data_base.*',
                'sys_dept.title as department_name',
                'sys_dept.address as department_address'
            )
            ->leftJoin('sys_dept', 'i_data_base.dept_id', '=', 'sys_dept.id')
            ->where('i_data_base.id', $id)
            ->first();
        
        if (!$base) {
            return null;
        }
        
        $vehicle = DB::table('i_vehicle_base')
            ->where('plateno', $base->plateno)
            ->where('vehicletype', $base->vehicletype)
            ->first();
        
        $brake = [
            'front' => DB::table('i_data_brake_front')
                ->where('seriesno', $base->seriesno)
                ->where('inspecttimes', $base->inspecttimes)
                ->first(),
            'rear' => DB::table('i_data_brake_rear')
                ->where('seriesno', $base->seriesno)
                ->where('inspecttimes', $base->inspecttimes)
                ->first(),
            'summary' => DB::table('i_data_brake_summary')
                ->where('seriesno', $base->seriesno)
                ->where('inspecttimes', $base->inspecttimes)
                ->first(),
        ];
        
        $emission = DB::table('i_data_gas')
            ->where('seriesno', $base->seriesno)
            ->where('inspecttimes', $base->inspecttimes)
            ->first();
        
        $headlamp = [
            'left' => DB::table('i_data_headlamp_left')
                ->where('seriesno', $base->seriesno)
                ->where('inspecttimes', $base->inspecttimes)
                ->first(),
            'right' => DB::table('i_data_headlamp_right')
                ->where('seriesno', $base->seriesno)
                ->where('inspecttimes', $base->inspecttimes)
                ->first(),
        ];
        
        $suspension = [
            'front' => DB::table('i_data_suspension_front')
                ->where('seriesno', $base->seriesno)
                ->where('inspecttimes', $base->inspecttimes)
                ->first(),
            'rear' => DB::table('i_data_suspension_rear')
                ->where('seriesno', $base->seriesno)
                ->where('inspecttimes', $base->inspecttimes)
                ->first(),
        ];
        
        $visual = DB::table('i_data_visual')
            ->where('seriesno', $base->seriesno)
            ->where('inspecttimes', $base->inspecttimes)
            ->get();
        
        $pit = DB::table('i_data_pit')
            ->where('seriesno', $base->seriesno)
            ->where('inspecttimes', $base->inspecttimes)
            ->get();
        
        return (object) [
            'base' => $base,
            'vehicle' => $vehicle,
            'brake' => $brake,
            'emission' => $emission,
            'headlamp' => $headlamp,
            'suspension' => $suspension,
            'visual' => $visual,
            'pit' => $pit,
        ];
    }
    
    private function generateBarcode($text)
    {
        // Simple barcode generation - in production, use a proper barcode library
        return base64_encode($text);
    }
    
    private function exportToExcel($data, $filename)
    {
        // This would use Maatwebsite\Excel package
        // For now, return CSV
        $csv = fopen('php://temp', 'r+');
        
        if ($data->count() > 0) {
            // Headers
            fputcsv($csv, array_keys((array)$data->first()));
            
            // Data
            foreach ($data as $row) {
                fputcsv($csv, (array)$row);
            }
        }
        
        rewind($csv);
        $output = stream_get_contents($csv);
        fclose($csv);
        
        return response($output)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
    }
}
