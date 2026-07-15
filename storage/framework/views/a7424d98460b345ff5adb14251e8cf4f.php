<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'value' => '-',
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
    'label',
    'value' => '-',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div>

    <label
        class="mb-2 block text-sm font-semibold text-slate-500">

        <?php echo e($label); ?>


    </label>

    <div
        class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800">

        <?php echo e(filled($value) ? $value : '-'); ?>


    </div>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/ui/detail-item.blade.php ENDPATH**/ ?>