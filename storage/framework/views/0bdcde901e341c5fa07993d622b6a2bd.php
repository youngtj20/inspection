<?php $__env->startSection('title', 'Inspection Details'); ?>

<?php
    /**
     * Helper: treat √ / v / 1 / OK as Pass, everything else as Fail.
     * Used for test-table status columns that contain legacy Chinese inspection symbols.
     */
    function inspPass($val): bool {
        return in_array(trim((string)$val), ['√', 'v', '1', 'OK', 'PASS'], true);
    }
?>
<?php $__env->startSection('content'); ?>
<div x-data="inspectionDetails()">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="<?php echo e(route('inspections.index')); ?>" class="text-gray-600 hover:text-gray-800 transition">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800">Inspection Details</h1>
                </div>
                <p class="text-gray-600">Series No: <span class="font-semibold text-gray-800"><?php echo e($inspection->seriesno); ?></span></p>
            </div>
            <div class="flex items-center gap-3">
                <?php if(in_array($inspection->testresult, ['Y', 'N', '1', '0'])): ?>
                    <?php $isPassed = in_array($inspection->testresult, ['Y', '1']); ?>
                    <a href="<?php echo e(route('inspections.certificate', $inspection->id)); ?>"
                       class="inline-flex items-center px-4 py-2 <?php echo e($isPassed ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'); ?> text-white font-semibold rounded-lg shadow-md transition duration-150">
                        <i class="fas fa-certificate mr-2"></i>
                        <?php echo e($isPassed ? 'View Certificate (PASSED)' : 'View Certificate (FAILED)'); ?>

                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('inspections.edit', $inspection->id)); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="mb-6">
        <?php if(in_array($inspection->testresult, ['1', 'Y'])): ?>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-check-circle text-4xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">INSPECTION PASSED</h2>
                            <p class="text-green-100 mt-1">This vehicle has successfully passed all inspection tests</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-green-100">Inspection Date</div>
                        <div class="text-xl font-bold"><?php echo e(\Carbon\Carbon::parse($inspection->inspectdate)->format('M d, Y')); ?></div>
                    </div>
                </div>
            </div>
        <?php elseif(in_array($inspection->testresult, ['0', 'N'])): ?>
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-times-circle text-4xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">INSPECTION FAILED</h2>
                            <p class="text-red-100 mt-1">This vehicle did not pass the inspection requirements</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-red-100">Inspection Date</div>
                        <div class="text-xl font-bold"><?php echo e(\Carbon\Carbon::parse($inspection->inspectdate)->format('M d, Y')); ?></div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-clock text-4xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">INSPECTION PENDING</h2>
                            <p class="text-yellow-100 mt-1">This inspection is still in progress</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-yellow-100">Started On</div>
                        <div class="text-xl font-bold"><?php echo e(\Carbon\Carbon::parse($inspection->inspectdate)->format('M d, Y')); ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Vehicle Information -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-car mr-2"></i>
                    Vehicle Information
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start justify-between pb-4 border-b border-gray-200">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Plate Number</div>
                            <div class="text-lg font-bold text-gray-900"><?php echo e($inspection->plateno); ?></div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            <?php echo e($inspection->vehicletype); ?>

                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Make</div>
                            <div class="text-sm font-medium text-gray-900"><?php echo e($inspection->makeofvehicle ?? 'N/A'); ?></div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Model</div>
                            <div class="text-sm font-medium text-gray-900"><?php echo e($inspection->model ?? 'N/A'); ?></div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Owner</div>
                        <div class="text-sm font-medium text-gray-900"><?php echo e($inspection->owner ?? 'N/A'); ?></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Engine No</div>
                            <div class="text-sm font-medium text-gray-900"><?php echo e($inspection->engineno ?? 'N/A'); ?></div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Chassis No</div>
                            <div class="text-sm font-medium text-gray-900"><?php echo e($inspection->chassisno ?? 'N/A'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inspection Information -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Inspection Information
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Inspection Date</div>
                            <div class="text-sm font-medium text-gray-900">
                                <?php echo e(\Carbon\Carbon::parse($inspection->inspectdate)->format('M d, Y')); ?>

                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?php echo e(\Carbon\Carbon::parse($inspection->inspectdate)->format('h:i A')); ?>

                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Inspection Times</div>
                            <div class="text-sm font-medium text-gray-900"><?php echo e($inspection->inspecttimes ?? 1); ?></div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-200">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Inspector</div>
                        <div class="text-sm font-medium text-gray-900"><?php echo e($inspection->inspector ?? 'Not Assigned'); ?></div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Department</div>
                        <div class="text-sm font-medium text-gray-900"><?php echo e($inspection->department_name ?? 'N/A'); ?></div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">License Type</div>
                        <div class="text-sm font-medium text-gray-900"><?php echo e($inspection->licencetype ?? 'N/A'); ?></div>
                    </div>

                    <div class="pt-2 border-t border-gray-200">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Created Date</div>
                        <div class="text-sm font-medium text-gray-900">
                            <?php echo e(\Carbon\Carbon::parse($inspection->createDate)->format('M d, Y h:i A')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Results Tabs -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6" x-data="{ activeTab: 'brake' }">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-vial mr-2"></i>
                Test Results
            </h3>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 bg-gray-50">
            <nav class="flex flex-wrap -mb-px px-6" aria-label="Tabs">
                <button @click="activeTab = 'brake'" 
                        :class="activeTab === 'brake' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-car-crash mr-2"></i>Brake Test
                </button>
                <button @click="activeTab = 'sideslip'" 
                        :class="activeTab === 'sideslip' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-arrows-alt-h mr-2"></i>Side Slip
                </button>
                <button @click="activeTab = 'emission'" 
                        :class="activeTab === 'emission' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-smog mr-2"></i>Emission
                </button>
                <button @click="activeTab = 'headlamp'" 
                        :class="activeTab === 'headlamp' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-lightbulb mr-2"></i>Headlamp
                </button>
                <button @click="activeTab = 'suspension'" 
                        :class="activeTab === 'suspension' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-car-side mr-2"></i>Suspension
                </button>
                <button @click="activeTab = 'visual'" 
                        :class="activeTab === 'visual' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-eye mr-2"></i>Visual
                </button>
                <button @click="activeTab = 'pit'"
                        :class="activeTab === 'pit' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-wrench mr-2"></i>Pit Inspection
                </button>
                <?php if($speedometerData): ?>
                <button @click="activeTab = 'speedometer'"
                        :class="activeTab === 'speedometer' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i>Speedometer
                </button>
                <?php endif; ?>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Brake Test Tab -->
            <div x-show="activeTab === 'brake'" x-cloak>
                <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-car-crash text-indigo-600 mr-2"></i>
                    Brake Test Results
                </h4>

                
                <h5 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2 mt-1">Service Brake</h5>
                <div class="overflow-x-auto mb-6">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Axle</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Left Load (kg)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Right Load (kg)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total Load (kg)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Left Force (N)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Right Force (N)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Efficiency (%)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Diff</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                                $brakeAxles = [
                                    'front'  => ['label' => 'Front',  'color' => 'blue'],
                                    'rear'   => ['label' => 'Rear',   'color' => 'purple'],
                                    'rear02' => ['label' => 'Rear 2', 'color' => 'indigo'],
                                    'rear03' => ['label' => 'Rear 3', 'color' => 'indigo'],
                                    'rear04' => ['label' => 'Rear 4', 'color' => 'indigo'],
                                    'rear05' => ['label' => 'Rear 5', 'color' => 'indigo'],
                                    'rear06' => ['label' => 'Rear 6', 'color' => 'indigo'],
                                ];
                            ?>
                            <?php $__currentLoopData = $brakeAxles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($brakeData[$key])): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-<?php echo e($meta['color']); ?>-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-car text-<?php echo e($meta['color']); ?>-600 text-xs"></i>
                                            </div>
                                            <div class="ml-2 text-sm font-semibold text-gray-900"><?php echo e($meta['label']); ?></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($brakeData[$key]->lftaxleload ?? '—'); ?></td>
                                    <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($brakeData[$key]->rgtaxleload ?? '—'); ?></td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900"><?php echo e($brakeData[$key]->axleload ?? '—'); ?></td>
                                    <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($brakeData[$key]->lftbrakeforce ?? '—'); ?></td>
                                    <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($brakeData[$key]->rgtbrakeforce ?? '—'); ?></td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900"><?php echo e($brakeData[$key]->brakeeff ?? '—'); ?>%</td>
                                    <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($brakeData[$key]->brakediff ?? '—'); ?></td>
                                    <td class="px-4 py-3 text-center">
                                        <?php if(inspPass($brakeData[$key]->stsbrakeeff ?? '')): ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Pass</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Fail</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($brakeData['summary']): ?>
                                <tr class="bg-blue-50 font-semibold">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" colspan="6">Overall Brake Summary</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e(number_format($brakeData['summary']->tolbrakeeff ?? 0, 2)); ?>%</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if(inspPass($brakeData['summary']->stsbrakeeff ?? '')): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Pass
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i> Fail
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if(!$brakeData['front'] && !$brakeData['rear']): ?>
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">No brake test data available</h3>
                                            <p class="text-gray-500">Brake tests have not been performed yet</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <?php if(!empty($brakeData['rear']) && ($brakeData['rear']->lfthandbrake || $brakeData['rear']->rgthandbrake || $brakeData['rear']->handbrakeeff)): ?>
                <h5 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2 mt-4">Handbrake</h5>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-orange-50 border-b border-orange-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Axle</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Left Force (N)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Right Force (N)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Left Eff (%)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Right Eff (%)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total Eff (%)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Diff</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Eff Status</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Diff Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $hb = $brakeData['rear']; ?>
                            <tr class="hover:bg-orange-50 transition">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-hand-rock text-orange-600 text-xs"></i>
                                        </div>
                                        <div class="ml-2 text-sm font-semibold text-gray-900">Rear</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($hb->lfthandbrake ?? '—'); ?></td>
                                <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($hb->rgthandbrake ?? '—'); ?></td>
                                <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($hb->lfthandbrakeeff ?? '—'); ?>%</td>
                                <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($hb->rgthandbrakeeff ?? '—'); ?>%</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900"><?php echo e($hb->handbrakeeff ?? '—'); ?>%</td>
                                <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($hb->handbrakediff ?? '—'); ?></td>
                                <td class="px-4 py-3 text-center">
                                    <?php if(inspPass($hb->stshandbrakeeff ?? '')): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Pass</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Fail</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <?php if(inspPass($hb->stshandbrakediff ?? '')): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Pass</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Fail</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

            </div>

            <!-- Side Slip Test Tab -->
            <div x-show="activeTab === 'sideslip'" x-cloak>
                <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-arrows-alt-h text-indigo-600 mr-2"></i>
                    Side Slip Test Results
                </h4>
                <?php if($sideslipData): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Side Slip Value</div>
                            <div class="text-2xl font-bold text-gray-900"><?php echo e($sideslipData->slide ?? 'N/A'); ?></div>
                            <div class="text-xs text-gray-500 mt-1">m/km</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Test Status</div>
                            <div class="mt-2">
                                <?php if(inspPass($sideslipData->stsslide ?? '')): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Pass
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Fail
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Standard Limit</div>
                            <div class="text-2xl font-bold text-gray-900">≤ 5</div>
                            <div class="text-xs text-gray-500 mt-1">m/km</div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No side slip test data available</h3>
                        <p class="text-gray-500">Side slip test has not been performed yet</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Emission Test Tab -->
            <div x-show="activeTab === 'emission'" x-cloak>
                <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-smog text-indigo-600 mr-2"></i>
                    Emission Test Results
                </h4>
                
                <!-- Gas Emission Data -->
                <?php if($emissionData): ?>
                    <div class="mb-6">
                        <h5 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-wind text-blue-600 mr-2"></i>
                            Gas Emission Test (Petrol Vehicles)
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                                <div class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-2">Idle HC</div>
                                <div class="text-3xl font-bold text-blue-900"><?php echo e($emissionData->idlhcaverage ?? 'N/A'); ?></div>
                                <div class="text-xs text-blue-600 mt-1">ppm (Standard: ≤ 200)</div>
                                <div class="mt-2">
                                    <?php if(inspPass($emissionData->stsidlhc ?? '')): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Pass
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i> Fail
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                                <div class="text-xs font-semibold text-green-700 uppercase tracking-wider mb-2">Idle CO</div>
                                <div class="text-3xl font-bold text-green-900"><?php echo e($emissionData->idlcoaverage ?? 'N/A'); ?></div>
                                <div class="text-xs text-green-600 mt-1">% (Standard: ≤ 0.5)</div>
                                <div class="mt-2">
                                    <?php if(inspPass($emissionData->stsidlco ?? '')): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Pass
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i> Fail
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                                <div class="text-xs font-semibold text-purple-700 uppercase tracking-wider mb-2">High HC</div>
                                <div class="text-3xl font-bold text-purple-900"><?php echo e($emissionData->hghhcaverage ?? 'N/A'); ?></div>
                                <div class="text-xs text-purple-600 mt-1">ppm</div>
                            </div>
                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                                <div class="text-xs font-semibold text-orange-700 uppercase tracking-wider mb-2">High CO</div>
                                <div class="text-3xl font-bold text-orange-900"><?php echo e($emissionData->hghcoaverage ?? 'N/A'); ?></div>
                                <div class="text-xs text-orange-600 mt-1">%</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Smoke Emission Data -->
                <?php if($smokeData): ?>
                    <div class="mb-6">
                        <h5 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-cloud text-gray-600 mr-2"></i>
                            Smoke Emission Test (Diesel Vehicles)
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                                <div class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Smoke (N) Average</div>
                                <div class="text-3xl font-bold text-gray-900"><?php echo e($smokeData->naverage ?? 'N/A'); ?></div>
                                <div class="text-xs text-gray-600 mt-1">Readings: <?php echo e($smokeData->n1 ?? '-'); ?> / <?php echo e($smokeData->n2 ?? '-'); ?> / <?php echo e($smokeData->n3 ?? '-'); ?> / <?php echo e($smokeData->n4 ?? '-'); ?></div>
                                <div class="mt-2">
                                    <?php if(inspPass($smokeData->stsn ?? '')): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Pass
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i> Fail
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-4 border border-indigo-200">
                                <div class="text-xs font-semibold text-indigo-700 uppercase tracking-wider mb-2">Opacity (K) Average</div>
                                <div class="text-3xl font-bold text-indigo-900"><?php echo e($smokeData->kaverage ?? 'N/A'); ?></div>
                                <div class="text-xs text-indigo-600 mt-1">Readings: <?php echo e($smokeData->k1 ?? '-'); ?> / <?php echo e($smokeData->k2 ?? '-'); ?> / <?php echo e($smokeData->k3 ?? '-'); ?> / <?php echo e($smokeData->k4 ?? '-'); ?></div>
                                <div class="mt-2">
                                    <?php if(inspPass($smokeData->stsk ?? '')): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Pass
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i> Fail
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(!$emissionData && !$smokeData): ?>
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No emission test data available</h3>
                        <p class="text-gray-500">Emission test has not been performed yet</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Headlamp Test Tab -->
            <div x-show="activeTab === 'headlamp'" x-cloak>
                <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-lightbulb text-indigo-600 mr-2"></i>
                    Headlamp Test Results
                </h4>
                <?php if($headlampData['left'] || $headlampData['right']): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php if($headlampData['left']): ?>
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                                    Left Headlamp
                                </h5>
                                <?php if(inspPass($headlampData['left']->stslightintensity ?? '')): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Pass
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> Fail
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Light Intensity:</span>
                                    <span class="text-lg font-bold text-gray-900"><?php echo e($headlampData['left']->lightintensity ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">LR Offset (Far):</span>
                                    <span class="text-lg font-bold text-gray-900"><?php echo e($headlampData['left']->offsetlrfar ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">UD Offset (Far):</span>
                                    <span class="text-lg font-bold text-gray-900"><?php echo e($headlampData['left']->offsetudfar ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Height:</span>
                                    <span class="text-lg font-bold text-gray-900"><?php echo e($headlampData['left']->height ?? 'N/A'); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($headlampData['right']): ?>
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                                    Right Headlamp
                                </h5>
                                <?php if(inspPass($headlampData['right']->stslightintensity ?? '')): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Pass
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> Fail
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Light Intensity:</span>
                                    <span class="text-lg font-bold text-gray-900"><?php echo e($headlampData['right']->lightintensity ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">LR Offset (Far):</span>
                                    <span class="text-lg font-bold text-gray-900"><?php echo e($headlampData['right']->offsetlrfar ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">UD Offset (Far):</span>
                                    <span class="text-lg font-bold text-gray-900"><?php echo e($headlampData['right']->offsetudfar ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Height:</span>
                                    <span class="text-lg font-bold text-gray-900"><?php echo e($headlampData['right']->height ?? 'N/A'); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No headlamp test data available</h3>
                        <p class="text-gray-500">Headlamp test has not been performed yet</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Suspension Test Tab -->
            <div x-show="activeTab === 'suspension'" x-cloak>
                <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-car-side text-indigo-600 mr-2"></i>
                    Suspension Test Results
                </h4>
                <?php if($suspensionData['front'] || $suspensionData['rear']): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Axle</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Left Suspension (%)</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Right Suspension (%)</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Difference (%)</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if($suspensionData['front']): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-car text-blue-600"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-semibold text-gray-900">Front</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($suspensionData['front']->lftsuspension ?? 'N/A'); ?>%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($suspensionData['front']->rgtsuspension ?? 'N/A'); ?>%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($suspensionData['front']->suspensiondiff ?? 'N/A'); ?>%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <?php if(inspPass($suspensionData['front']->stssuspension ?? '')): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Pass
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Fail
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if($suspensionData['rear']): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-car text-purple-600"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-semibold text-gray-900">Rear</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($suspensionData['rear']->lftsuspension ?? 'N/A'); ?>%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($suspensionData['rear']->rgtsuspension ?? 'N/A'); ?>%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($suspensionData['rear']->suspensiondiff ?? 'N/A'); ?>%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <?php if(inspPass($suspensionData['rear']->stssuspension ?? '')): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Pass
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Fail
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No suspension test data available</h3>
                        <p class="text-gray-500">Suspension test has not been performed yet</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Visual Inspection Tab -->
            <div x-show="activeTab === 'visual'" x-cloak>
                <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-eye text-indigo-600 mr-2"></i>
                    Visual Inspection Results
                </h4>
                <?php if($visualData && count($visualData) > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $visualData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visual): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-orange-500">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                                <?php echo e($visual->defectcode ?? 'N/A'); ?>

                                            </span>
                                            <span class="text-sm font-semibold text-gray-900"><?php echo e($visual->category ?? 'N/A'); ?></span>
                                        </div>
                                        <?php if($visual->description): ?>
                                            <p class="text-sm text-gray-600 mt-2"><?php echo e($visual->description); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No visual inspection data available</h3>
                        <p class="text-gray-500">Visual inspection has not been performed yet</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pit Inspection Tab -->
            <div x-show="activeTab === 'pit'" x-cloak>
                <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-wrench text-indigo-600 mr-2"></i>
                    Pit Inspection Results
                </h4>
                <?php if($pitData && count($pitData) > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $pitData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                <?php echo e($pit->defectcode ?? 'N/A'); ?>

                                            </span>
                                            <span class="text-sm font-semibold text-gray-900"><?php echo e($pit->category ?? 'N/A'); ?></span>
                                        </div>
                                        <?php if($pit->description): ?>
                                            <p class="text-sm text-gray-600 mt-2"><?php echo e($pit->description); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No pit inspection data available</h3>
                        <p class="text-gray-500">Pit inspection has not been performed yet</p>
                    </div>
                <?php endif; ?>
            </div>
            <!-- Speedometer Test Tab -->
            <?php if($speedometerData): ?>
            <div x-show="activeTab === 'speedometer'" x-cloak>
                <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-tachometer-alt text-indigo-600 mr-2"></i>
                    Speedometer Test Results
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Speed Reading</div>
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($speedometerData->speed ?? 'N/A'); ?></div>
                        <div class="text-xs text-gray-500 mt-1">km/h</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Test Status</div>
                        <div class="mt-2">
                            <?php if(inspPass($speedometerData->stsspeed ?? '')): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Pass
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Fail
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Conclusion -->
    <?php if($inspection->conclusion): ?>
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-file-alt mr-2"></i>
                Conclusion & Remarks
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700 leading-relaxed"><?php echo e($inspection->conclusion); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="flex items-center gap-3 flex-wrap">
        <a href="<?php echo e(route('inspections.index')); ?>" 
           class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition duration-150">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to List
        </a>
        <a href="<?php echo e(route('inspections.edit', $inspection->id)); ?>" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150">
            <i class="fas fa-edit mr-2"></i>
            Edit Inspection
        </a>
        <?php if(in_array($inspection->testresult, ['Y', 'N', '1', '0'])): ?>
            <?php $isPassed = in_array($inspection->testresult, ['Y', '1']); ?>
            <a href="<?php echo e(route('inspections.certificate', $inspection->id)); ?>"
               class="inline-flex items-center px-4 py-2 <?php echo e($isPassed ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'); ?> text-white font-medium rounded-lg transition duration-150">
                <i class="fas fa-certificate mr-2"></i>
                <?php echo e($isPassed ? 'Certificate (PASSED)' : 'Certificate (FAILED)'); ?>

            </a>
        <?php endif; ?>
        <button @click="deleteInspection()" 
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-150 ml-auto">
            <i class="fas fa-trash mr-2"></i>
            Delete Inspection
        </button>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function inspectionDetails() {
    return {
        deleteInspection() {
            if (confirm('Are you sure you want to delete this inspection record? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo e(route("inspections.destroy", $inspection->id)); ?>';
                
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\talk2\OneDrive\Desktop\inspection\resources\views/inspections/show.blade.php ENDPATH**/ ?>