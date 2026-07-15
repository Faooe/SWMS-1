<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    
    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            GPS Validation

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Employee location verification.

        </p>

    </div>

    
    <div class="space-y-5">

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Verification

            </span>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attendance->location_verified): ?>

                <span class="rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">

                    ✓ Verified

                </span>

            <?php else: ?>

                <span class="rounded-full bg-red-100 px-4 py-1 text-sm font-semibold text-red-700">

                    ✕ Not Verified

                </span>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Employee Distance

            </span>

            <span class="font-semibold">

                <?php echo e(number_format($attendance->check_in_distance ?? 0,2)); ?> m

            </span>

        </div>

        <hr>

        
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Allowed Radius

            </span>

            <span class="font-semibold">

                <?php echo e($attendance->allowed_radius ?? '-'); ?> m

            </span>

        </div>

        <hr>

        
        <div>

            <p class="text-sm text-slate-500">

                Latitude

            </p>

            <p class="font-mono text-sm">

                <?php echo e($attendance->check_in_latitude); ?>


            </p>

        </div>

        
        <div>

            <p class="text-sm text-slate-500">

                Longitude

            </p>

            <p class="font-mono text-sm">

                <?php echo e($attendance->check_in_longitude); ?>


            </p>

        </div>

    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attendance->check_in_latitude && $attendance->check_in_longitude): ?>

        <div class="mt-6 overflow-hidden rounded-2xl border">

            <iframe
                width="100%"
                height="280"
                frameborder="0"
                scrolling="no"
                loading="lazy"
                src="https://www.openstreetmap.org/export/embed.html?bbox=<?php echo e($attendance->check_in_longitude-0.002); ?>,<?php echo e($attendance->check_in_latitude-0.002); ?>,<?php echo e($attendance->check_in_longitude+0.002); ?>,<?php echo e($attendance->check_in_latitude+0.002); ?>&layer=mapnik&marker=<?php echo e($attendance->check_in_latitude); ?>,<?php echo e($attendance->check_in_longitude); ?>">
            </iframe>

        </div>

        <a

            href="https://www.google.com/maps?q=<?php echo e($attendance->check_in_latitude); ?>,<?php echo e($attendance->check_in_longitude); ?>"

            target="_blank"

            class="mt-4 inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

            Open Google Maps

        </a>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/attendance/partials/gps-card.blade.php ENDPATH**/ ?>