@extends('layouts.app')

@section('title', 'Daily Report')

@section('content')

{{-- Header --}}
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daily Inspection Report</h1>
        <p class="text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
    </div>
    <a href="{{ route('reports.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
        <i class="fas fa-arrow-left mr-2 text-sm"></i>Back to Reports
    </a>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" action="{{ route('reports.daily') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Date</label>
            <input type="date" name="date" value="{{ $date }}"
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Department</label>
            <select name="department"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[180px]">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('reports.daily', ['date' => $date, 'department' => request('department'), 'format' => 'pdf']) }}"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </form>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-blue-100 text-xs font-semibold uppercase tracking-wider">Total</p>
        <h3 class="text-4xl font-extrabold mt-1">{{ number_format($stats['total']) }}</h3>
        <p class="text-blue-200 text-xs mt-2">inspections today</p>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-green-100 text-xs font-semibold uppercase tracking-wider">Passed</p>
        <h3 class="text-4xl font-extrabold mt-1">{{ number_format($stats['passed']) }}</h3>
        <p class="text-green-200 text-xs mt-2">vehicles</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-red-100 text-xs font-semibold uppercase tracking-wider">Failed</p>
        <h3 class="text-4xl font-extrabold mt-1">{{ number_format($stats['failed']) }}</h3>
        <p class="text-red-200 text-xs mt-2">vehicles</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-yellow-100 text-xs font-semibold uppercase tracking-wider">Pending</p>
        <h3 class="text-4xl font-extrabold mt-1">{{ number_format($stats['pending']) }}</h3>
        <p class="text-yellow-200 text-xs mt-2">vehicles</p>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-purple-100 text-xs font-semibold uppercase tracking-wider">Pass Rate</p>
        <h3 class="text-4xl font-extrabold mt-1">{{ $stats['pass_rate'] }}<span class="text-2xl">%</span></h3>
        <p class="text-purple-200 text-xs mt-2">of completed</p>
    </div>
</div>

{{-- Pass/Fail Progress Bar --}}
@if($stats['total'] > 0)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <div class="flex justify-between items-center mb-3">
        <span class="text-sm font-semibold text-gray-700">Result Distribution</span>
        <span class="text-xs text-gray-400">{{ $stats['passed'] }} passed · {{ $stats['failed'] }} failed · {{ $stats['pending'] }} pending</span>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-5 overflow-hidden flex">
        @php
            $pp = $stats['total'] > 0 ? round($stats['passed'] / $stats['total'] * 100, 1) : 0;
            $fp = $stats['total'] > 0 ? round($stats['failed'] / $stats['total'] * 100, 1) : 0;
        @endphp
        <div class="bg-green-500 h-5 flex items-center justify-center text-white text-xs font-bold transition-all"
             style="width: {{ $pp }}%" title="Passed {{ $pp }}%">
            {{ $pp > 5 ? $pp . '%' : '' }}
        </div>
        <div class="bg-red-400 h-5 flex items-center justify-center text-white text-xs font-bold transition-all"
             style="width: {{ $fp }}%" title="Failed {{ $fp }}%">
            {{ $fp > 5 ? $fp . '%' : '' }}
        </div>
        <div class="bg-gray-200 h-5 flex-1"></div>
    </div>
    <div class="flex gap-5 mt-2">
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-1.5"></span>Passed</span>
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-red-400 mr-1.5"></span>Failed</span>
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-gray-200 mr-1.5"></span>Pending</span>
    </div>
</div>
@endif

{{-- Department Breakdown (only when not filtered to one dept) --}}
@if(count($deptBreakdown) > 1)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-700 flex items-center">
            <i class="fas fa-building text-indigo-500 mr-2"></i>By Department
        </h3>
        <span class="text-xs text-gray-400">{{ count($deptBreakdown) }} departments</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Passed</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Failed</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Pending</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Pass Rate</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Distribution</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($deptBreakdown as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $row['name'] }}</td>
                    <td class="px-5 py-3 text-right text-gray-700">{{ number_format($row['total']) }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-green-700">{{ number_format($row['passed']) }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-red-700">{{ number_format($row['failed']) }}</td>
                    <td class="px-5 py-3 text-right text-yellow-700">{{ number_format($row['pending']) }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold
                            {{ $row['pass_rate'] >= 80 ? 'bg-green-100 text-green-800' : ($row['pass_rate'] >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $row['pass_rate'] }}%
                        </span>
                    </td>
                    <td class="px-5 py-3 w-32">
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden flex">
                            <div class="bg-green-500 h-2" style="width: {{ $row['total'] > 0 ? round($row['passed'] / $row['total'] * 100) : 0 }}%"></div>
                            <div class="bg-red-400 h-2" style="width: {{ $row['total'] > 0 ? round($row['failed'] / $row['total'] * 100) : 0 }}%"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Inspections Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
        <h3 class="text-sm font-bold text-gray-700 flex items-center">
            <i class="fas fa-list-alt text-blue-500 mr-2"></i>
            Inspection Records
        </h3>
        <span class="text-xs bg-blue-50 text-blue-700 font-semibold px-3 py-1 rounded-full">
            {{ number_format(count($inspections)) }} records
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plate No</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Vehicle</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Owner</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Result</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">View</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($inspections as $i => $inspection)
                <tr class="hover:bg-blue-50/30 transition">
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $i + 1 }}</td>
                    <td class="px-5 py-3 whitespace-nowrap">
                        <span class="font-bold text-blue-700 text-sm">{{ $inspection->plateno }}</span>
                        <div class="text-xs text-gray-400">{{ $inspection->seriesno }}</div>
                    </td>
                    <td class="px-5 py-3">
                        <div class="font-medium text-gray-800">{{ $inspection->makeofvehicle ?? '—' }}</div>
                        <div class="text-xs text-gray-400">{{ $inspection->model ?? '' }}
                            @if($inspection->vehicletype)
                                <span class="ml-1 inline-flex px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600">{{ $inspection->vehicletype }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-700">{{ Str::limit($inspection->owner ?? '—', 22) }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $inspection->department_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-400 text-xs whitespace-nowrap">
                        {{ $inspection->inspectdate ? \Carbon\Carbon::parse($inspection->inspectdate)->format('H:i') : '—' }}
                    </td>
                    <td class="px-5 py-3 text-center whitespace-nowrap">
                        @if(in_array($inspection->testresult, ['1', 'Y']))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Passed
                            </span>
                        @elseif(in_array($inspection->testresult, ['0', 'N']))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                <i class="fas fa-times mr-1"></i>Failed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('inspections.show', $inspection->id) }}"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-16 text-center">
                        <i class="fas fa-clipboard-list text-5xl text-gray-200 mb-3 block"></i>
                        <p class="text-gray-500 font-medium">No inspections recorded for this date</p>
                        <p class="text-gray-400 text-xs mt-1">Try selecting a different date or department</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
