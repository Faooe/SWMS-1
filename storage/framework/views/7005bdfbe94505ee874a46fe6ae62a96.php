<?php $__env->startSection('title', 'Office Management'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-8">

    
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Office Management

            </h1>

            <p class="mt-2 text-slate-500">

                Manage office locations, GPS coordinates, attendance radius, and office information.

            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="<?php echo e(route('offices.create')); ?>"
                class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">

                + Add Office

            </a>

            <a
                href="#"
                class="rounded-xl bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-700">

                Export PDF

            </a>

        </div>

    </div>

    
    <?php echo $__env->make('office.partials.statistics', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('office.partials.filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('office.partials.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/office/index.blade.php ENDPATH**/ ?>