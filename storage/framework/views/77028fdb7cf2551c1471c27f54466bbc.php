

<?php $__env->startSection('title', 'Daily Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daily Inspection Report</h1>
            <p class="text-gray-600">Inspection statistics for <?php echo e(\Carbon\Carbon::parse($date)->format('F d, Y')); ?></p>
        </div>
        <a href="<?php echo e(route('reports.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Reports
        </a>
    </div>
</div>

<!-- Date Filter -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="<?php echo e(route('reports.daily')); ?>" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
            <input type="date" name="date" value="<?php echo e($date); ?>" 
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
            <select name="department" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">All Departments</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($dept->id); ?>" <?php echo e(request('department') == $dept->id ? 'selected' : ''); ?>>
                        <?php echo e($dept->title); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
            <i class="fas fa-filter mr-2"></i>Filter
        </button>
        <a href="<?php echo e(route('reports.daily', ['date' => $date, 'format' => 'pdf'])); ?>" 
           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
            <i class="fas fa-file-pdf mr-2"></i>Download PDF
        </a>
    </form>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Inspections</p>
                <h3 class="text-3xl font-bold mt-1"><?php echo e(number_format($stats['total'])); ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-clipboard-check text-2xl"></i>
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

    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Pending</p>
                <h3 class="text-3xl font-bold mt-1"><?php echo e(number_format($stats['pending'])); ?></h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Inspections Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-list text-gray-600 mr-2"></i>
            Inspection Records
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Series No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Plate No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Owner</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Result</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $inspections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inspection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-900"><?php echo e($inspection->seriesno); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-bold text-blue-600"><?php echo e($inspection->plateno); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><?php echo e($inspection->makeofvehicle ?? 'N/A'); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($inspection->model ?? ''); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><?php echo e(Str::limit($inspection->owner ?? 'N/A', 25)); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600"><?php echo e($inspection->department_name ?? 'N/A'); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($inspection->testresult === '1'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i> Passed
                                </span>
                            <?php elseif($inspection->testresult === '0'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i> Failed
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="<?php echo e(route('inspections.show', $inspection->id)); ?>" 
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No inspections found</h3>
                                <p class="text-gray-500">No inspections were recorded for this date.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\talk2\OneDrive\Desktop\inspection\resources\views/reports/daily.blade.php ENDPATH**/ ?>