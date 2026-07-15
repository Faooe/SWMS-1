<?php $__env->startSection('title', 'Attendance Detail'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-8">

    
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Attendance Detail

            </h1>

            <p class="mt-2 text-slate-500">

                Complete attendance information, GPS validation, attendance photos, and activity timeline.

            </p>

        </div>

        <div class="flex items-center gap-3">

            <a
                href="<?php echo e(route('attendance.index')); ?>"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 hover:bg-slate-100">

                ← Back

            </a>

        </div>

    </div>

    
    <div class="grid grid-cols-12 gap-6">

        
        <div class="col-span-12 lg:col-span-6">

            <?php echo $__env->make('attendance.partials.employee-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>

        
        <div class="col-span-12 lg:col-span-6">

            <?php echo $__env->make('attendance.partials.attendance-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>

        
        <div class="col-span-12 lg:col-span-5">

            <?php echo $__env->make('attendance.partials.gps-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>

        
        <div class="col-span-12 lg:col-span-7">

            <?php echo $__env->make('attendance.partials.photos-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>

        
        <div class="col-span-12">

            <?php echo $__env->make('attendance.partials.timeline-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>

    </div>

</div>
<div
    id="photoModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80">

    <div class="relative max-w-6xl">

        <button

            onclick="closePhoto()"

            class="absolute -top-12 right-0 rounded-lg bg-white px-4 py-2">

            ✕

        </button>

        <img

            id="photoPreview"

            class="max-h-[90vh] rounded-2xl shadow-2xl">

    </div>

</div>
<?php $__env->startPush('scripts'); ?>

<script>

function openPhoto(url){

    document
        .getElementById('photoPreview')
        .src=url;

    document
        .getElementById('photoModal')
        .classList
        .remove('hidden');

    document
        .getElementById('photoModal')
        .classList
        .add('flex');

}

function closePhoto(){

    document
        .getElementById('photoModal')
        .classList
        .remove('flex');

    document
        .getElementById('photoModal')
        .classList
        .add('hidden');

}

</script>

<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/attendance/show.blade.php ENDPATH**/ ?>