@php
    $hasOfficeCheckIn = $officeAttendance?->hasCheckedIn() ?? false;
    $hasAssignmentCheckIn = $assignmentAttendance?->hasCheckedIn() ?? false;
    $hasAnyCheckIn = $hasOfficeCheckIn || $hasAssignmentCheckIn;

    $hasOpenAttendance = ($hasOfficeCheckIn && !($officeAttendance?->hasCheckedOut() ?? false))
        || ($hasAssignmentCheckIn && !($assignmentAttendance?->hasCheckedOut() ?? false));

    $isLate = ($officeAttendance?->attendance_status === 'Late')
        || ($assignmentAttendance?->attendance_status === 'Late');

    if (!$hasAnyCheckIn) {
        $todayStatusLabel = 'Belum Check In';
        $todayStatusClass = 'border-slate-200 bg-slate-50 text-slate-600';
        $todayStatusDot = 'bg-slate-300';
    } elseif ($hasOpenAttendance) {
        $todayStatusLabel = $isLate ? 'Sedang Bekerja · Terlambat' : 'Sedang Bekerja';
        $todayStatusClass = $isLate
            ? 'border-amber-200 bg-amber-50 text-amber-700'
            : 'border-blue-200 bg-blue-50 text-blue-700';
        $todayStatusDot = $isLate ? 'bg-amber-500' : 'bg-blue-500';
    } else {
        $todayStatusLabel = 'Attendance Selesai';
        $todayStatusClass = 'border-emerald-200 bg-emerald-50 text-emerald-700';
        $todayStatusDot = 'bg-emerald-500';
    }
@endphp

<div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
        <p class="text-sm font-semibold text-blue-600">Attendance Employee</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Attendance Hari Ini</h2>
        <p class="mt-1 text-sm text-slate-500">
            {{ now()->translatedFormat('l, d F Y') }} · Catat kehadiran sesuai lokasi kerja yang ditentukan.
        </p>
    </div>

    <div class="inline-flex w-fit items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold shadow-sm {{ $todayStatusClass }}">
        <span class="h-2.5 w-2.5 rounded-full {{ $todayStatusDot }}"></span>
        {{ $todayStatusLabel }}
    </div>
</div>
