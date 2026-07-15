<?php $__env->startSection('title', 'Company Management'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-6">

    
    
    

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Company Management

            </h1>

            <p class="mt-1 text-sm text-slate-500">

                Kelola seluruh perusahaan yang menggunakan Smart Workforce Management System.

            </p>

        </div>

        <a href="<?php echo e(route('platform.companies.create')); ?>">

            <button

                type="button"

                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

                <i data-lucide="plus" class="h-4 w-4"></i>

                Add Company

            </button>

        </a>

    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>

        <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">

            <?php echo e(session('success')); ?>


        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    
    

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-5">

        <div class="rounded-2xl bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <p class="text-sm text-slate-500">Total Company</p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">

                    <i data-lucide="building-2" class="h-5 w-5 text-blue-600"></i>

                </div>

            </div>

            <h2 class="mt-3 text-3xl font-bold text-slate-800">

                <?php echo e($statistics['total']); ?>


            </h2>

        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <p class="text-sm text-slate-500">Active Company</p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100">

                    <i data-lucide="circle-check" class="h-5 w-5 text-green-600"></i>

                </div>

            </div>

            <h2 class="mt-3 text-3xl font-bold text-slate-800">

                <?php echo e($statistics['active']); ?>


            </h2>

        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <p class="text-sm text-slate-500">Free Plan</p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">

                    <i data-lucide="package" class="h-5 w-5 text-slate-600"></i>

                </div>

            </div>

            <h2 class="mt-3 text-3xl font-bold text-slate-800">

                <?php echo e($statistics['free']); ?>


            </h2>

        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <p class="text-sm text-slate-500">Premium Plan</p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100">

                    <i data-lucide="gem" class="h-5 w-5 text-amber-600"></i>

                </div>

            </div>

            <h2 class="mt-3 text-3xl font-bold text-slate-800">

                <?php echo e($statistics['premium']); ?>


            </h2>

        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <p class="text-sm text-slate-500">Employees</p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100">

                    <i data-lucide="users" class="h-5 w-5 text-purple-600"></i>

                </div>

            </div>

            <h2 class="mt-3 text-3xl font-bold text-slate-800">

                <?php echo e($statistics['employees']); ?>


            </h2>

        </div>

    </div>

    
    
    

    <div class="rounded-2xl bg-white p-5 shadow-sm">

        <form method="GET" class="flex flex-col gap-3 md:flex-row">

            <div class="relative flex-1">

                <i data-lucide="search" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>

                <input

                    type="text"

                    name="search"

                    value="<?php echo e(request('search')); ?>"

                    placeholder="Cari nama, kode, atau email company..."

                    class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            </div>

            <button

                type="submit"

                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

                <i data-lucide="search" class="h-4 w-4"></i>

                Search

            </button>

        </form>

    </div>

    
    
    

    <div class="rounded-2xl bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Company

                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Super Admin

                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Plan

                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Employee

                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Status

                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <?php

                            $admin = $company->users

                                ->firstWhere('role.code', 'SUPER_ADMIN');

                            $employeeRatio = $company->max_employee > 0

                                ? $company->employees_count / $company->max_employee

                                : 0;

                        ?>

                        <tr class="border-t border-slate-100">

                            
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-4">

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($company->logo): ?>

                                        <img

                                            src="<?php echo e(asset('storage/'.$company->logo)); ?>"

                                            class="h-12 w-12 rounded-xl object-cover">

                                    <?php else: ?>

                                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 font-bold text-blue-700">

                                            <?php echo e(strtoupper(substr($company->name, 0, 1))); ?>


                                        </div>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <div>

                                        <h3 class="font-semibold text-slate-800">

                                            <?php echo e($company->name); ?>


                                        </h3>

                                        <p class="text-sm text-slate-500">

                                            <?php echo e($company->code); ?>


                                        </p>

                                    </div>

                                </div>

                            </td>

                            
                            <td class="px-6 py-5">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($admin): ?>

                                    <div class="font-medium text-slate-800">

                                        <?php echo e($admin->employee?->full_name); ?>


                                    </div>

                                    <div class="text-sm text-slate-500">

                                        <?php echo e($admin->email); ?>


                                    </div>

                                <?php else: ?>

                                    <span class="text-sm text-slate-400">—</span>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </td>

                            
                            <td class="px-6 py-5 text-center">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($company->subscription_plan === 'Free'): ?>

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">

                                        <i data-lucide="package" class="h-3.5 w-3.5"></i>

                                        Free

                                    </span>

                                <?php elseif($company->subscription_plan === 'Premium Go'): ?>

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700">

                                        <i data-lucide="zap" class="h-3.5 w-3.5"></i>

                                        Premium Go

                                    </span>

                                <?php else: ?>

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-100 px-3 py-1.5 text-xs font-semibold text-purple-700">

                                        <i data-lucide="crown" class="h-3.5 w-3.5"></i>

                                        <?php echo e($company->subscription_plan); ?>


                                    </span>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </td>

                            
                            <td class="px-6 py-5 text-center">

                                <span class="text-sm font-semibold <?php echo e($employeeRatio >= 0.9 ? 'text-amber-600' : 'text-slate-800'); ?>">

                                    <?php echo e($company->employees_count); ?>


                                </span>

                                <span class="text-sm text-slate-400">

                                    / <?php echo e($company->max_employee); ?>


                                </span>

                            </td>

                            
                            <td class="px-6 py-5 text-center">

                                <form

                                    action="<?php echo e(route('platform.companies.toggle-status', $company)); ?>"

                                    method="POST"

                                    onsubmit="return confirm('<?php echo e($company->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?> company <?php echo e($company->name); ?>?')">

                                    <?php echo csrf_field(); ?>

                                    <?php echo method_field('PATCH'); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($company->is_active): ?>

                                        <button

                                            type="submit"

                                            class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700 transition hover:bg-green-200">

                                            <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>

                                            Active

                                        </button>

                                    <?php else: ?>

                                        <button

                                            type="submit"

                                            class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-200">

                                            <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>

                                            Inactive

                                        </button>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </form>

                            </td>

                            
                            <td class="px-6 py-5">

                                <div class="flex justify-center gap-2">

                                    <a href="<?php echo e(route('platform.companies.show', $company)); ?>">

                                        <button

                                            type="button"

                                            class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">

                                            Detail

                                        </button>

                                    </a>

                                    <a href="<?php echo e(route('platform.companies.edit', $company)); ?>">

                                        <button

                                            type="button"

                                            class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">

                                            Edit

                                        </button>

                                    </a>

                                    <form

                                        action="<?php echo e(route('platform.companies.destroy', $company)); ?>"

                                        method="POST"

                                        onsubmit="return confirm('Yakin ingin menghapus company ini?')">

                                        <?php echo csrf_field(); ?>

                                        <?php echo method_field('DELETE'); ?>

                                        <button

                                            type="submit"

                                            class="rounded-xl bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100">

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td colspan="6" class="py-16 text-center">

                                <i data-lucide="building-2" class="mx-auto h-10 w-10 text-slate-300"></i>

                                <h3 class="mt-4 text-lg font-bold text-slate-800">

                                    Belum Ada Company

                                </h3>

                                <p class="mt-1 text-sm text-slate-500">

                                    Silakan tambahkan company baru.

                                </p>

                                <a href="<?php echo e(route('platform.companies.create')); ?>" class="mt-4 inline-block">

                                    <button

                                        type="button"

                                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

                                        <i data-lucide="plus" class="h-4 w-4"></i>

                                        Add Company

                                    </button>

                                </a>

                            </td>

                        </tr>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </tbody>

            </table>

        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($companies->hasPages()): ?>

            <div class="border-t border-slate-100 px-6 py-4">

                <?php echo e($companies->links()); ?>


            </div>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/platform/company/index.blade.php ENDPATH**/ ?>