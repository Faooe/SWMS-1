@php
    $rows = collect($dailyAttendance ?? []);
    $summary = $dailyAttendanceSummary ?? [];

    $formatMinutes = static function (int $minutes): string {
        if ($minutes <= 0) return '0m';
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;
        return $hours > 0 ? $hours.'j '.($mins > 0 ? $mins.'m' : '') : $mins.'m';
    };
@endphp

@if($assignment->daily_attendance_enabled)
<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 bg-gradient-to-r from-blue-50 via-white to-cyan-50 px-6 py-6 sm:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-sm">
                    <i data-lucide="calendar-check-2" class="h-6 w-6"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Attendance Harian Assignment</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Check In dan Check Out dilakukan terpisah pada setiap hari wajib selama assignment.
                    </p>
                </div>
            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-bold text-blue-700">
                <i data-lucide="repeat-2" class="h-3.5 w-3.5"></i>
                {{ $assignment->attendance_day_rule === 'EVERY_DAY' ? 'Setiap Hari' : 'Kalender Kerja' }}
            </span>
        </div>
    </div>

    <div class="p-6 sm:p-8">
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-blue-600">Kehadiran</span>
                    <i data-lucide="badge-percent" class="h-4 w-4 text-blue-500"></i>
                </div>
                <div class="mt-2 text-2xl font-black text-slate-900">{{ number_format((float)($summary['attendance_rate'] ?? 0), 1) }}%</div>
                <p class="mt-1 text-xs text-slate-500">{{ $summary['attended_days'] ?? 0 }} dari {{ $summary['required_days'] ?? 0 }} hari wajib sudah Check In</p>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Selesai</span>
                    <i data-lucide="circle-check-big" class="h-4 w-4 text-emerald-500"></i>
                </div>
                <div class="mt-2 text-2xl font-black text-slate-900">{{ $summary['completed_days'] ?? 0 }}/{{ $summary['required_days'] ?? 0 }}</div>
                <p class="mt-1 text-xs text-slate-500">Hari wajib yang sudah Check Out</p>
            </div>

            <div class="rounded-2xl border border-violet-100 bg-violet-50/70 p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-violet-600">Jam Kerja</span>
                    <i data-lucide="timer" class="h-4 w-4 text-violet-500"></i>
                </div>
                <div class="mt-2 text-2xl font-black text-slate-900">{{ $formatMinutes((int)($summary['work_minutes'] ?? 0)) }}</div>
                <p class="mt-1 text-xs text-slate-500">Akumulasi dari attendance assignment</p>
            </div>

            <div class="rounded-2xl border border-rose-100 bg-rose-50/70 p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-rose-600">Tidak Hadir</span>
                    <i data-lucide="calendar-x-2" class="h-4 w-4 text-rose-500"></i>
                </div>
                <div class="mt-2 text-2xl font-black text-slate-900">{{ $summary['absent_days'] ?? 0 }}</div>
                <p class="mt-1 text-xs text-slate-500">Hari wajib yang sudah terlewat tanpa Check In</p>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
            <div class="hidden grid-cols-[minmax(150px,1.2fr)_minmax(120px,.9fr)_minmax(160px,1fr)_minmax(180px,1.3fr)] gap-4 bg-slate-50 px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-500 md:grid">
                <span>Tanggal</span>
                <span>Status</span>
                <span>Check In / Out</span>
                <span>Ringkasan</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($rows as $row)
                    @php
                        $date = \Carbon\Carbon::parse($row['date']);
                        $isToday = $date->isToday();
                        $status = $row['status'] ?? 'UPCOMING';

                        [$statusLabel, $statusClass, $statusIcon] = match($status) {
                            'OFF' => ['Libur', 'bg-slate-100 text-slate-600', 'coffee'],
                            'PRESENT' => ['Hadir', 'bg-emerald-100 text-emerald-700', 'circle-check'],
                            'LATE' => ['Terlambat', 'bg-amber-100 text-amber-700', 'clock-alert'],
                            'WORKING' => ['Sedang Bekerja', 'bg-blue-100 text-blue-700', 'loader-circle'],
                            'ABSENT' => ['Tidak Hadir', 'bg-rose-100 text-rose-700', 'circle-x'],
                            'TODAY' => ['Belum Check In', 'bg-cyan-100 text-cyan-700', 'log-in'],
                            default => ['Akan Datang', 'bg-slate-100 text-slate-500', 'calendar-clock'],
                        };

                        $metrics = [];
                        if (($row['late_minutes'] ?? 0) > 0) $metrics[] = 'Telat '.$formatMinutes((int)$row['late_minutes']);
                        if (($row['work_minutes'] ?? 0) > 0) $metrics[] = 'Kerja '.$formatMinutes((int)$row['work_minutes']);
                        if (($row['early_leave_minutes'] ?? 0) > 0) $metrics[] = 'Pulang awal '.$formatMinutes((int)$row['early_leave_minutes']);
                        if (($row['overtime_minutes'] ?? 0) > 0) $metrics[] = 'Lembur '.$formatMinutes((int)$row['overtime_minutes']);
                    @endphp

                    <div class="grid gap-3 px-5 py-4 md:grid-cols-[minmax(150px,1.2fr)_minmax(120px,.9fr)_minmax(160px,1fr)_minmax(180px,1.3fr)] md:items-center {{ $isToday ? 'bg-blue-50/50' : 'bg-white' }}">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800">{{ $date->translatedFormat('D, d M Y') }}</span>
                                @if($isToday)
                                    <span class="rounded-full bg-blue-600 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">Hari ini</span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-slate-400">{{ ($row['required'] ?? false) ? 'Attendance wajib' : 'Tidak wajib attendance' }}</p>
                        </div>

                        <div>
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold {{ $statusClass }}">
                                <i data-lucide="{{ $statusIcon }}" class="h-3.5 w-3.5 {{ $status === 'WORKING' ? 'animate-spin' : '' }}"></i>
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <span class="rounded-lg bg-slate-100 px-2 py-1">{{ $row['check_in'] ?? '--:--' }}</span>
                            <i data-lucide="arrow-right" class="h-3.5 w-3.5 text-slate-400"></i>
                            <span class="rounded-lg bg-slate-100 px-2 py-1">{{ $row['check_out'] ?? '--:--' }}</span>
                        </div>

                        <div class="text-sm text-slate-500">
                            @if($metrics)
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($metrics as $metric)
                                        <span class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600">{{ $metric }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-slate-400">Belum ada metrik kerja</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-sm text-slate-500">Kalender attendance belum tersedia.</div>
                @endforelse
            </div>
        </div>

        @if(($summary['overtime_minutes'] ?? 0) > 0 || ($summary['early_leave_minutes'] ?? 0) > 0 || ($summary['late_days'] ?? 0) > 0)
            <div class="mt-4 flex flex-wrap gap-2 text-xs">
                @if(($summary['late_days'] ?? 0) > 0)
                    <span class="rounded-full bg-amber-50 px-3 py-1.5 font-semibold text-amber-700">{{ $summary['late_days'] }} hari terlambat</span>
                @endif
                @if(($summary['overtime_minutes'] ?? 0) > 0)
                    <span class="rounded-full bg-violet-50 px-3 py-1.5 font-semibold text-violet-700">Total lembur {{ $formatMinutes((int)$summary['overtime_minutes']) }}</span>
                @endif
                @if(($summary['early_leave_minutes'] ?? 0) > 0)
                    <span class="rounded-full bg-orange-50 px-3 py-1.5 font-semibold text-orange-700">Pulang awal {{ $formatMinutes((int)$summary['early_leave_minutes']) }}</span>
                @endif
            </div>
        @endif
    </div>
</div>
@endif
