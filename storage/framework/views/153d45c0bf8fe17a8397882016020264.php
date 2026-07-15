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

        <div>

            <h2 class="text-xl font-semibold text-slate-800">

                Office List

            </h2>

            <p class="mt-1 text-sm text-slate-500">

                <?php echo e($offices->total()); ?> office(s) found.

            </p>

        </div>

    </div>

    
    <div class="overflow-hidden rounded-2xl border border-slate-200">

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

            <table class="min-w-full whitespace-nowrap">

                <thead class="sticky top-0 z-10 bg-slate-100">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">

                            Office

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">

                            Code

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">

                            Province

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">

                            City

                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">

                            Radius

                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">

                            Employees

                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">

                            Status

                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $offices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr class="transition hover:bg-slate-50">

                            
                            <td class="px-6 py-4">

                                <div>

                                    <h3 class="font-semibold text-slate-800">

                                        <?php echo e($office->name); ?>


                                    </h3>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->is_head_office): ?>

                                        <span class="mt-1 inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">

                                            Head Office

                                        </span>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </div>

                            </td>

                            
                            <td class="px-6 py-4">

                                <?php echo e($office->code); ?>


                            </td>

                            
                            <td class="px-6 py-4">

                                <?php echo e($office->province ?? '-'); ?>


                            </td>

                            
                            <td class="px-6 py-4">

                                <?php echo e($office->city ?? '-'); ?>


                            </td>

                            
                            <td class="px-6 py-4 text-center">

                                <?php echo e(number_format($office->radius)); ?> m

                            </td>

                            
                            <td class="px-6 py-4 text-center">

                                <?php echo e($office->employees_count); ?>


                            </td>

                            
                            <td class="px-6 py-4 text-center">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($office->is_active): ?>

                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">

                                        Active

                                    </span>

                                <?php else: ?>

                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">

                                        Inactive

                                    </span>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </td>

                            
                            <td class="px-6 py-4">

                                <div class="flex justify-center gap-2">

                                    <a
                                        href="<?php echo e(route('offices.show',$office)); ?>"
                                        class="rounded-lg bg-sky-100 p-2 text-sky-700 transition hover:bg-sky-200">

                                        <i data-lucide="eye" class="h-4 w-4"></i>

                                    </a>

                                    <a
                                        href="<?php echo e(route('offices.edit',$office)); ?>"
                                        class="rounded-lg bg-amber-100 p-2 text-amber-700 transition hover:bg-amber-200">

                                        <i data-lucide="pencil" class="h-4 w-4"></i>

                                    </a>

                                    <form
                                        action="<?php echo e(route('offices.destroy',$office)); ?>"
                                        method="POST"
                                        onsubmit="return confirm('Delete this office?')">

                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button
                                            class="rounded-lg bg-red-100 p-2 text-red-700 transition hover:bg-red-200">

                                            <i data-lucide="trash-2" class="h-4 w-4"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td
                                colspan="8"
                                class="px-6 py-16 text-center text-slate-500">

                                No office data found.

                            </td>

                        </tr>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    
    <div class="mt-6">

        <?php echo e($offices->withQueryString()->links()); ?>


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
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/office/partials/table.blade.php ENDPATH**/ ?>