<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([

    'title',

    'value' => 0,

    'icon' => 'layout-dashboard',

    'color' => 'blue',

    'description' => null,

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

    'title',

    'value' => 0,

    'icon' => 'layout-dashboard',

    'color' => 'blue',

    'description' => null,

]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php

$colors = [

    'blue' => [

        'card' => 'from-blue-500 to-blue-600',

        'icon' => 'bg-blue-100 text-blue-600',

    ],

    'green' => [

        'card' => 'from-green-500 to-green-600',

        'icon' => 'bg-green-100 text-green-600',

    ],

    'emerald' => [

        'card' => 'from-emerald-500 to-emerald-600',

        'icon' => 'bg-emerald-100 text-emerald-600',

    ],

    'red' => [

        'card' => 'from-red-500 to-red-600',

        'icon' => 'bg-red-100 text-red-600',

    ],

    'orange' => [

        'card' => 'from-orange-500 to-orange-600',

        'icon' => 'bg-orange-100 text-orange-600',

    ],

    'purple' => [

        'card' => 'from-purple-500 to-purple-600',

        'icon' => 'bg-purple-100 text-purple-600',

    ],

    'slate' => [

        'card' => 'from-slate-500 to-slate-600',

        'icon' => 'bg-slate-100 text-slate-600',

    ],

];

$current = $colors[$color] ?? $colors['blue'];

?>

<div
    class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

    <div class="flex items-center justify-between p-6">

        <div>

            <p class="text-sm font-medium text-slate-500">

                <?php echo e($title); ?>


            </p>

            <h2 class="mt-2 text-4xl font-bold text-slate-800">

                <?php echo e($value); ?>


            </h2>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($description): ?>

                <p class="mt-3 text-sm text-slate-500">

                    <?php echo e($description); ?>


                </p>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

        <div
            class="flex h-16 w-16 items-center justify-center rounded-2xl <?php echo e($current['icon']); ?>">

            <i
                data-lucide="<?php echo e($icon); ?>"
                class="h-8 w-8">

            </i>

        </div>

    </div>

    <div
        class="h-2 bg-gradient-to-r <?php echo e($current['card']); ?>">

    </div>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/ui/stat-card.blade.php ENDPATH**/ ?>