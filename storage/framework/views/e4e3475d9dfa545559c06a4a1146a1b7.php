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

            Employee Information

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Employee identity and placement.

        </p>

    </div>

    
    <div class="space-y-5">

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Full Name

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->employee?->full_name ?? '-'); ?>


            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Employee Number

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->employee?->employee_number ?? '-'); ?>


            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Position

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->employee?->currentEmployment?->position?->name ?? '-'); ?>


            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Office

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->employee?->currentEmployment?->office?->name ?? '-'); ?>


            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Attendance Type

            </span>

            <span class="rounded-full bg-slate-100 px-4 py-1 text-sm font-semibold">

                <?php echo e($attendance->attendance_type); ?>


            </span>

        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attendance->attendance_type === 'ASSIGNMENT' && $attendance->assignment): ?>

            <hr>

            <div class="flex items-center justify-between">

                <span class="text-slate-500">

                    Assignment

                </span>

                <span class="font-semibold">

                    <?php echo e($attendance->assignment->title); ?>


                </span>

            </div>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/attendance/partials/employee-card.blade.php ENDPATH**/ ?>