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

    $attendanceStatusClass = match ($attendanceStatus) {
        'Present' => 'bg-emerald-50 text-emerald-700',
        'Late' => 'bg-amber-50 text-amber-700',
        'Leave', 'Permission' => 'bg-blue-50 text-blue-700',
        'Absent' => 'bg-red-50 text-red-700',
        default => 'bg-slate-100 text-slate-600',
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

<x-ui.card class="overflow-hidden p-0">
    <div class="border-b border-slate-100 px-6 py-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Hari Ini</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900">Workspace Harian</h2>
                <p class="mt-1 text-sm text-slate-500">Pantau attendance dan assignment yang perlu kamu fokuskan hari ini.</p>
            </div>
            <a href="{{ route('employee.attendance.index') }}" class="inline-flex items-center justify-center rounded-xl border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 transition hover:border-blue-200 hover:bg-blue-100">
                Buka Attendance
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 px-6 py-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,.95fr)]">
        <section>
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <i data-lucide="clock-3" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Attendance Hari Ini</p>
                        <p class="mt-1 text-sm text-slate-500">Status attendance dan ringkasan jam kerja.</p>
                    </div>
                </div>

                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $attendanceStatusClass }}">
                    {{ $attendanceStatusLabel }}
                </span>
            </div>

            <div class="mt-5 grid grid-cols-3 gap-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                    <p class="text-xs font-medium text-slate-400">Check In</p>
                    <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $checkIn ?: '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                    <p class="text-xs font-medium text-slate-400">Check Out</p>
                    <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $checkOut ?: '-' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                    <p class="text-xs font-medium text-slate-400">Jam Kerja</p>
                    <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $workLabel }}</p>
                </div>
            </div>

            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                <span class="inline-flex items-center gap-2 text-sm text-slate-500">
                    <i data-lucide="{{ $todayAttendance?->attendance_type === 'ASSIGNMENT' ? 'map-pin' : 'building-2' }}" class="h-4 w-4"></i>
                    {{ $attendanceSourceLabel }}
                </span>

                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('employee.attendance.index') }}" class="font-semibold text-blue-600 hover:text-blue-700">Buka Attendance</a>
                    <a href="{{ route('employee.attendance.history') }}" class="font-semibold text-slate-600 hover:text-blue-600">Riwayat</a>
                </div>
            </div>
        </section>

        <section class="lg:border-l lg:border-slate-100 lg:pl-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Assignment Hari Ini</p>
                        <p class="mt-1 text-sm text-slate-500">Pekerjaan yang perlu kamu prioritaskan pada hari ini.</p>
                    </div>
                </div>
                <a href="{{ route('employee.assignments.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat semua</a>
            </div>

            <div class="mt-5 space-y-3">
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

                    <a href="{{ route('employee.assignments.show', $assignment->uuid) }}" class="group flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-4 transition hover:border-blue-200 hover:bg-slate-50">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <i data-lucide="clipboard-check" class="h-5 w-5"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="truncate text-sm font-semibold text-slate-900 group-hover:text-blue-700">{{ $assignment->title }}</p>
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusClass }}">{{ $displayStatus }}</span>
                            </div>
                            <p class="mt-1 truncate text-sm text-slate-500">
                                {{ $assignment->location_name }} · {{ optional($assignment->start_datetime)->format('H:i') }}–{{ optional($assignment->end_datetime)->format('H:i') }}
                            </p>
                        </div>
                        <i data-lucide="chevron-right" class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:text-blue-500"></i>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-5 py-8 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-white text-slate-400 shadow-sm">
                            <i data-lucide="clipboard-check" class="h-5 w-5"></i>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-slate-700">Tidak ada assignment aktif hari ini.</p>
                        <p class="mt-1 text-sm text-slate-500">Kamu bisa melihat seluruh assignment dari menu Assignment.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-ui.card>
