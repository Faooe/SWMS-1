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
            Assignment Hari Ini
        </h3>

        <a
            href="<?php echo e(route('employee.assignments.index')); ?>"
            class="text-sm font-medium text-blue-600 hover:underline">
            Lihat Semua &rarr;
        </a>

    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($todayAssignment): ?>

        <?php
            $statusColor = match($todayAssignment->status) {
                'Draft' => 'bg-slate-100 text-slate-700',
                'Assigned' => 'bg-blue-100 text-blue-700',
                'In Progress' => 'bg-orange-100 text-orange-700',
                'Completed' => 'bg-green-100 text-green-700',
                'Cancelled' => 'bg-red-100 text-red-700',
                default => 'bg-slate-100 text-slate-700',
            };
        ?>

        <div class="rounded-2xl border border-slate-200 p-5">

            <div class="flex items-start justify-between gap-3">

                <h4 class="font-bold text-slate-800">
                    <?php echo e($todayAssignment->title); ?>

                </h4>

                <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-semibold <?php echo e($statusColor); ?>">
                    <?php echo e($todayAssignment->status); ?>

                </span>

            </div>

            <p class="mt-2 flex items-center gap-1.5 text-sm text-slate-500">
                <i data-lucide="map-pin" class="h-4 w-4"></i>
                <?php echo e($todayAssignment->location_name); ?>

            </p>

            <a
                href="<?php echo e(route('employee.assignments.show', $todayAssignment->uuid)); ?>"
                class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:underline">
                Lihat Detail &rarr;
            </a>

        </div>

    <?php else: ?>

        <div class="flex flex-col items-center justify-center py-10 text-center">

            <i data-lucide="clipboard-x" class="mb-3 h-10 w-10 text-slate-300"></i>

            <p class="text-slate-500">Tidak ada assignment hari ini.</p>

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
<?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/dashboard/partials/assignment.blade.php ENDPATH**/ ?>