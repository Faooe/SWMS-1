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

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100">

                <i data-lucide="map-pinned" class="h-5 w-5 text-amber-600"></i>

            </div>

            <h3 class="text-lg font-bold text-slate-800">

                Assignment Attendance

            </h3>

        </div>

    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$assignment): ?>

        <p class="text-sm text-slate-500">

            Tidak ada assignment aktif untuk hari ini.

        </p>

    <?php else: ?>

        <div class="rounded-2xl bg-slate-50 p-4">

            <p class="font-semibold text-slate-800">

                <?php echo e($assignment->title); ?>


            </p>

            <p class="mt-1 text-sm text-slate-500">

                <?php echo e($assignment->location_name); ?>


            </p>

        </div>

        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">

            <div>

                <p class="text-slate-500">Radius</p>

                <p class="font-semibold text-slate-800"><?php echo e($assignment->radius); ?> m</p>

            </div>

            <div>

                <p class="text-slate-500">Status</p>

                <p class="font-semibold text-slate-800">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assignmentAttendance && $assignmentAttendance->isCompleted()): ?>

                        Completed

                    <?php elseif($assignmentAttendance && $assignmentAttendance->hasCheckedIn()): ?>

                        Checked In

                    <?php else: ?>

                        Not Checked In

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </p>

            </div>

        </div>

           <a href="<?php echo e(route('employee.assignments.show', $assignment->uuid)); ?>" class="mt-5 block rounded-xl bg-amber-600 py-3 text-center font-semibold text-white transition hover:bg-amber-700">

                  Open Assignment

            </a>

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
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/attendance/partials/assignment-card.blade.php ENDPATH**/ ?>