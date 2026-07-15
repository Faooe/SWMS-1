<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['id' => 'office-card','dataOfficeLat' => ''.e($office->latitude ?? '').'','dataOfficeLng' => ''.e($office->longitude ?? '').'','dataOfficeRadius' => ''.e($office->radius ?? '').'','dataOfficePolygon' => ''.e($office && $office->polygon ? json_encode($office->polygon) : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'office-card','data-office-lat' => ''.e($office->latitude ?? '').'','data-office-lng' => ''.e($office->longitude ?? '').'','data-office-radius' => ''.e($office->radius ?? '').'','data-office-polygon' => ''.e($office && $office->polygon ? json_encode($office->polygon) : '').'']); ?>

    <div class="mb-5 flex items-center justify-between">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100">

                <i data-lucide="building-2" class="h-5 w-5 text-blue-600"></i>

            </div>

            <h3 class="text-lg font-bold text-slate-800">

                Office Attendance

            </h3>

        </div>

        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">

            GPS Ready

        </span>

    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$office): ?>

        <p class="text-sm text-slate-500">

            Kamu belum ditempatkan di office manapun. Hubungi admin perusahaan.

        </p>

    <?php else: ?>

        <div class="grid grid-cols-2 gap-4 text-sm">

            <div>

                <p class="text-slate-500">Latitude</p>

                <p id="current-lat" class="font-mono font-semibold text-slate-800">-</p>

            </div>

            <div>

                <p class="text-slate-500">Longitude</p>

                <p id="current-lng" class="font-mono font-semibold text-slate-800">-</p>

            </div>

            <div>

                <p class="text-slate-500">Distance</p>

                <p id="office-distance" class="font-semibold text-slate-800">-</p>

            </div>

            <div>

                <p class="text-slate-500">Radius</p>

                <p class="font-semibold text-slate-800"><?php echo e($office->radius); ?> m</p>

            </div>

        </div>

        <div class="mt-4">

            <span

                id="office-location-status"

                class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">

                Locating...

            </span>

        </div>

        <div

            id="office-mini-map"

            class="mt-4 h-48 w-full overflow-hidden rounded-2xl border border-slate-200">

        </div>

        <div class="mt-5 flex gap-3">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$officeAttendance || !$officeAttendance->hasCheckedIn()): ?>

                <button

                    type="button"

                    id="office-check-in-btn"

                    disabled

                    class="flex-1 rounded-xl bg-blue-600 py-3 font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">

                    Check In

                </button>

            <?php elseif(!$officeAttendance->hasCheckedOut()): ?>

                <div class="flex-1 rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-600">

                    Checked in at

                    <span class="font-semibold text-slate-800">

                        <?php echo e($officeAttendance->check_in_time); ?>


                    </span>

                </div>

                <button

                    type="button"

                    id="office-check-out-btn"

                    disabled

                    class="flex-1 rounded-xl bg-slate-900 py-3 font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50">

                    Check Out

                </button>

            <?php else: ?>

                <div class="flex-1 rounded-xl bg-green-50 px-4 py-3 text-center text-sm font-semibold text-green-700">

                    Completed (<?php echo e($officeAttendance->check_in_time); ?> - <?php echo e($officeAttendance->check_out_time); ?>)

                </div>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

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
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/attendance/partials/office-card.blade.php ENDPATH**/ ?>