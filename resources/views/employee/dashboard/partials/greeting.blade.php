@php
    $todayStatus = $todayAttendance?->attendance_status;
    $todayStatusLabel = match ($todayStatus) {
        'Present' => 'Hadir',
        'Late' => 'Terlambat',
        'Leave' => 'Cuti',
        'Permission' => 'Izin',
        'Absent' => 'Tidak Hadir',
        default => 'Belum Check In',
    };

    $statusDot = match ($todayStatus) {
        'Present' => 'bg-emerald-500',
        'Late' => 'bg-amber-500',
        'Leave', 'Permission' => 'bg-blue-500',
        'Absent' => 'bg-red-500',
        default => 'bg-slate-300',
    };
@endphp

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-4">
        <div class="rounded-full ring-4 ring-blue-50">
            <x-ui.avatar :employee="$employee" size="14" />
        </div>

        <div>
            <p class="text-sm font-medium text-blue-600">Dashboard Employee</p>
            <h1 class="mt-0.5 text-2xl font-bold tracking-tight text-slate-900">
                Halo, {{ $employee->full_name }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                {{ now()->translatedFormat('l, d F Y') }} · Fokus pada pekerjaan dan attendance hari ini.
            </p>
        </div>
    </div>

    <div class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm">
        <span class="h-2.5 w-2.5 rounded-full {{ $statusDot }}"></span>
        {{ $todayStatusLabel }}
    </div>
</div>
