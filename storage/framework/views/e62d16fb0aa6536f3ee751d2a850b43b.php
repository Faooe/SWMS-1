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

    <div class="mb-5 flex items-center justify-between">

        <h3 class="text-lg font-semibold text-slate-800">
            Attendance Hari Ini
        </h3>

        <a
            href="<?php echo e(route('employee.attendance.index')); ?>"
            class="text-sm font-medium text-blue-600 hover:underline">
            Lihat &rarr;
        </a>

    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($todayAttendance): ?>

        <div class="grid grid-cols-2 gap-4">

            <div class="rounded-2xl bg-slate-50 p-4">

                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Check In</p>

                <p class="mt-2 text-2xl font-bold text-slate-800">
                    <?php echo e(optional($todayAttendance->check_in_time)->format('H:i') ?? '-'); ?>

                </p>

            </div>

            <div class="rounded-2xl bg-slate-50 p-4">

                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Check Out</p>

                <p class="mt-2 text-2xl font-bold text-slate-800">
                    <?php echo e(optional($todayAttendance->check_out_time)->format('H:i') ?? '-'); ?>

                </p>

            </div>

        </div>

        <div class="mt-4 flex items-center gap-2">

            <span class="text-sm text-slate-500">Status:</span>

            <?php
                $statusColor = match($todayAttendance->attendance_status) {
                    'Present' => 'bg-green-100 text-green-700',
                    'Late' => 'bg-orange-100 text-orange-700',
                    'Absent' => 'bg-red-100 text-red-700',
                    default => 'bg-slate-100 text-slate-700',
                };
            ?>

            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold <?php echo e($statusColor); ?>">
                <?php echo e($todayAttendance->attendance_status); ?>

            </span>

            <span class="ml-auto inline-flex items-center gap-1 text-xs text-slate-400">
                <i data-lucide="<?php echo e($todayAttendance->attendance_type === 'OFFICE' ? 'building-2' : 'map-pin'); ?>" class="h-3.5 w-3.5"></i>
                <?php echo e($todayAttendance->attendance_type === 'OFFICE' ? 'Office' : 'Assignment'); ?>

            </span>

        </div>

    <?php else: ?>

        <div class="flex flex-col items-center justify-center py-10 text-center">

            <i data-lucide="clock-alert" class="mb-3 h-10 w-10 text-slate-300"></i>

            <p class="text-slate-500">Kamu belum melakukan check in hari ini.</p>

            <a
                href="<?php echo e(route('employee.attendance.index')); ?>"
                class="mt-4 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                Check In Sekarang
            </a>

        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
<?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/dashboard/partials/attendance.blade.php ENDPATH**/ ?>