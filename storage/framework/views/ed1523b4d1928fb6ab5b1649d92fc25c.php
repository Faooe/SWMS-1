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

    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Date</th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Type</th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Check In</th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Check Out</th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>

                </tr>

            </thead>

            <tbody>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr class="border-t">

                        <td class="px-4 py-4 text-sm">

                            <?php echo e(\Carbon\Carbon::parse($item->attendance_date)->translatedFormat('d M Y')); ?>


                        </td>

                        <td class="px-4 py-4 text-sm">

                            <?php echo e($item->attendance_type); ?>


                        </td>

                        <td class="px-4 py-4 text-sm">

                            <?php echo e($item->check_in_time ?? '-'); ?>


                        </td>

                        <td class="px-4 py-4 text-sm">

                            <?php echo e($item->check_out_time ?? '-'); ?>


                        </td>

                        <td class="px-4 py-4">

                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">

                                <?php echo e($item->attendance_status); ?>


                            </span>

                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td colspan="5" class="py-8 text-center text-sm text-slate-500">

                            Belum ada riwayat absensi.

                        </td>

                    </tr>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </tbody>

        </table>

    </div>

    <div class="mt-4">

        <?php echo e($history->links()); ?>


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
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/attendance/partials/attendance-history.blade.php ENDPATH**/ ?>