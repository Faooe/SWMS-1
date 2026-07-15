<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="sticky top-0 z-10 bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Employee
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Office
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Date
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Check In
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr class="hover:bg-slate-50 transition">

                        
                        <td class="px-6 py-5">

                            <div class="flex items-center gap-3">

                                <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.avatar','data' => ['name' => $attendance->employee->full_name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attendance->employee->full_name)]); ?>
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

                                <div>

                                    <div class="font-semibold text-slate-800">

                                        <?php echo e($attendance->employee->full_name); ?>


                                    </div>

                                    <div class="text-sm text-slate-500">

                                        <?php echo e($attendance->employee->employee_number); ?>


                                    </div>

                                </div>

                            </div>

                        </td>

                        
                        <td class="px-6 py-5">

                            <?php echo e($attendance->office?->name ?? '-'); ?>


                        </td>

                        
                        <td class="px-6 py-5">

                            <?php echo e($attendance->attendance_date->format('d M Y')); ?>


                        </td>

                        
                        <td class="px-6 py-5">

                            <?php echo e(optional($attendance->check_in_time)->format('H:i') ?? '-'); ?>


                        </td>

                        
                        <td class="px-6 py-5">

                            <?php

                                $color = match($attendance->attendance_status){

                                    'Present' => 'green',

                                    'Late' => 'orange',

                                    'Absent' => 'red',

                                    'Leave' => 'purple',

                                    default => 'blue',

                                };

                            ?>

                            <span
                                class="rounded-full bg-<?php echo e($color); ?>-100 px-3 py-1 text-sm font-semibold text-<?php echo e($color); ?>-700">

                                <?php echo e($attendance->attendance_status); ?>


                            </span>

                        </td>

                        
                        <td class="px-6 py-5 text-center">

                            <a

                                href="<?php echo e(route('attendance.show',$attendance->id)); ?>"

                                class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">

                                Detail

                            </a>

                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td colspan="6" class="py-12 text-center text-slate-400">

                            No attendance data found.

                        </td>

                    </tr>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </tbody>

        </table>

    </div>

    <div class="border-t bg-slate-50 px-6 py-4">

        <?php echo e($attendances->links()); ?>


    </div>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/attendance/partials/table.blade.php ENDPATH**/ ?>