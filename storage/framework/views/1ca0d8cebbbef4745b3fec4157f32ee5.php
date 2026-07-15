<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => '#',
    'icon',
    'title',
    'active' => false,
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
    'href' => '#',
    'icon',
    'title',
    'active' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a

    href="<?php echo e($href); ?>"

    title="<?php echo e($title); ?>"

    <?php echo e($attributes->merge([

        'class' => '

        sidebar-item

        group

        relative

        flex

        items-center

        gap-3

        rounded-2xl

        px-4

        py-3

        transition-all

        duration-200

        overflow-hidden

        '.

        (

            $active

                ? 'bg-blue-600 text-white shadow-md shadow-blue-200'

                : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'

        )

    ])); ?>


>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($active): ?>

        <span

            class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-white">

        </span>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div

        class="sidebar-icon flex h-10 w-10 shrink-0 items-center justify-center rounded-xl

        <?php echo e($active

                ? 'bg-white/20'

                : 'bg-slate-100 group-hover:bg-white'); ?>">

        <i

            data-lucide="<?php echo e($icon); ?>"

            class="h-5 w-5">

        </i>

    </div>

    <span

        class="sidebar-label truncate font-medium">

        <?php echo e($title); ?>


    </span>

</a><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/ui/sidebar-item.blade.php ENDPATH**/ ?>