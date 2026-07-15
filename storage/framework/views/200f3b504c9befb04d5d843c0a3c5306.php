<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
'color'=>'green'
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
'color'=>'green'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php

$colors=[

'green'=>'bg-green-100 text-green-700',

'red'=>'bg-red-100 text-red-700',

'yellow'=>'bg-yellow-100 text-yellow-700',

'blue'=>'bg-blue-100 text-blue-700',

];

?>

<span

class="rounded-full px-3 py-1 text-sm font-medium <?php echo e($colors[$color]); ?>"

>

<?php echo e($slot); ?>


</span><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/ui/badge.blade.php ENDPATH**/ ?>