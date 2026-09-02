@php
    $attendanceStatus = $todayAttendance?->attendance_status;
    $attendanceStatusLabel = match ($attendanceStatus) {
        'Present' => 'Hadir',
        'Late' => 'Terlambat',
        'Leave' => 'Cuti',
        'Permission' => 'Izin',
        'Absent' => 'Tidak Hadir',
        default => 'Belum Check In',
    };

    $attendanceSourceLabel = $todayAttendance
        ? ($todayAttendance->attendance_type === 'ASSIGNMENT' ? 'Attendance Assignment' : 'Attendance Office')
        : 'Belum ada attendance';

    $checkIn = optional($todayAttendance?->check_in_time)->format('H:i');
    $checkOut = optional($todayAttendance?->check_out_time)->format('H:i');
    $workMinutes = (int) ($todayAttendance?->work_minutes ?? 0);
    $workHours = intdiv($workMinutes, 60);
    $workRemain = $workMinutes % 60;
    $workLabel = $workMinutes > 0
        ? trim(($workHours > 0 ? $workHours.'j ' : '').($workRemain > 0 ? $workRemain.'m' : ''))
        : '-';
@endphp

<x-ui.card class="p-0 overflow-hidden">
    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Hari Ini</p>
                <h2 class="mt-1 text-lg font-bold text-slate-900">Ringkasan Aktivitas</h2>
            </div>
            <a href="{{ route('employee.attendance.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                Buka Attendance
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2">
        <section class="px-5 py-5 sm:px-6 lg:border-r lg:border-slate-100">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Attendance</p>
                    <p class="mt-1 text-sm text-slate-500">Status dan jam kerja hari ini.</p>
                </div>

                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ $attendanceStatusLabel }}
                </span>
            </div>

            <div class="mt-5 grid grid-cols-3 divide-x divide-slate-100 rounded-2xl bg-slate-50 px-2 py-4">
                <div class="px-3">
                    <p class="text-xs text-slate-400">Check In</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">{{ $checkIn ?: '-' }}</p>
                </div>
                <div class="px-3">
                    <p class="text-xs text-slate-400">Check Out</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">{{ $checkOut ?: '-' }}</p>
                </div>
                <div class="px-3">
                    <p class="text-xs text-slate-400">Jam Kerja</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">{{ $workLabel }}</p>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between gap-3 text-sm">
                <span class="inline-flex items-center gap-1.5 text-slate-500">
                    <i data-lucide="{{ $todayAttendance?->attendance_type === 'ASSIGNMENT' ? 'map-pin' : 'building-2' }}" class="h-4 w-4"></i>
                    {{ $attendanceSourceLabel }}
                </span>
                <a href="{{ route('employee.attendance.history') }}" class="font-semibold text-slate-600 hover:text-blue-600">
                    Riwayat
                </a>
            </div>
        </section>

        <section class="border-t border-slate-100 px-5 py-5 sm:px-6 lg:border-t-0">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Assignment Hari Ini</p>
                    <p class="mt-1 text-sm text-slate-500">Prioritaskan pekerjaan yang sedang aktif.</p>
                </div>
                <a href="{{ route('employee.assignments.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat semua</a>
            </div>

            <div class="mt-4 divide-y divide-slate-100">
                @forelse($todayAssignments->take(3) as $assignment)
                    @php
                        $employeeRow = $assignment->employees->firstWhere('id', $employee->id);
                        $pivot = $employeeRow?->pivot;
                        $displayStatus = match ($pivot?->review_status) {
                            'Pending Review' => 'Pending Review',
                            'Needs Revision' => 'Needs Revision',
                            'Approved' => 'Completed',
                            'Not Worked', 'Expired' => 'Not Worked',
                            default => $pivot?->status ?? $assignment->status,
                        };
                        $statusClass = match ($displayStatus) {
                            'Needs Revision', 'Not Worked' => 'bg-red-50 text-red-700',
                            'Completed' => 'bg-emerald-50 text-emerald-700',
                            'Pending Review' => 'bg-amber-50 text-amber-700',
                            default => 'bg-blue-50 text-blue-700',
                        };
                    @endphp

                    <a href="{{ route('employee.assignments.show', $assignment->uuid) }}" class="group flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <p class="truncate text-sm font-semibold text-slate-900 group-hover:text-blue-700">{{ $assignment->title }}</p>
                                <span class="hidden rounded-full px-2 py-0.5 text-[10px] font-semibold sm:inline-flex {{ $statusClass }}">{{ $displayStatus }}</span>
                            </div>
                            <p class="mt-0.5 truncate text-xs text-slate-500">
                                {{ $assignment->location_name }} · {{ optional($assignment->start_datetime)->format('H:i') }}–{{ optional($assignment->end_datetime)->format('H:i') }}
                            </p>
                        </div>
                        <i data-lucide="chevron-right" class="h-4 w-4 shrink-0 text-slate-300 group-hover:text-blue-500"></i>
                    </a>
                @empty
                    <div class="py-7 text-center">
                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <i data-lucide="clipboard-check" class="h-5 w-5"></i>
                        </div>
                        <p class="mt-3 text-sm font-medium text-slate-600">Tidak ada assignment aktif hari ini.</p>
                        <p class="mt-1 text-xs text-slate-400">Kamu bisa melihat seluruh assignment dari menu Assignment.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-ui.card>
