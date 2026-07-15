<form
    method="GET"
    action="<?php echo e(route('assignments.index')); ?>"
    class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="grid gap-5 lg:grid-cols-5">

        
        <div class="lg:col-span-2">

            <label
                class="mb-2 block text-sm font-semibold text-slate-700">

                Search

            </label>

            <input

                type="text"

                name="search"

                value="<?php echo e(request('search')); ?>"

                placeholder="Assignment Number / Title..."

                class="w-full rounded-2xl border border-slate-300 px-4 py-3
                       focus:border-blue-500
                       focus:ring-4
                       focus:ring-blue-100">

        </div>

        
        <div>

            <label
                class="mb-2 block text-sm font-semibold text-slate-700">

                Priority

            </label>

            <select

                name="priority"

                class="w-full rounded-2xl border border-slate-300 px-4 py-3">

                <option value="">

                    All

                </option>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                    'Low',
                    'Medium',
                    'High',
                    'Critical'
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <option

                        value="<?php echo e($priority); ?>"

                        <?php if(request('priority')==$priority): echo 'selected'; endif; ?>>

                        <?php echo e($priority); ?>


                    </option>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </select>

        </div>

        
        <div>

            <label
                class="mb-2 block text-sm font-semibold text-slate-700">

                Status

            </label>

            <select

                name="status"

                class="w-full rounded-2xl border border-slate-300 px-4 py-3">

                <option value="">

                    All

                </option>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                    'Draft',
                    'Assigned',
                    'In Progress',
                    'Completed',
                    'Cancelled'
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <option

                        value="<?php echo e($status); ?>"

                        <?php if(request('status')==$status): echo 'selected'; endif; ?>>

                        <?php echo e($status); ?>


                    </option>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </select>

        </div>

        
        <div
            class="flex items-end gap-3">

            <button

                type="submit"

                class="flex-1 rounded-2xl bg-blue-600 px-5 py-3
                       font-semibold text-white
                       transition
                       hover:bg-blue-700">

                Filter

            </button>

            <a

                href="<?php echo e(route('assignments.index')); ?>"

                class="rounded-2xl border border-slate-300
                       px-5 py-3
                       font-semibold
                       hover:bg-slate-100">

                Reset

            </a>

        </div>

    </div>

</form><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/assignment/filters/search.blade.php ENDPATH**/ ?>