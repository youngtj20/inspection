@extends('layouts.app')

@section('title', 'Monthly Report')

@section('content')

{{-- Header --}}
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Monthly Inspection Report</h1>
        <p class="text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</p>
    </div>
    <a href="{{ route('reports.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
        <i class="fas fa-arrow-left mr-2 text-sm"></i>Back to Reports
    </a>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" action="{{ route('reports.monthly') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Month</label>
            <input type="month" name="month" value="{{ $month }}"
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
        <div class="flex flex-wrap gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-filter"></i>Filter
            </button>
            <a href="{{ route('reports.monthly', ['month' => $month, 'department' => request('department'), 'format' => 'pdf']) }}"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-file-pdf"></i>Summary PDF
            </a>
            <a href="{{ route('reports.monthly', ['month' => $month, 'department' => request('department'), 'format' => 'details-pdf']) }}"
               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-file-pdf"></i>Details PDF
            </a>
            <a href="{{ route('reports.monthly', ['month' => $month, 'department' => request('department'), 'format' => 'excel']) }}"
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-file-csv"></i>Export CSV
            </a>
        </div>
    </form>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-blue-100 text-xs font-semibold uppercase tracking-wider">Total</p>
        <h3 class="text-4xl font-extrabold mt-1">{{ number_format($stats['total']) }}</h3>
        <p class="text-blue-200 text-xs mt-2">inspections</p>
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
        <span class="text-sm font-semibold text-gray-700">Monthly Result Distribution</span>
        <span class="text-xs text-gray-400">{{ $stats['passed'] }} passed · {{ $stats['failed'] }} failed · {{ $stats['pending'] }} pending</span>
    </div>
    @php
        $pp = $stats['total'] > 0 ? round($stats['passed'] / $stats['total'] * 100, 1) : 0;
        $fp = $stats['total'] > 0 ? round($stats['failed'] / $stats['total'] * 100, 1) : 0;
    @endphp
    <div class="w-full bg-gray-100 rounded-full h-5 overflow-hidden flex">
        <div class="bg-green-500 h-5 flex items-center justify-center text-white text-xs font-bold"
             style="width: {{ $pp }}%">{{ $pp > 8 ? $pp . '%' : '' }}</div>
        <div class="bg-red-400 h-5 flex items-center justify-center text-white text-xs font-bold"
             style="width: {{ $fp }}%">{{ $fp > 8 ? $fp . '%' : '' }}</div>
        <div class="bg-gray-200 h-5 flex-1"></div>
    </div>
    <div class="flex gap-5 mt-2">
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-1.5"></span>Passed ({{ $pp }}%)</span>
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-red-400 mr-1.5"></span>Failed ({{ $fp }}%)</span>
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-gray-200 mr-1.5"></span>Pending</span>
    </div>
</div>
@endif

{{-- Two-column: Department Stats + Vehicle Type Stats --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">

    {{-- Department Breakdown --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                <i class="fas fa-building text-indigo-500 mr-2"></i>By Department
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Department</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Pass</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Fail</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Rate</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase w-24">Bar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($departmentStats as $dept)
                    @php
                        $comp = $dept->passed + $dept->failed;
                        $rate = $comp > 0 ? round($dept->passed / $comp * 100, 1) : 0;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2.5 font-medium text-gray-800">{{ $dept->department_name ?? 'Unassigned' }}</td>
                        <td class="px-4 py-2.5 text-right text-gray-600">{{ number_format($dept->total) }}</td>
                        <td class="px-4 py-2.5 text-right font-semibold text-green-700">{{ number_format($dept->passed) }}</td>
                        <td class="px-4 py-2.5 text-right font-semibold text-red-700">{{ number_format($dept->failed) }}</td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold
                                {{ $rate >= 80 ? 'bg-green-100 text-green-800' : ($rate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $rate }}%
                            </span>
                        </td>
                        <td class="px-4 py-2.5">
                            <div class="w-full bg-gray-100 rounded-full h-2 flex overflow-hidden">
                                <div class="bg-green-500 h-2" style="width: {{ $dept->total > 0 ? round($dept->passed / $dept->total * 100) : 0 }}%"></div>
                                <div class="bg-red-400 h-2" style="width: {{ $dept->total > 0 ? round($dept->failed / $dept->total * 100) : 0 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-xs">No department data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Vehicle Type Breakdown --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                <i class="fas fa-car text-blue-500 mr-2"></i>By Vehicle Type
            </h3>
        </div>
        @if($vehicleTypeStats->count())
        <div class="divide-y divide-gray-50">
            @foreach($vehicleTypeStats as $type => $vt)
            @php
                $vComp = $vt['passed'] + $vt['failed'];
                $vRate = $vComp > 0 ? round($vt['passed'] / $vComp * 100, 1) : 0;
                $vPct  = $vt['total'] > 0 ? round($vt['passed'] / $vt['total'] * 100) : 0;
                $vFPct = $vt['total'] > 0 ? round($vt['failed'] / $vt['total'] * 100) : 0;
            @endphp
            <div class="px-5 py-3 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-semibold text-gray-800">{{ $type ?: 'Unspecified' }}</span>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="text-gray-500">{{ number_format($vt['total']) }} total</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-bold
                            {{ $vRate >= 80 ? 'bg-green-100 text-green-800' : ($vRate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $vRate }}%
                        </span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden flex">
                    <div class="bg-green-500 h-2.5" style="width: {{ $vPct }}%"></div>
                    <div class="bg-red-400 h-2.5" style="width: {{ $vFPct }}%"></div>
                    <div class="bg-gray-200 h-2.5 flex-1"></div>
                </div>
                <div class="flex gap-4 mt-1 text-xs text-gray-400">
                    <span class="text-green-700 font-medium">{{ number_format($vt['passed']) }} passed</span>
                    <span class="text-red-700 font-medium">{{ number_format($vt['failed']) }} failed</span>
                    @if($vt['total'] - $vt['passed'] - $vt['failed'] > 0)
                    <span class="text-yellow-700">{{ number_format($vt['total'] - $vt['passed'] - $vt['failed']) }} pending</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-5 py-8 text-center text-gray-400 text-xs">No vehicle type data</div>
        @endif
    </div>

</div>

{{-- Inspector Performance --}}
@if($inspectorStats->count())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-700 flex items-center">
            <i class="fas fa-user-check text-teal-500 mr-2"></i>Inspector Performance
        </h3>
        <span class="text-xs text-gray-400">{{ $inspectorStats->count() }} inspector(s)</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Inspector</th>
                    <th class="px-5 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-5 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Passed</th>
                    <th class="px-5 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Failed</th>
                    <th class="px-5 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Pass Rate</th>
                    <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase w-32">Distribution</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($inspectorStats->sortByDesc('total') as $inspector => $ins)
                @php
                    $iComp = $ins['passed'] + $ins['failed'];
                    $iRate = $iComp > 0 ? round($ins['passed'] / $iComp * 100, 1) : 0;
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-2.5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-teal-600 text-xs"></i>
                            </div>
                            <span class="font-medium text-gray-800">{{ $inspector }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-2.5 text-right text-gray-600">{{ number_format($ins['total']) }}</td>
                    <td class="px-5 py-2.5 text-right font-semibold text-green-700">{{ number_format($ins['passed']) }}</td>
                    <td class="px-5 py-2.5 text-right font-semibold text-red-700">{{ number_format($ins['failed']) }}</td>
                    <td class="px-5 py-2.5 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold
                            {{ $iRate >= 80 ? 'bg-green-100 text-green-800' : ($iRate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $iRate }}%
                        </span>
                    </td>
                    <td class="px-5 py-2.5">
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden flex">
                            <div class="bg-green-500 h-2" style="width: {{ $ins['total'] > 0 ? round($ins['passed'] / $ins['total'] * 100) : 0 }}%"></div>
                            <div class="bg-red-400 h-2" style="width: {{ $ins['total'] > 0 ? round($ins['failed'] / $ins['total'] * 100) : 0 }}%"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Daily Activity + Inspection Type side by side --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Daily Activity --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                <i class="fas fa-calendar-check text-orange-500 mr-2"></i>Daily Activity
            </h3>
            <span class="text-xs text-gray-400">{{ count($dailyStats) }} working day(s)</span>
        </div>
        @if(count($dailyStats))
        <div class="overflow-x-auto max-h-72 overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 sticky top-0">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Pass</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Fail</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Bar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($dailyStats as $day => $d)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2 font-medium text-gray-700">
                            {{ $day === 'Unknown' ? '—' : \Carbon\Carbon::parse($day)->format('d M') }}
                            @if($day !== 'Unknown')
                            <span class="text-xs text-gray-400 ml-1">{{ \Carbon\Carbon::parse($day)->format('D') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right text-gray-600">{{ number_format($d['total']) }}</td>
                        <td class="px-4 py-2 text-right text-green-700 font-semibold">{{ number_format($d['passed']) }}</td>
                        <td class="px-4 py-2 text-right text-red-700 font-semibold">{{ number_format($d['failed']) }}</td>
                        <td class="px-4 py-2 w-24">
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden flex">
                                <div class="bg-green-500 h-2" style="width: {{ $d['total'] > 0 ? round($d['passed'] / $d['total'] * 100) : 0 }}%"></div>
                                <div class="bg-red-400 h-2" style="width: {{ $d['total'] > 0 ? round($d['failed'] / $d['total'] * 100) : 0 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-5 py-8 text-center text-gray-400 text-xs">No daily data</div>
        @endif
    </div>

    {{-- Inspection Type --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                <i class="fas fa-tags text-pink-500 mr-2"></i>By Inspection Type
            </h3>
        </div>
        @if($inspectionTypeStats->count())
        <div class="divide-y divide-gray-50">
            @foreach($inspectionTypeStats->sortByDesc('total') as $type => $it)
            @php
                $iComp = $it['passed'] + $it['failed'];
                $iRate = $iComp > 0 ? round($it['passed'] / $iComp * 100, 1) : 0;
            @endphp
            <div class="px-5 py-3 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-semibold text-gray-800">{{ $type ?: 'Unspecified' }}</span>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-gray-400">{{ number_format($it['total']) }}</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-bold
                            {{ $iRate >= 80 ? 'bg-green-100 text-green-800' : ($iRate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $iRate }}%
                        </span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden flex">
                    <div class="bg-green-500 h-2.5" style="width: {{ $it['total'] > 0 ? round($it['passed'] / $it['total'] * 100) : 0 }}%"></div>
                    <div class="bg-red-400 h-2.5" style="width: {{ $it['total'] > 0 ? round($it['failed'] / $it['total'] * 100) : 0 }}%"></div>
                    <div class="bg-gray-200 h-2.5 flex-1"></div>
                </div>
                <div class="flex gap-4 mt-1 text-xs">
                    <span class="text-green-700 font-medium">{{ number_format($it['passed']) }} passed</span>
                    <span class="text-red-700 font-medium">{{ number_format($it['failed']) }} failed</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-5 py-8 text-center text-gray-400 text-xs">No inspection type data</div>
        @endif
    </div>

</div>

@endsection
