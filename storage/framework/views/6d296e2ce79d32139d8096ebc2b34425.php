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

            Attendance Photos

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Employee attendance documentation.

        </p>

    </div>

    <div class="grid gap-6 md:grid-cols-2">

        
        <div>

            <h3 class="mb-3 font-semibold text-slate-700">

                Check In

            </h3>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attendance->check_in_photo): ?>

                <button
                    onclick="openPhoto('<?php echo e(asset('storage/'.$attendance->check_in_photo)); ?>')"
                    class="group w-full">

                    <div class="overflow-hidden rounded-2xl border">

                        <img

                            src="<?php echo e(asset('storage/'.$attendance->check_in_photo)); ?>"

                            class="h-72 w-full object-cover transition duration-300 group-hover:scale-110">

                    </div>

                    <p class="mt-3 text-sm text-blue-600">

                        Click to Preview

                    </p>

                </button>

            <?php else: ?>

                <div class="flex h-72 items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50">

                    <div class="text-center">

                        <i
                            data-lucide="camera"
                            class="mx-auto h-10 w-10 text-slate-400">

                        </i>

                        <p class="mt-3 text-slate-400">

                            No Check In Photo

                        </p>

                    </div>

                </div>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

        
        <div>

            <h3 class="mb-3 font-semibold text-slate-700">

                Check Out

            </h3>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attendance->check_out_photo): ?>

                <button
                    onclick="openPhoto('<?php echo e(asset('storage/'.$attendance->check_out_photo)); ?>')"
                    class="group w-full">

                    <div class="overflow-hidden rounded-2xl border">

                        <img

                            src="<?php echo e(asset('storage/'.$attendance->check_out_photo)); ?>"

                            class="h-72 w-full object-cover transition duration-300 group-hover:scale-110">

                    </div>

                    <p class="mt-3 text-sm text-blue-600">

                        Click to Preview

                    </p>

                </button>

            <?php else: ?>

                <div class="flex h-72 items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50">

                    <div class="text-center">

                        <i
                            data-lucide="camera"
                            class="mx-auto h-10 w-10 text-slate-400">

                        </i>

                        <p class="mt-3 text-slate-400">

                            No Check Out Photo

                        </p>

                    </div>

                </div>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/attendance/partials/photos-card.blade.php ENDPATH**/ ?>