<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'employee' => null,
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
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if (isset($component)) { $__componentOriginal70be86c470f0bdd09cb96a796f41a3f3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal70be86c470f0bdd09cb96a796f41a3f3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee.section-card','data' => ['title' => 'Employee Photo','description' => 'Upload employee profile photo.','icon' => 'image']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee.section-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Employee Photo','description' => 'Upload employee profile photo.','icon' => 'image']); ?>

    <div
        x-data="{

            preview: '<?php echo e($employee?->photo ? asset('storage/'.$employee->photo) : ''); ?>',

            updatePreview(event){

                const file = event.target.files[0];

                if(!file) return;

                this.preview = URL.createObjectURL(file);

            }

        }"

        class="space-y-6">

        
        <div class="flex justify-center">

            <template x-if="preview">

                <img
                    :src="preview"
                    class="h-44 w-44 rounded-full border-4 border-slate-200 object-cover shadow">

            </template>

            <template x-if="!preview">

                <div
                    class="flex h-44 w-44 items-center justify-center rounded-full border-2 border-dashed border-slate-300 bg-slate-50">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-14 w-14 text-slate-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 16l5-5 4 4 7-7 2 2v8H3z"/>

                    </svg>

                </div>

            </template>

        </div>

        
        <label
            class="flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-10 transition hover:border-blue-500 hover:bg-blue-50">

            <div class="text-center">

                <h3 class="font-semibold">

                    Click to Upload

                </h3>

                <p class="mt-2 text-sm text-slate-500">

                    JPG, PNG, JPEG

                </p>

                <p class="text-sm text-slate-500">

                    Maximum 2 MB

                </p>

            </div>

            <input
                type="file"
                name="photo"
                class="hidden"
                accept="image/*"
                @change="updatePreview">

        </label>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['photo'];
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

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal70be86c470f0bdd09cb96a796f41a3f3)): ?>
<?php $attributes = $__attributesOriginal70be86c470f0bdd09cb96a796f41a3f3; ?>
<?php unset($__attributesOriginal70be86c470f0bdd09cb96a796f41a3f3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal70be86c470f0bdd09cb96a796f41a3f3)): ?>
<?php $component = $__componentOriginal70be86c470f0bdd09cb96a796f41a3f3; ?>
<?php unset($__componentOriginal70be86c470f0bdd09cb96a796f41a3f3); ?>
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/employee/forms/photo-upload.blade.php ENDPATH**/ ?>