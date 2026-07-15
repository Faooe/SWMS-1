<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'employee' => null,
    'size' => '12',
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
    'employee' => null,
    'size' => '12',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php

$sizeClass = match($size){

    '8' => 'h-8 w-8 text-xs',

    '10' => 'h-10 w-10 text-sm',

    '12' => 'h-12 w-12 text-base',

    '16' => 'h-16 w-16 text-xl',

    '20' => 'h-20 w-20 text-2xl',

    '24' => 'h-24 w-24 text-3xl',

    default => 'h-12 w-12 text-base',

};

?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee && $employee->photo): ?>

<img

    src="<?php echo e(asset('storage/'.$employee->photo)); ?>"

    alt="<?php echo e($employee->full_name); ?>"

    class="<?php echo e($sizeClass); ?> rounded-full object-cover border border-slate-200">

<?php else: ?>

<div

    class="<?php echo e($sizeClass); ?> rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">

    <?php echo e(strtoupper(substr($employee?->full_name ?? 'U',0,1))); ?>


</div>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/ui/avatar.blade.php ENDPATH**/ ?>