<div class="overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-blue-600 to-cyan-500 p-8 text-white shadow-sm">

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

        <div class="flex items-center gap-4">

            <div class="rounded-full ring-4 ring-white/30">

                <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.avatar','data' => ['employee' => $employee,'size' => '16']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['employee' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee),'size' => '16']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2)): ?>
<?php $attributes = $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2; ?>
<?php unset($__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2)): ?>
<?php $component = $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2; ?>
<?php unset($__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2); ?>
<?php endif; ?>

            </div>

            <div>

                <h1 class="text-2xl font-bold lg:text-3xl">
                    Halo, <?php echo e($employee->full_name); ?>

                </h1>

                <p class="mt-1 text-sm text-blue-100 lg:text-base">
                    Selamat datang kembali. <?php echo e(now()->translatedFormat('l, d F Y')); ?>

                </p>

            </div>

        </div>

        <div class="flex items-center gap-3 lg:flex-col lg:items-end">

            <span class="text-xs font-medium uppercase tracking-wide text-blue-100">
                Status Hari Ini
            </span>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($todayAttendance): ?>

                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold backdrop-blur">
                    <span class="h-2.5 w-2.5 rounded-full bg-green-300"></span>
                    <?php echo e($todayAttendance->attendance_status); ?>

                </span>

            <?php else: ?>

                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold backdrop-blur">
                    <span class="h-2.5 w-2.5 rounded-full bg-red-300"></span>
                    Belum Check In
                </span>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

    </div>

</div>
<?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/dashboard/partials/greeting.blade.php ENDPATH**/ ?>