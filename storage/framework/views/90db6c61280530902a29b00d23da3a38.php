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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.section-card','data' => ['title' => 'Assignment Information','description' => 'General information about this assignment.','icon' => 'clipboard-list']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.section-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Assignment Information','description' => 'General information about this assignment.','icon' => 'clipboard-list']); ?>

    <div class="grid gap-6 md:grid-cols-2">

        <div>

            <p class="text-sm text-slate-500">

                Assignment Number

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                <?php echo e($assignment->assignment_number); ?>


            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Office

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                <?php echo e($assignment->office?->name); ?>


            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Assignment Type

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                <?php echo e($assignment->assignment_type); ?>


            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Priority

            </p>

            <span class="mt-2 inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">

                <?php echo e($assignment->priority); ?>


            </span>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Status

            </p>

            <span class="mt-2 inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">

                <?php echo e($assignment->status); ?>


            </span>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Created By

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                <?php echo e($assignment->creator?->employee?->full_name); ?>


            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Start Date

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                <?php echo e($assignment->start_datetime->format('d M Y H:i')); ?>


            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                End Date

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                <?php echo e($assignment->end_datetime->format('d M Y H:i')); ?>


            </h3>

        </div>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6f3b777e07976364c604768a84e2a4f)): ?>
<?php $attributes = $__attributesOriginalf6f3b777e07976364c604768a84e2a4f; ?>
<?php unset($__attributesOriginalf6f3b777e07976364c604768a84e2a4f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6f3b777e07976364c604768a84e2a4f)): ?>
<?php $component = $__componentOriginalf6f3b777e07976364c604768a84e2a4f; ?>
<?php unset($__componentOriginalf6f3b777e07976364c604768a84e2a4f); ?>
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/assignment/show/information.blade.php ENDPATH**/ ?>