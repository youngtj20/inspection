

<?php $__env->startSection('title', 'Vehicle — <?php echo e($vehicle->plateno); ?>'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $fuelLabels = ['A' => 'Petrol', 'B' => 'Diesel', 'C' => 'LPG', 'D' => 'CNG', 'E' => 'Electric', 'F' => 'Hybrid'];
    $fuelLabel  = $fuelLabels[$vehicle->fueltype ?? ''] ?? ($vehicle->fueltype ?? '—');
    $driveLabels = ['1' => 'Front-Wheel', '2' => 'Rear-Wheel', '3' => 'All-Wheel', '4' => '4WD'];
    $driveLabel  = $driveLabels[$vehicle->drivemethod ?? ''] ?? ($vehicle->drivemethod ?? '—');
    $headlampLabels = ['1' => 'Symmetric', '2' => 'Asymmetric'];
    $headlampLabel  = $headlampLabels[$vehicle->headlampsystem ?? ''] ?? ($vehicle->headlampsystem ?? '—');
    $typeLabels = ['1' => 'Initial', '2' => 'Re-inspect', '3' => 'Follow-up'];

    $passRate    = $stats['pass_rate'];
    $lastInspect = $stats['last_inspection'];
    $firstInspect= $stats['first_inspection'];
?>


<div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
    <div class="flex items-start gap-4">
        
        <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md">
            <i class="fas fa-car text-white text-2xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight"><?php echo e($vehicle->plateno); ?></h1>
            <p class="text-gray-500 mt-0.5">
                <?php echo e($vehicle->makeofvehicle ?? ''); ?>

                <?php if($vehicle->model): ?> · <?php echo e($vehicle->model); ?> <?php endif; ?>
                <?php if($vehicle->vehicletype): ?>
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700"><?php echo e($vehicle->vehicletype); ?></span>
                <?php endif; ?>
            </p>
            <?php if($vehicle->dept_name): ?>
            <p class="text-xs text-gray-400 mt-1"><i class="fas fa-building mr-1"></i><?php echo e($vehicle->dept_name); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
        <a href="<?php echo e(route('vehicles.edit', $vehicle->id)); ?>"
           class="inline-flex items-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-semibold rounded-lg transition text-sm shadow-sm">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <a href="<?php echo e(route('vehicles.history', $vehicle->id)); ?>"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm shadow-sm">
            <i class="fas fa-history mr-2"></i>Full History
        </a>
        <a href="<?php echo e(route('vehicles.index')); ?>"
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium rounded-lg transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>
</div>


<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Inspections</p>
        <p class="text-3xl font-extrabold text-gray-800 mt-1"><?php echo e(number_format($stats['total_inspections'])); ?></p>
        <?php if($firstInspect && $lastInspect && $firstInspect !== $lastInspect): ?>
        <p class="text-xs text-gray-400 mt-1">
            <?php echo e(\Carbon\Carbon::parse($firstInspect)->format('M Y')); ?> –
            <?php echo e(\Carbon\Carbon::parse($lastInspect)->format('M Y')); ?>

        </p>
        <?php elseif($lastInspect): ?>
        <p class="text-xs text-gray-400 mt-1"><?php echo e(\Carbon\Carbon::parse($lastInspect)->format('M d, Y')); ?></p>
        <?php endif; ?>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Passed</p>
        <p class="text-3xl font-extrabold text-green-600 mt-1"><?php echo e(number_format($stats['passed'])); ?></p>
        <p class="text-xs text-gray-400 mt-1"><?php echo e($passRate); ?>% pass rate</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs font-semibold text-red-500 uppercase tracking-wider">Failed</p>
        <p class="text-3xl font-extrabold text-red-500 mt-1"><?php echo e(number_format($stats['failed'])); ?></p>
        <?php if($stats['failed'] > 0 && $stats['total_inspections'] > 0): ?>
        <p class="text-xs text-gray-400 mt-1"><?php echo e(round($stats['failed'] / $stats['total_inspections'] * 100, 1)); ?>% of total</p>
        <?php endif; ?>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Pass Rate</p>
        <p class="text-3xl font-extrabold <?php echo e($passRate >= 80 ? 'text-green-600' : ($passRate >= 60 ? 'text-yellow-500' : 'text-red-500')); ?> mt-1"><?php echo e($passRate); ?>%</p>
        <?php if($lastInspect): ?>
        <p class="text-xs text-gray-400 mt-1">Last: <?php echo e(\Carbon\Carbon::parse($lastInspect)->diffForHumans()); ?></p>
        <?php endif; ?>
    </div>
</div>


<?php if($stats['total_inspections'] > 0): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex items-center justify-between mb-2">
        <span class="text-xs font-semibold text-gray-600">Pass / Fail Breakdown</span>
        <span class="text-xs text-gray-400">
            <?php echo e(number_format($stats['passed'])); ?> passed &nbsp;·&nbsp;
            <?php echo e(number_format($stats['failed'])); ?> failed
            <?php if($stats['pending'] > 0): ?> &nbsp;·&nbsp; <?php echo e(number_format($stats['pending'])); ?> pending <?php endif; ?>
        </span>
    </div>
    <?php
        $pPct = $stats['total_inspections'] > 0 ? round($stats['passed'] / $stats['total_inspections'] * 100, 1) : 0;
        $fPct = $stats['total_inspections'] > 0 ? round($stats['failed'] / $stats['total_inspections'] * 100, 1) : 0;
    ?>
    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden flex">
        <div class="bg-green-500 h-3 transition-all duration-500" style="width:<?php echo e($pPct); ?>%"></div>
        <div class="bg-red-400 h-3 transition-all duration-500" style="width:<?php echo e($fPct); ?>%"></div>
        <div class="bg-gray-300 h-3 flex-1"></div>
    </div>
    <div class="flex gap-4 mt-2">
        <span class="flex items-center text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-1.5 inline-block"></span>Passed (<?php echo e($pPct); ?>%)</span>
        <span class="flex items-center text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-red-400 mr-1.5 inline-block"></span>Failed (<?php echo e($fPct); ?>%)</span>
        <?php if($stats['pending'] > 0): ?>
        <span class="flex items-center text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-gray-300 mr-1.5 inline-block"></span>Pending</span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-3.5">
            <h2 class="font-semibold text-white flex items-center gap-2 text-sm">
                <i class="fas fa-car"></i> Vehicle Information
            </h2>
        </div>
        <div class="divide-y divide-gray-50">
            <?php
            $vFields = [
                ['Plate No.',      $vehicle->plateno,        'fas fa-id-card'],
                ['Vehicle Type',   $vehicle->vehicletype,    'fas fa-tag'],
                ['Make',           $vehicle->makeofvehicle,  'fas fa-industry'],
                ['Model',          $vehicle->model,          'fas fa-car-side'],
                ['Engine No.',     $vehicle->engineno,       'fas fa-cog'],
                ['Chassis No.',    $vehicle->chassisno,      'fas fa-barcode'],
                ['Odometer (km)',  $vehicle->odmeter ? number_format($vehicle->odmeter) : null, 'fas fa-tachometer-alt'],
            ];
            ?>
            <?php $__currentLoopData = $vFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-start gap-3 px-5 py-3">
                <div class="w-7 flex-shrink-0 text-center mt-0.5">
                    <i class="<?php echo e($icon); ?> text-gray-300 text-xs"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-400"><?php echo e($label); ?></p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5 break-words"><?php echo e($value ?? '—'); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-5 py-3.5">
            <h2 class="font-semibold text-white flex items-center gap-2 text-sm">
                <i class="fas fa-user"></i> Owner & Registration
            </h2>
        </div>
        <div class="divide-y divide-gray-50">
            <?php
            $oFields = [
                ['Owner',           $vehicle->owner,          'fas fa-user'],
                ['Address',         $vehicle->address,        'fas fa-map-marker-alt'],
                ['Phone',           $vehicle->phoneno,        'fas fa-phone'],
                ['Presenter',       $vehicle->presentor,      'fas fa-user-tie'],
                ['Invoice No.',     $vehicle->invoiceno,      'fas fa-file-invoice'],
                ['Register Date',   $vehicle->registerdate ? \Carbon\Carbon::parse($vehicle->registerdate)->format('Y-m-d') : null, 'fas fa-calendar-check'],
                ['Product Date',    $vehicle->productdate ? \Carbon\Carbon::parse($vehicle->productdate)->format('Y-m-d') : null, 'fas fa-calendar'],
                ['Department',      $vehicle->dept_name,      'fas fa-building'],
            ];
            ?>
            <?php $__currentLoopData = $oFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-start gap-3 px-5 py-3">
                <div class="w-7 flex-shrink-0 text-center mt-0.5">
                    <i class="<?php echo e($icon); ?> text-gray-300 text-xs"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-400"><?php echo e($label); ?></p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5 break-words"><?php echo e($value ?? '—'); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-5 py-3.5">
            <h2 class="font-semibold text-white flex items-center gap-2 text-sm">
                <i class="fas fa-sliders-h"></i> Specifications
            </h2>
        </div>
        <div class="divide-y divide-gray-50">
            <?php
            $sFields = [
                ['Licence Type',    $vehicle->licencetype,    'fas fa-id-badge'],
                ['Fuel Type',       $fuelLabel,               'fas fa-gas-pump'],
                ['Drive Method',    $driveLabel,              'fas fa-road'],
                ['Headlamp System', $headlampLabel,           'fas fa-lightbulb'],
                ['Axle Number',     $vehicle->axisnumber,     'fas fa-circle-notch'],
                ['Net Weight',      $vehicle->netweight ? number_format($vehicle->netweight) . ' kg' : null, 'fas fa-weight'],
                ['Gross Weight',    $vehicle->grossweight ? number_format($vehicle->grossweight) . ' kg' : null, 'fas fa-truck'],
                ['Persons',         $vehicle->personstocarry, 'fas fa-users'],
                ['Load (kg)',       $vehicle->authorizedtocarry ? number_format($vehicle->authorizedtocarry) : null, 'fas fa-box'],
                ['Heavy/Light',     $vehicle->heavyorlight,   'fas fa-balance-scale'],
                ['Handbrake Type',  $vehicle->handbrake,      'fas fa-hand-paper'],
            ];
            ?>
            <?php $__currentLoopData = $sFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-start gap-3 px-5 py-3">
                <div class="w-7 flex-shrink-0 text-center mt-0.5">
                    <i class="<?php echo e($icon); ?> text-gray-300 text-xs"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-400"><?php echo e($label); ?></p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5"><?php echo e($value ?? '—'); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-clipboard-list text-blue-500"></i>
            Recent Inspections
        </h2>
        <?php if($stats['total_inspections'] > 5): ?>
        <a href="<?php echo e(route('vehicles.history', $vehicle->id)); ?>"
           class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
            View all <?php echo e(number_format($stats['total_inspections'])); ?> <i class="fas fa-arrow-right text-xs"></i>
        </a>
        <?php endif; ?>
    </div>

    <?php if($inspections->isEmpty()): ?>
    <div class="py-14 text-center">
        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-clipboard text-gray-300 text-2xl"></i>
        </div>
        <p class="text-gray-500 font-medium">No inspections yet</p>
        <p class="text-gray-400 text-sm mt-1">Start a new inspection for this vehicle.</p>
        <a href="<?php echo e(route('inspections.create', ['plateno' => $vehicle->plateno])); ?>"
           class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>New Inspection
        </a>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-left">Series No</th>
                    <th class="px-5 py-3 text-left">Type</th>
                    <th class="px-5 py-3 text-left">Inspector</th>
                    <th class="px-5 py-3 text-center">Times</th>
                    <th class="px-5 py-3 text-center">Result</th>
                    <th class="px-5 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__currentLoopData = $inspections->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isPassed = in_array($ins->testresult, ['1','Y']);
                    $isFailed = in_array($ins->testresult, ['0','N']);
                ?>
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <div class="font-semibold text-gray-800">
                            <?php echo e($ins->inspectdate ? \Carbon\Carbon::parse($ins->inspectdate)->format('Y-m-d') : '—'); ?>

                        </div>
                        <?php if($ins->inspectdate): ?>
                        <div class="text-xs text-gray-400 mt-0.5"><?php echo e(\Carbon\Carbon::parse($ins->inspectdate)->diffForHumans()); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="font-mono text-xs text-gray-700 bg-gray-100 px-2 py-0.5 rounded"><?php echo e($ins->seriesno); ?></span>
                    </td>
                    <td class="px-5 py-3.5 text-xs text-gray-500">
                        <?php echo e($typeLabels[$ins->inspecttype ?? ''] ?? ($ins->inspecttype ?? '—')); ?>

                    </td>
                    <td class="px-5 py-3.5 text-gray-700"><?php echo e($ins->inspector ?? '—'); ?></td>
                    <td class="px-5 py-3.5 text-center">
                        <?php $t = (int)($ins->inspecttimes ?? 1); ?>
                        <?php if($t > 1): ?>
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-700 text-xs font-bold"><?php echo e($t); ?></span>
                        <?php else: ?>
                        <span class="text-gray-400">1</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <?php if($isPassed): ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            <i class="fas fa-check-circle text-green-500"></i> Passed
                        </span>
                        <?php elseif($isFailed): ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            <i class="fas fa-times-circle text-red-500"></i> Failed
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <div class="flex items-center justify-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                            <a href="<?php echo e(route('inspections.show', $ins->seriesno)); ?>"
                               class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                               title="View Details">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <?php if($isPassed): ?>
                            <a href="<?php echo e(route('inspections.certificate', $ins->seriesno)); ?>"
                               class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 transition"
                               title="Certificate">
                                <i class="fas fa-file-pdf text-xs"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php if($stats['total_inspections'] > 5): ?>
    <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-center">
        <a href="<?php echo e(route('vehicles.history', $vehicle->id)); ?>"
           class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            View all <?php echo e(number_format($stats['total_inspections'])); ?> inspections
            <i class="fas fa-arrow-right ml-1 text-xs"></i>
        </a>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\talk2\OneDrive\Desktop\inspection\resources\views/vehicles/show.blade.php ENDPATH**/ ?>