<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'assignment'
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
    'assignment'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if (isset($component)) { $__componentOriginalf6f3b777e07976364c604768a84e2a4f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6f3b777e07976364c604768a84e2a4f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.section-card','data' => ['title' => 'Assigned Employee','description' => 'Employees assigned to this assignment.','icon' => 'users']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.section-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Assigned Employee','description' => 'Employees assigned to this assignment.','icon' => 'users']); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assignment->employees->isEmpty()): ?>

        <div class="rounded-2xl border border-dashed border-slate-300 py-12 text-center">

            <i
                data-lucide="users"
                class="mx-auto h-12 w-12 text-slate-300">
            </i>

            <h3 class="mt-4 text-lg font-semibold text-slate-700">

                No Employee Assigned

            </h3>

            <p class="mt-2 text-slate-500">

                Assign employees from Edit Assignment.

            </p>

        </div>

    <?php else: ?>

        <div class="grid gap-5 lg:grid-cols-2">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $assignment->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php

                    $status = $employee->pivot?->status ?? 'Assigned';

                    $badge = match($status){

                        'Completed'
                            => 'bg-green-100 text-green-700',

                        'In Progress'
                            => 'bg-blue-100 text-blue-700',

                        'Rejected'
                            => 'bg-red-100 text-red-700',

                        'Accepted'
                            => 'bg-cyan-100 text-cyan-700',

                        default
                            => 'bg-yellow-100 text-yellow-700',

                    };

                ?>

                <div
                    class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md">

                    <div class="flex items-center gap-4">

                        
                        <div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->photo): ?>

                                <img
                                    src="<?php echo e(asset('storage/'.$employee->photo)); ?>"
                                    class="h-16 w-16 rounded-full object-cover">

                            <?php else: ?>

                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">

                                    <i
                                        data-lucide="user"
                                        class="h-8 w-8 text-blue-600">
                                    </i>

                                </div>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        </div>

                        
                        <div class="flex-1">

                            <h3 class="text-lg font-bold text-slate-800">

                                <?php echo e($employee->full_name); ?>


                            </h3>

                            <p class="text-sm text-slate-500">

                                <?php echo e($employee->currentEmployment?->position?->name ?? '-'); ?>


                            </p>

                            <p class="mt-1 text-xs text-slate-400">

                                <?php echo e($employee->currentEmployment?->office?->name ?? '-'); ?>


                            </p>

                        </div>

                        
                        <div>

                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold <?php echo e($badge); ?>">

                                <?php echo e($status); ?>


                            </span>

                        </div>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6f3b777e07976364c604768a84e2a4f)): ?>
<?php $attributes = $__attributesOriginalf6f3b777e07976364c604768a84e2a4f; ?>
<?php unset($__attributesOriginalf6f3b777e07976364c604768a84e2a4f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6f3b777e07976364c604768a84e2a4f)): ?>
<?php $component = $__componentOriginalf6f3b777e07976364c604768a84e2a4f; ?>
<?php unset($__componentOriginalf6f3b777e07976364c604768a84e2a4f); ?>
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/assignment/show/employees.blade.php ENDPATH**/ ?>