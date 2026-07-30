<x-ui.card>

    {{-- Header --}}
    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">
            Employee Information
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Employee identity and placement.
        </p>

    </div>

    {{-- Body --}}
    <div class="grid gap-5 sm:grid-cols-2">

        <x-ui.detail-item
            label="Full Name"
            :value="$attendance->employee?->full_name" />

        <x-ui.detail-item
            label="Employee Number"
            :value="$attendance->employee?->employee_number" />

        <x-ui.detail-item
            label="Position"
            :value="$attendance->employee?->currentEmployment?->position?->name" />

        <x-ui.detail-item
            label="Office"
            :value="$attendance->employee?->currentEmployment?->office?->name" />

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-500">
                Attendance Type
            </label>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                    {{ $attendance->attendance_type }}
                </span>
            </div>
        </div>

        @if($attendance->attendance_type === 'ASSIGNMENT' && $attendance->assignment)

            <x-ui.detail-item
                label="Assignment"
                :value="$attendance->assignment->title" />

        @endif

    </div>

</x-ui.card>
