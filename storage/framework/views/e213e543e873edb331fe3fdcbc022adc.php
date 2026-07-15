<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    
    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            Activity Timeline

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Employee attendance activities.

        </p>

    </div>

    <div
        class="max-h-[380px] overflow-y-auto pr-3">

        <div class="relative ml-4 border-l-2 border-slate-200">

            
            <div class="relative pb-10 pl-8">

                <div
                    class="absolute -left-[11px] flex h-5 w-5 items-center justify-center rounded-full bg-green-500 ring-4 ring-white">

                </div>

                <h3 class="font-semibold">

                    Employee Check In

                </h3>

                <p class="mt-1 text-sm text-slate-500">

                    <?php echo e($attendance->check_in_time
                        ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i')
                        : '-'); ?>


                </p>

            </div>

            
            <div class="relative pb-10 pl-8">

                <div
                    class="absolute -left-[11px] flex h-5 w-5 items-center justify-center rounded-full bg-blue-500 ring-4 ring-white">

                </div>

                <h3 class="font-semibold">

                    GPS Verification

                </h3>

                <p class="mt-1 text-sm text-slate-500">

                    <?php echo e($attendance->location_verified
                        ? 'Location Verified'
                        : 'Location Not Verified'); ?>


                </p>

                <p class="text-xs text-slate-400">

                    Distance

                    <?php echo e(number_format($attendance->check_in_distance ?? 0,2)); ?>


                    Meter

                </p>

            </div>

            
            <div class="relative pb-10 pl-8">

                <div
                    class="absolute -left-[11px] flex h-5 w-5 items-center justify-center rounded-full bg-purple-500 ring-4 ring-white">

                </div>

                <h3 class="font-semibold">

                    Attendance Status

                </h3>

                <span
                    class="mt-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-sm">

                    <?php echo e($attendance->attendance_status); ?>


                </span>

            </div>

            
            <div class="relative pl-8">

                <div
                    class="absolute -left-[11px] flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 ring-4 ring-white">

                </div>

                <h3 class="font-semibold">

                    Employee Check Out

                </h3>

                <p class="mt-1 text-sm text-slate-500">

                    <?php echo e($attendance->check_out_time
                        ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i')
                        : 'Not Checked Out Yet'); ?>


                </p>

            </div>

        </div>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/attendance/partials/timeline-card.blade.php ENDPATH**/ ?>