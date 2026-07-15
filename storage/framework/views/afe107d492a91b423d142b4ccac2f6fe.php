<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([

'type'=>'button',

'variant'=>'primary'

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

'type'=>'button',

'variant'=>'primary'

]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php

$style=[

'primary'=>'bg-blue-600 hover:bg-blue-700 text-white',

'secondary'=>'bg-slate-100 hover:bg-slate-200 text-slate-700',

'danger'=>'bg-red-600 hover:bg-red-700 text-white',

][$variant];

?>

<button

type="<?php echo e($type); ?>"

<?php echo e($attributes->merge([

'class'=>"inline-flex items-center gap-2 rounded-xl px-5 py-3 font-medium transition {$style}"

])); ?>


>

<?php echo e($slot); ?>


</button><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/ui/button.blade.php ENDPATH**/ ?>