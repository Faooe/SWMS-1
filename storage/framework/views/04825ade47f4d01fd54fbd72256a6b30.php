<?php $__env->startSection('title', 'Employee Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-6">

    <?php echo $__env->make('employee.dashboard.partials.greeting', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('employee.dashboard.partials.statistics', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <?php echo $__env->make('employee.dashboard.partials.attendance', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('employee.dashboard.partials.assignment', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <?php echo $__env->make('employee.dashboard.partials.activities', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('employee.dashboard.partials.quick-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/dashboard/index.blade.php ENDPATH**/ ?>