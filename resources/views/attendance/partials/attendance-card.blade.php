@php
    $statusMeta = match($attendance->attendance_status) {
        'Present' => ['label' => 'Tepat', 'class' => 'bg-emerald-50 text-emerald-700', 'icon' => 'circle-check'],
        'Late' => ['label' => 'Telat', 'class' => 'bg-amber-50 text-amber-700', 'icon' => 'clock-3'],
        'Leave' => ['label' => 'Leave', 'class' => 'bg-blue-50 text-blue-700', 'icon' => 'calendar-days'],
        'Permission' => ['label' => 'Izin', 'class' => 'bg-violet-50 text-violet-700', 'icon' => 'file-check-2'],
        'Absent' => ['label' => 'Absen', 'class' => 'bg-red-50 text-red-700', 'icon' => 'circle-x'],
        default => ['label' => $attendance->attendance_status ?: '-', 'class' => 'bg-slate-100 text-slate-700', 'icon' => 'circle-help'],
    };
@endphp

<x-ui.card>
    <div class="mb-5 flex items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <i data-lucide="calendar-check-2" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">Attendance Information</h2>
                <p class="mt-1 text-sm text-slate-500">Ringkasan waktu dan status kehadiran employee.</p>
            </div>
        </div>

        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusMeta['class'] }}">
            <i data-lucide="{{ $statusMeta['icon'] }}" class="h-3.5 w-3.5"></i>
            {{ $statusMeta['label'] }}
        </span>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <x-ui.detail-item label="Attendance Date" :value="$attendance->attendance_date?->format('d F Y')" />
        <x-ui.detail-item label="Late Minutes" :value="($attendance->late_minutes ?? 0) . ' menit'" />
        <x-ui.detail-item label="Check In" :value="$attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : null" />
        <x-ui.detail-item label="Check Out" :value="$attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : null" />
    </div>
</x-ui.card>
