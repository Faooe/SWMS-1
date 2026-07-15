<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'employees'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'employees'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

        <table class="min-w-full">

            
            <thead class="sticky top-0 z-10 bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Employee
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Department
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Position
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                        Action
                    </th>

                </tr>

            </thead>

            
            <tbody class="divide-y divide-slate-200">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr
                        class="transition duration-300 hover:bg-slate-50">

                        
                        <td class="px-6 py-5">

                            <div class="flex items-center gap-4">

                                <?php if (isset($component)) { $__componentOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald04dd79f9e235eb8e58dee4526a2f3c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.avatar','data' => ['employee' => $employee,'size' => '12']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['employee' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($employee),'size' => '12']); ?>
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

                                    <h3
                                        class="font-semibold text-slate-800">

                                        <?php echo e($employee->full_name); ?>


                                    </h3>

                                    <p
                                        class="text-sm text-slate-500">

                                        <?php echo e($employee->email); ?>


                                    </p>

                                </div>

                            </div>

                        </td>

                        
                        <td class="px-6 py-5">

                            <span class="font-medium">

                                <?php echo e($employee->currentEmployment?->department?->name ?? '-'); ?>


                            </span>

                        </td>

                        
                        <td class="px-6 py-5">

                            <span class="font-medium">

                                <?php echo e($employee->currentEmployment?->position?->name ?? '-'); ?>


                            </span>

                        </td>

                        
                        <td class="px-6 py-5">

                            <form
                                action="<?php echo e(route('employees.toggle-status', $employee)); ?>"
                                method="POST"
                                onsubmit="return confirm('<?php echo e($employee->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?> employee <?php echo e($employee->full_name); ?>?')">

                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->is_active): ?>

                                    <button
                                        type="submit"
                                        title="Klik untuk menonaktifkan"
                                        class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700 transition hover:bg-green-200">

                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-green-500">
                                        </span>

                                        Active

                                    </button>

                                <?php else: ?>

                                    <button
                                        type="submit"
                                        title="Klik untuk mengaktifkan"
                                        class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700 transition hover:bg-red-200">

                                        <span
                                            class="h-2.5 w-2.5 rounded-full bg-red-500">
                                        </span>

                                        Inactive

                                    </button>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </form>

                        </td>

                        
                        <td class="px-6 py-5">

                            <div
                                class="flex items-center justify-center gap-2">

                                
                                <a
                                    href="<?php echo e(route('employees.show',$employee)); ?>"
                                    title="View Employee"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-600 transition hover:bg-sky-600 hover:text-white">

                                    <i
                                        data-lucide="eye"
                                        class="h-4 w-4">
                                    </i>

                                </a>

                                
                                <a
                                    href="<?php echo e(route('employees.edit',$employee)); ?>"
                                    title="Edit Employee"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-500 hover:text-white">

                                    <i
                                        data-lucide="pencil"
                                        class="h-4 w-4">
                                    </i>

                                </a>

                                
                                <form
                                    action="<?php echo e(route('employees.destroy',$employee)); ?>"
                                    method="POST"
                                    onsubmit="return confirm('Delete employee <?php echo e($employee->full_name); ?>?')">

                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <button
                                        type="submit"
                                        title="Delete Employee"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-600 hover:text-white">

                                        <i
                                            data-lucide="trash-2"
                                            class="h-4 w-4">
                                        </i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td
                            colspan="5"
                            class="py-20">

                            <div
                                class="flex flex-col items-center justify-center">

                                <i
                                    data-lucide="users-round"
                                    class="mb-4 h-14 w-14 text-slate-300">
                                </i>

                                <h3
                                    class="text-lg font-semibold text-slate-700">

                                    No Employee Found

                                </h3>

                                <p
                                    class="mt-2 text-sm text-slate-400">

                                    There are no employee records available.

                                </p>

                            </div>

                        </td>

                    </tr>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </tbody>

        </table>

    </div>

    
    <div
        class="border-t border-slate-200 bg-slate-50 px-6 py-4">

        <?php echo e($employees->withQueryString()->links()); ?>


    </div>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/employee/table.blade.php ENDPATH**/ ?>