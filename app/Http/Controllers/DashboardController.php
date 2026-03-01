<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private $isSuperAdmin = null;
    
    private function isSuperAdmin()
    {
        if ($this->isSuperAdmin === null) {
            $user = auth()->user();
            // Cache per user for 10 minutes — avoid DB hit on every dashboard request
            $this->isSuperAdmin = cache()->remember('is_super_admin_' . $user->id, 600, fn() =>
                DB::table('sys_user_role')
                    ->join('sys_role', 'sys_user_role.role_id', '=', 'sys_role.id')
                    ->where('sys_user_role.user_id', $user->id)
                    ->where('sys_role.name', 'super-admin')
                    ->exists()
            );
        }
        return $this->isSuperAdmin;
    }
    
    public function index()
    {
        $user = auth()->user();
        $deptId = $user->dept_id;
        
        // Get statistics
        $stats = $this->getStatistics($deptId);

        // Get upcoming inspections (vehicles due for inspection)
        $upcomingInspections = $this->getUpcomingInspections($deptId);

        // Get inspection trends
        $trends = $this->getInspectionTrends($deptId);

        return view('dashboard.index', compact('stats', 'upcomingInspections', 'trends'));
    }
    
    private function getStatistics($deptId)
    {
        $dept = $deptId ?: 'all';
        $isSuperAdmin = $this->isSuperAdmin();
        $todayKey = Carbon::today()->format('Y-m-d');

        // Outer cache: covers all stats together — 5 min TTL (driven by today's count freshness)
        // On cache hit: single file read (~1ms) instead of 7 DB queries
        $outerKey = "dashboard_stats_all_{$dept}_{$todayKey}";
        if ($cached = cache()->get($outerKey)) {
            return $cached;
        }

        // --- Fast time-based counts (createDate index — each <20ms) ---
        $todayInspections = DB::table('i_data_base')
            ->when($deptId && !$isSuperAdmin, fn($q) => $q->where('dept_id', $deptId))
            ->whereDate('createDate', Carbon::today())
            ->count();

        $monthInspections = cache()->remember("stat_month_{$dept}_" . now()->format('Y-m'), 600, fn() =>
            DB::table('i_data_base')
                ->when($deptId && !$isSuperAdmin, fn($q) => $q->where('dept_id', $deptId))
                ->where('createDate', '>=', Carbon::now()->startOfMonth())
                ->count()
        );

        $yearInspections = cache()->remember("stat_year_{$dept}_" . now()->format('Y'), 3600, fn() =>
            DB::table('i_data_base')
                ->when($deptId && !$isSuperAdmin, fn($q) => $q->where('dept_id', $deptId))
                ->where('createDate', '>=', Carbon::now()->startOfYear())
                ->count()
        );

        // --- Month totals: pass/fail scoped to current month only (fast — uses createDate index) ---
        $monthKey = now()->format('Y-m');
        $slowStats = cache()->remember("stat_totals_{$dept}_{$monthKey}", 600, function () use ($deptId, $isSuperAdmin) {
            $passFail = DB::table('i_data_base')
                ->select('testresult', DB::raw('COUNT(*) as cnt'))
                ->when($deptId && !$isSuperAdmin, fn($q) => $q->where('dept_id', $deptId))
                ->where('createDate', '>=', Carbon::now()->startOfMonth())
                ->groupBy('testresult')
                ->pluck('cnt', 'testresult');

            $passed  = ($passFail->get('1') ?? 0) + ($passFail->get('Y') ?? 0);
            $failed  = ($passFail->get('0') ?? 0) + ($passFail->get('N') ?? 0);
            $total   = $passFail->sum();
            $pending = $total - $passed - $failed;

            return compact('total', 'passed', 'failed', 'pending');
        });

        // --- Ancillary counts: cache 6 hours each (very stable data) ---
        $totalVehicles = cache()->remember("total_vehicles_{$dept}", 21600, fn() =>
            DB::select('SELECT COUNT(*) as cnt FROM (SELECT 1 FROM i_vehicle_register GROUP BY plateno, vehicletype) sub')[0]->cnt
        );

        $activeInspectors = cache()->remember("active_inspectors_{$dept}", 21600, fn() =>
            DB::table('sys_user')
                ->join('sys_user_role', 'sys_user.id', '=', 'sys_user_role.user_id')
                ->join('sys_role', 'sys_user_role.role_id', '=', 'sys_role.id')
                ->where('sys_role.name', 'inspector')
                ->where('sys_user.status', 1)
                ->when($deptId && !$isSuperAdmin, fn($q) => $q->where('sys_user.dept_id', $deptId))
                ->count()
        );

        $equipmentCount = cache()->remember("equipment_count_{$dept}", 21600, fn() =>
            DB::table('f_equipment_files')
                ->when($deptId && !$isSuperAdmin, fn($q) => $q->where('dept_id', $deptId))
                ->count()
        );

        $passRate = ($slowStats['passed'] + $slowStats['failed']) > 0
            ? round($slowStats['passed'] / ($slowStats['passed'] + $slowStats['failed']) * 100, 2)
            : 0;

        $stats = [
            'total_inspections'   => $slowStats['total'],   // current month total
            'today_inspections'   => $todayInspections,
            'month_inspections'   => $monthInspections,
            'year_inspections'    => $yearInspections,
            'passed_inspections'  => $slowStats['passed'],  // current month passed
            'failed_inspections'  => $slowStats['failed'],  // current month failed
            'pending_inspections' => $slowStats['pending'],
            'total_vehicles'      => $totalVehicles,
            'active_inspectors'   => $activeInspectors,
            'equipment_count'     => $equipmentCount,
            'pass_rate'           => $passRate,
            'stats_month'         => Carbon::now()->format('M Y'),
        ];

        // Store assembled result for 5 minutes — next visit is a single cache read
        cache()->put($outerKey, $stats, 300);

        return $stats;
    }
    
    private function calculatePassRate($query)
    {
        $total = (clone $query)->whereNotNull('testresult')->where('testresult', '!=', '')->count();
        if ($total == 0) return 0;
        
        $passed = (clone $query)->whereIn('testresult', ['1', 'Y'])->count();
        return round(($passed / $total) * 100, 2);
    }
    
    private function getRecentInspections($deptId, $limit = 10)
    {
        $dept = $deptId ?: 'all';
        // Cache 2 min — scoped to today so it stays fresh without hammering the JOIN
        $key = "recent_insp_{$dept}_" . Carbon::today()->format('Y-m-d') . "_{$limit}";
        return cache()->remember($key, 120, function () use ($deptId, $limit) {
            return DB::table('i_data_base')
                ->select(
                    'i_data_base.id',
                    'i_data_base.plateno',
                    'i_data_base.vehicletype',
                    'i_data_base.seriesno',
                    'i_data_base.inspectdate',
                    'i_data_base.testresult',
                    'i_data_base.inspector',
                    'i_data_base.createDate',
                    'i_vehicle_register.makeofvehicle',
                    'i_vehicle_register.model',
                    'i_vehicle_register.owner',
                    'sys_dept.title as department_name'
                )
                ->leftJoin('i_vehicle_register', function ($join) {
                    $join->on('i_data_base.plateno', '=', 'i_vehicle_register.plateno')
                         ->on('i_data_base.vehicletype', '=', 'i_vehicle_register.vehicletype')
                         ->on('i_data_base.seriesno', '=', 'i_vehicle_register.seriesno')
                         ->on('i_data_base.inspecttimes', '=', 'i_vehicle_register.inspecttimes');
                })
                ->leftJoin('sys_dept', 'i_data_base.dept_id', '=', 'sys_dept.id')
                ->when($deptId && !$this->isSuperAdmin(), fn($q) => $q->where('i_data_base.dept_id', $deptId))
                ->where('i_data_base.createDate', '>=', Carbon::now()->startOfMonth())
                ->orderByDesc('i_data_base.createDate')
                ->limit($limit)
                ->get();
        });
    }
    
    private function getUpcomingInspections($deptId, $limit = 10)
    {
        // Vehicles whose last inspection was over 11 months ago (due for annual re-inspection)
        $elevenMonthsAgo = Carbon::now()->subMonths(11)->format('Y-m-d');

        return DB::table('i_data_base as d')
            ->select(
                'd.plateno',
                'd.vehicletype',
                'd.owner',
                DB::raw('MAX(d.inspectdate) as last_inspection_date'),
                DB::raw('DATEDIFF(NOW(), MAX(d.inspectdate)) as days_since_inspection')
            )
            ->when($deptId && !$this->isSuperAdmin(), fn($q) => $q->where('d.dept_id', $deptId))
            ->groupBy('d.plateno', 'd.vehicletype', 'd.owner')
            ->havingRaw('MAX(d.inspectdate) < ?', [$elevenMonthsAgo])
            ->orderByRaw('MAX(d.inspectdate) ASC')
            ->limit($limit)
            ->get();
    }
    
    private function getInspectionTrends($deptId, $weeks = 8)
    {
        $dept      = $deptId ?: 'all';
        $startDate = Carbon::now()->startOfWeek()->subWeeks($weeks - 1);
        $cacheKey  = "trends_weekly_{$dept}_{$weeks}_" . $startDate->format('Y-W');

        return cache()->remember($cacheKey, 300, function () use ($deptId, $startDate) {
            $data = DB::table('i_data_base')
                ->select(
                    DB::raw('YEARWEEK(inspectdate, 1) as yw'),
                    DB::raw('MIN(inspectdate) as week_start'),
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN testresult IN ("1","Y") THEN 1 ELSE 0 END) as passed'),
                    DB::raw('SUM(CASE WHEN testresult IN ("0","N") THEN 1 ELSE 0 END) as failed')
                )
                ->where('inspectdate', '>=', $startDate->format('Y-m-d'))
                ->when($deptId && !$this->isSuperAdmin(), fn($q) => $q->where('dept_id', $deptId))
                ->groupBy('yw')
                ->orderBy('yw')
                ->get();

            return [
                'labels' => $data->map(fn($r) => Carbon::parse($r->week_start)->format('M d'))->toArray(),
                'total'  => $data->pluck('total')->map(fn($v) => (int) $v)->toArray(),
                'passed' => $data->pluck('passed')->map(fn($v) => (int) $v)->toArray(),
                'failed' => $data->pluck('failed')->map(fn($v) => (int) $v)->toArray(),
            ];
        });
    }
    
    public function getStats(Request $request)
    {
        $deptId = auth()->user()->dept_id;
        $stats = $this->getStatistics($deptId);
        
        return response()->json($stats);
    }
    
    public function getChartData(Request $request)
    {
        $deptId = auth()->user()->dept_id;
        $type  = $request->get('type', 'trends');
        $weeks = (int) $request->get('weeks', 8);

        switch ($type) {
            case 'trends':
                return response()->json($this->getInspectionTrends($deptId, $weeks));
            
            case 'vehicle_types':
                return response()->json($this->getVehicleTypeDistribution($deptId));
            
            case 'defects':
                return response()->json($this->getCommonDefects($deptId));
            
            case 'inspector_performance':
                return response()->json($this->getInspectorPerformance($deptId));
            
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }
    
    private function getVehicleTypeDistribution($deptId)
    {
        // Cache key for vehicle type distribution
        $cacheKey = "vehicle_types_" . ($deptId ?: 'all');
        
        if (cache()->has($cacheKey)) {
            return cache($cacheKey);
        }
        
        $data = DB::table('i_data_base')
            ->select('vehicletype', DB::raw('COUNT(*) as count'))
            ->when($deptId && !$this->isSuperAdmin(), fn($q) => $q->where('dept_id', $deptId))
            ->whereNotNull('vehicletype')
            ->where('vehicletype', '!=', '')
            ->groupBy('vehicletype')
            ->orderBy('count', 'desc')
            ->limit(8) // Limit to top 8 vehicle types
            ->get();
        
        $result = [
            'labels' => $data->pluck('vehicletype')->toArray(),
            'data' => $data->pluck('count')->toArray(),
        ];
        
        // Cache for 1 hour
        cache([$cacheKey => $result], now()->addHour());
        
        return $result;
    }
    
    private function getCommonDefects($deptId, $limit = 10)
    {
        $data = DB::table('i_data_visual')
            ->select('category', DB::raw('COUNT(*) as count'))
            ->when($deptId && !$this->isSuperAdmin(), fn($q) => $q->where('dept_id', $deptId))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
        
        return [
            'labels' => $data->pluck('category')->toArray(),
            'data' => $data->pluck('count')->toArray(),
        ];
    }
    
    private function getInspectorPerformance($deptId)
    {
        $data = DB::table('i_data_base')
            ->select(
                'inspector',
                DB::raw('COUNT(*) as total_inspections'),
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, starttime, endTime)) as avg_duration')
            )
            ->when($deptId && !$this->isSuperAdmin(), fn($q) => $q->where('dept_id', $deptId))
            ->whereNotNull('inspector')
            ->groupBy('inspector')
            ->orderBy('total_inspections', 'desc')
            ->limit(10)
            ->get();
        
        return [
            'labels' => $data->pluck('inspector')->toArray(),
            'inspections' => $data->pluck('total_inspections')->toArray(),
            'avg_duration' => $data->pluck('avg_duration')->map(fn($v) => round($v, 2))->toArray(),
        ];
    }
    
    public function search(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all');
        
        $results = [];
        
        if ($type === 'all' || $type === 'vehicles') {
            $results['vehicles'] = DB::table('i_vehicle_base')
                ->where('plateno', 'LIKE', "%{$query}%")
                ->orWhere('owner', 'LIKE', "%{$query}%")
                ->orWhere('engineno', 'LIKE', "%{$query}%")
                ->orWhere('chassisno', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get();
        }
        
        if ($type === 'all' || $type === 'inspections') {
            $results['inspections'] = DB::table('i_data_base')
                ->where('seriesno', 'LIKE', "%{$query}%")
                ->orWhere('plateno', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get();
        }
        
        return response()->json($results);
    }
    
    public function activityLog(Request $request)
    {
        $logs = DB::table('sys_action_log')
            ->select('sys_action_log.*', 'sys_user.nickname as user_name')
            ->leftJoin('sys_user', 'sys_action_log.oper_by', '=', 'sys_user.id')
            ->orderBy('sys_action_log.create_date', 'desc')
            ->paginate(50);
        
        return view('dashboard.activity-log', compact('logs'));
    }
}
