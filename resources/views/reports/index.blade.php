@extends('layouts.app')

@section('title', 'Reports')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Reports & Analytics</h1>
    <p class="text-gray-500 mt-1">Generate, view and export vehicle inspection reports</p>
</div>

{{-- Live Summary Strip --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-5 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-xs font-semibold uppercase tracking-wider">Today — Total</p>
                <h3 class="text-3xl font-extrabold mt-1">{{ number_format($todayStats['total']) }}</h3>
                <p class="text-blue-200 text-xs mt-1">{{ \Carbon\Carbon::parse($today)->format('M d, Y') }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-clipboard-check text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-md p-5 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-xs font-semibold uppercase tracking-wider">Today — Passed</p>
                <h3 class="text-3xl font-extrabold mt-1">{{ number_format($todayStats['passed']) }}</h3>
                @php $todayComp = $todayStats['passed'] + $todayStats['failed']; @endphp
                <p class="text-green-200 text-xs mt-1">
                    {{ $todayComp > 0 ? round($todayStats['passed'] / $todayComp * 100, 1) : 0 }}% pass rate
                </p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-md p-5 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-xs font-semibold uppercase tracking-wider">Today — Failed</p>
                <h3 class="text-3xl font-extrabold mt-1">{{ number_format($todayStats['failed']) }}</h3>
                <p class="text-red-200 text-xs mt-1">
                    {{ $todayComp > 0 ? round($todayStats['failed'] / $todayComp * 100, 1) : 0 }}% fail rate
                </p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-times-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md p-5 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-xs font-semibold uppercase tracking-wider">This Month — Total</p>
                <h3 class="text-3xl font-extrabold mt-1">{{ number_format($monthStats['total']) }}</h3>
                <p class="text-purple-200 text-xs mt-1">{{ $monthStats['pass_rate'] }}% pass rate</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-calendar-alt text-2xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Month pass-rate bar --}}
@if($monthStats['total'] > 0)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-8">
    <div class="flex items-center justify-between mb-3">
        <span class="text-sm font-semibold text-gray-700">
            <i class="fas fa-chart-bar text-indigo-500 mr-1"></i>
            This Month — Pass / Fail Breakdown
        </span>
        <span class="text-xs text-gray-400">{{ number_format($monthStats['passed']) }} passed &nbsp;·&nbsp; {{ number_format($monthStats['failed']) }} failed &nbsp;·&nbsp; {{ number_format($monthStats['total'] - $monthStats['passed'] - $monthStats['failed']) }} pending</span>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden flex">
        @php
            $pct   = $monthStats['pass_rate'];
            $fPct  = $monthStats['total'] > 0 ? round($monthStats['failed'] / $monthStats['total'] * 100, 1) : 0;
        @endphp
        <div class="bg-green-500 h-4 transition-all duration-500" style="width: {{ $pct }}%" title="Passed: {{ $pct }}%"></div>
        <div class="bg-red-400 h-4 transition-all duration-500" style="width: {{ $fPct }}%" title="Failed: {{ $fPct }}%"></div>
        <div class="bg-gray-300 h-4 flex-1" title="Pending"></div>
    </div>
    <div class="flex gap-4 mt-2">
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-1"></span>Passed ({{ $pct }}%)</span>
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-red-400 mr-1"></span>Failed ({{ $fPct }}%)</span>
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-gray-300 mr-1"></span>Pending</span>
    </div>
</div>
@endif

{{-- Report Type Cards --}}
<h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Report Types</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    {{-- Daily --}}
    <a href="{{ route('reports.daily') }}"
       class="group bg-white rounded-xl shadow-md hover:shadow-xl border border-gray-100 hover:border-blue-300 transition-all duration-300 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-500 transition-colors duration-300">
                    <i class="fas fa-calendar-day text-blue-600 text-xl group-hover:text-white transition-colors duration-300"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-gray-800">Daily Report</h3>
                    <p class="text-xs text-blue-500 font-medium">Day-by-day analysis</p>
                </div>
            </div>
            <p class="text-gray-500 text-sm leading-relaxed">
                View all inspections for a specific date. Filter by department, download as PDF, and track daily performance metrics.
            </p>
            <div class="mt-5 flex items-center justify-between">
                <span class="text-xs text-gray-400">
                    <i class="fas fa-clock mr-1"></i>Today: {{ number_format($todayStats['total']) }} inspections
                </span>
                <span class="flex items-center text-blue-600 text-sm font-semibold group-hover:translate-x-1 transition-transform">
                    View <i class="fas fa-arrow-right ml-2"></i>
                </span>
            </div>
        </div>
    </a>

    {{-- Monthly --}}
    <a href="{{ route('reports.monthly') }}"
       class="group bg-white rounded-xl shadow-md hover:shadow-xl border border-gray-100 hover:border-green-300 transition-all duration-300 overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-500 transition-colors duration-300">
                    <i class="fas fa-calendar-alt text-green-600 text-xl group-hover:text-white transition-colors duration-300"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-gray-800">Monthly Report</h3>
                    <p class="text-xs text-green-500 font-medium">Monthly statistics</p>
                </div>
            </div>
            <p class="text-gray-500 text-sm leading-relaxed">
                Comprehensive monthly statistics with department breakdown, inspector performance, vehicle type analysis, and export options.
            </p>
            <div class="mt-5 flex items-center justify-between">
                <span class="text-xs text-gray-400">
                    <i class="fas fa-chart-line mr-1"></i>This month: {{ number_format($monthStats['total']) }} inspections
                </span>
                <span class="flex items-center text-green-600 text-sm font-semibold group-hover:translate-x-1 transition-transform">
                    View <i class="fas fa-arrow-right ml-2"></i>
                </span>
            </div>
        </div>
    </a>

    {{-- Department --}}
    <a href="{{ route('reports.department') }}"
       class="group bg-white rounded-xl shadow-md hover:shadow-xl border border-gray-100 hover:border-purple-300 transition-all duration-300 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-500 transition-colors duration-300">
                    <i class="fas fa-building text-purple-600 text-xl group-hover:text-white transition-colors duration-300"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-gray-800">Department Report</h3>
                    <p class="text-xs text-purple-500 font-medium">Multi-department comparison</p>
                </div>
            </div>
            <p class="text-gray-500 text-sm leading-relaxed">
                Compare performance across one or more departments. View equipment, personnel, pass rates and export combined PDF reports.
            </p>
            <div class="mt-5 flex items-center justify-between">
                <span class="text-xs text-gray-400">
                    <i class="fas fa-building mr-1"></i>{{ $deptCount }} active departments
                </span>
                <span class="flex items-center text-purple-600 text-sm font-semibold group-hover:translate-x-1 transition-transform">
                    View <i class="fas fa-arrow-right ml-2"></i>
                </span>
            </div>
        </div>
    </a>

</div>

{{-- Quick Actions --}}
<h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Quick Actions</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <a href="{{ route('reports.daily', ['format' => 'pdf', 'date' => $today]) }}"
       class="group flex items-center gap-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-red-200 hover:shadow-md p-4 transition-all duration-200">
        <div class="w-11 h-11 rounded-lg bg-red-50 group-hover:bg-red-100 flex items-center justify-center flex-shrink-0 transition-colors">
            <i class="fas fa-file-pdf text-red-500 text-xl"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Today's PDF</p>
            <p class="text-xs text-gray-400">Download today's report</p>
        </div>
    </a>

    <a href="{{ route('reports.export', ['type' => 'inspections']) }}"
       class="group flex items-center gap-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-green-200 hover:shadow-md p-4 transition-all duration-200">
        <div class="w-11 h-11 rounded-lg bg-green-50 group-hover:bg-green-100 flex items-center justify-center flex-shrink-0 transition-colors">
            <i class="fas fa-file-csv text-green-600 text-xl"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Export Inspections</p>
            <p class="text-xs text-gray-400">Download as CSV (this month)</p>
        </div>
    </a>

    <a href="{{ route('reports.export', ['type' => 'vehicles']) }}"
       class="group flex items-center gap-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-green-200 hover:shadow-md p-4 transition-all duration-200">
        <div class="w-11 h-11 rounded-lg bg-teal-50 group-hover:bg-teal-100 flex items-center justify-center flex-shrink-0 transition-colors">
            <i class="fas fa-car text-teal-600 text-xl"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Export Vehicles</p>
            <p class="text-xs text-gray-400">Download vehicle list as CSV</p>
        </div>
    </a>

    <a href="{{ route('vehicles.index') }}"
       class="group flex items-center gap-4 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md p-4 transition-all duration-200">
        <div class="w-11 h-11 rounded-lg bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center flex-shrink-0 transition-colors">
            <i class="fas fa-history text-blue-500 text-xl"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Vehicle History</p>
            <p class="text-xs text-gray-400">Search inspection records</p>
        </div>
    </a>

</div>

@endsection
