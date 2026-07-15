<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'employees',
    'assignment' => null,
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
    'employees',
    'assignment' => null,
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.assignment.section-card','data' => ['title' => 'Employee Information','description' => 'Select employees assigned to this assignment.','icon' => 'users']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('assignment.section-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Employee Information','description' => 'Select employees assigned to this assignment.','icon' => 'users']); ?>

    <?php

        $selectedEmployees = old(

            'employees',

            $assignment?->employees?->pluck('id')->toArray() ?? []

        );

    ?>

    
    <div class="mb-6 grid gap-4 md:grid-cols-3">

        
        <div class="relative">

            <i
                data-lucide="search"
                class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400">
            </i>

            <input

                id="employee-search"

                type="text"

                placeholder="Search employee..."

                class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-12 pr-4 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

        </div>

        
        <select

            id="status-filter"

            class="rounded-xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>

        </select>

        
        <select

            id="busy-filter"

            class="rounded-xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            <option value="">All Employee</option>
            <option value="free">Available</option>
            <option value="busy">Has Assignment</option>

        </select>

    </div>

    
    <div
        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="max-h-[520px] overflow-y-auto">

            <table
                id="employee-table"
                class="min-w-full divide-y divide-slate-200">

                <thead
                    class="sticky top-0 z-10 bg-slate-50">

                    <tr>

                        <th class="w-14 px-5 py-4">

                            <input
                                id="select-all"
                                type="checkbox"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                        </th>

                        <th
                            class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">

                            Employee

                        </th>

                        <th
                            class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">

                            Position

                        </th>

                        <th
                            class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">

                            Status

                        </th>

                        <th
                            class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">

                            Current Assignment

                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php

                            $checked = in_array(

                                $employee->id,

                                $selectedEmployees

                            );

                            $employment = $employee->currentEmployment;

                        ?>

                        <tr

                            class="employee-row cursor-pointer transition hover:bg-blue-50"

                            data-name="<?php echo e(strtolower($employee->full_name)); ?>"

                            data-status="<?php echo e($employee->is_active ? 'active' : 'inactive'); ?>"

                            data-busy="<?php echo e($employee->current_assignment ? 'busy' : 'free'); ?>">

                            
                            <td class="px-5 py-4">

                                <input

                                    type="checkbox"

                                    name="employees[]"

                                    value="<?php echo e($employee->id); ?>"

                                    class="employee-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500"

                                    <?php if($checked): echo 'checked'; endif; ?>>

                            </td>

                            
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-4">

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->photo): ?>

                                        <img

                                            src="<?php echo e(asset('storage/'.$employee->photo)); ?>"

                                            class="h-11 w-11 rounded-full object-cover">

                                    <?php else: ?>

                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">

                                            <?php echo e(strtoupper(substr($employee->full_name, 0, 1))); ?>


                                        </div>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <div>

                                        <div class="font-semibold text-slate-800">

                                            <?php echo e($employee->full_name); ?>


                                        </div>

                                        <div class="text-sm text-slate-500">

                                            <?php echo e($employee->employee_number); ?>


                                        </div>

                                    </div>

                                </div>

                            </td>

                            
                            <td class="px-5 py-4">

                                <div class="text-sm font-medium text-slate-700">

                                    <?php echo e($employment?->position?->name ?? '-'); ?>


                                </div>

                            </td>

                            
                            <td class="px-5 py-4 text-center">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->is_active): ?>

                                    <span
                                        class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">

                                        Active

                                    </span>

                                <?php else: ?>

                                    <span
                                        class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">

                                        Inactive

                                    </span>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </td>

                            
                            <td class="px-5 py-4 text-center">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->current_assignment): ?>

                                    <div>

                                        <div class="font-medium text-amber-600">

                                            <?php echo e($employee->current_assignment->assignment->title); ?>


                                        </div>

                                        <div class="text-xs text-slate-500">

                                            <?php echo e($employee->current_assignment->status); ?>


                                        </div>

                                    </div>

                                <?php else: ?>

                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">

                                        Available

                                    </span>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    
    <div class="mt-4 flex items-center justify-between text-sm text-slate-500">

        <span>

            Total Employee : <?php echo e($employees->count()); ?>


        </span>

        <span
            id="selected-count"
            class="rounded-xl bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

            0 Selected

        </span>

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

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    */

    const search = document.getElementById('employee-search');

    const statusFilter = document.getElementById('status-filter');

    const busyFilter = document.getElementById('busy-filter');

    const rows = document.querySelectorAll('.employee-row');

    const checkboxes = document.querySelectorAll('.employee-checkbox');

    const counter = document.getElementById('selected-count');

    const selectAll = document.getElementById('select-all');

    /*
    |--------------------------------------------------------------------------
    | Update Counter
    |--------------------------------------------------------------------------
    */

    function updateCounter()
    {
        let total = 0;

        checkboxes.forEach(box => {

            const row = box.closest('tr');

            if (box.checked) {

                total++;

                row.classList.add(

                    'bg-blue-50',

                    'border-l-4',

                    'border-blue-500'

                );

            } else {

                row.classList.remove(

                    'bg-blue-50',

                    'border-l-4',

                    'border-blue-500'

                );

            }

        });

        counter.innerHTML = `${total} Selected`;

        /*
        |--------------------------------------------------------------------------
        | Select All State (hanya checkbox yg sedang terlihat)
        |--------------------------------------------------------------------------
        */

        const visibleCheckboxes = Array.from(checkboxes).filter(

            box => box.closest('tr').style.display !== 'none'

        );

        const visibleChecked = visibleCheckboxes.filter(box => box.checked).length;

        selectAll.checked =

            visibleCheckboxes.length > 0 &&

            visibleChecked === visibleCheckboxes.length;

    }

    /*
    |--------------------------------------------------------------------------
    | Apply Filter (Search + Status + Availability)
    |--------------------------------------------------------------------------
    */

    function applyFilter()
    {
        const keyword = search.value.toLowerCase();

        const status = statusFilter.value;

        const busy = busyFilter.value;

        rows.forEach(row => {

            let show = true;

            if (keyword) {

                show = show && row.dataset.name.includes(keyword);

            }

            if (status) {

                show = show && row.dataset.status === status;

            }

            if (busy) {

                show = show && row.dataset.busy === busy;

            }

            row.style.display = show ? '' : 'none';

        });

        updateCounter();
    }

    /*
    |--------------------------------------------------------------------------
    | Filter Events
    |--------------------------------------------------------------------------
    */

    search.addEventListener('keyup', applyFilter);

    statusFilter.addEventListener('change', applyFilter);

    busyFilter.addEventListener('change', applyFilter);

    /*
    |--------------------------------------------------------------------------
    | Checkbox
    |--------------------------------------------------------------------------
    */

    checkboxes.forEach(box => {

        box.addEventListener(

            'change',

            updateCounter

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    selectAll.addEventListener(

        'change',

        function () {

            checkboxes.forEach(box => {

                /*
                |------------------------------------------
                | hanya checkbox yg terlihat
                |------------------------------------------
                */

                if (box.closest('tr').style.display !== 'none') {

                    box.checked = this.checked;

                }

            });

            updateCounter();

        }

    );

    /*
    |--------------------------------------------------------------------------
    | Click Row
    |--------------------------------------------------------------------------
    */

    rows.forEach(row => {

        row.addEventListener('click', function (e) {

            if (e.target.tagName === 'INPUT') {

                return;

            }

            const checkbox = row.querySelector(

                '.employee-checkbox'

            );

            checkbox.checked = !checkbox.checked;

            updateCounter();

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Initial
    |--------------------------------------------------------------------------
    */

    updateCounter();

});

</script>

<?php $__env->stopPush(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/assignment/forms/employee-information.blade.php ENDPATH**/ ?>