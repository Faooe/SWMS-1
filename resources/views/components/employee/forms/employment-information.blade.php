@props([
    'employee' => null,
    'departments',
    'positions',
    'teams',
    'offices',
    'employees',
])

<x-employee.section-card
    title="Pekerjaan & Penempatan"
    description="Atur department, posisi, office, team, supervisor, dan status kerja."
    icon="briefcase-business">

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        <x-ui.select name="department_id" label="Department" :options="$departments" :selected="$employee?->currentEmployment?->department_id" placeholder="Pilih Department" required />
        <x-ui.select name="position_id" label="Position" :options="$positions" :selected="$employee?->currentEmployment?->position_id" placeholder="Pilih Position" required />

        <div>
            <label for="office_id" class="mb-2 block text-sm font-semibold text-slate-700">Office</label>
            <select id="office_id" name="office_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">Gunakan Head Office</option>
                @foreach($offices as $officeItem)
                    <option value="{{ $officeItem->id }}" @selected(old('office_id', $employee?->currentEmployment?->office_id) == $officeItem->id)>
                        {{ $officeItem->name }}{{ $officeItem->is_head_office ? ' · Head Office' : '' }}
                    </option>
                @endforeach
            </select>
            @error('office_id')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="team_id" class="mb-2 block text-sm font-semibold text-slate-700">Team</label>
            <select id="team_id" name="team_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">Tanpa Team</option>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}" data-department-id="{{ $team->department_id }}" @selected(old('team_id', $employee?->currentEmployment?->team_id) == $team->id)>{{ $team->name }}</option>
                @endforeach
            </select>
            <p class="mt-2 text-xs text-slate-400">Team mengikuti Department yang dipilih.</p>
            @error('team_id')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="supervisor_id" class="mb-2 block text-sm font-semibold text-slate-700">Supervisor</label>
            <select id="supervisor_id" name="supervisor_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">Tanpa Supervisor</option>
                @foreach($employees as $supervisor)
                    @continue($employee && $supervisor->id === $employee->id)
                    <option value="{{ $supervisor->id }}" @selected(old('supervisor_id', $employee?->currentEmployment?->supervisor_id) == $supervisor->id)>{{ $supervisor->full_name }} · {{ $supervisor->employee_number }}</option>
                @endforeach
            </select>
            @error('supervisor_id')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="employment_type" class="mb-2 flex items-center gap-1 text-sm font-semibold text-slate-700">Employment Type <span class="text-red-500">*</span></label>
            <select id="employment_type" name="employment_type" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">Pilih Type</option>
                @foreach(['Permanent','Contract','Internship'] as $type)
                    <option value="{{ $type }}" @selected(old('employment_type', $employee?->currentEmployment?->employment_type) === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="employment_status" class="mb-2 flex items-center gap-1 text-sm font-semibold text-slate-700">Employment Status <span class="text-red-500">*</span></label>
            <select id="employment_status" name="employment_status" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">Pilih Status</option>
                @foreach(['Active','Resigned','Retired','Suspended'] as $status)
                    <option value="{{ $status }}" @selected(old('employment_status', $employee?->currentEmployment?->employment_status) === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <x-ui.input name="start_date" type="date" label="Tanggal Mulai" :value="$employee?->currentEmployment?->start_date?->format('Y-m-d')" required />
    </div>
</x-employee.section-card>

@once
    @push('scripts')
        <script>
            (function () {
                function filterTeamsByDepartment() {
                    const dept = document.getElementById('department_id');
                    const team = document.getElementById('team_id');
                    if (!dept || !team) return;

                    const selected = dept.value;
                    let selectedVisible = false;
                    Array.from(team.options).forEach((option) => {
                        if (!option.value) return;
                        const visible = !selected || option.dataset.departmentId === selected;
                        option.hidden = !visible;
                        if (visible && option.selected) selectedVisible = true;
                    });
                    if (!selectedVisible) team.value = '';
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const dept = document.getElementById('department_id');
                    if (!dept) return;
                    filterTeamsByDepartment();
                    dept.addEventListener('change', filterTeamsByDepartment);
                });
            })();
        </script>
    @endpush
@endonce
