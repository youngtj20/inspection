

<?php $__env->startSection('title', 'Department Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Department Report</h1>
            <p class="text-gray-600">Performance metrics and statistics by department</p>
        </div>
        <a href="<?php echo e(route('reports.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Reports
        </a>
    </div>
</div>

<!-- Filter Form -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="<?php echo e(route('reports.department')); ?>" id="deptReportForm">
        <div class="flex flex-wrap items-start gap-6">

            
            <div class="flex-1 min-w-[240px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Department(s)
                    <span class="text-xs text-gray-400 ml-1">(hold Ctrl / Cmd to select multiple)</span>
                </label>
                <select name="departments[]" multiple
                        class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        style="height: 160px; padding: 4px 8px;">
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dept->id); ?>"
                            <?php echo e(in_array($dept->id, $deptIds ?? []) ? 'selected' : ''); ?>

                            class="py-1 px-2 rounded cursor-pointer">
                            <?php echo e($dept->title); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    <?php echo e(count($deptIds ?? [])); ?> department(s) selected
                </p>
            </div>

            
            <div class="flex flex-col gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="date_from" value="<?php echo e($dateFrom ?? ''); ?>"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="date_to" value="<?php echo e($dateTo ?? ''); ?>"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex gap-2 mt-1">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg flex items-center">
                        <i class="fas fa-filter mr-2"></i>Generate Report
                    </button>
                    <?php if(!empty($deptIds)): ?>
                    <button type="submit" name="format" value="pdf"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i>PDF
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>

<?php if(!empty($deptIds) && isset($stats)): ?>


<div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <?php if($department): ?>
                <h2 class="text-2xl font-bold"><?php echo e($department->title); ?></h2>
            <?php else: ?>
                <h2 class="text-2xl font-bold"><?php echo e(count($deptIds)); ?> Departments Combined</h2>
                <p class="text-blue-100 mt-1 text-sm">
                    <?php echo e(collect($deptBreakdown)->pluck('title')->implode(' · ')); ?>

                </p>
            <?php endif; ?>
            <p class="text-blue-100 mt-1">
                Report Period: <?php echo e(\Carbon\Carbon::parse($dateFrom)->format('M d, Y')); ?> – <?php echo e(\Carbon\Carbon::parse($dateTo)->format('M d, Y')); ?>

            </p>
        </div>
        <div class="bg-white bg-opacity-20 rounded-full p-4">
            <i class="fas fa-building text-4xl"></i>
        </div>
    </div>
</div>


<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-md p-4 border-t-4 border-blue-500">
        <p class="text-gray-500 text-xs font-medium uppercase">Total Inspections</p>
        <h3 class="text-2xl font-bold text-gray-800 mt-1"><?php echo e(number_format($stats['total'])); ?></h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-4 border-t-4 border-green-500">
        <p class="text-gray-500 text-xs font-medium uppercase">Passed</p>
        <h3 class="text-2xl font-bold text-green-600 mt-1"><?php echo e(number_format($stats['passed'])); ?></h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-4 border-t-4 border-red-500">
        <p class="text-gray-500 text-xs font-medium uppercase">Failed</p>
        <h3 class="text-2xl font-bold text-red-600 mt-1"><?php echo e(number_format($stats['failed'])); ?></h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-4 border-t-4 border-yellow-500">
        <p class="text-gray-500 text-xs font-medium uppercase">Pending</p>
        <h3 class="text-2xl font-bold text-yellow-600 mt-1"><?php echo e(number_format($stats['pending'])); ?></h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-4 border-t-4 border-purple-500">
        <p class="text-gray-500 text-xs font-medium uppercase">Pass Rate</p>
        <h3 class="text-2xl font-bold text-purple-600 mt-1"><?php echo e($stats['pass_rate']); ?>%</h3>
    </div>
</div>


<?php if(count($deptBreakdown) > 1): ?>
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
                <?php $__currentLoopData = $deptBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900"><?php echo e($row['title']); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700 text-right"><?php echo e(number_format($row['total'])); ?></td>
                    <td class="px-4 py-3 text-sm font-semibold text-green-700 text-right"><?php echo e(number_format($row['passed'])); ?></td>
                    <td class="px-4 py-3 text-sm font-semibold text-red-700 text-right"><?php echo e(number_format($row['failed'])); ?></td>
                    <td class="px-4 py-3 text-sm text-yellow-700 text-right"><?php echo e(number_format($row['pending'])); ?></td>
                    <td class="px-4 py-3 text-right">
                        <?php $rate = $row['pass_rate']; ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                            <?php echo e($rate >= 80 ? 'bg-green-100 text-green-800' : ($rate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                            <?php echo e($rate); ?>%
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 text-right"><?php echo e(number_format($row['equipment'])); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700 text-right"><?php echo e(number_format($row['personnel'])); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700 text-right"><?php echo e(number_format($row['active_users'])); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <tr class="bg-gray-100 font-semibold border-t-2 border-gray-300">
                    <td class="px-4 py-3 text-sm text-gray-900">Total</td>
                    <td class="px-4 py-3 text-sm text-gray-900 text-right"><?php echo e(number_format($stats['total'])); ?></td>
                    <td class="px-4 py-3 text-sm text-green-700 text-right"><?php echo e(number_format($stats['passed'])); ?></td>
                    <td class="px-4 py-3 text-sm text-red-700 text-right"><?php echo e(number_format($stats['failed'])); ?></td>
                    <td class="px-4 py-3 text-sm text-yellow-700 text-right"><?php echo e(number_format($stats['pending'])); ?></td>
                    <td class="px-4 py-3 text-right">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                            <?php echo e($stats['pass_rate'] >= 80 ? 'bg-green-100 text-green-800' : ($stats['pass_rate'] >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                            <?php echo e($stats['pass_rate']); ?>%
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900 text-right"><?php echo e(number_format(collect($deptBreakdown)->sum('equipment'))); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-900 text-right"><?php echo e(number_format(collect($deptBreakdown)->sum('personnel'))); ?></td>
                    <td class="px-4 py-3 text-sm text-gray-900 text-right"><?php echo e(number_format(collect($deptBreakdown)->sum('active_users'))); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>


<?php if(count($deptBreakdown) === 1): ?>
<?php $row = $deptBreakdown[0]; ?>
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
                    <span class="font-medium"><?php echo e($row['pass_rate']); ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full" style="width: <?php echo e($row['pass_rate']); ?>%"></div>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Active Users</span>
                    <span class="font-bold text-blue-600"><?php echo e($row['active_users']); ?></span>
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
                <span class="font-medium"><?php echo e($row['title']); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Status</span>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total Equipment</span>
                <span class="font-medium"><?php echo e($row['equipment']); ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total Personnel</span>
                <span class="font-medium"><?php echo e($row['personnel']); ?></span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php else: ?>

<div class="bg-white rounded-lg shadow-md p-12 text-center">
    <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
    <h3 class="text-xl font-semibold text-gray-800 mb-2">Select One or More Departments</h3>
    <p class="text-gray-500">Hold <kbd class="px-1 py-0.5 text-xs bg-gray-100 border border-gray-300 rounded">Ctrl</kbd>
        (or <kbd class="px-1 py-0.5 text-xs bg-gray-100 border border-gray-300 rounded">Cmd</kbd> on Mac)
        and click to select multiple departments, then click Generate Report.</p>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Show live count of selected departments
document.querySelector('select[name="departments[]"]').addEventListener('change', function () {
    this.closest('.flex-1').querySelector('p').textContent =
        this.selectedOptions.length + ' department(s) selected';
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\talk2\OneDrive\Desktop\inspection\resources\views/reports/department.blade.php ENDPATH**/ ?>