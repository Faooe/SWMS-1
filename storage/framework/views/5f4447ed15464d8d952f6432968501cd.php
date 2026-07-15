<?php $__env->startSection('title', 'Department Management'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-8">

    
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Department Management
            </h1>

            <p class="mt-2 text-slate-500">
                Kelola data department sebagai master data pengelompokan Team dan Employee.
            </p>

        </div>

        <a
            href="<?php echo e(route('departments.create')); ?>"
            class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">

            + Add Department

        </a>

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

    
    <div class="grid gap-6 md:grid-cols-3">

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
            <p class="text-sm text-slate-500">Total Department</p>
            <h2 class="mt-3 text-4xl font-bold"><?php echo e(number_format($statistics['total'])); ?></h2>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <p class="text-sm text-slate-500">Active</p>
            <h2 class="mt-3 text-4xl font-bold text-green-600"><?php echo e(number_format($statistics['active'])); ?></h2>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <p class="text-sm text-slate-500">Inactive</p>
            <h2 class="mt-3 text-4xl font-bold text-red-600"><?php echo e(number_format($statistics['inactive'])); ?></h2>
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

    </div>

    
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

        <form method="GET" class="grid gap-4 md:grid-cols-4">

            <div class="relative md:col-span-2">

                <i data-lucide="search" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>

                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Cari nama atau kode department..."
                    class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            </div>

            <select
                name="is_active"
                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option value="">All Status</option>
                <option value="1" <?php if(request('is_active') === '1'): echo 'selected'; endif; ?>>Active</option>
                <option value="0" <?php if(request('is_active') === '0'): echo 'selected'; endif; ?>>Inactive</option>

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

    
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

            <table class="min-w-full">

                <thead class="sticky top-0 z-10 bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Code</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Description</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Teams</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Action</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-5 font-semibold text-slate-800"><?php echo e($department->code); ?></td>

                            <td class="px-6 py-5">

                                <a
                                    href="<?php echo e(route('departments.show', $department)); ?>"
                                    class="font-medium text-blue-600 hover:underline">

                                    <?php echo e($department->name); ?>


                                </a>

                            </td>

                            <td class="max-w-xs truncate px-6 py-5 text-slate-500"><?php echo e($department->description ?? '-'); ?></td>

                            <td class="px-6 py-5 text-center"><?php echo e($department->teams_count); ?></td>

                            <td class="px-6 py-5 text-center">

                                <form
                                    action="<?php echo e(route('departments.toggle-status', $department)); ?>"
                                    method="POST"
                                    onsubmit="return confirm('<?php echo e($department->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?> department <?php echo e($department->name); ?>?')">

                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($department->is_active): ?>

                                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700 transition hover:bg-green-200">
                                            <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                                            Active
                                        </button>

                                    <?php else: ?>

                                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700 transition hover:bg-red-200">
                                            <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                            Inactive
                                        </button>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </form>

                            </td>

                            <td class="px-6 py-5">

                                <div class="flex items-center justify-center gap-2">

                                    <a
                                        href="<?php echo e(route('departments.show', $department)); ?>"
                                        title="Lihat Detail Department"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-600 transition hover:bg-blue-600 hover:text-white">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </a>

                                    <a
                                        href="<?php echo e(route('departments.edit', $department)); ?>"
                                        title="Edit Department"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-500 hover:text-white">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>

                                    <form
                                        action="<?php echo e(route('departments.destroy', $department)); ?>"
                                        method="POST"
                                        onsubmit="return confirm('Delete department <?php echo e($department->name); ?>?')">

                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button type="submit" title="Delete Department" class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-600 hover:text-white">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td colspan="6" class="py-20">

                                <div class="flex flex-col items-center justify-center">

                                    <i data-lucide="briefcase-business" class="mb-4 h-14 w-14 text-slate-300"></i>

                                    <h3 class="text-lg font-semibold text-slate-700">No Department Found</h3>

                                    <p class="mt-2 text-sm text-slate-400">There are no department records available.</p>

                                </div>

                            </td>

                        </tr>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
            <?php echo e($departments->withQueryString()->links()); ?>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/department/index.blade.php ENDPATH**/ ?>