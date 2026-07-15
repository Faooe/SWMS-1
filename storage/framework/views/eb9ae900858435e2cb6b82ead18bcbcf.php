<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'description' => '',
    'icon' => 'folder'
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
    'description' => '',
    'icon' => 'folder'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    
    <div class="border-b border-slate-100 bg-slate-50 px-8 py-6">

        <div class="flex items-center gap-4">

            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100">

                <i
                    data-lucide="<?php echo e($icon); ?>"
                    class="h-6 w-6 text-blue-600">
                </i>

            </div>

            <div>

                <h2 class="text-xl font-bold text-slate-800">

                    <?php echo e($title); ?>


                </h2>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($description): ?>

                    <p class="mt-1 text-sm text-slate-500">

                        <?php echo e($description); ?>


                    </p>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </div>

        </div>

    </div>

    
    <div class="p-8">

        <?php echo e($slot); ?>


    </div>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/employee/section-card.blade.php ENDPATH**/ ?>