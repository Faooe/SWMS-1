@php
    $attendanceTypeLabel = match($attendance->attendance_type) {
        'OFFICE' => 'Office',
        'ASSIGNMENT' => 'Assignment',
        default => $attendance->attendance_type ?: '-',
    };
@endphp

<x-ui.card>
    <div class="mb-5 flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
            <i data-lucide="user-round" class="h-5 w-5"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-900">Employee Information</h2>
            <p class="mt-1 text-sm text-slate-500">Identitas employee dan konteks lokasi attendance.</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <x-ui.detail-item label="Full Name" :value="$attendance->employee?->full_name" />
        <x-ui.detail-item label="Employee Number" :value="$attendance->employee?->employee_number" />
        <x-ui.detail-item label="Position" :value="$attendance->employee?->currentEmployment?->position?->name" />
        <x-ui.detail-item label="Office" :value="$attendance->office?->name ?? $attendance->employee?->currentEmployment?->office?->name" />

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-500">Attendance Type</label>
            <div class="flex min-h-[48px] items-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    <i data-lucide="{{ $attendance->attendance_type === 'ASSIGNMENT' ? 'clipboard-list' : 'building-2' }}" class="h-3.5 w-3.5"></i>
                    {{ $attendanceTypeLabel }}
                </span>
            </div>
        </div>

        @if($attendance->attendance_type === 'ASSIGNMENT' && $attendance->assignment)
            <x-ui.detail-item label="Assignment" :value="$attendance->assignment->title" />
        @endif
    </div>
</x-ui.card>
