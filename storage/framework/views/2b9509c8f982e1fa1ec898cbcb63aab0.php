<?php $__env->startSection('title', 'Leave / Permission Management'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-6">

    
    
    

    <div>

        <h1 class="text-3xl font-bold text-slate-800">

            Leave / Permission Management

        </h1>

        <p class="mt-2 text-slate-500">

            Review dan setujui pengajuan izin karyawan. Izin yang disetujui otomatis
            tercatat sebagai attendance status Permission dan aman dari auto-absent.

        </p>

    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>

        <div class="rounded-2xl bg-green-100 px-5 py-4 text-sm font-medium text-green-700">

            <?php echo e(session('success')); ?>


        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>

        <div class="rounded-2xl bg-red-100 px-5 py-4 text-sm font-medium text-red-700">

            <?php echo e(session('error')); ?>


        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    
    

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

        <form

            method="GET"

            class="grid gap-4 md:grid-cols-4">

            <div class="relative md:col-span-2">

                <i

                    data-lucide="search"

                    class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400">

                </i>

                <input

                    type="text"

                    name="search"

                    value="<?php echo e(request('search')); ?>"

                    placeholder="Cari nama karyawan..."

                    class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            </div>

            <select

                name="status"

                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option value="">All Status</option>

                <option value="Pending" <?php if(request('status') == 'Pending'): echo 'selected'; endif; ?>>Pending</option>

                <option value="Approved" <?php if(request('status') == 'Approved'): echo 'selected'; endif; ?>>Approved</option>

                <option value="Rejected" <?php if(request('status') == 'Rejected'): echo 'selected'; endif; ?>>Rejected</option>

            </select>

            <button

                type="submit"

                class="rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

                Filter

            </button>

        </form>

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

    
    
    

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'overflow-hidden p-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'overflow-hidden p-0']); ?>

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

        <table class="w-full text-left text-sm">

            <thead class="sticky top-0 z-10 border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">

                <tr>

                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Period</th>
                    <th class="px-6 py-4">Reason</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Action</th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <td class="px-6 py-4 font-medium text-slate-800">

                            <?php echo e($leave->employee->full_name); ?>


                        </td>

                        <td class="px-6 py-4 text-slate-600">

                            <?php echo e($leave->type); ?>


                        </td>

                        <td class="px-6 py-4 text-slate-600">

                            <?php echo e($leave->start_date->format('d M Y')); ?>

                            &mdash;
                            <?php echo e($leave->end_date->format('d M Y')); ?>

                            <span class="text-xs text-slate-400">
                                (<?php echo e($leave->duration); ?> hari)
                            </span>

                        </td>

                        <td class="max-w-xs truncate px-6 py-4 text-slate-600">

                            <?php echo e($leave->reason); ?>


                        </td>

                        <td class="px-6 py-4">

                            <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['color' => match($leave->status) {
                                'Approved' => 'green',
                                'Rejected' => 'red',
                                default => 'yellow',
                            }]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(match($leave->status) {
                                'Approved' => 'green',
                                'Rejected' => 'red',
                                default => 'yellow',
                            })]); ?>

                                <?php echo e($leave->status); ?>


                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>

                        </td>

                        <td class="px-6 py-4">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($leave->canBeReviewed()): ?>

                                <div class="flex items-center justify-end gap-2">

                                    <form

                                        method="POST"

                                        action="<?php echo e(route('leaves.approve', $leave)); ?>">

                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>

                                        <button

                                            type="submit"

                                            class="rounded-xl bg-green-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-green-700">

                                            Approve

                                        </button>

                                    </form>

                                    <form

                                        method="POST"

                                        action="<?php echo e(route('leaves.reject', $leave)); ?>">

                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>

                                        <button

                                            type="submit"

                                            class="rounded-xl bg-red-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-red-700">

                                            Reject

                                        </button>

                                    </form>

                                </div>

                            <?php else: ?>

                                <span class="block text-right text-xs text-slate-400">

                                    Reviewed by <?php echo e($leave->approver?->name ?? '-'); ?>


                                </span>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td colspan="6" class="px-6 py-16 text-center text-slate-500">

                            Belum ada pengajuan izin.

                        </td>

                    </tr>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </tbody>

        </table>

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

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($leaves->hasPages()): ?>

        <div>

            <?php echo e($leaves->appends(request()->query())->links()); ?>


        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/leaves/index.blade.php ENDPATH**/ ?>