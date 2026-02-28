@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div x-data="dashboard()" x-init="init()" x-cloak>
    <!-- Loading Overlay -->
    <div x-show="loading" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 font-medium">Loading dashboard...</span>
        </div>
    </div>
    
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ auth()->user()->nickname }}</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Inspections -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Inspections</p>
                    <h3 class="text-3xl font-bold text-gray-800" x-text="stats.total_inspections || '0'"></h3>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-arrow-up"></i>
                        <span x-text="stats.month_inspections || '0'"></span> this month
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Passed Inspections -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Passed</p>
                    <h3 class="text-3xl font-bold text-green-600" x-text="stats.passed_inspections || '0'"></h3>
                    <p class="text-sm text-gray-600 mt-2">
                        <span x-text="stats.pass_rate || '0'"></span>% pass rate
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Failed Inspections -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Failed</p>
                    <h3 class="text-3xl font-bold text-red-600" x-text="stats.failed_inspections || '0'"></h3>
                    <p class="text-sm text-gray-600 mt-2">
                        Requires attention
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-2xl text-red-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Today's Inspections -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Today</p>
                    <h3 class="text-3xl font-bold text-purple-600" x-text="stats.today_inspections || '0'"></h3>
                    <p class="text-sm text-gray-600 mt-2">
                        <span x-text="stats.pending_inspections || '0'"></span> pending
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-day text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Inspection Trends -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Inspection Trends</h3>
                <select x-model="trendsFilter" @change="loadTrends()" class="text-sm border border-gray-300 rounded px-3 py-1">
                    <option value="12">Last 12 Months</option>
                    <option value="6">Last 6 Months</option>
                    <option value="3">Last 3 Months</option>
                </select>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
        
        <!-- Vehicle Type Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Vehicle Type Distribution</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="vehicleTypeChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent Inspections -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Inspections</h3>
                    <a href="{{ route('inspections.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto" style="max-height: 400px;">
                <table class="w-full">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Series No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plate No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentInspections as $inspection)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('inspections.show', $inspection->id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $inspection->seriesno }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $inspection->plateno }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($inspection->inspectdate)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(in_array($inspection->testresult, ['1', 'Y']))
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                    Passed
                                </span>
                                @elseif(in_array($inspection->testresult, ['0', 'N']))
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                    Failed
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                    Pending
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No recent inspections
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Upcoming Inspections -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Vehicles Due for Inspection</h3>
                    <span class="text-sm text-gray-600">{{ count($upcomingInspections) }} vehicles</span>
                </div>
            </div>
            <div class="overflow-x-auto" style="max-height: 400px;">
                <table class="w-full">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plate No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Inspection</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($upcomingInspections as $vehicle)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $vehicle->plateno }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ Str::limit($vehicle->owner, 20) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($vehicle->last_inspection_date)
                                {{ \Carbon\Carbon::parse($vehicle->last_inspection_date)->format('M d, Y') }}
                                <br>
                                <span class="text-xs text-red-600">
                                    ({{ $vehicle->days_since_inspection }} days ago)
                                </span>
                                @else
                                <span class="text-red-600">Never inspected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('inspections.create', ['plateno' => $vehicle->plateno]) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-plus-circle mr-1"></i> Inspect
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No vehicles due for inspection
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('inspections.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <i class="fas fa-plus-circle text-3xl text-blue-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">New Inspection</span>
            </a>
            
            <a href="{{ route('vehicles.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                <i class="fas fa-car text-3xl text-green-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Register Vehicle</span>
            </a>
            
            <a href="{{ route('reports.daily') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition">
                <i class="fas fa-file-pdf text-3xl text-purple-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Daily Report</span>
            </a>
            
            <a href="{{ route('search') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition">
                <i class="fas fa-search text-3xl text-orange-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Search Records</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function dashboard() {
    return {
        stats: @json($stats),
        trendsFilter: '12',
        trendsChart: null,
        vehicleTypeChart: null,
        loading: false,
        
        init() {
            this.loading = true;
            try {
                this.initTrendsChart();
                this.initVehicleTypeChart();
            } catch (error) {
                console.error('Error initializing dashboard:', error);
            } finally {
                setTimeout(() => this.loading = false, 500);
            }
        },
        
        initTrendsChart() {
            const ctx = document.getElementById('trendsChart');
            if (!ctx) return;
            
            // Destroy existing chart if it exists
            if (this.trendsChart) {
                this.trendsChart.destroy();
            }
            
            const trends = @json($trends);
            
            this.trendsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trends.labels,
                    datasets: [
                        {
                            label: 'Total',
                            data: trends.total,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Passed',
                            data: trends.passed,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Failed',
                            data: trends.failed,
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        },
        
        initVehicleTypeChart() {
            const ctx = document.getElementById('vehicleTypeChart');
            if (!ctx) return;
            
            // Destroy existing chart if it exists
            if (this.vehicleTypeChart) {
                this.vehicleTypeChart.destroy();
            }
            
            fetch('{{ route("dashboard.charts") }}?type=vehicle_types')
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load chart data');
                    return response.json();
                })
                .then(data => {
                    if (data.labels && data.labels.length > 0) {
                        this.vehicleTypeChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: data.labels || [],
                                datasets: [{
                                    data: data.data || [],
                                    backgroundColor: [
                                        'rgb(59, 130, 246)',
                                        'rgb(34, 197, 94)',
                                        'rgb(239, 68, 68)',
                                        'rgb(168, 85, 247)',
                                        'rgb(251, 146, 60)',
                                        'rgb(236, 72, 153)',
                                        'rgb(14, 165, 233)',
                                        'rgb(245, 158, 11)'
                                    ],
                                    borderWidth: 2,
                                    borderColor: 'white'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '60%',
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true,
                                            pointStyle: 'circle'
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        titleColor: 'white',
                                        bodyColor: 'white',
                                        borderColor: 'rgba(0, 0, 0, 0.1)',
                                        borderWidth: 1
                                    }
                                },
                                animation: {
                                    animateRotate: true,
                                    animateScale: true
                                }
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading vehicle type chart:', error);
                });
        },
        
        loadTrends() {
            if (!this.trendsChart) return;
            
            // Reload trends chart with new filter
            fetch(`{{ route("dashboard.charts") }}?type=trends&months=${this.trendsFilter}`)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load trends data');
                    return response.json();
                })
                .then(data => {
                    this.trendsChart.data.labels = data.labels || [];
                    this.trendsChart.data.datasets[0].data = data.total || [];
                    this.trendsChart.data.datasets[1].data = data.passed || [];
                    this.trendsChart.data.datasets[2].data = data.failed || [];
                    this.trendsChart.update();
                })
                .catch(error => {
                    console.error('Error loading trends:', error);
                });
        }
    }
}
</script>
@endpush
