<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\LoadsDepartments;

class InspectionController extends Controller
{
    use LoadsDepartments;
    public function index(Request $request)
    {
        $user = auth()->user();
        // Cache the super-admin check per user for 10 minutes
        $isSuperAdmin = cache()->remember('is_super_admin_' . $user->id, 600, fn() =>
            DB::table('sys_user_role')
                ->join('sys_role', 'sys_user_role.role_id', '=', 'sys_role.id')
                ->where('sys_user_role.user_id', $user->id)
                ->where('sys_role.name', 'super-admin')
                ->exists()
        );
        
        // Build optimized query
        $query = DB::table('i_data_base');
        
        // Apply department filter first (most restrictive for non-admins)
        if ($user->dept_id && !$isSuperAdmin) {
            $query->where('i_data_base.dept_id', $user->dept_id);
        }
        
        // Apply user filters in order of selectivity (most restrictive first)
        if ($request->filled('plate_no')) {
            $plateNo = trim($request->plate_no);
            if (strpos($plateNo, '%') === false && strpos($plateNo, '_') === false) {
                $query->where('i_data_base.plateno', $plateNo);
            } else {
                $query->where('i_data_base.plateno', 'LIKE', $plateNo);
            }
        }

        if ($request->filled('chassis_no')) {
            // chassis is in i_vehicle_register — we need a subquery to match plateno+vehicletype
            $matchingPairs = DB::table('i_vehicle_register')
                ->where('chassisno', 'LIKE', '%' . trim($request->chassis_no) . '%')
                ->select('plateno', 'vehicletype')
                ->distinct()
                ->get();
            $query->where(function ($q) use ($matchingPairs) {
                foreach ($matchingPairs as $pair) {
                    $q->orWhere(fn($s) => $s
                        ->where('i_data_base.plateno', $pair->plateno)
                        ->where('i_data_base.vehicletype', $pair->vehicletype)
                    );
                }
                if ($matchingPairs->isEmpty()) {
                    $q->whereRaw('1=0'); // no matches — return nothing
                }
            });
        }

        if ($request->filled('department')) {
            // resolveDeptFilter handles both state IDs (expands to all child centers)
            // and center IDs (filters directly)
            $deptIds = $this->resolveDeptFilter($request->department);
            if (!empty($deptIds)) {
                $query->whereIn('i_data_base.dept_id', $deptIds);
            }
        }

        if ($request->filled('test_result')) {
            $query->where('i_data_base.testresult', $request->test_result);
        }
        
        // Date filters - use direct comparison instead of whereDate for better performance
        if ($request->filled('date_from')) {
            $query->where('i_data_base.inspectdate', '>=', $request->date_from . ' 00:00:00');
        }
        
        if ($request->filled('date_to')) {
            $query->where('i_data_base.inspectdate', '<=', $request->date_to . ' 23:59:59');
        }
        
        // Get counts — cache for 5 min when no filters active (full-table scan ~280ms cold)
        $hasFilters = $request->hasAny(['plate_no','chassis_no','department','test_result','date_from','date_to']);
        $statsCacheKey = $hasFilters
            ? null
            : 'insp_stats_' . ($user->dept_id ?: 'all') . ($isSuperAdmin ? '_sa' : '');

        $statsData = $statsCacheKey
            ? cache()->remember($statsCacheKey, 300, fn() =>
                (clone $query)->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN testresult IN ("1", "Y") THEN 1 ELSE 0 END) as passed,
                    SUM(CASE WHEN testresult IN ("0", "N") THEN 1 ELSE 0 END) as failed
                ')->first()
              )
            : (clone $query)->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN testresult IN ("1", "Y") THEN 1 ELSE 0 END) as passed,
                SUM(CASE WHEN testresult IN ("0", "N") THEN 1 ELSE 0 END) as failed
              ')->first();

        // Use simplePaginate to avoid a second COUNT(*) on 700k rows (~250ms saved)
        $inspections = $query
            ->select(
                'i_data_base.id',
                'i_data_base.seriesno',
                'i_data_base.plateno',
                'i_data_base.inspectdate',
                'i_data_base.testresult',
                'i_data_base.inspecttimes',
                'i_data_base.vehicletype',
                'i_data_base.register',
                'i_vehicle_register.makeofvehicle',
                'i_vehicle_register.model',
                'i_vehicle_register.owner',
                'i_vehicle_register.chassisno',
                'sys_dept.title as department_name'
            )
            ->leftJoin('i_vehicle_register', function($join) {
                $join->on('i_data_base.plateno', '=', 'i_vehicle_register.plateno')
                     ->on('i_data_base.vehicletype', '=', 'i_vehicle_register.vehicletype')
                     ->on('i_data_base.seriesno', '=', 'i_vehicle_register.seriesno')
                     ->on('i_data_base.inspecttimes', '=', 'i_vehicle_register.inspecttimes');
            })
            ->leftJoin('sys_dept', 'i_data_base.dept_id', '=', 'sys_dept.id')
            ->orderBy('i_data_base.createDate', 'desc')
            ->simplePaginate(10)
            ->appends($request->except('page'));

        // Grouped departments: states with their centers (cached 6h)
        $departments = $this->getGroupedDepartments();
        // Flat id→title map for resolving filter labels in the view
        $deptLookup  = $this->getDeptLookup();

        // Cache vehicle types for 6 hours (stable list)
        $vehicleTypes = cache()->remember('vehicle_types_idb_' . ($user->dept_id ?: 'all'), 21600, fn() =>
            DB::table('i_data_base')
                ->select('vehicletype')
                ->when($user->dept_id && !$isSuperAdmin, fn($q) => $q->where('dept_id', $user->dept_id))
                ->distinct()
                ->orderBy('vehicletype')
                ->pluck('vehicletype')
        );

        // Pass stats to view
        $stats = [
            'total' => $statsData->total ?? 0,
            'passed' => $statsData->passed ?? 0,
            'failed' => $statsData->failed ?? 0,
            'current_page' => $inspections->count()
        ];
        
        return view('inspections.index', compact('inspections', 'departments', 'deptLookup', 'vehicleTypes', 'stats'));
    }
    
    public function create()
    {
        // Do NOT load all vehicles — the form uses a live search endpoint instead
        $departments = $this->getGroupedDepartments();

        return view('inspections.create', compact('departments'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plateno' => 'required|string|max:30',
            'vehicletype' => 'required|string|max:4',
            'inspecttype' => 'required|string|max:2',
            'dept_id' => 'required|exists:sys_dept,id',
        ]);
        
        // Generate series number
        $seriesno = $this->generateSeriesNumber();
        
        // Get vehicle details
        $vehicle = DB::table('i_vehicle_base')
            ->where('plateno', $validated['plateno'])
            ->where('vehicletype', $validated['vehicletype'])
            ->first();
        
        if (!$vehicle) {
            return back()->withErrors(['plateno' => 'Vehicle not found in database']);
        }
        
        // Get inspection times for this vehicle
        $inspecttimes = DB::table('i_data_base')
            ->where('plateno', $validated['plateno'])
            ->where('vehicletype', $validated['vehicletype'])
            ->max('inspecttimes') + 1;
        
        // Create inspection registration
        $registrationId = DB::table('i_vehicle_register')->insertGetId([
            'seriesno' => $seriesno,
            'inspecttimes' => $inspecttimes,
            'stationno' => $request->stationno ?? '001',
            'inspectdate' => Carbon::now()->format('Y-m-d H:i:s'),
            'registertime' => Carbon::now()->format('H:i:s'),
            'plateno' => $validated['plateno'],
            'inspecttype' => $validated['inspecttype'],
            'vehicletype' => $validated['vehicletype'],
            'engineno' => $vehicle->engineno,
            'makeofvehicle' => $vehicle->makeofvehicle,
            'model' => $vehicle->model,
            'licencetype' => $vehicle->licencetype,
            'owner' => $vehicle->owner,
            'identificationmark' => $vehicle->identificationmark,
            'address' => $vehicle->address,
            'phoneno' => $vehicle->phoneno,
            'netweight' => $vehicle->netweight,
            'authorizedtocarry' => $vehicle->authorizedtocarry,
            'grossweight' => $vehicle->grossweight,
            'personstocarry' => $vehicle->personstocarry,
            'fueltype' => $vehicle->fueltype,
            'headlampsystem' => $vehicle->headlampsystem,
            'drivemethod' => $vehicle->drivemethod,
            'axisnumber' => $vehicle->axisnumber,
            'handbrake' => $vehicle->handbrake,
            'registerdate' => $vehicle->registerdate,
            'productdate' => $vehicle->productdate,
            'heavyorlight' => $vehicle->heavyorlight,
            'chassisno' => $vehicle->chassisno,
            'acceptmember' => auth()->user()->nickname,
            'odmeter' => $request->odmeter,
            'presentor' => $request->presentor,
            'dept_id' => $validated['dept_id'],
            'createDate' => Carbon::now(),
        ]);
        
        // Create base inspection record
        $inspectionId = DB::table('i_data_base')->insertGetId([
            'plateno' => $validated['plateno'],
            'vehicletype' => $validated['vehicletype'],
            'licencetype' => $vehicle->licencetype,
            'seriesno' => $seriesno,
            'inspectdate' => Carbon::now()->format('Y-m-d H:i:s'),
            'inspecttimes' => $inspecttimes,
            'inspecttype' => $validated['inspecttype'],
            'starttime' => Carbon::now()->format('Y-m-d H:i:s'),
            'register' => auth()->user()->nickname,
            'owner' => $vehicle->owner,
            'dept_id' => $validated['dept_id'],
            'createDate' => Carbon::now(),
        ]);
        
        // Log activity
        $this->logActivity('Inspection Created', 'i_data_base', $inspectionId, 
            "Created inspection {$seriesno} for vehicle {$validated['plateno']}");
        
        return redirect()->route('inspections.show', $inspectionId)
            ->with('success', 'Inspection created successfully. Series No: ' . $seriesno);
    }
    
    public function show($id)
    {
        $inspection = DB::table('i_data_base')
            ->select(
                'i_data_base.*',
                'i_vehicle_register.makeofvehicle',
                'i_vehicle_register.model',
                'i_vehicle_register.engineno',
                'i_vehicle_register.chassisno',
                'i_vehicle_register.owner',
                'sys_dept.title as department_name'
            )
            ->leftJoin('i_vehicle_register', function($join) {
                $join->on('i_data_base.plateno', '=', 'i_vehicle_register.plateno')
                     ->on('i_data_base.vehicletype', '=', 'i_vehicle_register.vehicletype')
                     ->on('i_data_base.seriesno', '=', 'i_vehicle_register.seriesno')
                     ->on('i_data_base.inspecttimes', '=', 'i_vehicle_register.inspecttimes');
            })
            ->leftJoin('sys_dept', 'i_data_base.dept_id', '=', 'sys_dept.id')
            ->where('i_data_base.id', $id)
            ->first();

        if (!$inspection) {
            abort(404, 'Inspection not found');
        }
        
        // Fetch all test data in parallel using individual fast indexed lookups
        $sn = $inspection->seriesno;
        $it = $inspection->inspecttimes;

        // seriesno is a per-department daily counter — NOT globally unique.
        // Must always filter by dept_id to avoid mixing records from different departments/vehicles.
        $deptId = $inspection->dept_id;

        // Standard tables use lowercase (seriesno, inspecttimes, dept_id)
        // i_data_brake_rear06 and i_data_speedometer use camelCase keys — handle separately
        $brakeFront  = DB::table('i_data_brake_front')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();
        $brakeRear   = DB::table('i_data_brake_rear')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();
        $brakeRear02 = DB::table('i_data_brake_rear02')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();
        $brakeRear03 = DB::table('i_data_brake_rear03')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();
        $brakeRear04 = DB::table('i_data_brake_rear04')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();
        // i_data_brake_rear05 has no dept_id column — filter by seriesno + inspecttimes only
        $brakeRear05 = DB::table('i_data_brake_rear05')->where('seriesno', $sn)->where('inspecttimes', $it)->first();
        // rear06 uses camelCase column names (dept_id is still lowercase)
        $brakeRear06 = DB::table('i_data_brake_rear06')->where('seriesNo', $sn)->where('inspectTimes', $it)->where('dept_id', $deptId)->first();
        $brakeSummary = DB::table('i_data_brake_summary')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();

        $emissionData = DB::table('i_data_gas')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();
        $smokeData    = DB::table('i_data_smoke')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();

        $headlampLeft  = DB::table('i_data_headlamp_left')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();
        $headlampRight = DB::table('i_data_headlamp_right')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();

        $suspFront = DB::table('i_data_suspension_front')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();
        $suspRear  = DB::table('i_data_suspension_rear')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();

        $sideslipData    = DB::table('i_data_sideslip')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->first();
        // speedometer uses camelCase column names (dept_id is still lowercase)
        $speedometerData = DB::table('i_data_speedometer')->where('seriesNo', $sn)->where('inspectTimes', $it)->where('dept_id', $deptId)->first();

        $brakeData = [
            'front'   => $brakeFront,
            'rear'    => $brakeRear,
            'rear02'  => $brakeRear02,
            'rear03'  => $brakeRear03,
            'rear04'  => $brakeRear04,
            'rear05'  => $brakeRear05,
            'rear06'  => $brakeRear06,
            'summary' => $brakeSummary,
        ];
        $headlampData   = ['left' => $headlampLeft, 'right' => $headlampRight];
        $suspensionData = ['front' => $suspFront, 'rear' => $suspRear];

        // These return collections — scoped by dept_id to prevent cross-department mixing
        $visualData = DB::table('i_data_visual')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->get();
        $pitData    = DB::table('i_data_pit')->where('seriesno', $sn)->where('inspecttimes', $it)->where('dept_id', $deptId)->get();

        return view('inspections.show', compact(
            'inspection',
            'brakeData',
            'emissionData',
            'smokeData',
            'headlampData',
            'suspensionData',
            'sideslipData',
            'speedometerData',
            'visualData',
            'pitData'
        ));
    }
    
    public function saveBrakeTest(Request $request, $id)
    {
        $inspection = DB::table('i_data_base')->find($id);
        
        if (!$inspection) {
            return response()->json(['error' => 'Inspection not found'], 404);
        }
        
        $validated = $request->validate([
            'axle' => 'required|in:front,rear,rear02,rear03,rear04,rear05',
            'lftaxleload' => 'required|numeric',
            'rgtaxleload' => 'required|numeric',
            'lftbrakeforce' => 'required|numeric',
            'rgtbrakeforce' => 'required|numeric',
        ]);
        
        $table = 'i_data_brake_' . $validated['axle'];
        
        // Calculate values
        $axleload = $validated['lftaxleload'] + $validated['rgtaxleload'];
        $brakeeff = ($axleload > 0) ? (($validated['lftbrakeforce'] + $validated['rgtbrakeforce']) / $axleload) * 100 : 0;
        $brakediff = abs($validated['lftbrakeforce'] - $validated['rgtbrakeforce']);
        
        DB::table($table)->insert([
            'seriesno' => $inspection->seriesno,
            'inspecttimes' => $inspection->inspecttimes,
            'lftaxleload' => $validated['lftaxleload'],
            'rgtaxleload' => $validated['rgtaxleload'],
            'axleload' => $axleload,
            'lftbrakeforce' => $validated['lftbrakeforce'],
            'rgtbrakeforce' => $validated['rgtbrakeforce'],
            'brakeeff' => round($brakeeff, 2),
            'brakediff' => $brakediff,
            'stsbrakeeff' => $brakeeff >= 50 ? '1' : '0',
            'stsbrakediff' => $brakediff <= 8 ? '1' : '0',
            'dept_id' => $inspection->dept_id,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Brake test data saved']);
    }
    
    public function saveEmissionTest(Request $request, $id)
    {
        $inspection = DB::table('i_data_base')->find($id);
        
        if (!$inspection) {
            return response()->json(['error' => 'Inspection not found'], 404);
        }
        
        $validated = $request->validate([
            'idlhcaverage' => 'required|numeric',
            'idlcoaverage' => 'required|numeric',
            'hghhcaverage' => 'nullable|numeric',
            'hghcoaverage' => 'nullable|numeric',
        ]);
        
        DB::table('i_data_gas')->insert([
            'seriesno' => $inspection->seriesno,
            'inspecttimes' => $inspection->inspecttimes,
            'idlhcaverage' => $validated['idlhcaverage'],
            'idlcoaverage' => $validated['idlcoaverage'],
            'hghhcaverage' => $validated['hghhcaverage'] ?? null,
            'hghcoaverage' => $validated['hghcoaverage'] ?? null,
            'stsidlhc' => $validated['idlhcaverage'] <= 200 ? '1' : '0',
            'stsidlco' => $validated['idlcoaverage'] <= 0.5 ? '1' : '0',
            'dept_id' => $inspection->dept_id,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Emission test data saved']);
    }
    
    public function finalize(Request $request, $id)
    {
        $inspection = DB::table('i_data_base')->find($id);
        
        if (!$inspection) {
            return back()->withErrors(['error' => 'Inspection not found']);
        }
        
        // Determine overall result based on all tests (dept_id required — seriesno is per-department)
        $testResult = $this->calculateOverallResult($inspection->seriesno, $inspection->inspecttimes, $inspection->dept_id);
        
        DB::table('i_data_base')
            ->where('id', $id)
            ->update([
                'endTime' => Carbon::now()->format('Y-m-d H:i:s'),
                'testresult' => $testResult,
                'conclusion' => $testResult == '1' ? 'PASSED' : 'FAILED',
                'inspector' => auth()->user()->nickname,
                'updateDate' => Carbon::now(),
            ]);
        
        $this->logActivity('Inspection Finalized', 'i_data_base', $id, 
            "Finalized inspection {$inspection->seriesno} with result: " . ($testResult == '1' ? 'PASSED' : 'FAILED'));
        
        return redirect()->route('inspections.show', $id)
            ->with('success', 'Inspection finalized successfully');
    }
    
    public function certificate($id)
    {
        $inspection = DB::table('i_data_base')
            ->select('i_data_base.*', 'i_vehicle_register.*', 'sys_dept.title as department_name')
            ->leftJoin('i_vehicle_register', function($join) {
                $join->on('i_data_base.plateno', '=', 'i_vehicle_register.plateno')
                     ->on('i_data_base.vehicletype', '=', 'i_vehicle_register.vehicletype')
                     ->on('i_data_base.seriesno', '=', 'i_vehicle_register.seriesno')
                     ->on('i_data_base.inspecttimes', '=', 'i_vehicle_register.inspecttimes');
            })
            ->leftJoin('sys_dept', 'i_data_base.dept_id', '=', 'sys_dept.id')
            ->where('i_data_base.id', $id)
            ->first();

        if (!$inspection || !in_array($inspection->testresult, ['Y', 'N', '1', '0'])) {
            abort(404, 'Certificate not available — inspection has no result yet');
        }

        // Normalise: treat Y/1 as passed, N/0 as failed
        $passed = in_array($inspection->testresult, ['Y', '1']);

        return view('inspections.certificate', compact('inspection', 'passed'));
    }
    
    // Helper methods
    private function generateSeriesNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastSeries = DB::table('i_data_base')
            ->where('seriesno', 'LIKE', $date . '%')
            ->orderBy('seriesno', 'desc')
            ->value('seriesno');
        
        if ($lastSeries) {
            $sequence = intval(substr($lastSeries, -4)) + 1;
        } else {
            $sequence = 1;
        }
        
        return $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
    
    private function getBrakeTestData($seriesno, $inspecttimes, $deptId)
    {
        return [
            'front' => DB::table('i_data_brake_front')
                ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first(),
            'rear' => DB::table('i_data_brake_rear')
                ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first(),
            'rear02' => DB::table('i_data_brake_rear02')
                ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first(),
            'rear03' => DB::table('i_data_brake_rear03')
                ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first(),
            // rear05 has no dept_id column
            'rear05' => DB::table('i_data_brake_rear05')
                ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->first(),
            'summary' => DB::table('i_data_brake_summary')
                ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first(),
        ];
    }

    private function getEmissionTestData($seriesno, $inspecttimes, $deptId)
    {
        return DB::table('i_data_gas')
            ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first();
    }

    private function getSmokeTestData($seriesno, $inspecttimes, $deptId)
    {
        return DB::table('i_data_smoke')
            ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first();
    }

    private function getHeadlampTestData($seriesno, $inspecttimes, $deptId)
    {
        return [
            'left' => DB::table('i_data_headlamp_left')
                ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first(),
            'right' => DB::table('i_data_headlamp_right')
                ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first(),
        ];
    }

    private function getSuspensionTestData($seriesno, $inspecttimes, $deptId)
    {
        return [
            'front' => DB::table('i_data_suspension_front')
                ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first(),
            'rear' => DB::table('i_data_suspension_rear')
                ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first(),
        ];
    }

    private function getSideslipTestData($seriesno, $inspecttimes, $deptId)
    {
        return DB::table('i_data_sideslip')
            ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->first();
    }

    private function getVisualInspectionData($seriesno, $inspecttimes, $deptId)
    {
        return DB::table('i_data_visual')
            ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->get();
    }

    private function getPitInspectionData($seriesno, $inspecttimes, $deptId)
    {
        return DB::table('i_data_pit')
            ->where('seriesno', $seriesno)->where('inspecttimes', $inspecttimes)->where('dept_id', $deptId)->get();
    }
    
    private function calculateOverallResult($seriesno, $inspecttimes, $deptId)
    {
        // seriesno is a per-department counter — always scope by dept_id
        $brakePass = DB::table('i_data_brake_summary')
            ->where('seriesno', $seriesno)
            ->where('inspecttimes', $inspecttimes)
            ->where('dept_id', $deptId)
            ->whereIn('stsbrakeeff', ['√', 'v', '1', 'OK'])
            ->exists();

        $emissionPass = DB::table('i_data_gas')
            ->where('seriesno', $seriesno)
            ->where('inspecttimes', $inspecttimes)
            ->where('dept_id', $deptId)
            ->whereIn('stsidlhc', ['√', 'v', '1', 'OK'])
            ->whereIn('stsidlco', ['√', 'v', '1', 'OK'])
            ->exists();

        $criticalDefects = DB::table('i_data_visual')
            ->where('seriesno', $seriesno)
            ->where('inspecttimes', $inspecttimes)
            ->where('dept_id', $deptId)
            ->where('category', 'LIKE', '%CRITICAL%')
            ->exists();
        
        return ($brakePass && $emissionPass && !$criticalDefects) ? '1' : '0';
    }
    
    private function logActivity($name, $model, $recordId, $message)
    {
        DB::table('sys_action_log')->insert([
            'name' => $name,
            'type' => 1,
            'ipaddr' => request()->ip(),
            'model' => $model,
            'record_id' => $recordId,
            'message' => $message,
            'oper_name' => auth()->user()->nickname,
            'oper_by' => auth()->id(),
            'create_date' => Carbon::now(),
        ]);
    }
}
