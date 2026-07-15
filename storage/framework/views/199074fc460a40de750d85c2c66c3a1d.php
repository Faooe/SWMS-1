<?php $__env->startSection('title', 'Attendance Management'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-8">

    
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Attendance Management

            </h1>

            <p class="mt-2 text-slate-500">

                Monitor employee attendance, check-in, check-out, GPS validation, and attendance history.

            </p>

        </div>

        <div class="flex items-center gap-3">

            <a
                href="<?php echo e(route('attendance.index')); ?>"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100">

                Refresh

            </a>

            <a
                href="<?php echo e(route('attendance.export.pdf', request()->query())); ?>"
                class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">

                Export PDF

            </a>

        </div>

    </div>

    
    <?php echo $__env->make('attendance.partials.statistics', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('attendance.partials.filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('attendance.partials.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/attendance/index.blade.php ENDPATH**/ ?>