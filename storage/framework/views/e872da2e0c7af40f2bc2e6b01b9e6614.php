use Illuminate\Support\Str;
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

<?php

use Carbon\Carbon;

$groupedLogs = $assignment->logs
    ->sortByDesc('created_at')
    ->groupBy(function ($log) {
        return $log->created_at->format('Y-m-d');
    });

?>

<?php if (isset($component)) { $__componentOriginalf6f3b777e07976364c604768a84e2a4f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6f3b777e07976364c604768a84e2a4f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.section-card','data' => ['title' => 'Assignment Timeline','description' => 'History of assignment activities.','icon' => 'history']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.section-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Assignment Timeline','description' => 'History of assignment activities.','icon' => 'history']); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assignment->logs->isEmpty()): ?>

        <div class="py-16 text-center">

            <i
                data-lucide="history"
                class="mx-auto h-14 w-14 text-slate-300">
            </i>

            <h3 class="mt-4 text-lg font-semibold text-slate-700">
                No Activity
            </h3>

            <p class="mt-2 text-slate-500">
                Timeline will appear after activity is recorded.
            </p>

        </div>

    <?php else: ?>

        <div
            class="relative max-h-[650px] overflow-y-auto pr-3 scroll-smooth timeline-scroll">

            
            <div
                class="absolute left-5 top-0 h-full w-0.5 bg-slate-200">
            </div>

            <div class="space-y-10">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groupedLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $logs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php

                        $carbon = Carbon::parse($date);

                        if($carbon->isToday()){

                            $title = 'Today';

                        }elseif($carbon->isYesterday()){

                            $title = 'Yesterday';

                        }else{

                            $title = $carbon->translatedFormat('d F Y');

                        }

                    ?>

                    
                    <div
                        class="sticky top-0 z-20 bg-white pb-5 pt-1">

                        <div
                            class="flex items-center gap-4">

                            <div
                                class="h-px flex-1 bg-slate-200">
                            </div>

                            <span
                                class="rounded-full bg-slate-100 px-4 py-1 text-xs font-bold uppercase tracking-widest text-slate-600">

                                <?php echo e($title); ?>


                            </span>

                            <div
                                class="h-px flex-1 bg-slate-200">
                            </div>

                        </div>

                    </div>

                    <div class="space-y-8">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php

                                $color = match($log->action){

                                    'ASSIGNMENT_CREATED'
                                        => 'bg-blue-500',

                                    'ASSIGNMENT_UPDATED'
                                        => 'bg-amber-500',

                                    'EMPLOYEE_ASSIGNED'
                                        => 'bg-indigo-500',

                                    'EMPLOYEE_ACCEPTED'
                                        => 'bg-cyan-500',

                                    'CHECK_IN'
                                        => 'bg-green-500',

                                    'CHECK_OUT'
                                        => 'bg-orange-500',

                                    'PROGRESS_UPDATED'
                                        => 'bg-violet-500',

                                    'PHOTO_UPLOADED'
                                        => 'bg-pink-500',

                                    'ASSIGNMENT_COMPLETED'
                                        => 'bg-emerald-600',

                                    'ASSIGNMENT_CANCELLED'
                                        => 'bg-red-600',

                                    default
                                        => 'bg-slate-500',

                                };

                                $icon = match($log->action){

                                    'ASSIGNMENT_CREATED'
                                        => 'plus-circle',

                                    'ASSIGNMENT_UPDATED'
                                        => 'square-pen',

                                    'EMPLOYEE_ASSIGNED'
                                        => 'users',

                                    'EMPLOYEE_ACCEPTED'
                                        => 'thumbs-up',

                                    'CHECK_IN'
                                        => 'map-pin',

                                    'CHECK_OUT'
                                        => 'map-pin-check',

                                    'PROGRESS_UPDATED'
                                        => 'hourglass',

                                    'PHOTO_UPLOADED'
                                        => 'camera',

                                    'ASSIGNMENT_COMPLETED'
                                        => 'badge-check',

                                    'ASSIGNMENT_CANCELLED'
                                        => 'circle-x',

                                    default
                                        => 'history',

                                };
                                $action = match($log->action){

                                'ASSIGNMENT_CREATED'
                                    => 'Assignment Created',

                                'ASSIGNMENT_UPDATED'
                                    => 'Assignment Updated',

                                'EMPLOYEE_ASSIGNED'
                                    => 'Employee Assigned',

                                'EMPLOYEE_ACCEPTED'
                                    => 'Employee Accepted Assignment',

                                'CHECK_IN'
                                    => 'Employee Check In',

                                'CHECK_OUT'
                                    => 'Employee Check Out',

                                'PROGRESS_UPDATED'
                                    => 'Progress Updated',

                                'PHOTO_UPLOADED'
                                    => 'Photo Uploaded',

                                'ASSIGNMENT_COMPLETED'
                                    => 'Assignment Completed',

                                'ASSIGNMENT_CANCELLED'
                                    => 'Assignment Cancelled',

                                default
                                    => Str::headline($log->action),

                            };
                            $badge = match($log->action){

                                'CHECK_IN'
                                    => 'bg-green-100 text-green-700',

                                'CHECK_OUT'
                                    => 'bg-orange-100 text-orange-700',

                                'ASSIGNMENT_COMPLETED'
                                    => 'bg-emerald-100 text-emerald-700',

                                'ASSIGNMENT_CANCELLED'
                                    => 'bg-red-100 text-red-700',

                                'EMPLOYEE_ASSIGNED'
                                    => 'bg-indigo-100 text-indigo-700',

                                default
                                    => 'bg-slate-100 text-slate-700',

                            };

                            ?>

                            <div
                                class="relative flex gap-5">

                                
                                <div
                                    class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full <?php echo e($color); ?> text-white shadow">

                                    <i
                                        data-lucide="<?php echo e($icon); ?>"
                                        class="h-5 w-5">
                                    </i>

                                </div>

                                
                                <div
                                    class="flex-1 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:border-blue-300 hover:shadow-md">

                                    <div
                                        class="flex items-start justify-between gap-5">

                                        <div>

                                            <h4
                                                class="font-bold tracking-wide text-slate-800">

                                                <?php echo e($action); ?>


                                            </h4>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->description): ?>

                                                <p
                                                    class="mt-2 leading-relaxed text-slate-600">

                                                    <?php echo e($log->description); ?>


                                                </p>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        </div>

                                        <div class="shrink-0 text-right">

                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-semibold <?php echo e($badge); ?>">

                                            <?php echo e($action); ?>


                                        </span>

                                        <div
                                            class="mt-3 text-sm text-slate-500">

                                            <?php echo e($log->created_at->format('d M Y')); ?>


                                            <br>

                                            <?php echo e($log->created_at->format('H:i')); ?>


                                        </div>

                                    </div>

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->employee || $log->user): ?>

                                        <div
                                            class="mt-5 flex flex-wrap gap-6 border-t border-slate-100 pt-4 text-sm">

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->employee): ?>

                                                <div>

                                                    <span
                                                        class="font-semibold text-slate-700">

                                                        Employee

                                                    </span>

                                                    <br>

                                                    <span
                                                        class="text-slate-500">

                                                        <?php echo e($log->employee->full_name); ?>


                                                    </span>

                                                </div>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->user): ?>

                                                <div>

                                                    <span
                                                        class="font-semibold text-slate-700">

                                                        By

                                                    </span>

                                                    <br>

                                                    <span
                                                        class="text-slate-500">

                                                        <?php echo e($log->user->employee?->full_name); ?>


                                                    </span>

                                                </div>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        </div>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </div>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </div>

        </div>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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

<style>

.timeline-scroll{

    scroll-behavior:smooth;

}

.timeline-scroll::-webkit-scrollbar{

    width:8px;

}

.timeline-scroll::-webkit-scrollbar-track{

    background:#f1f5f9;

    border-radius:999px;

}

.timeline-scroll::-webkit-scrollbar-thumb{

    background:#cbd5e1;

    border-radius:999px;

}

.timeline-scroll::-webkit-scrollbar-thumb:hover{

    background:#94a3b8;

}

</style><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/assignment/show/timeline.blade.php ENDPATH**/ ?>