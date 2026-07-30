@props([
    'employees',
    'assignment' => null,
])

<x-assignment.section-card
    title="Employee Information"
    description="Select employees assigned to this assignment."
    icon="users">

    @php

        $selectedEmployees = old(

            'employees',

            $assignment?->employees?->pluck('id')->toArray() ?? []

        );

    @endphp

    {{-- Toolbar --}}
    <div class="mb-6 grid gap-4 md:grid-cols-3">

        {{-- Search --}}
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

        {{-- Status Filter --}}
        <select

            id="status-filter"

            class="rounded-xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>

        </select>

        {{-- Availability Filter --}}
        <select

            id="busy-filter"

            class="rounded-xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

            <option value="">All Employee</option>
            <option value="free">Available</option>
            <option value="busy">Has Assignment</option>

        </select>

    </div>

    {{-- Table --}}
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

                    @foreach($employees as $employee)

                        @php

                            $checked = in_array(

                                $employee->id,

                                $selectedEmployees

                            );

                            $employment = $employee->currentEmployment;

                        @endphp

                        <tr

                            class="employee-row cursor-pointer transition hover:bg-blue-50"

                            data-name="{{ strtolower($employee->full_name) }}"

                            data-status="{{ $employee->is_active ? 'active' : 'inactive' }}"

                            data-busy="{{ $employee->current_assignment ? 'busy' : 'free' }}">

                            {{-- Checkbox --}}
                            <td class="px-5 py-4">

                                <input

                                    type="checkbox"

                                    name="employees[]"

                                    value="{{ $employee->id }}"

                                    class="employee-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500"

                                    @checked($checked)>

                            </td>

                            {{-- Employee --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-4">

                                    @if($employee->photo)

                                        <img

                                            src="{{ asset('storage/'.$employee->photo) }}"

                                            class="h-11 w-11 rounded-full object-cover">

                                    @else

                                        <div
                                            class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">

                                            {{ strtoupper(substr($employee->full_name, 0, 1)) }}

                                        </div>

                                    @endif

                                    <div>

                                        <div class="font-semibold text-slate-800">

                                            {{ $employee->full_name }}

                                        </div>

                                        <div class="text-sm text-slate-500">

                                            {{ $employee->employee_number }}

                                        </div>

                                    </div>

                                </div>

                            </td>

                            {{-- Position --}}
                            <td class="px-5 py-4">

                                <div class="text-sm font-medium text-slate-700">

                                    {{ $employment?->position?->name ?? '-' }}

                                </div>

                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 text-center">

                                @if($employee->is_active)

                                    <span
                                        class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">

                                        Active

                                    </span>

                                @else

                                    <span
                                        class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">

                                        Inactive

                                    </span>

                                @endif

                            </td>

                            {{-- Current Assignment --}}
                            <td class="px-5 py-4 text-center">

                                @if($employee->current_assignment)

                                    <div>

                                        <div class="font-medium text-amber-600">

                                            {{ $employee->current_assignment->assignment->title }}

                                        </div>

                                        <div class="text-xs text-slate-500">

                                            {{ $employee->current_assignment->status }}

                                        </div>

                                    </div>

                                @else

                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">

                                        Available

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    {{-- Summary --}}
    <div class="mt-4 flex items-center justify-between text-sm text-slate-500">

        <span>

            Total Employee : {{ $employees->count() }}

        </span>

        <span
            id="selected-count"
            class="rounded-xl bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

            0 Selected

        </span>

    </div>

</x-assignment.section-card>

@push('scripts')

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

@endpush