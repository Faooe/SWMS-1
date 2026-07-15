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

<div class="flex flex-col gap-6 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between">

    
    <div>

        <a
            href="<?php echo e(route('assignments.index')); ?>"
            class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition hover:text-blue-700">

            <i
                data-lucide="arrow-left"
                class="h-4 w-4">
            </i>

            Back to Assignment

        </a>

        <h1 class="text-3xl font-bold text-slate-800">

            <?php echo e($assignment->title); ?>


        </h1>

        <div class="mt-4 flex flex-wrap gap-3">

            <span
                class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">

                <?php echo e($assignment->assignment_type); ?>


            </span>

            <?php

                $priorityColor = match($assignment->priority){

                    'Critical' => 'bg-red-100 text-red-700',

                    'High' => 'bg-orange-100 text-orange-700',

                    'Medium' => 'bg-yellow-100 text-yellow-700',

                    default => 'bg-green-100 text-green-700'

                };

                $statusColor = match($assignment->status){

                    'Completed' => 'bg-green-100 text-green-700',

                    'Assigned' => 'bg-blue-100 text-blue-700',

                    'In Progress' => 'bg-orange-100 text-orange-700',

                    'Cancelled' => 'bg-red-100 text-red-700',

                    default => 'bg-slate-100 text-slate-700'

                };

            ?>

            <span
                class="rounded-full px-4 py-2 text-sm font-semibold <?php echo e($priorityColor); ?>">

                <?php echo e($assignment->priority); ?>


            </span>

            <span
                class="rounded-full px-4 py-2 text-sm font-semibold <?php echo e($statusColor); ?>">

                <?php echo e($assignment->status); ?>


            </span>

        </div>

    </div>

    
    <div class="flex items-center gap-3">

    <a
        href="<?php echo e(route('assignments.edit', $assignment)); ?>"
        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 transition hover:bg-amber-200">

        <i
            data-lucide="square-pen"
            class="h-5 w-5">
        </i>

    </a>

    <form
        action="<?php echo e(route('assignments.destroy',$assignment)); ?>"
        method="POST"
        onsubmit="return confirm('Delete this assignment?')">

        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>

        <button
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-700 transition hover:bg-red-200">

            <i
                data-lucide="trash-2"
                class="h-5 w-5">
            </i>

        </button>

    </form>

</div>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/assignment/show/header.blade.php ENDPATH**/ ?>