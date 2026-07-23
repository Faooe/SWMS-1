@props([
    'employee' => null,
    'offices',
    'departments',
    'positions',
    'teams',
    'employees',
])

<x-employee.section-card
    title="Employment Information"
    description="Current employment information."
    icon="briefcase">

    <div class="grid gap-6 md:grid-cols-2">

        {{-- Office --}}
        <x-ui.select
            name="office_id"
            label="Office"
            :options="$offices"
            :selected="$employee?->currentEmployment?->office_id"
            placeholder="Select Office"
            required />

        {{-- Department --}}
        <x-ui.select
            name="department_id"
            label="Department"
            :options="$departments"
            :selected="$employee?->currentEmployment?->department_id"
            placeholder="Select Department"
            required />

        {{-- Position --}}
        <x-ui.select
            name="position_id"
            label="Position"
            :options="$positions"
            :selected="$employee?->currentEmployment?->position_id"
            placeholder="Select Position"
            required />

        {{-- Team --}}
        <div>

            <label
                for="team_id"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Team

            </label>

            <select
                id="team_id"
                name="team_id"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                <option value="">
                    Select Team
                </option>

                @foreach($teams as $team)
                    <option
                        value="{{ $team->id }}"
                        data-department-id="{{ $team->department_id }}"
                        @selected(old('team_id', $employee?->currentEmployment?->team_id) == $team->id)>
                        {{ $team->name }}
                    </option>
                @endforeach

            </select>

            <p class="mt-2 text-xs text-slate-400">
                Pilih Department terlebih dahulu untuk menampilkan Team yang sesuai.
            </p>

            @error('team_id')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror

        </div>

        {{-- Supervisor --}}
        <div>

            <x-ui.select
                name="supervisor_id"
                label="Supervisor"
                :options="$employees"
                :selected="$employee?->currentEmployment?->supervisor_id"
                placeholder="Select Supervisor" />

            <p class="mt-2 text-xs text-slate-400">
                Atasan langsung dari employee ini (untuk struktur organisasi &amp; alur approval). Opsional.
            </p>

        </div>

        {{-- Employment Type --}}
        <div>

            <label
                for="employment_type"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Employment Type

                <span class="text-red-500">*</span>

            </label>

            <select

                id="employment_type"

                name="employment_type"

                required

                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"

            >

                <option value="">

                    Select Type

                </option>

                <option
                    value="Permanent"
                    @selected(old('employment_type',$employee?->currentEmployment?->employment_type)=='Permanent')>

                    Permanent

                </option>

                <option
                    value="Contract"
                    @selected(old('employment_type',$employee?->currentEmployment?->employment_type)=='Contract')>

                    Contract

                </option>

                <option
                    value="Internship"
                    @selected(old('employment_type',$employee?->currentEmployment?->employment_type)=='Internship')>

                    Internship

                </option>

            </select>

            @error('employment_type')

                <p class="mt-2 text-sm text-red-500">

                    {{ $message }}

                </p>

            @enderror

        </div>

        {{-- Employment Status --}}
        <div>

            <label
                for="employment_status"
                class="mb-2 flex items-center gap-1 text-sm font-semibold text-slate-700">

                Employment Status

                <span class="text-red-500">*</span>

            </label>

            <select

                id="employment_status"

                name="employment_status"

                required

                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"

            >

                <option value="">

                    Select Status

                </option>

                <option
                    value="Active"
                    @selected(old('employment_status',$employee?->currentEmployment?->employment_status)=='Active')>

                    Active

                </option>

                <option
                    value="Probation"
                    @selected(old('employment_status',$employee?->currentEmployment?->employment_status)=='Probation')>

                    Probation

                </option>

                <option
                    value="Resigned"
                    @selected(old('employment_status',$employee?->currentEmployment?->employment_status)=='Resigned')>

                    Resigned

                </option>

            </select>

            @error('employment_status')

                <p class="mt-2 text-sm text-red-500">

                    {{ $message }}

                </p>

            @enderror

        </div>

        {{-- Start Date --}}
        <x-ui.input
            name="start_date"
            type="date"
            label="Start Date"
            :value="$employee?->currentEmployment?->start_date?->format('Y-m-d')"
            required />

    </div>

</x-employee.section-card>

@once
    @push('scripts')
        <script>
            (function () {

                function filterTeamsByDepartment() {

                    const deptSelect = document.getElementById('department_id');
                    const teamSelect = document.getElementById('team_id');

                    if (!deptSelect || !teamSelect) {
                        return;
                    }

                    const selectedDept = deptSelect.value;
                    let selectedStillVisible = false;

                    Array.from(teamSelect.options).forEach(function (option) {

                        if (!option.value) {
                            return;
                        }

                        const optionDept = option.getAttribute('data-department-id');
                        const isVisible = !selectedDept || optionDept === selectedDept;

                        option.hidden = !isVisible;

                        if (isVisible && option.selected) {
                            selectedStillVisible = true;
                        }

                    });

                    if (!selectedStillVisible) {
                        teamSelect.value = '';
                    }

                }

                document.addEventListener('DOMContentLoaded', function () {

                    const deptSelect = document.getElementById('department_id');

                    if (!deptSelect) {
                        return;
                    }

                    // Terapkan filter saat halaman dimuat (mis. saat Edit Employee)
                    filterTeamsByDepartment();

                    deptSelect.addEventListener('change', filterTeamsByDepartment);

                });

            })();
        </script>
    @endpush
@endonce