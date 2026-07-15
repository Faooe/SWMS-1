<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'assignments'
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
    'assignments'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assignments->count()): ?>

<div class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

        <table class="min-w-full">

            <thead class="sticky top-0 z-10 bg-slate-100">

                <tr>

                    <th class="px-6 py-4 text-left">Number</th>

                    <th class="px-6 py-4 text-left">Assignment</th>

                    <th class="px-6 py-4 text-left">Office</th>

                    <th class="px-6 py-4 text-left">Priority</th>

                    <th class="px-6 py-4 text-left">Status</th>

                    <th class="px-6 py-4 text-left">Employees</th>

                    <th class="px-6 py-4 text-left">Schedule</th>

                    <th class="px-6 py-4 text-center">Action</th>

                </tr>

            </thead>

            <tbody>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php if (isset($component)) { $__componentOriginal9fceb37ab8166ea2f7578e5622c12eab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9fceb37ab8166ea2f7578e5622c12eab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.table.row','data' => ['assignment' => $assignment]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.table.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['assignment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assignment)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9fceb37ab8166ea2f7578e5622c12eab)): ?>
<?php $attributes = $__attributesOriginal9fceb37ab8166ea2f7578e5622c12eab; ?>
<?php unset($__attributesOriginal9fceb37ab8166ea2f7578e5622c12eab); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9fceb37ab8166ea2f7578e5622c12eab)): ?>
<?php $component = $__componentOriginal9fceb37ab8166ea2f7578e5622c12eab; ?>
<?php unset($__componentOriginal9fceb37ab8166ea2f7578e5622c12eab); ?>
<?php endif; ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<div class="mt-6">

    <?php echo e($assignments->links()); ?>


</div>

<?php else: ?>

<?php if (isset($component)) { $__componentOriginal5f7b83fa6f98e0d1c7794fc562ff2871 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5f7b83fa6f98e0d1c7794fc562ff2871 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.table.empty','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.table.empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5f7b83fa6f98e0d1c7794fc562ff2871)): ?>
<?php $attributes = $__attributesOriginal5f7b83fa6f98e0d1c7794fc562ff2871; ?>
<?php unset($__attributesOriginal5f7b83fa6f98e0d1c7794fc562ff2871); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5f7b83fa6f98e0d1c7794fc562ff2871)): ?>
<?php $component = $__componentOriginal5f7b83fa6f98e0d1c7794fc562ff2871; ?>
<?php unset($__componentOriginal5f7b83fa6f98e0d1c7794fc562ff2871); ?>
<?php endif; ?>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/assignment/table/table.blade.php ENDPATH**/ ?>