<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => '',
    'name' => null,
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'required' => false,
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
    'label' => '',
    'name' => null,
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'required' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="space-y-2">

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>

        <label
            <?php if($name): ?>
                for="<?php echo e($name); ?>"
            <?php endif; ?>
            class="flex items-center gap-1 text-sm font-semibold text-slate-700">

            <?php echo e($label); ?>


            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($required): ?>
                <span class="text-red-500">*</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </label>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <input

        <?php if($name): ?>
            id="<?php echo e($name); ?>"
            name="<?php echo e($name); ?>"
        <?php endif; ?>

        type="<?php echo e($type); ?>"

        value="<?php echo e(old($name ?? '', $value)); ?>"

        placeholder="<?php echo e($placeholder); ?>"

        <?php if($required): ?>
            required
        <?php endif; ?>

        <?php echo e($attributes->merge([

            'class' => '
                w-full
                rounded-2xl
                border
                border-slate-300
                bg-white
                px-4
                py-3
                text-slate-800
                shadow-sm
                outline-none
                transition
                duration-200

                placeholder:text-slate-400

                focus:border-blue-500
                focus:ring-4
                focus:ring-blue-100

                disabled:bg-slate-100
                disabled:text-slate-500
                disabled:cursor-not-allowed

                '.(
                    $name && $errors->has($name)
                        ? 'border-red-500 focus:border-red-500 focus:ring-red-100'
                        : ''
                )

        ])); ?>


    >

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($name): ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

            <p class="flex items-center gap-1 text-sm text-red-500">

                <i
                    data-lucide="circle-alert"
                    class="h-4 w-4">
                </i>

                <?php echo e($message); ?>


            </p>

        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/ui/input.blade.php ENDPATH**/ ?>