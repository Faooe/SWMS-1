<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => '',
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select...',
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
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select...',
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
            for="<?php echo e($name); ?>"
            class="flex items-center gap-1 text-sm font-semibold text-slate-700">

            <?php echo e($label); ?>


            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($required): ?>

                <span class="text-red-500">*</span>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </label>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <select

        id="<?php echo e($name); ?>"

        name="<?php echo e($name); ?>"

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
                shadow-sm
                outline-none

                focus:border-blue-500
                focus:ring-4
                focus:ring-blue-100

                '.(
                    $errors->has($name)

                    ? 'border-red-500'

                    : ''

                )

        ])); ?>


    >

        <option value="">

            <?php echo e($placeholder); ?>


        </option>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php

                /*
                |--------------------------------------------------------------------------
                | Eloquent Model / Object
                |--------------------------------------------------------------------------
                */

                if (is_object($option)) {

                    $value = $option->id;

                    $text = $option->name;

                }

                /*
                |--------------------------------------------------------------------------
                | Associative Array
                |--------------------------------------------------------------------------
                */

                elseif (!is_numeric($key)) {

                    $value = $key;

                    $text = $option;

                }

                /*
                |--------------------------------------------------------------------------
                | Simple Array
                |--------------------------------------------------------------------------
                */

                else {

                    $value = $option;

                    $text = $option;

                }

            ?>

            <option

                value="<?php echo e($value); ?>"

                <?php if(old($name, $selected) == $value): echo 'selected'; endif; ?>

            >

                <?php echo e($text); ?>


            </option>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </select>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

        <p class="text-sm text-red-500">

            <?php echo e($message); ?>


        </p>

    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/ui/select.blade.php ENDPATH**/ ?>