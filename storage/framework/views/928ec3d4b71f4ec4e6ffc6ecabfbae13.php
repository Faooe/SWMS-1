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

<tr class="transition hover:bg-slate-50">

    
    <td class="px-6 py-5">

        <div class="font-semibold">

            <?php echo e($assignment->assignment_number); ?>


        </div>

    </td>

    
    <td class="px-6 py-5">

        <div class="font-semibold">

            <?php echo e($assignment->title); ?>


        </div>

        <div class="mt-1 text-sm text-slate-500">

            <?php echo e($assignment->assignment_type); ?>


        </div>

    </td>

    
    <td class="px-6 py-5">

        <?php echo e($assignment->office?->name); ?>


    </td>

    
    <td class="px-6 py-5">

        <?php

            $priorityColor = match($assignment->priority){

                'Critical' => 'bg-red-100 text-red-700',

                'High' => 'bg-orange-100 text-orange-700',

                'Medium' => 'bg-yellow-100 text-yellow-700',

                default => 'bg-green-100 text-green-700'

            };

        ?>

        <span class="rounded-full px-3 py-1 text-xs font-semibold <?php echo e($priorityColor); ?>">

            <?php echo e($assignment->priority); ?>


        </span>

    </td>

    
    <td class="px-6 py-5">

        <?php

            $statusColor = match($assignment->status){

                'Completed' => 'bg-green-100 text-green-700',

                'Assigned' => 'bg-blue-100 text-blue-700',

                'In Progress' => 'bg-orange-100 text-orange-700',

                'Cancelled' => 'bg-red-100 text-red-700',

                default => 'bg-slate-100 text-slate-700'

            };

        ?>

        <span class="rounded-full px-3 py-1 text-xs font-semibold <?php echo e($statusColor); ?>">

            <?php echo e($assignment->status); ?>


        </span>

    </td>

    
    <td class="px-6 py-5">

        <?php echo e($assignment->employees->count()); ?>


    </td>

    
    <td class="px-6 py-5 text-sm">

        <div>

            <?php echo e($assignment->start_datetime->format('d M Y')); ?>


        </div>

        <div class="text-slate-500">

            <?php echo e($assignment->start_datetime->format('H:i')); ?>


            -

            <?php echo e($assignment->end_datetime->format('H:i')); ?>


        </div>

    </td>

    
    <td class="px-6 py-5">

        <div class="flex items-center gap-2">

            <a
                href="<?php echo e(route('assignments.show',$assignment)); ?>"
                class="rounded-xl bg-blue-100 p-2 text-blue-700 transition hover:bg-blue-200">

                <i data-lucide="eye" class="h-4 w-4"></i>

            </a>

            <a
                href="<?php echo e(route('assignments.edit',$assignment)); ?>"
                class="rounded-xl bg-yellow-100 p-2 text-yellow-700 transition hover:bg-yellow-200">

                <i data-lucide="pencil" class="h-4 w-4"></i>

            </a>

            <form
                method="POST"
                action="<?php echo e(route('assignments.destroy',$assignment)); ?>"
                onsubmit="return confirm('Delete this assignment?')">

                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>

                <button
                    class="rounded-xl bg-red-100 p-2 text-red-700 transition hover:bg-red-200">

                    <i data-lucide="trash-2" class="h-4 w-4"></i>

                </button>

            </form>

        </div>

    </td>

</tr><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/assignment/table/row.blade.php ENDPATH**/ ?>