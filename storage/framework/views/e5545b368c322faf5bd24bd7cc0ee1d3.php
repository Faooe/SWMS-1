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

            Attendance Information

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Employee attendance summary.

        </p>

    </div>

    
    <div class="space-y-5">

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Attendance Date

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->attendance_date->format('d F Y')); ?>


            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Status

            </span>

            <?php

                $statusColor = match($attendance->attendance_status){

                    'Present' => 'bg-green-100 text-green-700',

                    'Late' => 'bg-orange-100 text-orange-700',

                    'Leave' => 'bg-blue-100 text-blue-700',

                    'Permission' => 'bg-yellow-100 text-yellow-700',

                    'Absent' => 'bg-red-100 text-red-700',

                    default => 'bg-slate-100 text-slate-700',

                };

            ?>

            <span
                class="rounded-full px-4 py-1 text-sm font-semibold <?php echo e($statusColor); ?>">

                <?php echo e($attendance->attendance_status); ?>


            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Check In

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->check_in_time
                    ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i')
                    : '-'); ?>


            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Check Out

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->check_out_time
                    ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i')
                    : '-'); ?>


            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Late Minutes

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->late_minutes); ?> Minute

            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Allowed Radius

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->allowed_radius); ?> m

            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Employee Distance

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->check_in_distance); ?> m

            </span>

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
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/attendance/partials/attendance-card.blade.php ENDPATH**/ ?>