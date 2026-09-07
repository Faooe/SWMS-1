@php
    $timelineStatus = match($attendance->attendance_status) {
        'Present' => 'Tepat',
        'Late' => 'Telat',
        'Permission' => 'Izin',
        'Absent' => 'Absen',
        default => $attendance->attendance_status ?: '-',
    };
@endphp

<x-ui.card>
    <div class="mb-5 flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
            <i data-lucide="history" class="h-5 w-5"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-900">Activity Timeline</h2>
            <p class="mt-1 text-sm text-slate-500">Urutan aktivitas attendance pada tanggal ini.</p>
        </div>
    </div>

    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><i data-lucide="log-in" class="h-4 w-4"></i></div>
                <div><p class="text-xs font-medium text-slate-400">Check In</p><p class="mt-0.5 font-semibold text-slate-900">{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</p></div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><i data-lucide="map-pin-check" class="h-4 w-4"></i></div>
                <div><p class="text-xs font-medium text-slate-400">GPS Validation</p><p class="mt-0.5 font-semibold {{ $attendance->location_verified ? 'text-emerald-700' : 'text-red-700' }}">{{ $attendance->location_verified ? 'Verified' : 'Not Verified' }}</p></div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i data-lucide="badge-info" class="h-4 w-4"></i></div>
                <div><p class="text-xs font-medium text-slate-400">Status</p><p class="mt-0.5 font-semibold text-slate-900">{{ $timelineStatus }}</p></div>
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><i data-lucide="log-out" class="h-4 w-4"></i></div>
                <div><p class="text-xs font-medium text-slate-400">Check Out</p><p class="mt-0.5 font-semibold text-slate-900">{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : 'Belum check out' }}</p></div>
            </div>
        </div>
    </div>
</x-ui.card>
