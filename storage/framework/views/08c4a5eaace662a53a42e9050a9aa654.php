<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'assignment'
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
    'assignment'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if (isset($component)) { $__componentOriginalf6f3b777e07976364c604768a84e2a4f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6f3b777e07976364c604768a84e2a4f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.section-card','data' => ['title' => 'Location Information','description' => 'Assignment destination and attendance radius.','icon' => 'map-pin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.section-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Location Information','description' => 'Assignment destination and attendance radius.','icon' => 'map-pin']); ?>

    <div class="grid gap-6 lg:grid-cols-2">

        <div>

            <div class="space-y-5">

                <div>

                    <p class="text-sm text-slate-500">

                        Location

                    </p>

                    <h3 class="mt-1 text-lg font-semibold">

                        <?php echo e($assignment->location_name); ?>


                    </h3>

                </div>

                <div>

                    <p class="text-sm text-slate-500">

                        Address

                    </p>

                    <p class="mt-1">

                        <?php echo e($assignment->address); ?>


                    </p>

                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>

                        <p class="text-sm text-slate-500">

                            Latitude

                        </p>

                        <h4 class="font-semibold">

                            <?php echo e($assignment->latitude); ?>


                        </h4>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">

                            Longitude

                        </p>

                        <h4 class="font-semibold">

                            <?php echo e($assignment->longitude); ?>


                        </h4>

                    </div>

                </div>

                <div>

                    <p class="text-sm text-slate-500">

                        Attendance Radius

                    </p>

                    <h3 class="text-lg font-bold text-blue-600">

                        <?php echo e($assignment->radius); ?> Meter

                    </h3>

                </div>

                <a
                    target="_blank"
                    href="https://maps.google.com/?q=<?php echo e($assignment->latitude); ?>,<?php echo e($assignment->longitude); ?>"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">

                    <i data-lucide="navigation"></i>

                    Open Google Maps

                </a>

            </div>

        </div>

        <div>

            <div
                id="show-map"
                class="h-[420px] rounded-3xl border border-slate-300">

            </div>

        </div>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6f3b777e07976364c604768a84e2a4f)): ?>
<?php $attributes = $__attributesOriginalf6f3b777e07976364c604768a84e2a4f; ?>
<?php unset($__attributesOriginalf6f3b777e07976364c604768a84e2a4f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6f3b777e07976364c604768a84e2a4f)): ?>
<?php $component = $__componentOriginalf6f3b777e07976364c604768a84e2a4f; ?>
<?php unset($__componentOriginalf6f3b777e07976364c604768a84e2a4f); ?>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>

<script>

document.addEventListener('DOMContentLoaded',()=>{

    const lat=<?php echo e($assignment->latitude); ?>;

    const lng=<?php echo e($assignment->longitude); ?>;

    const radius=<?php echo e($assignment->radius); ?>;

    const map=L.map('show-map').setView([lat,lng],16);

    L.tileLayer(

        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

        {

            attribution:'© OpenStreetMap'

        }

    ).addTo(map);

    L.marker([lat,lng]).addTo(map);

    L.circle([lat,lng],{

        radius:radius,

        color:'#2563eb',

        fillColor:'#3b82f6',

        fillOpacity:.15

    }).addTo(map);

});

</script>

<?php $__env->stopPush(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/assignment/show/location.blade.php ENDPATH**/ ?>