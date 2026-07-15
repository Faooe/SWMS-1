<?php $__env->startSection('title','Assignment Detail'); ?>

<?php $__env->startSection('page-title','Assignment Detail'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-6">

    <a
        href="<?php echo e(route('employee.assignments.index')); ?>"
        class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700">

        <i
            data-lucide="arrow-left"
            class="h-4 w-4">
        </i>

        Back to Assignment

    </a>

    <?php echo $__env->make('employee.assignments.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('employee.assignments.partials.description', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('employee.assignments.partials.location', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('employee.assignments.partials.team', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('employee.assignments.partials.timeline', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('employee.assignments.partials.actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/assignments/show.blade.php ENDPATH**/ ?>