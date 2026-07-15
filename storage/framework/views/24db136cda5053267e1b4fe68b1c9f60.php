<?php $__env->startSection('title', 'My Assignment'); ?>

<?php $__env->startSection('page-title', 'My Assignment'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-6">

    
    
    

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                My Assignment

            </h1>

            <p class="mt-1 text-sm text-slate-500">

                Lihat dan kelola semua penugasan kamu.

            </p>

        </div>

        <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

            <?php echo e($assignments->total()); ?> Assignment

        </span>

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

                    placeholder="Cari assignment..."

                    class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            </div>

            <select

                name="status"

                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option value="">All Status</option>

                <option

                    value="Assigned"

                    <?php if(request('status') == 'Assigned'): echo 'selected'; endif; ?>>

                    Assigned

                </option>

                <option

                    value="In Progress"

                    <?php if(request('status') == 'In Progress'): echo 'selected'; endif; ?>>

                    In Progress

                </option>

                <option

                    value="Completed"

                    <?php if(request('status') == 'Completed'): echo 'selected'; endif; ?>>

                    Completed

                </option>

                <option

                    value="Cancelled"

                    <?php if(request('status') == 'Cancelled'): echo 'selected'; endif; ?>>

                    Cancelled

                </option>

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

    
    
    

    <div class="grid gap-5">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <?php echo $__env->make('employee.assignments.partials.card', [

                'assignment' => $assignment,

            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

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

                <div class="py-16 text-center">

                    <i

                        data-lucide="clipboard-list"

                        class="mx-auto h-12 w-12 text-slate-300">

                    </i>

                    <h3 class="mt-5 text-lg font-bold text-slate-800">

                        No Assignment

                    </h3>

                    <p class="mt-2 text-sm text-slate-500">

                        Kamu belum memiliki assignment apapun.

                    </p>

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

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    
    
    

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assignments->hasPages()): ?>

        <div>

            <?php echo e($assignments->appends(request()->query())->links()); ?>


        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/assignments/index.blade.php ENDPATH**/ ?>