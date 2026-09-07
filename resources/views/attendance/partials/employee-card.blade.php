<x-ui.card>
    <div class="mb-5 flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
            <i data-lucide="user-round" class="h-5 w-5"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-900">Employee Information</h2>
            <p class="mt-1 text-sm text-slate-500">Identitas employee dan penempatan kerja.</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <x-ui.detail-item label="Full Name" :value="$attendance->employee?->full_name" />
        <x-ui.detail-item label="Employee Number" :value="$attendance->employee?->employee_number" />
        <x-ui.detail-item label="Position" :value="$attendance->employee?->currentEmployment?->position?->name" />
        <x-ui.detail-item label="Office" :value="$attendance->employee?->currentEmployment?->office?->name" />

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-500">Attendance Type</label>
            <div class="min-h-[48px] rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ $attendance->attendance_type ?: '-' }}
                </span>
            </div>
        </div>

        @if($attendance->attendance_type === 'ASSIGNMENT' && $attendance->assignment)
            <x-ui.detail-item label="Assignment" :value="$attendance->assignment->title" />
        @endif
    </div>
</x-ui.card>
