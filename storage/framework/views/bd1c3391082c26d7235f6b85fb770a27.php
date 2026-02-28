<?php $__env->startSection('title', 'Inspections'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="inspectionsPage()">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Vehicle Inspections</h1>
                <p class="text-gray-600 mt-1">Manage and track all vehicle inspection records</p>
            </div>
            <a href="<?php echo e(route('inspections.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-150 ease-in-out">
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
                    <h3 class="text-3xl font-bold mt-1"><?php echo e(number_format($stats['total'])); ?></h3>
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
                    <h3 class="text-3xl font-bold mt-1"><?php echo e(number_format($stats['passed'])); ?></h3>
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
                    <h3 class="text-3xl font-bold mt-1"><?php echo e(number_format($stats['failed'])); ?></h3>
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
                    <h3 class="text-3xl font-bold mt-1"><?php echo e($stats['current_page']); ?></h3>
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
            <form method="GET" action="<?php echo e(route('inspections.index')); ?>">
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
                               value="<?php echo e(request('plate_no')); ?>">
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
                               value="<?php echo e(request('chassis_no')); ?>">
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
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dept->id); ?>" <?php echo e(request('department') == $dept->id ? 'selected' : ''); ?>>
                                    <?php echo e($dept->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                               value="<?php echo e(request('date_from')); ?>">
                    </div>

                    <!-- To Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-gray-400 mr-1"></i>
                            To Date
                        </label>
                        <input type="date" name="date_to"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="<?php echo e(request('date_to')); ?>">
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
                            <option value="Y" <?php echo e(request('test_result') === 'Y' ? 'selected' : ''); ?>>Passed</option>
                            <option value="N" <?php echo e(request('test_result') === 'N' ? 'selected' : ''); ?>>Failed</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters Display -->
                <?php if(request()->hasAny(['plate_no', 'chassis_no', 'date_from', 'date_to', 'department', 'test_result'])): ?>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center flex-wrap gap-2">
                        <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                        <?php if(request('plate_no')): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Plate: <?php echo e(request('plate_no')); ?>

                                <a href="<?php echo e(route('inspections.index', request()->except('plate_no'))); ?>" class="ml-2 hover:text-blue-900">×</a>
                            </span>
                        <?php endif; ?>
                        <?php if(request('chassis_no')): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Chassis: <?php echo e(request('chassis_no')); ?>

                                <a href="<?php echo e(route('inspections.index', request()->except('chassis_no'))); ?>" class="ml-2 hover:text-blue-900">×</a>
                            </span>
                        <?php endif; ?>
                        <?php if(request('date_from')): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                From: <?php echo e(request('date_from')); ?>

                                <a href="<?php echo e(route('inspections.index', request()->except('date_from'))); ?>" class="ml-2 hover:text-green-900">×</a>
                            </span>
                        <?php endif; ?>
                        <?php if(request('date_to')): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                To: <?php echo e(request('date_to')); ?>

                                <a href="<?php echo e(route('inspections.index', request()->except('date_to'))); ?>" class="ml-2 hover:text-green-900">×</a>
                            </span>
                        <?php endif; ?>
                        <?php if(request('department')): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Dept: <?php echo e($departments->firstWhere('id', request('department'))->title ?? request('department')); ?>

                                <a href="<?php echo e(route('inspections.index', request()->except('department'))); ?>" class="ml-2 hover:text-yellow-900">×</a>
                            </span>
                        <?php endif; ?>
                        <?php if(request('test_result')): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-<?php echo e(request('test_result') == 'Y' ? 'green' : 'red'); ?>-100 text-<?php echo e(request('test_result') == 'Y' ? 'green' : 'red'); ?>-800">
                                Result: <?php echo e(request('test_result') == 'Y' ? 'Passed' : 'Failed'); ?>

                                <a href="<?php echo e(route('inspections.index', request()->except('test_result'))); ?>" class="ml-2">×</a>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Filter Actions -->
                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150">
                        <i class="fas fa-search mr-2"></i>
                        Apply Filters
                    </button>
                    <a href="<?php echo e(route('inspections.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition duration-150">
                        <i class="fas fa-redo mr-2"></i>
                        Clear All
                    </a>
                    <div class="ml-auto text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        <?php echo e(number_format($stats['total'])); ?> results found
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
                    Showing <?php echo e(number_format($stats['total'])); ?> results
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
                    <?php $__empty_1 = true; $__currentLoopData = $inspections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inspection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-900"><?php echo e($inspection->seriesno); ?></div>
                                        <div class="text-xs text-gray-500">Times: <?php echo e($inspection->inspecttimes ?? 1); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                                        <i class="fas fa-car text-gray-500 mr-2 text-xs"></i>
                                        <?php echo e($inspection->plateno); ?>

                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium"><?php echo e($inspection->makeofvehicle ?? 'N/A'); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($inspection->model ?? '—'); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-gray-600"><?php echo e($inspection->chassisno ?? '—'); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?php echo e(Str::limit($inspection->owner ?? '—', 25)); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700"><?php echo e($inspection->register ?? '—'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php if($inspection->inspectdate): ?>
                                        <?php echo e(\Carbon\Carbon::parse($inspection->inspectdate)->format('M d, Y')); ?>

                                    <?php else: ?>
                                        <span class="text-gray-400">N/A</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <?php if($inspection->inspectdate): ?>
                                        <?php echo e(\Carbon\Carbon::parse($inspection->inspectdate)->format('h:i A')); ?>

                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if(in_array($inspection->testresult, ['1', 'Y'])): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Passed
                                    </span>
                                <?php elseif(in_array($inspection->testresult, ['0', 'N'])): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Failed
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?php echo e($inspection->department_name ?? 'N/A'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?php echo e(route('inspections.show', $inspection->id)); ?>" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition duration-150" 
                                       title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="<?php echo e(route('inspections.edit', $inspection->id)); ?>" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition duration-150" 
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <?php if(in_array($inspection->testresult, ['1', 'Y'])): ?>
                                    <a href="<?php echo e(route('inspections.certificate', $inspection->id)); ?>" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition duration-150" 
                                       title="Certificate">
                                        <i class="fas fa-certificate text-sm"></i>
                                    </a>
                                    <?php endif; ?>
                                    <button @click="deleteInspection(<?php echo e($inspection->id); ?>)" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition duration-150" 
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No inspection records found</h3>
                                    <p class="text-gray-500">Try adjusting your filters or create a new inspection</p>
                                    <a href="<?php echo e(route('inspections.create')); ?>" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150">
                                        <i class="fas fa-plus mr-2"></i>
                                        Create New Inspection
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($inspections->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <?php echo e($inspections->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
                csrfToken.value = '<?php echo e(csrf_token()); ?>';
                
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\talk2\OneDrive\Desktop\inspection\resources\views/inspections/index.blade.php ENDPATH**/ ?>