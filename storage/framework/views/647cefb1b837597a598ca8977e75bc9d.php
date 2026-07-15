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

    <h3 class="mb-5 text-lg font-semibold text-slate-800">
        Quick Actions
    </h3>

    <div class="grid grid-cols-2 gap-4">

        <a
            href="<?php echo e(route('employee.assignments.index')); ?>"
            class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 p-5 text-center transition hover:border-blue-300 hover:bg-blue-50">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                <i data-lucide="clipboard-list" class="h-5 w-5"></i>
            </div>

            <span class="text-sm font-semibold text-slate-700">Assignment</span>

        </a>

        <a
            href="<?php echo e(route('employee.attendance.index')); ?>"
            class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 p-5 text-center transition hover:border-green-300 hover:bg-green-50">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-600">
                <i data-lucide="calendar-check" class="h-5 w-5"></i>
            </div>

            <span class="text-sm font-semibold text-slate-700">Attendance</span>

        </a>

        <a
            href="<?php echo e(route('employee.attendance.history')); ?>"
            class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 p-5 text-center transition hover:border-purple-300 hover:bg-purple-50">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                <i data-lucide="history" class="h-5 w-5"></i>
            </div>

            <span class="text-sm font-semibold text-slate-700">History</span>

        </a>

        <a
            href="<?php echo e(route('employee.profile')); ?>"
            class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 p-5 text-center transition hover:border-orange-300 hover:bg-orange-50">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                <i data-lucide="user-round" class="h-5 w-5"></i>
            </div>

            <span class="text-sm font-semibold text-slate-700">Profile</span>

        </a>

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
<?php endif; ?>
<?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/dashboard/partials/quick-actions.blade.php ENDPATH**/ ?>