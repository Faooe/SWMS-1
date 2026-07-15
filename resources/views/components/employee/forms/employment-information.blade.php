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
        <x-ui.select
            name="team_id"
            label="Team"
            :options="$teams"
            :selected="$employee?->currentEmployment?->team_id"
            placeholder="Select Team" />

        {{-- Supervisor --}}
        <x-ui.select
            name="supervisor_id"
            label="Supervisor"
            :options="$employees"
            :selected="$employee?->currentEmployment?->supervisor_id"
            placeholder="Select Supervisor" />

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
                class="mb-2 block text-sm font-semibold text-slate-700">

                Employment Status

            </label>

            <select

                id="employment_status"

                name="employment_status"

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

        {{-- Employee Status --}}
        <div>

            <label
                for="is_active"
                class="mb-2 block text-sm font-semibold text-slate-700">

                Employee Status

            </label>

            <select

                id="is_active"

                name="is_active"

                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm
                    focus:border-blue-500
                    focus:ring-4
                    focus:ring-blue-100">

                <option
                    value="1"
                    @selected(old('is_active', $employee?->is_active) == true)>

                    Active

                </option>

                <option
                    value="0"
                    @selected(old('is_active', $employee?->is_active) == false)>

                    Inactive

                </option>

            </select>

            @error('is_active')

                <p class="mt-2 text-sm text-red-500">

                    {{ $message }}

                </p>

            @enderror

        </div>

    </div>

</x-employee.section-card>