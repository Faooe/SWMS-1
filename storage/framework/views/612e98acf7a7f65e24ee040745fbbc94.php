<?php

$pivot = $assignment

    ->employees

    ->firstWhere('id', auth()->user()->employee->id)

    ?->pivot;

$status = $pivot?->status ?? 'Assigned';

$statusColor = match($status){

    'Assigned'
        => 'bg-blue-100 text-blue-700',

    'Accepted'
        => 'bg-indigo-100 text-indigo-700',

    'In Progress'
        => 'bg-amber-100 text-amber-700',

    'Completed'
        => 'bg-green-100 text-green-700',

    'Rejected'
        => 'bg-red-100 text-red-700',

    default
        => 'bg-slate-100 text-slate-700',

};

$priorityColor = match($assignment->priority){

    'Critical'
        => 'bg-red-500',

    'High'
        => 'bg-orange-500',

    'Medium'
        => 'bg-yellow-500',

    default
        => 'bg-green-500',

};

$totalEmployee = $assignment->employees->count();

$progress = match($status){

    'Assigned' => 10,

    'Accepted' => 30,

    'In Progress' => 60,

    'Completed' => 100,

    'Rejected' => 0,

    default => 0,

};

?>

<div
    class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

    <div class="p-6">

        
        <div class="mb-5 flex items-center justify-between">

            <div
                class="flex items-center gap-2">

                <span
                    class="h-3 w-3 rounded-full <?php echo e($priorityColor); ?>">
                </span>

                <span
                    class="font-semibold">

                    <?php echo e($assignment->priority); ?>


                </span>

            </div>

            <span
                class="rounded-full px-3 py-1 text-xs font-semibold <?php echo e($statusColor); ?>">

                <?php echo e($status); ?>


            </span>

        </div>

        
        <h2
            class="text-xl font-bold text-slate-800">

            <?php echo e($assignment->title); ?>


        </h2>

        <p
            class="mt-1 text-sm text-slate-500">

            <?php echo e($assignment->assignment_number); ?>


        </p>

        
        <div
            class="mt-6 space-y-3 text-sm">

            <div
                class="flex items-center gap-3">

                <i
                    data-lucide="map-pin"
                    class="h-4 w-4 text-slate-400">
                </i>

                <?php echo e($assignment->location_name); ?>


            </div>

            <div
                class="flex items-center gap-3">

                <i
                    data-lucide="building-2"
                    class="h-4 w-4 text-slate-400">
                </i>

                <?php echo e($assignment->office?->name); ?>


            </div>

            <div
                class="flex items-center gap-3">

                <i
                    data-lucide="users"
                    class="h-4 w-4 text-slate-400">
                </i>

                <?php echo e($totalEmployee); ?> Employee

            </div>

            <div
                class="flex items-center gap-3">

                <i
                    data-lucide="calendar-clock"
                    class="h-4 w-4 text-slate-400">
                </i>

                <?php echo e($assignment->end_datetime->format('d M Y • H:i')); ?>


            </div>

        </div>

        
        <div
            class="mt-6">

            <div
                class="mb-2 flex justify-between text-sm">

                <span>

                    Progress

                </span>

                <span>

                    <?php echo e($progress); ?>%

                </span>

            </div>

            <div
                class="h-2 overflow-hidden rounded-full bg-slate-200">

                <div
                    class="h-full rounded-full bg-blue-600"

                    style="width:<?php echo e($progress); ?>%">
                </div>

            </div>

        </div>

    </div>

    
    <div
        class="border-t bg-slate-50 px-6 py-4">

        <a

            href="<?php echo e(route('employee.assignments.show', $assignment->uuid)); ?>"

            class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 font-semibold text-white transition hover:bg-blue-700">

            View Detail

        </a>

    </div>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/assignments/partials/card.blade.php ENDPATH**/ ?>