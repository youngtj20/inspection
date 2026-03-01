@extends('layouts.app')

@section('title', 'Department Report')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Department Report</h1>
            <p class="text-gray-600">Performance metrics and statistics by department</p>
        </div>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Reports
        </a>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" action="{{ route('reports.department') }}" class="flex flex-wrap items-end gap-4">
        <div style="min-width:200px;">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Department</label>
            @include('partials.dept-select', [
                'name'     => 'department',
                'selected' => request('department'),
                'class'    => 'px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent',
            ])
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">From Date</label>
            <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}"
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">To Date</label>
            <input type="date" name="date_to" value="{{ $dateTo ?? '' }}"
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-filter"></i>Generate Report
            </button>
            @if(!empty($deptId))
            <button type="submit" name="format" value="pdf"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-file-pdf"></i>PDF
            </button>
            @endif
        </div>
    </form>
</div>

@if(!empty($deptId) && isset($stats))

{{-- ── Header banner ── --}}
<div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            @if($department)
                <h2 class="text-2xl font-bold">{{ $department->title }}</h2>
                @if(count($deptBreakdown) > 1)
                <p class="text-blue-100 mt-1 text-sm">
                    {{ collect($deptBreakdown)->pluck('title')->implode(' · ') }}
                </p>
                @endif
            @endif
            <p class="text-blue-100 mt-1">
                Report Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
            </p>
        </div>
        <div class="bg-white bg-opacity-20 rounded-full p-4">
            <i class="fas fa-building text-4xl"></i>
        </div>
    </div>
</div>

{{-- ── Combined stats cards ── --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-md p-4 border-t-4 border-blue-500">
        <p class="text-gray-500 text-xs font-medium uppercase">Total Inspections</p>
        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total']) }}</h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-4 border-t-4 border-green-500">
        <p class="text-gray-500 text-xs font-medium uppercase">Passed</p>
        <h3 class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['passed']) }}</h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-4 border-t-4 border-red-500">
        <p class="text-gray-500 text-xs font-medium uppercase">Failed</p>
        <h3 class="text-2xl font-bold text-red-600 mt-1">{{ number_format($stats['failed']) }}</h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-4 border-t-4 border-yellow-500">
        <p class="text-gray-500 text-xs font-medium uppercase">Pending</p>
        <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($stats['pending']) }}</h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-4 border-t-4 border-purple-500">
        <p class="text-gray-500 text-xs font-medium uppercase">Pass Rate</p>
        <h3 class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['pass_rate'] }}%</h3>
    </div>
</div>

{{-- ── Per-department breakdown table ── --}}
@if(count($deptBreakdown) > 1)
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <i class="fas fa-table mr-2"></i>Per-Department Breakdown
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Department</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Passed</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Failed</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Pending</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Pass Rate</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Equipment</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Personnel</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Active Users</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($deptBreakdown as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $row['title'] }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 text-right">{{ number_format($row['total']) }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-green-700 text-right">{{ number_format($row['passed']) }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-red-700 text-right">{{ number_format($row['failed']) }}</td>
                    <td class="px-4 py-3 text-sm text-yellow-700 text-right">{{ number_format($row['pending']) }}</td>
                    <td class="px-4 py-3 text-right">
                        @php $rate = $row['pass_rate']; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                            {{ $rate >= 80 ? 'bg-green-100 text-green-800' : ($rate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $rate }}%
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 text-right">{{ number_format($row['equipment']) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 text-right">{{ number_format($row['personnel']) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 text-right">{{ number_format($row['active_users']) }}</td>
                </tr>
                @endforeach
                {{-- Totals row --}}
                <tr class="bg-gray-100 font-semibold border-t-2 border-gray-300">
                    <td class="px-4 py-3 text-sm text-gray-900">Total</td>
                    <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ number_format($stats['total']) }}</td>
                    <td class="px-4 py-3 text-sm text-green-700 text-right">{{ number_format($stats['passed']) }}</td>
                    <td class="px-4 py-3 text-sm text-red-700 text-right">{{ number_format($stats['failed']) }}</td>
                    <td class="px-4 py-3 text-sm text-yellow-700 text-right">{{ number_format($stats['pending']) }}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                            {{ $stats['pass_rate'] >= 80 ? 'bg-green-100 text-green-800' : ($stats['pass_rate'] >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $stats['pass_rate'] }}%
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ number_format(collect($deptBreakdown)->sum('equipment')) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ number_format(collect($deptBreakdown)->sum('personnel')) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ number_format(collect($deptBreakdown)->sum('active_users')) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ── Single-dept detail cards (when only one selected) ── --}}
@if(count($deptBreakdown) === 1)
@php $row = $deptBreakdown[0]; @endphp
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
            Performance Summary
        </h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Pass Rate</span>
                    <span class="font-medium">{{ $row['pass_rate'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full" style="width: {{ $row['pass_rate'] }}%"></div>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Active Users</span>
                    <span class="font-bold text-blue-600">{{ $row['active_users'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            Department Details
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Department Name</span>
                <span class="font-medium">{{ $row['title'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Status</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total Equipment</span>
                <span class="font-medium">{{ $row['equipment'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total Personnel</span>
                <span class="font-medium">{{ $row['personnel'] }}</span>
            </div>
        </div>
    </div>
</div>
@endif

@else
{{-- ── No selection prompt ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
    <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
    <h3 class="text-xl font-semibold text-gray-800 mb-2">Select a Department</h3>
    <p class="text-gray-500 text-sm">Choose a department or state from the dropdown above, then click Generate Report.<br>
        Selecting a state will show all its centers combined.</p>
</div>
@endif
@endsection

