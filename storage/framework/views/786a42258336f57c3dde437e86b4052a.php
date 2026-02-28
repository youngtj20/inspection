<?php $__env->startSection('title', 'Monthly Report'); ?>

<?php $__env->startSection('content'); ?>


<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Monthly Inspection Report</h1>
        <p class="text-gray-500 mt-0.5"><?php echo e(\Carbon\Carbon::parse($month . '-01')->format('F Y')); ?></p>
    </div>
    <a href="<?php echo e(route('reports.index')); ?>"
       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
        <i class="fas fa-arrow-left mr-2 text-sm"></i>Back to Reports
    </a>
</div>


<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" action="<?php echo e(route('reports.monthly')); ?>" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Month</label>
            <input type="month" name="month" value="<?php echo e($month); ?>"
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wider">Department</label>
            <select name="department"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[180px]">
                <option value="">All Departments</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($dept->id); ?>" <?php echo e(request('department') == $dept->id ? 'selected' : ''); ?>>
                        <?php echo e($dept->title); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-filter"></i>Filter
            </button>
            <a href="<?php echo e(route('reports.monthly', ['month' => $month, 'department' => request('department'), 'format' => 'pdf'])); ?>"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-file-pdf"></i>Summary PDF
            </a>
            <a href="<?php echo e(route('reports.monthly', ['month' => $month, 'department' => request('department'), 'format' => 'details-pdf'])); ?>"
               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-file-pdf"></i>Details PDF
            </a>
            <a href="<?php echo e(route('reports.monthly', ['month' => $month, 'department' => request('department'), 'format' => 'excel'])); ?>"
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg flex items-center gap-2 transition">
                <i class="fas fa-file-csv"></i>Export CSV
            </a>
        </div>
    </form>
</div>


<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-blue-100 text-xs font-semibold uppercase tracking-wider">Total</p>
        <h3 class="text-4xl font-extrabold mt-1"><?php echo e(number_format($stats['total'])); ?></h3>
        <p class="text-blue-200 text-xs mt-2">inspections</p>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-green-100 text-xs font-semibold uppercase tracking-wider">Passed</p>
        <h3 class="text-4xl font-extrabold mt-1"><?php echo e(number_format($stats['passed'])); ?></h3>
        <p class="text-green-200 text-xs mt-2">vehicles</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-red-100 text-xs font-semibold uppercase tracking-wider">Failed</p>
        <h3 class="text-4xl font-extrabold mt-1"><?php echo e(number_format($stats['failed'])); ?></h3>
        <p class="text-red-200 text-xs mt-2">vehicles</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-yellow-100 text-xs font-semibold uppercase tracking-wider">Pending</p>
        <h3 class="text-4xl font-extrabold mt-1"><?php echo e(number_format($stats['pending'])); ?></h3>
        <p class="text-yellow-200 text-xs mt-2">vehicles</p>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md p-5 text-white">
        <p class="text-purple-100 text-xs font-semibold uppercase tracking-wider">Pass Rate</p>
        <h3 class="text-4xl font-extrabold mt-1"><?php echo e($stats['pass_rate']); ?><span class="text-2xl">%</span></h3>
        <p class="text-purple-200 text-xs mt-2">of completed</p>
    </div>
</div>


<?php if($stats['total'] > 0): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <div class="flex justify-between items-center mb-3">
        <span class="text-sm font-semibold text-gray-700">Monthly Result Distribution</span>
        <span class="text-xs text-gray-400"><?php echo e($stats['passed']); ?> passed · <?php echo e($stats['failed']); ?> failed · <?php echo e($stats['pending']); ?> pending</span>
    </div>
    <?php
        $pp = $stats['total'] > 0 ? round($stats['passed'] / $stats['total'] * 100, 1) : 0;
        $fp = $stats['total'] > 0 ? round($stats['failed'] / $stats['total'] * 100, 1) : 0;
    ?>
    <div class="w-full bg-gray-100 rounded-full h-5 overflow-hidden flex">
        <div class="bg-green-500 h-5 flex items-center justify-center text-white text-xs font-bold"
             style="width: <?php echo e($pp); ?>%"><?php echo e($pp > 8 ? $pp . '%' : ''); ?></div>
        <div class="bg-red-400 h-5 flex items-center justify-center text-white text-xs font-bold"
             style="width: <?php echo e($fp); ?>%"><?php echo e($fp > 8 ? $fp . '%' : ''); ?></div>
        <div class="bg-gray-200 h-5 flex-1"></div>
    </div>
    <div class="flex gap-5 mt-2">
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-1.5"></span>Passed (<?php echo e($pp); ?>%)</span>
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-red-400 mr-1.5"></span>Failed (<?php echo e($fp); ?>%)</span>
        <span class="flex items-center text-xs text-gray-500"><span class="inline-block w-3 h-3 rounded-full bg-gray-200 mr-1.5"></span>Pending</span>
    </div>
</div>
<?php endif; ?>


<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                <i class="fas fa-building text-indigo-500 mr-2"></i>By Department
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Department</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Pass</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Fail</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Rate</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase w-24">Bar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $__empty_1 = true; $__currentLoopData = $departmentStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $comp = $dept->passed + $dept->failed;
                        $rate = $comp > 0 ? round($dept->passed / $comp * 100, 1) : 0;
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2.5 font-medium text-gray-800"><?php echo e($dept->department_name ?? 'Unassigned'); ?></td>
                        <td class="px-4 py-2.5 text-right text-gray-600"><?php echo e(number_format($dept->total)); ?></td>
                        <td class="px-4 py-2.5 text-right font-semibold text-green-700"><?php echo e(number_format($dept->passed)); ?></td>
                        <td class="px-4 py-2.5 text-right font-semibold text-red-700"><?php echo e(number_format($dept->failed)); ?></td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold
                                <?php echo e($rate >= 80 ? 'bg-green-100 text-green-800' : ($rate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                                <?php echo e($rate); ?>%
                            </span>
                        </td>
                        <td class="px-4 py-2.5">
                            <div class="w-full bg-gray-100 rounded-full h-2 flex overflow-hidden">
                                <div class="bg-green-500 h-2" style="width: <?php echo e($dept->total > 0 ? round($dept->passed / $dept->total * 100) : 0); ?>%"></div>
                                <div class="bg-red-400 h-2" style="width: <?php echo e($dept->total > 0 ? round($dept->failed / $dept->total * 100) : 0); ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-xs">No department data</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                <i class="fas fa-car text-blue-500 mr-2"></i>By Vehicle Type
            </h3>
        </div>
        <?php if($vehicleTypeStats->count()): ?>
        <div class="divide-y divide-gray-50">
            <?php $__currentLoopData = $vehicleTypeStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $vt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $vComp = $vt['passed'] + $vt['failed'];
                $vRate = $vComp > 0 ? round($vt['passed'] / $vComp * 100, 1) : 0;
                $vPct  = $vt['total'] > 0 ? round($vt['passed'] / $vt['total'] * 100) : 0;
                $vFPct = $vt['total'] > 0 ? round($vt['failed'] / $vt['total'] * 100) : 0;
            ?>
            <div class="px-5 py-3 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-semibold text-gray-800"><?php echo e($type ?: 'Unspecified'); ?></span>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="text-gray-500"><?php echo e(number_format($vt['total'])); ?> total</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-bold
                            <?php echo e($vRate >= 80 ? 'bg-green-100 text-green-800' : ($vRate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                            <?php echo e($vRate); ?>%
                        </span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden flex">
                    <div class="bg-green-500 h-2.5" style="width: <?php echo e($vPct); ?>%"></div>
                    <div class="bg-red-400 h-2.5" style="width: <?php echo e($vFPct); ?>%"></div>
                    <div class="bg-gray-200 h-2.5 flex-1"></div>
                </div>
                <div class="flex gap-4 mt-1 text-xs text-gray-400">
                    <span class="text-green-700 font-medium"><?php echo e(number_format($vt['passed'])); ?> passed</span>
                    <span class="text-red-700 font-medium"><?php echo e(number_format($vt['failed'])); ?> failed</span>
                    <?php if($vt['total'] - $vt['passed'] - $vt['failed'] > 0): ?>
                    <span class="text-yellow-700"><?php echo e(number_format($vt['total'] - $vt['passed'] - $vt['failed'])); ?> pending</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="px-5 py-8 text-center text-gray-400 text-xs">No vehicle type data</div>
        <?php endif; ?>
    </div>

</div>


<?php if($inspectorStats->count()): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-700 flex items-center">
            <i class="fas fa-user-check text-teal-500 mr-2"></i>Inspector Performance
        </h3>
        <span class="text-xs text-gray-400"><?php echo e($inspectorStats->count()); ?> inspector(s)</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Inspector</th>
                    <th class="px-5 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-5 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Passed</th>
                    <th class="px-5 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Failed</th>
                    <th class="px-5 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Pass Rate</th>
                    <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase w-32">Distribution</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php $__currentLoopData = $inspectorStats->sortByDesc('total'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inspector => $ins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $iComp = $ins['passed'] + $ins['failed'];
                    $iRate = $iComp > 0 ? round($ins['passed'] / $iComp * 100, 1) : 0;
                ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-2.5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-teal-600 text-xs"></i>
                            </div>
                            <span class="font-medium text-gray-800"><?php echo e($inspector); ?></span>
                        </div>
                    </td>
                    <td class="px-5 py-2.5 text-right text-gray-600"><?php echo e(number_format($ins['total'])); ?></td>
                    <td class="px-5 py-2.5 text-right font-semibold text-green-700"><?php echo e(number_format($ins['passed'])); ?></td>
                    <td class="px-5 py-2.5 text-right font-semibold text-red-700"><?php echo e(number_format($ins['failed'])); ?></td>
                    <td class="px-5 py-2.5 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold
                            <?php echo e($iRate >= 80 ? 'bg-green-100 text-green-800' : ($iRate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                            <?php echo e($iRate); ?>%
                        </span>
                    </td>
                    <td class="px-5 py-2.5">
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden flex">
                            <div class="bg-green-500 h-2" style="width: <?php echo e($ins['total'] > 0 ? round($ins['passed'] / $ins['total'] * 100) : 0); ?>%"></div>
                            <div class="bg-red-400 h-2" style="width: <?php echo e($ins['total'] > 0 ? round($ins['failed'] / $ins['total'] * 100) : 0); ?>%"></div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>


<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                <i class="fas fa-calendar-check text-orange-500 mr-2"></i>Daily Activity
            </h3>
            <span class="text-xs text-gray-400"><?php echo e(count($dailyStats)); ?> working day(s)</span>
        </div>
        <?php if(count($dailyStats)): ?>
        <div class="overflow-x-auto max-h-72 overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 sticky top-0">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Pass</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Fail</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Bar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $__currentLoopData = $dailyStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2 font-medium text-gray-700">
                            <?php echo e($day === 'Unknown' ? '—' : \Carbon\Carbon::parse($day)->format('d M')); ?>

                            <?php if($day !== 'Unknown'): ?>
                            <span class="text-xs text-gray-400 ml-1"><?php echo e(\Carbon\Carbon::parse($day)->format('D')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2 text-right text-gray-600"><?php echo e(number_format($d['total'])); ?></td>
                        <td class="px-4 py-2 text-right text-green-700 font-semibold"><?php echo e(number_format($d['passed'])); ?></td>
                        <td class="px-4 py-2 text-right text-red-700 font-semibold"><?php echo e(number_format($d['failed'])); ?></td>
                        <td class="px-4 py-2 w-24">
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden flex">
                                <div class="bg-green-500 h-2" style="width: <?php echo e($d['total'] > 0 ? round($d['passed'] / $d['total'] * 100) : 0); ?>%"></div>
                                <div class="bg-red-400 h-2" style="width: <?php echo e($d['total'] > 0 ? round($d['failed'] / $d['total'] * 100) : 0); ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="px-5 py-8 text-center text-gray-400 text-xs">No daily data</div>
        <?php endif; ?>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                <i class="fas fa-tags text-pink-500 mr-2"></i>By Inspection Type
            </h3>
        </div>
        <?php if($inspectionTypeStats->count()): ?>
        <div class="divide-y divide-gray-50">
            <?php $__currentLoopData = $inspectionTypeStats->sortByDesc('total'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $iComp = $it['passed'] + $it['failed'];
                $iRate = $iComp > 0 ? round($it['passed'] / $iComp * 100, 1) : 0;
            ?>
            <div class="px-5 py-3 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-semibold text-gray-800"><?php echo e($type ?: 'Unspecified'); ?></span>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-gray-400"><?php echo e(number_format($it['total'])); ?></span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-bold
                            <?php echo e($iRate >= 80 ? 'bg-green-100 text-green-800' : ($iRate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                            <?php echo e($iRate); ?>%
                        </span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden flex">
                    <div class="bg-green-500 h-2.5" style="width: <?php echo e($it['total'] > 0 ? round($it['passed'] / $it['total'] * 100) : 0); ?>%"></div>
                    <div class="bg-red-400 h-2.5" style="width: <?php echo e($it['total'] > 0 ? round($it['failed'] / $it['total'] * 100) : 0); ?>%"></div>
                    <div class="bg-gray-200 h-2.5 flex-1"></div>
                </div>
                <div class="flex gap-4 mt-1 text-xs">
                    <span class="text-green-700 font-medium"><?php echo e(number_format($it['passed'])); ?> passed</span>
                    <span class="text-red-700 font-medium"><?php echo e(number_format($it['failed'])); ?> failed</span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="px-5 py-8 text-center text-gray-400 text-xs">No inspection type data</div>
        <?php endif; ?>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\talk2\OneDrive\Desktop\inspection\resources\views/reports/monthly.blade.php ENDPATH**/ ?>