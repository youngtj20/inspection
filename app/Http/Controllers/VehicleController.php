<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $perPage   = 20;
        $page      = (int) $request->get('page', 1);
        $offset    = ($page - 1) * $perPage;
        $hasRegFilter = $request->filled('owner') || $request->filled('chassis_no');

        if ($hasRegFilter) {
            // --- Filter path: search i_vehicle_register directly ---
            // Get the latest register row per (plateno, vehicletype) that matches the filter
            $regQuery = DB::table('i_vehicle_register as r')
                ->select(
                    'r.id', 'r.plateno', 'r.vehicletype', 'r.licencetype',
                    'r.owner', 'r.engineno', 'r.chassisno', 'r.makeofvehicle',
                    'r.model', 'r.address', 'r.phoneno', 'r.fueltype', 'r.netweight',
                    'r.registerdate', 'r.dept_id', 'r.createDate',
                    'd.title as dept_name',
                    DB::raw('MAX(r.id) OVER (PARTITION BY r.plateno, r.vehicletype) as max_id')
                )
                ->leftJoin('sys_dept as d', 'r.dept_id', '=', 'd.id');

            if ($request->filled('plate_no')) {
                $regQuery->where('r.plateno', 'LIKE', '%' . $request->plate_no . '%');
            }
            if ($request->filled('owner')) {
                $regQuery->where('r.owner', 'LIKE', '%' . $request->owner . '%');
            }
            if ($request->filled('chassis_no')) {
                $regQuery->where('r.chassisno', 'LIKE', '%' . $request->chassis_no . '%');
            }

            // Wrap to keep only the latest row per vehicle
            $wrapped = DB::table(DB::raw("({$regQuery->toSql()}) as rr"))
                ->mergeBindings($regQuery)
                ->whereColumn('rr.id', 'rr.max_id')
                ->orderByDesc('rr.createDate');

            $total    = DB::table(DB::raw("({$wrapped->toSql()}) as cnt"))
                ->mergeBindings($wrapped)
                ->count();

            $rows = (clone $wrapped)->limit($perPage)->offset($offset)->get();

            $vehicles = new \Illuminate\Pagination\LengthAwarePaginator(
                $rows->values(), $total, $perPage, $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

        } else {
            // --- Default path: drive from i_data_base (fast, uses covering index) ---
            $baseQuery = DB::table('i_data_base')
                ->select('plateno', 'vehicletype', DB::raw('MAX(createDate) as last_seen'));

            if ($request->filled('plate_no')) {
                $baseQuery->where('plateno', 'LIKE', '%' . $request->plate_no . '%');
            }

            $baseQuery->groupBy('plateno', 'vehicletype');

            $cacheKey = 'vehicle_total_' . md5($request->getQueryString() ?? '');
            $total = cache()->remember($cacheKey, 300, fn() =>
                DB::table(DB::raw("({$baseQuery->toSql()}) as sub"))
                    ->mergeBindings($baseQuery)
                    ->count()
            );

            $pageCacheKey = 'vehicle_page_' . md5(($request->getQueryString() ?? '') . '_p' . $page);
            $page20 = cache()->remember($pageCacheKey, 120, fn() =>
                (clone $baseQuery)->orderByDesc('last_seen')->limit($perPage)->offset($offset)->get()
            );

            if ($page20->isEmpty()) {
                $vehicles = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect(), $total, $perPage, $page,
                    ['path' => $request->url(), 'query' => $request->query()]
                );
                $vehicleTypes = collect();
                return view('vehicles.index', compact('vehicles', 'vehicleTypes'));
            }

            $pairs = $page20->map(fn($r) => [$r->plateno, $r->vehicletype])->toArray();

            $latestRegIds = DB::table('i_vehicle_register')
                ->select('plateno', 'vehicletype', DB::raw('MAX(id) as max_id'))
                ->where(function ($q) use ($pairs) {
                    foreach ($pairs as $pair) {
                        $q->orWhere(fn($s) => $s->where('plateno', $pair[0])->where('vehicletype', $pair[1]));
                    }
                })
                ->groupBy('plateno', 'vehicletype')
                ->pluck('max_id');

            $registerRows = DB::table('i_vehicle_register as r')
                ->select(
                    'r.id', 'r.plateno', 'r.vehicletype', 'r.licencetype',
                    'r.owner', 'r.engineno', 'r.chassisno', 'r.makeofvehicle',
                    'r.model', 'r.address', 'r.phoneno', 'r.fueltype', 'r.netweight',
                    'r.registerdate', 'r.dept_id', 'r.createDate',
                    'd.title as dept_name'
                )
                ->whereIn('r.id', $latestRegIds)
                ->leftJoin('sys_dept as d', 'r.dept_id', '=', 'd.id')
                ->get()
                ->keyBy(fn($r) => $r->plateno . '|' . $r->vehicletype);

            $filtered = $page20->map(function ($base) use ($registerRows) {
                return $registerRows->get($base->plateno . '|' . $base->vehicletype) ?? (object)[
                    'id' => null, 'plateno' => $base->plateno, 'vehicletype' => $base->vehicletype,
                    'owner' => null, 'makeofvehicle' => null, 'model' => null,
                    'engineno' => null, 'chassisno' => null, 'fueltype' => null,
                    'netweight' => null, 'dept_name' => null, 'createDate' => $base->last_seen,
                    'phoneno' => null, 'address' => null, 'licencetype' => null,
                    'registerdate' => null, 'dept_id' => null,
                ];
            });

            $vehicles = new \Illuminate\Pagination\LengthAwarePaginator(
                $filtered->values(), $total, $perPage, $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        $vehicleTypes = cache()->remember('vehicle_types_list', 1800, fn() =>
            DB::table('i_data_base')->distinct()->orderBy('vehicletype')->pluck('vehicletype')
        );

        return view('vehicles.index', compact('vehicles', 'vehicleTypes'));
    }
    
    public function create()
    {
        return view('vehicles.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plateno' => 'required|string|max:30|unique:i_vehicle_base,plateno',
            'vehicletype' => 'required|string|max:4',
            'engineno' => 'required|string|max:50',
            'chassisno' => 'required|string|max:50',
            'makeofvehicle' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'licencetype' => 'required|string|max:1',
            'owner' => 'required|string|max:200',
            'address' => 'nullable|string|max:200',
            'phoneno' => 'nullable|string|max:30',
            'netweight' => 'nullable|numeric',
            'grossweight' => 'nullable|numeric',
            'authorizedtocarry' => 'nullable|numeric',
            'personstocarry' => 'nullable|numeric',
            'fueltype' => 'required|string|max:1',
            'headlampsystem' => 'nullable|string|max:1',
            'drivemethod' => 'nullable|string|max:1',
            'axisnumber' => 'nullable|integer',
            'registerdate' => 'nullable|date',
            'productdate' => 'nullable|date',
        ]);
        
        $id = DB::table('i_vehicle_base')->insertGetId(array_merge($validated, [
            'createDate' => now(),
            'create_date' => now(),
        ]));
        
        $this->logActivity('Vehicle Registered', 'i_vehicle_base', $id, 
            "Registered vehicle {$validated['plateno']}");
        
        return redirect()->route('vehicles.show', $id)
            ->with('success', 'Vehicle registered successfully');
    }
    
    public function show($id)
    {
        // Fetch vehicle details from i_vehicle_register with dept name
        $vehicle = DB::table('i_vehicle_register as r')
            ->select('r.*', 'd.title as dept_name')
            ->leftJoin('sys_dept as d', 'r.dept_id', '=', 'd.id')
            ->where('r.id', $id)
            ->first();

        if (!$vehicle) {
            abort(404, 'Vehicle not found');
        }

        // Get inspection history from i_data_base
        $inspections = DB::table('i_data_base')
            ->where('plateno', $vehicle->plateno)
            ->where('vehicletype', $vehicle->vehicletype)
            ->orderByDesc('inspectdate')
            ->get();

        $total   = $inspections->count();
        $passed  = $inspections->whereIn('testresult', ['1', 'Y'])->count();
        $failed  = $inspections->whereIn('testresult', ['0', 'N'])->count();
        $stats = [
            'total_inspections' => $total,
            'passed'            => $passed,
            'failed'            => $failed,
            'pending'           => $total - $passed - $failed,
            'pass_rate'         => ($passed + $failed) > 0 ? round($passed / ($passed + $failed) * 100, 1) : 0,
            'last_inspection'   => $inspections->first()?->inspectdate,
            'first_inspection'  => $inspections->last()?->inspectdate,
        ];

        return view('vehicles.show', compact('vehicle', 'inspections', 'stats'));
    }
    
    public function edit($id)
    {
        $vehicle = DB::table('i_vehicle_register')->find($id);

        if (!$vehicle) {
            abort(404, 'Vehicle not found');
        }

        return view('vehicles.edit', compact('vehicle'));
    }
    
    public function update(Request $request, $id)
    {
        $vehicle = DB::table('i_vehicle_base')->find($id);
        
        if (!$vehicle) {
            abort(404, 'Vehicle not found');
        }
        
        $validated = $request->validate([
            'owner' => 'required|string|max:200',
            'address' => 'nullable|string|max:200',
            'phoneno' => 'nullable|string|max:30',
            'netweight' => 'nullable|numeric',
            'grossweight' => 'nullable|numeric',
            'authorizedtocarry' => 'nullable|numeric',
            'personstocarry' => 'nullable|numeric',
        ]);
        
        DB::table('i_vehicle_base')
            ->where('id', $id)
            ->update($validated);
        
        $this->logActivity('Vehicle Updated', 'i_vehicle_base', $id, 
            "Updated vehicle {$vehicle->plateno}");
        
        return redirect()->route('vehicles.show', $id)
            ->with('success', 'Vehicle updated successfully');
    }
    
    public function history($id)
    {
        $vehicle = DB::table('i_vehicle_register')->find($id);

        if (!$vehicle) {
            abort(404, 'Vehicle not found');
        }

        $inspections = DB::table('i_data_base')
            ->select('i_data_base.*', 'sys_dept.title as department_name')
            ->leftJoin('sys_dept', 'i_data_base.dept_id', '=', 'sys_dept.id')
            ->where('i_data_base.plateno', $vehicle->plateno)
            ->where('i_data_base.vehicletype', $vehicle->vehicletype)
            ->orderByDesc('i_data_base.inspectdate')
            ->get();

        return view('vehicles.history', compact('vehicle', 'inspections'));
    }
    
    public function filter(Request $request)
    {
        $query = DB::table('i_vehicle_base');
        
        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('plateno', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('owner', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('engineno', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('chassisno', 'LIKE', '%' . $request->q . '%');
            });
        }
        
        $vehicles = $query->limit(20)->get();
        
        return response()->json($vehicles);
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
            'create_date' => now(),
        ]);
    }
}
