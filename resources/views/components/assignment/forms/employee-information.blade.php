@props(['employees','assignment' => null])

@php
    $selectedEmployees = old('employees', $assignment?->employees?->pluck('id')->toArray() ?? []);
@endphp

<x-assignment.section-card title="Team Assignment" description="Pilih employee yang bertanggung jawab pada pekerjaan ini." icon="users">
    <div class="mb-4 grid gap-3 md:grid-cols-2">
        <div class="relative">
            <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
            <input id="employee-search" type="text" placeholder="Cari nama / NIP..." class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>
        <select id="busy-filter" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm">
            <option value="">Semua Employee</option>
            <option value="free">Tersedia</option>
            <option value="busy">Punya Assignment Aktif</option>
        </select>
    </div>

    <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
        <p class="text-xs text-slate-500">Pilih satu atau lebih employee. Employee yang sudah memiliki assignment aktif tetap ditandai agar mudah dipertimbangkan.</p>
        <span id="selected-count" class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">0 Dipilih</span>
    </div>

    <div class="max-h-[500px] overflow-auto rounded-2xl border border-slate-200 bg-white">
        <table id="employee-table" class="min-w-full">
            <thead class="sticky top-0 z-10 border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="w-12 px-4 py-3"><input id="select-all" type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"></th>
                    <th class="px-4 py-3">Employee</th>
                    <th class="px-4 py-3">Posisi / Office</th>
                    <th class="px-4 py-3">Ketersediaan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($employees as $employee)
                    @php
                        $checked = in_array($employee->id, $selectedEmployees);
                        $employment = $employee->currentEmployment;
                        $busy = (bool) $employee->current_assignment;
                    @endphp
                    <tr class="employee-row cursor-pointer transition hover:bg-slate-50"
                        data-name="{{ strtolower($employee->full_name.' '.$employee->employee_number) }}"
                        data-status="{{ $employee->is_active ? 'active' : 'inactive' }}"
                        data-busy="{{ $busy ? 'busy' : 'free' }}">
                        <td class="px-4 py-3">
                            <input type="checkbox" name="employees[]" value="{{ $employee->id }}" class="employee-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500" @checked($checked)>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($employee->photo)
                                    <img src="{{ secure_file_url($employee->photo) }}" class="h-10 w-10 shrink-0 rounded-full object-cover ring-1 ring-slate-200">
                                @else
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-blue-600">{{ strtoupper(substr($employee->full_name, 0, 1)) }}</div>
                                @endif
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-800">{{ $employee->full_name }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $employee->employee_number }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-slate-700">{{ $employment?->position?->name ?? '-' }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $employment?->office?->name ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if(!$employee->is_active)
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">Tidak Aktif</span>
                            @elseif($busy)
                                <div><span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">Ada Assignment Aktif</span><p class="mt-1 max-w-[220px] truncate text-xs text-slate-400">{{ $employee->current_assignment->assignment->title ?? '-' }}</p></div>
                            @else
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">Tersedia</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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

        counter.innerHTML = `${total} Dipilih`;

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

        const busy = busyFilter.value;

        rows.forEach(row => {

            let show = true;

            if (keyword) {

                show = show && row.dataset.name.includes(keyword);

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