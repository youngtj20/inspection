
<?php
    $name      = $name     ?? 'department';
    $allLabel  = $allLabel ?? 'All Departments';
    $selected  = $selected ?? null;
    $multiple  = $multiple ?? false;
    $class     = $class    ?? '';
    $style     = $style    ?? '';
    $nameAttr  = $multiple ? $name . '[]' : $name;
?>

<select name="<?php echo e($nameAttr); ?>"
        <?php echo e($multiple ? 'multiple' : ''); ?>

        class="ts-select <?php echo e($class); ?>"
        <?php if($style): ?> style="<?php echo e($style); ?>" <?php endif; ?>>

    <?php if(!$multiple): ?>
        
        <option value=""><?php echo e($allLabel); ?></option>

        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php $stateSelected = (string)$selected === (string)$state['id']; ?>
            <option value="<?php echo e($state['id']); ?>"
                    <?php echo e($stateSelected ? 'selected' : ''); ?>

                    data-dept-type="state">
                <?php echo e($state['title']); ?>

            </option>

            
            <?php $__currentLoopData = $state['centers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $cSelected = (string)$selected === (string)$center->id; ?>
                <option value="<?php echo e($center->id); ?>" <?php echo e($cSelected ? 'selected' : ''); ?>

                        data-dept-type="center">
                    <?php echo e($center->title); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php else: ?>
        
        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <optgroup label="▸ <?php echo e($state['title']); ?>">
                <?php $__currentLoopData = $state['centers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isSelected = in_array((string)$center->id, array_map('strval', (array) $selected));
                    ?>
                    <option value="<?php echo e($center->id); ?>" <?php echo e($isSelected ? 'selected' : ''); ?>>
                        <?php echo e($center->title); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </optgroup>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

</select>
<?php /**PATH C:\Users\talk2\OneDrive\Desktop\inspection\resources\views/partials/dept-select.blade.php ENDPATH**/ ?>