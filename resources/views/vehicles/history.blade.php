@extends('layouts.app')

@section('title', 'Inspection History — {{ $vehicle->plateno }}')

@section('content')

@php
    $total   = $inspections->count();
    $passed  = $inspections->whereIn('testresult', ['1', 'Y'])->count();
    $failed  = $inspections->whereIn('testresult', ['0', 'N'])->count();
    $pending = $total - $passed - $failed;
    $passRate = ($passed + $failed) > 0 ? round($passed / ($passed + $failed) * 100, 1) : 0;
    $firstDate = $inspections->last()?->inspectdate;
    $lastDate  = $inspections->first()?->inspectdate;
@endphp

{{-- ── Page Header ── --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-history text-blue-600 text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Inspection History</h1>
                <p class="text-gray-500 text-sm mt-0.5">
                    {{ $vehicle->plateno }}
                    @if($vehicle->makeofvehicle) · {{ $vehicle->makeofvehicle }} @endif
                    @if($vehicle->model) {{ $vehicle->model }} @endif
                </p>
            </div>
        </div>
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
        <a href="{{ route('vehicles.show', $vehicle->id) }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Vehicle Details
        </a>
        <a href="{{ route('vehicles.index') }}"
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 hover:border-gray-300 text-gray-600 font-medium rounded-lg transition text-sm">
            <i class="fas fa-list mr-2"></i>All Vehicles
        </a>
    </div>
</div>

{{-- ── Summary Cards ── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</p>
        <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ number_format($total) }}</p>
        @if($firstDate && $lastDate && $firstDate !== $lastDate)
        <p class="text-xs text-gray-400 mt-1">
            {{ \Carbon\Carbon::parse($firstDate)->format('M Y') }} –
            {{ \Carbon\Carbon::parse($lastDate)->format('M Y') }}
        </p>
        @endif
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Passed</p>
        <p class="text-3xl font-extrabold text-green-600 mt-1">{{ number_format($passed) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $passRate }}% pass rate</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs font-semibold text-red-500 uppercase tracking-wider">Failed</p>
        <p class="text-3xl font-extrabold text-red-500 mt-1">{{ number_format($failed) }}</p>
        @if($failed > 0 && $total > 0)
        <p class="text-xs text-gray-400 mt-1">{{ round($failed / $total * 100, 1) }}% of total</p>
        @endif
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Pass Rate</p>
        <p class="text-3xl font-extrabold text-purple-600 mt-1">{{ $passRate }}%</p>
        @if($pending > 0)
        <p class="text-xs text-gray-400 mt-1">{{ $pending }} pending</p>
        @endif
    </div>
</div>

{{-- ── Pass/Fail Bar ── --}}
@if($total > 0)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex items-center justify-between mb-2">
        <span class="text-xs font-semibold text-gray-600">Pass / Fail Breakdown</span>
        <span class="text-xs text-gray-400">
            {{ number_format($passed) }} passed &nbsp;·&nbsp;
            {{ number_format($failed) }} failed
            @if($pending > 0) &nbsp;·&nbsp; {{ number_format($pending) }} pending @endif
        </span>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden flex">
        @php
            $pPct = $total > 0 ? round($passed / $total * 100, 1) : 0;
            $fPct = $total > 0 ? round($failed / $total * 100, 1) : 0;
        @endphp
        <div class="bg-green-500 h-3 transition-all duration-500" style="width:{{ $pPct }}%" title="Passed {{ $pPct }}%"></div>
        <div class="bg-red-400 h-3 transition-all duration-500" style="width:{{ $fPct }}%" title="Failed {{ $fPct }}%"></div>
        <div class="bg-gray-300 h-3 flex-1" title="Pending"></div>
    </div>
    <div class="flex gap-4 mt-2">
        <span class="flex items-center text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-1.5 inline-block"></span>Passed ({{ $pPct }}%)</span>
        <span class="flex items-center text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-red-400 mr-1.5 inline-block"></span>Failed ({{ $fPct }}%)</span>
        @if($pending > 0)
        <span class="flex items-center text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-gray-300 mr-1.5 inline-block"></span>Pending</span>
        @endif
    </div>
</div>
@endif

{{-- ── Inspection Timeline Table ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-clipboard-list text-blue-500"></i>
            All Inspections
            <span class="ml-1 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-normal">{{ $total }}</span>
        </h2>
        <a href="{{ route('inspections.create', ['plateno' => $vehicle->plateno]) }}"
           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
            <i class="fas fa-plus mr-1.5"></i>New Inspection
        </a>
    </div>

    @if($inspections->isEmpty())
    <div class="py-20 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-clipboard text-gray-300 text-3xl"></i>
        </div>
        <p class="text-gray-500 font-medium">No inspection records found</p>
        <p class="text-gray-400 text-sm mt-1">This vehicle has not been inspected yet.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-left">Series No</th>
                    <th class="px-5 py-3 text-left">Dept</th>
                    <th class="px-5 py-3 text-left">Inspector</th>
                    <th class="px-5 py-3 text-center">Times</th>
                    <th class="px-5 py-3 text-left">Type</th>
                    <th class="px-5 py-3 text-center">Result</th>
                    <th class="px-5 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($inspections as $i => $ins)
                @php
                    $result = $ins->testresult;
                    $isPassed  = in_array($result, ['1', 'Y']);
                    $isFailed  = in_array($result, ['0', 'N']);
                    $inspDate  = $ins->inspectdate ? \Carbon\Carbon::parse($ins->inspectdate) : null;
                    $typeLabels = ['1'=>'Initial','2'=>'Re-inspect','3'=>'Follow-up'];
                    $typeLabel  = $typeLabels[$ins->inspecttype ?? ''] ?? ($ins->inspecttype ?? '—');
                @endphp
                <tr class="hover:bg-gray-50 transition-colors group {{ $isPassed ? '' : ($isFailed ? 'bg-red-50/30' : '') }}">
                    {{-- Row counter --}}
                    <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">{{ $i + 1 }}</td>

                    {{-- Date --}}
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        @if($inspDate)
                        <div class="font-semibold text-gray-800">{{ $inspDate->format('Y-m-d') }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $inspDate->diffForHumans() }}</div>
                        @else
                        <span class="text-gray-400">—</span>
                        @endif
                    </td>

                    {{-- Series No --}}
                    <td class="px-5 py-3.5">
                        <span class="font-mono text-xs text-gray-700 bg-gray-100 px-2 py-0.5 rounded">
                            {{ $ins->seriesno }}
                        </span>
                    </td>

                    {{-- Department --}}
                    <td class="px-5 py-3.5 text-gray-600 text-xs max-w-[140px] truncate" title="{{ $ins->department_name ?? '' }}">
                        {{ $ins->department_name ?? '—' }}
                    </td>

                    {{-- Inspector --}}
                    <td class="px-5 py-3.5">
                        <div class="text-gray-700">{{ $ins->inspector ?? '—' }}</div>
                        @if($ins->workerline)
                        <div class="text-xs text-gray-400 mt-0.5">Line: {{ $ins->workerline }}</div>
                        @endif
                    </td>

                    {{-- Inspect Times --}}
                    <td class="px-5 py-3.5 text-center">
                        @php $t = (int)($ins->inspecttimes ?? 1); @endphp
                        @if($t > 1)
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-700 text-xs font-bold" title="{{ $t }} inspection(s)">
                            {{ $t }}
                        </span>
                        @else
                        <span class="text-gray-400">1</span>
                        @endif
                    </td>

                    {{-- Inspect Type --}}
                    <td class="px-5 py-3.5">
                        <span class="text-xs text-gray-500">{{ $typeLabel }}</span>
                    </td>

                    {{-- Result Badge --}}
                    <td class="px-5 py-3.5 text-center">
                        @if($isPassed)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            <i class="fas fa-check-circle text-green-500"></i> Passed
                        </span>
                        @elseif($isFailed)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            <i class="fas fa-times-circle text-red-500"></i> Failed
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-5 py-3.5 text-center">
                        <div class="flex items-center justify-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('inspections.show', $ins->seriesno) }}"
                               class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                               title="View Inspection Details">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            @if($isPassed)
                            <a href="{{ route('inspections.certificate', $ins->seriesno) }}"
                               class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 transition"
                               title="Download Certificate">
                                <i class="fas fa-file-pdf text-xs"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Table Footer --}}
    <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
        <span>{{ $total }} record(s) — sorted by most recent first</span>
        @if($lastDate)
        <span>Latest: {{ \Carbon\Carbon::parse($lastDate)->format('M d, Y') }}</span>
        @endif
    </div>
    @endif
</div>

@endsection
