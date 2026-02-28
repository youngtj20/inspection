<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="dashboard()" x-init="init()">
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
        <p class="text-gray-600">Welcome back, <?php echo e(auth()->user()->nickname); ?></p>
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
                        <i class="fas fa-calendar-alt mr-1"></i>
                        <?php echo e($stats['stats_month'] ?? ''); ?>

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
                    <p class="text-sm text-gray-600 mb-1">Passed <span class="text-xs text-gray-400">(This Month)</span></p>
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
                    <p class="text-sm text-gray-600 mb-1">Failed <span class="text-xs text-gray-400">(This Month)</span></p>
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
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Inspection Trends</h3>
            <select x-model="trendsFilter" @change="loadTrends()" class="text-sm border border-gray-300 rounded px-3 py-1">
                <option value="4">Last 4 Weeks</option>
                <option value="8" selected>Last 8 Weeks</option>
                <option value="12">Last 12 Weeks</option>
            </select>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="trendsChart"></canvas>
        </div>
    </div>
    
    <!-- Vehicles Due for Inspection (full width) -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                    Vehicles Due for Inspection
                </h3>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                    <?php echo e(count($upcomingInspections)); ?> vehicles
                </span>
            </div>
        </div>
        <div class="overflow-x-auto" style="max-height: 400px;">
            <table class="w-full">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plate No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Inspection</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Overdue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $upcomingInspections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-orange-50">
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                            <?php echo e($vehicle->plateno); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                Type <?php echo e($vehicle->vehicletype); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <?php echo e(Str::limit($vehicle->owner ?? 'N/A', 25)); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <?php if($vehicle->last_inspection_date): ?>
                            <?php echo e(\Carbon\Carbon::parse($vehicle->last_inspection_date)->format('M d, Y')); ?>

                            <?php else: ?>
                            <span class="text-red-600">Never inspected</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($vehicle->last_inspection_date): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                <?php echo e($vehicle->days_since_inspection > 365 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800'); ?>">
                                <?php echo e($vehicle->days_since_inspection); ?> days
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('inspections.create', ['plateno' => $vehicle->plateno])); ?>"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition">
                                <i class="fas fa-plus-circle mr-1"></i> Inspect
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <i class="fas fa-check-circle text-4xl text-green-400 mb-3 block"></i>
                            All vehicles are up to date with inspections
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?php echo e(route('inspections.create')); ?>" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <i class="fas fa-plus-circle text-3xl text-blue-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">New Inspection</span>
            </a>
            
            <a href="<?php echo e(route('vehicles.create')); ?>" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                <i class="fas fa-car text-3xl text-green-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Register Vehicle</span>
            </a>
            
            <a href="<?php echo e(route('reports.daily')); ?>" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition">
                <i class="fas fa-file-pdf text-3xl text-purple-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Daily Report</span>
            </a>
            
            <a href="<?php echo e(route('search')); ?>" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition">
                <i class="fas fa-search text-3xl text-orange-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Search Records</span>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function dashboard() {
    return {
        stats: <?php echo json_encode($stats, 15, 512) ?>,
        trendsFilter: '8',
        trendsChart: null,
        loading: false,

        init() {
            this.loading = true;
            // Chart.js is loaded synchronously, init immediately
            this.$nextTick(() => {
                this.initTrendsChart();
                this.loading = false;
            });
        },
        
        initTrendsChart() {
            const ctx = document.getElementById('trendsChart');
            if (!ctx) return;
            
            // Destroy existing chart if it exists
            if (this.trendsChart) {
                this.trendsChart.destroy();
            }
            
            const trends = <?php echo json_encode($trends, 15, 512) ?>;
            
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

        loadTrends() {
            if (!this.trendsChart) return;
            
            // Reload trends chart with new filter
            fetch(`<?php echo e(route("dashboard.charts")); ?>?type=trends&weeks=${this.trendsFilter}`)
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\talk2\OneDrive\Desktop\inspection\resources\views/dashboard/index.blade.php ENDPATH**/ ?>