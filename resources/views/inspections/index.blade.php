@extends('layouts.app')

@section('title', 'Inspections')

@section('content')
<div x-data="inspectionsPage()">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Vehicle Inspections</h1>
                <p class="text-gray-600 mt-1">Manage and track all vehicle inspection records</p>
            </div>
            <a href="{{ route('inspections.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-150 ease-in-out">
                <i class="fas fa-plus mr-2"></i>
                New Inspection
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Records</p>
                    <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['total']) }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Passed</p>
                    <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['passed']) }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Failed</p>
                    <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['failed']) }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">This Page</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $stats['current_page'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="border-b border-gray-200 px-6 py-4">
            <button @click="showFilters = !showFilters" class="flex items-center justify-between w-full text-left">
                <div class="flex items-center">
                    <i class="fas fa-filter text-gray-600 mr-2"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Filters</h3>
                </div>
                <i class="fas fa-chevron-down transition-transform duration-200" :class="{'rotate-180': showFilters}"></i>
            </button>
        </div>
        
        <div x-show="showFilters" x-collapse class="px-6 py-4">
            <form method="GET" action="{{ route('inspections.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Plate Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-car text-gray-400 mr-1"></i>
                            Plate Number
                        </label>
                        <input type="text" name="plate_no"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g. ABC-001"
                               value="{{ request('plate_no') }}">
                    </div>

                    <!-- Chassis Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-barcode text-gray-400 mr-1"></i>
                            Chassis Number
                        </label>
                        <input type="text" name="chassis_no"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Chassis number"
                               value="{{ request('chassis_no') }}">
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building text-gray-400 mr-1"></i>
                            Department
                        </label>
                        <select name="department"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- From Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-gray-400 mr-1"></i>
                            From Date
                        </label>
                        <input type="date" name="date_from"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ request('date_from') }}">
                    </div>

                    <!-- To Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-gray-400 mr-1"></i>
                            To Date
                        </label>
                        <input type="date" name="date_to"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ request('date_to') }}">
                    </div>

                    <!-- Test Result -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-check-double text-gray-400 mr-1"></i>
                            Test Result
                        </label>
                        <select name="test_result"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Results</option>
                            <option value="Y" {{ request('test_result') === 'Y' ? 'selected' : '' }}>Passed</option>
                            <option value="N" {{ request('test_result') === 'N' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters Display -->
                @if(request()->hasAny(['plate_no', 'chassis_no', 'date_from', 'date_to', 'department', 'test_result']))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center flex-wrap gap-2">
                        <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                        @if(request('plate_no'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Plate: {{ request('plate_no') }}
                                <a href="{{ route('inspections.index', request()->except('plate_no')) }}" class="ml-2 hover:text-blue-900">×</a>
                            </span>
                        @endif
                        @if(request('chassis_no'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Chassis: {{ request('chassis_no') }}
                                <a href="{{ route('inspections.index', request()->except('chassis_no')) }}" class="ml-2 hover:text-blue-900">×</a>
                            </span>
                        @endif
                        @if(request('date_from'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                From: {{ request('date_from') }}
                                <a href="{{ route('inspections.index', request()->except('date_from')) }}" class="ml-2 hover:text-green-900">×</a>
                            </span>
                        @endif
                        @if(request('date_to'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                To: {{ request('date_to') }}
                                <a href="{{ route('inspections.index', request()->except('date_to')) }}" class="ml-2 hover:text-green-900">×</a>
                            </span>
                        @endif
                        @if(request('department'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Dept: {{ $departments->firstWhere('id', request('department'))->title ?? request('department') }}
                                <a href="{{ route('inspections.index', request()->except('department')) }}" class="ml-2 hover:text-yellow-900">×</a>
                            </span>
                        @endif
                        @if(request('test_result'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ request('test_result') == 'Y' ? 'green' : 'red' }}-100 text-{{ request('test_result') == 'Y' ? 'green' : 'red' }}-800">
                                Result: {{ request('test_result') == 'Y' ? 'Passed' : 'Failed' }}
                                <a href="{{ route('inspections.index', request()->except('test_result')) }}" class="ml-2">×</a>
                            </span>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Filter Actions -->
                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150">
                        <i class="fas fa-search mr-2"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('inspections.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition duration-150">
                        <i class="fas fa-redo mr-2"></i>
                        Clear All
                    </a>
                    <div class="ml-auto text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ number_format($stats['total']) }} results found
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Inspections Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-list text-gray-600 mr-2"></i>
                    Inspection Records
                </h3>
                <span class="text-sm text-gray-600">
                    Showing {{ number_format($stats['total']) }} results
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Series No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Plate No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Vehicle Info
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Chassis No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Owner
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Register
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Inspect Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Result
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Department
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($inspections as $inspection)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-900">{{ $inspection->seriesno }}</div>
                                        <div class="text-xs text-gray-500">Times: {{ $inspection->inspecttimes ?? 1 }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                                        <i class="fas fa-car text-gray-500 mr-2 text-xs"></i>
                                        {{ $inspection->plateno }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">{{ $inspection->makeofvehicle ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $inspection->model ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-gray-600">{{ $inspection->chassisno ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($inspection->owner ?? '—', 25) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700">{{ $inspection->register ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($inspection->inspectdate)
                                        {{ \Carbon\Carbon::parse($inspection->inspectdate)->format('M d, Y') }}
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    @if($inspection->inspectdate)
                                        {{ \Carbon\Carbon::parse($inspection->inspectdate)->format('h:i A') }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(in_array($inspection->testresult, ['1', 'Y']))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Passed
                                    </span>
                                @elseif(in_array($inspection->testresult, ['0', 'N']))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $inspection->department_name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('inspections.show', $inspection->id) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition duration-150" 
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('inspections.edit', $inspection->id) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition duration-150" 
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    @if(in_array($inspection->testresult, ['1', 'Y']))
                                    <a href="{{ route('inspections.certificate', $inspection->id) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition duration-150" 
                                       title="Certificate">
                                        <i class="fas fa-certificate text-sm"></i>
                                    </a>
                                    @endif
                                    <button @click="deleteInspection({{ $inspection->id }})" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition duration-150" 
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No inspection records found</h3>
                                    <p class="text-gray-500">Try adjusting your filters or create a new inspection</p>
                                    <a href="{{ route('inspections.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150">
                                        <i class="fas fa-plus mr-2"></i>
                                        Create New Inspection
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($inspections->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $inspections->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function inspectionsPage() {
    return {
        showFilters: true,
        
        deleteInspection(id) {
            if (confirm('Are you sure you want to delete this inspection record? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/inspections/${id}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    }
}
</script>
@endpush
@endsection
