@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')
<div x-data="vehiclesPage()">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Vehicle Registry</h1>
                <p class="text-gray-600 mt-1">Manage and track all registered vehicles</p>
            </div>
            <a href="{{ route('vehicles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-150 ease-in-out">
                <i class="fas fa-plus mr-2"></i>
                Register Vehicle
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Vehicles</p>
                    <h3 class="text-3xl font-bold mt-1">{{ number_format($vehicles->total()) }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-car text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Active</p>
                    <h3 class="text-3xl font-bold mt-1">{{ number_format($vehicles->total()) }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Vehicle Types</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $vehicleTypes->count() }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-layer-group text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">This Page</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $vehicles->count() }}</h3>
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
                <div class="flex items-center gap-3">
                    <i class="fas fa-filter text-gray-600"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Search / Filter</h3>
                    @if(request()->anyFilled(['plate_no','owner','chassis_no','department']))
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                        {{ collect(['plate_no','owner','chassis_no','department'])->filter(fn($k) => request()->filled($k))->count() }} active
                    </span>
                    @endif
                </div>
                <i class="fas fa-chevron-down transition-transform duration-200" :class="{'rotate-180': showFilters}"></i>
            </button>
        </div>
        
        <div x-show="showFilters" x-collapse class="px-6 py-4">
            <form method="GET" action="{{ route('vehicles.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Plate Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card text-gray-400 mr-1"></i>
                            Plate Number
                        </label>
                        <input type="text" name="plate_no"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g. ABC-001"
                               value="{{ request('plate_no') }}">
                    </div>

                    <!-- Owner -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-gray-400 mr-1"></i>
                            Owner Name
                        </label>
                        <input type="text" name="owner"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Owner name"
                               value="{{ request('owner') }}">
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
                        @include('partials.dept-select', [
                            'name'     => 'department',
                            'selected' => request('department'),
                            'class'    => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dept-select-searchable',
                        ])
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150">
                        <i class="fas fa-search mr-2"></i>
                        Search
                    </button>
                    <a href="{{ route('vehicles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition duration-150">
                        <i class="fas fa-redo mr-2"></i>
                        Reset
                    </a>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition duration-150 ml-auto">
                        <i class="fas fa-download mr-2"></i>
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Vehicles Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-list text-gray-600 mr-2"></i>
                    Registered Vehicles
                </h3>
                <span class="text-sm text-gray-600">
                    Showing {{ $vehicles->firstItem() ?? 0 }} to {{ $vehicles->lastItem() ?? 0 }} of {{ number_format($vehicles->total()) }} results
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Plate Number
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Vehicle Details
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Owner
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Engine/Chassis
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Upload Location
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-car text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-bold text-gray-900">{{ $vehicle->plateno }}</div>
                                        <div class="text-xs text-gray-500">{{ $vehicle->licencetype ?? 'N/A' }} License</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $vehicle->makeofvehicle ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $vehicle->model ?? 'Model N/A' }}</div>
                                <div class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-weight text-gray-400 mr-1"></i>
                                    {{ $vehicle->netweight ? number_format($vehicle->netweight) . ' kg' : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">{{ Str::limit($vehicle->owner, 30) }}</div>
                                @if($vehicle->phoneno)
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    {{ $vehicle->phoneno }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-600">
                                    <div class="mb-1">
                                        <span class="font-medium">Engine:</span> {{ $vehicle->engineno ?? 'N/A' }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Chassis:</span> {{ $vehicle->chassisno ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                    <i class="fas fa-tag mr-1"></i>
                                    Type {{ $vehicle->vehicletype }}
                                </span>
                                @if($vehicle->fueltype)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                        @if($vehicle->fueltype == 'P')
                                            <i class="fas fa-gas-pump mr-1"></i> Petrol
                                        @elseif($vehicle->fueltype == 'D')
                                            <i class="fas fa-oil-can mr-1"></i> Diesel
                                        @elseif($vehicle->fueltype == 'E')
                                            <i class="fas fa-bolt mr-1"></i> Electric
                                        @else
                                            {{ $vehicle->fueltype }}
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $vehicle->dept_name ?? 'N/A' }}
                                </div>
                                @if($vehicle->createDate)
                                <div class="text-xs text-gray-500 mt-1">
                                    Added: {{ \Carbon\Carbon::parse($vehicle->createDate)->format('M d, Y') }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($vehicle->id)
                                    <a href="{{ route('vehicles.show', $vehicle->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition duration-150"
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('vehicles.edit', $vehicle->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition duration-150"
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <a href="{{ route('vehicles.history', $vehicle->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition duration-150"
                                       title="Inspection History">
                                        <i class="fas fa-history text-sm"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('inspections.create', ['plateno' => $vehicle->plateno]) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200 transition duration-150"
                                       title="New Inspection">
                                        <i class="fas fa-clipboard-check text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-car text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No vehicles found</h3>
                                    <p class="text-gray-500">Try adjusting your filters or register a new vehicle</p>
                                    <a href="{{ route('vehicles.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150">
                                        <i class="fas fa-plus mr-2"></i>
                                        Register New Vehicle
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($vehicles->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $vehicles->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function vehiclesPage() {
    return {
        showFilters: true
    }
}
</script>
@endpush
@endsection
