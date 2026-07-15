<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'assignment' => null,
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
    'assignment' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="flex items-center justify-end gap-4">

    <a
        href="<?php echo e(route('assignments.index')); ?>"
        class="rounded-2xl border border-slate-300 px-6 py-3 font-medium text-slate-700 transition hover:bg-slate-100">

        Cancel

    </a>

    <button
        type="submit"
        class="rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">

        <?php echo e($assignment ? 'Update Assignment' : 'Create Assignment'); ?>


    </button>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/assignment/forms/action-buttons.blade.php ENDPATH**/ ?>