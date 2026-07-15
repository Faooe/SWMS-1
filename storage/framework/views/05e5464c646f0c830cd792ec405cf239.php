<div class="rounded-3xl bg-white p-8 shadow">

    <div class="flex items-start justify-between">

        <div>

            <h1
                class="text-3xl font-bold">

                <?php echo e($assignment->title); ?>


            </h1>

            <p
                class="mt-2 text-slate-500">

                <?php echo e($assignment->assignment_number); ?>


            </p>

        </div>

        <span
            class="rounded-full bg-blue-100 px-4 py-2 font-semibold text-blue-700">

            <?php echo e($assignment->status); ?>


        </span>

    </div>

    <div
        class="mt-8 grid gap-5 md:grid-cols-4">

        <div>

            <p class="text-sm text-slate-500">

                Priority

            </p>

            <h3 class="font-semibold">

                <?php echo e($assignment->priority); ?>


            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Office

            </p>

            <h3 class="font-semibold">

                <?php echo e($assignment->office?->name); ?>


            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Start

            </p>

            <h3 class="font-semibold">

                <?php echo e($assignment->start_datetime->format('d M Y H:i')); ?>


            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Deadline

            </p>

            <h3 class="font-semibold">

                <?php echo e($assignment->end_datetime->format('d M Y H:i')); ?>


            </h3>

        </div>

    </div>

</div><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/employee/assignments/partials/header.blade.php ENDPATH**/ ?>