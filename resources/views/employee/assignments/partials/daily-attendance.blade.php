@php
    $rows = collect($dailyAttendance ?? []);
    $summary = $dailyAttendanceSummary ?? [];
    $actions = $myActions ?? ($assignmentState['my_actions'] ?? []);
    $todayAttendance = $rows->firstWhere('date', today()->toDateString());
    $canCheckIn = (bool)($actions['can_check_in'] ?? false);
    $canCheckOut = (bool)($actions['can_check_out'] ?? false);
    $incompleteRows = $rows->where('status', 'INCOMPLETE');

    $formatMinutes = static function (int $minutes): string {
        if ($minutes <= 0) return '0m';
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;
        return $hours > 0 ? $hours.'j '.($mins > 0 ? $mins.'m' : '') : $mins.'m';
    };
@endphp

@if($assignment->daily_attendance_enabled)
<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 bg-white px-6 py-6 sm:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i data-lucide="calendar-check-2" class="h-6 w-6"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Attendance Harian Assignment</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Setiap hari wajib memiliki sesi Check In dan Check Out sendiri.
                    </p>
                </div>
            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">
                <i data-lucide="calendar-days" class="h-3.5 w-3.5"></i>
                {{ $assignment->attendance_day_rule === 'EVERY_DAY' ? 'Setiap Hari Kalender' : 'Hari Kerja Company' }}
            </span>
        </div>
    </div>

    <div class="p-6 sm:p-8">
        {{-- Semua metric memakai visual yang sama agar konsisten dengan card SWMS lain. --}}
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @php
                $metricCards = [
                    ['label' => 'Kehadiran', 'value' => number_format((float)($summary['attendance_rate'] ?? 0), 1).'%', 'hint' => ($summary['attended_days'] ?? 0).' dari '.($summary['required_days'] ?? 0).' hari wajib sudah Check In', 'icon' => 'badge-percent'],
                    ['label' => 'Selesai', 'value' => ($summary['completed_days'] ?? 0).'/'.($summary['required_days'] ?? 0), 'hint' => 'Hari wajib yang sudah Check Out', 'icon' => 'circle-check-big'],
                    ['label' => 'Jam Kerja', 'value' => $formatMinutes((int)($summary['work_minutes'] ?? 0)), 'hint' => 'Akumulasi attendance assignment', 'icon' => 'timer'],
                    ['label' => 'Tidak Hadir', 'value' => (string)($summary['absent_days'] ?? 0), 'hint' => 'Hari wajib terlewat tanpa Check In', 'icon' => 'calendar-x-2'],
                ];
            @endphp

            @foreach($metricCards as $metric)
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $metric['label'] }}</span>
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <i data-lucide="{{ $metric['icon'] }}" class="h-4 w-4"></i>
                        </span>
                    </div>
                    <div class="mt-2 text-2xl font-black text-slate-900">{{ $metric['value'] }}</div>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ $metric['hint'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-5 rounded-2xl border border-blue-100 bg-blue-50/50 px-4 py-3 text-sm text-slate-600">
            <div class="flex gap-3">
                <i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0 text-blue-600"></i>
                <p>
                    <strong class="text-slate-800">Selesai x/y</strong> adalah jumlah hari yang sudah melakukan <strong>Check Out</strong>.
                    Setelah Check In berhasil, tombol pada bagian ini otomatis berubah menjadi <strong>Check Out Hari Ini</strong>.
                </p>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
            <div class="hidden grid-cols-[minmax(150px,1.2fr)_minmax(130px,.9fr)_minmax(160px,1fr)_minmax(180px,1.3fr)] gap-4 bg-slate-50 px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-500 md:grid">
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
                            'PRESENT' => ['Selesai', 'bg-blue-50 text-blue-700', 'circle-check'],
                            'LATE' => ['Selesai • Terlambat', 'bg-blue-50 text-blue-700', 'clock-alert'],
                            'WORKING' => ['Sedang Bekerja', 'bg-blue-100 text-blue-700', 'loader-circle'],
                            'INCOMPLETE' => ['Belum Check Out', 'bg-slate-200 text-slate-700', 'clock-x'],
                            'ABSENT' => ['Tidak Hadir', 'bg-slate-200 text-slate-700', 'circle-x'],
                            'TODAY' => ['Belum Check In', 'bg-blue-50 text-blue-700', 'log-in'],
                            default => ['Akan Datang', 'bg-slate-100 text-slate-500', 'calendar-clock'],
                        };

                        $metrics = [];
                        if (($row['late_minutes'] ?? 0) > 0) $metrics[] = 'Telat '.$formatMinutes((int)$row['late_minutes']);
                        if (($row['work_minutes'] ?? 0) > 0) $metrics[] = 'Kerja '.$formatMinutes((int)$row['work_minutes']);
                        if (($row['early_leave_minutes'] ?? 0) > 0) $metrics[] = 'Pulang awal '.$formatMinutes((int)$row['early_leave_minutes']);
                        if (($row['overtime_minutes'] ?? 0) > 0) $metrics[] = 'Lembur '.$formatMinutes((int)$row['overtime_minutes']);
                    @endphp

                    <div class="grid gap-3 px-5 py-4 md:grid-cols-[minmax(150px,1.2fr)_minmax(130px,.9fr)_minmax(160px,1fr)_minmax(180px,1.3fr)] md:items-center {{ $isToday ? 'bg-blue-50/40' : 'bg-white' }}">
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
                            @elseif($status === 'INCOMPLETE')
                                @php $correction = $row['checkout_correction'] ?? null; @endphp
                                @if(($correction['status'] ?? null) === 'Pending')
                                    <span class="text-xs font-semibold text-blue-700">Koreksi Check Out menunggu review Company.</span>
                                @elseif(($correction['status'] ?? null) === 'Rejected')
                                    <span class="text-xs font-semibold text-slate-700">Koreksi sebelumnya ditolak. Kamu dapat mengajukan ulang.</span>
                                @else
                                    <span class="text-xs font-medium text-slate-600">Check Out tidak dilakukan sebelum batas harian.</span>
                                @endif
                            @else
                                <span class="text-xs text-slate-400">Belum ada metrik kerja</span>
                            @endif
                        </div>
                    </div>

                    @if($status === 'INCOMPLETE')
                        @php $correction = $row['checkout_correction'] ?? null; @endphp
                        <div class="border-t border-slate-100 bg-slate-50/60 px-5 py-4">
                            @if(($correction['status'] ?? null) === 'Pending')
                                <div class="flex items-start gap-3 text-sm text-slate-600">
                                    <i data-lucide="clock-3" class="mt-0.5 h-4 w-4 text-blue-600"></i>
                                    <div><strong class="text-slate-800">Koreksi diajukan:</strong> {{ $correction['requested_check_out_time'] ?? '-' }} · {{ $correction['reason'] ?? '-' }}</div>
                                </div>
                            @else
                                @if(($correction['status'] ?? null) === 'Rejected')
                                    <p class="mb-3 text-xs text-slate-500">Pengajuan sebelumnya ditolak{{ !empty($correction['review_notes']) ? ': '.$correction['review_notes'] : '.' }}</p>
                                @endif
                                <form method="POST" action="{{ route('employee.assignments.checkout-corrections.store', $assignment->uuid) }}" class="grid gap-3 md:grid-cols-[140px_minmax(0,1fr)_auto]">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $row['date'] }}">
                                    <input type="time" name="requested_check_out_time" required max="23:00" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">
                                    <input type="text" name="reason" required minlength="5" maxlength="1000" placeholder="Alasan lupa Check Out..." class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">
                                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white hover:bg-blue-700">Ajukan Koreksi</button>
                                </form>
                                <p class="mt-2 text-[11px] text-slate-400">Koreksi hanya untuk lupa Check Out. Hari tanpa Check In tidak dapat dikoreksi.</p>
                            @endif
                        </div>
                    @endif
                @empty
                    <div class="px-6 py-12 text-center text-sm text-slate-500">Kalender attendance belum tersedia.</div>
                @endforelse
            </div>
        </div>

        {{-- Aksi attendance sengaja berada di card kalender, bukan tersembunyi di sidebar. --}}
        @if($todayAttendance && ($todayAttendance['required'] ?? false))
            <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Attendance Hari Ini</p>
                        <p class="mt-1 font-bold text-slate-900">{{ today()->translatedFormat('D, d M Y') }}</p>
                        @if($canCheckOut)
                            <p class="mt-1 text-sm text-slate-500">Kamu sudah Check In. Tutup sesi hari ini setelah pekerjaan selesai.</p>
                        @elseif($canCheckIn)
                            <p class="mt-1 text-sm text-slate-500">Check In di lokasi assignment untuk memulai attendance hari ini.</p>
                        @elseif($todayAttendance['checked_out'] ?? false)
                            <p class="mt-1 text-sm text-slate-500">Sesi attendance hari ini sudah selesai.</p>
                        @endif
                    </div>

                    <div class="flex min-w-0 flex-col gap-2 sm:flex-row lg:min-w-[360px]">
                        @if($canCheckIn || $canCheckOut)
                            <a target="_blank" rel="noopener" href="https://www.google.com/maps?q={{ $assignment->latitude }},{{ $assignment->longitude }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-100">
                                <i data-lucide="navigation" class="h-4 w-4"></i>
                                Navigasi
                            </a>
                        @endif

                        @if($canCheckIn)
                            <form id="daily-assignment-check-in-form" method="POST" action="{{ route('employee.assignments.check-in', $assignment->uuid) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="latitude" class="js-assignment-lat">
                                <input type="hidden" name="longitude" class="js-assignment-lng">
                                <button type="submit" id="daily-assignment-check-in-btn" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-700">
                                    <i data-lucide="log-in" class="h-4 w-4"></i>
                                    Check In Hari Ini
                                </button>
                            </form>
                        @elseif($canCheckOut)
                            <form id="daily-assignment-check-out-form" method="POST" action="{{ route('employee.assignments.check-out', $assignment->uuid) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="latitude" class="js-assignment-lat">
                                <input type="hidden" name="longitude" class="js-assignment-lng">
                                <button type="submit" id="daily-assignment-check-out-btn" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-700">
                                    <i data-lucide="log-out" class="h-4 w-4"></i>
                                    Check Out Hari Ini
                                </button>
                            </form>
                        @elseif($todayAttendance['checked_out'] ?? false)
                            <div class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm font-bold text-blue-700">
                                <i data-lucide="circle-check" class="h-4 w-4"></i>
                                Attendance Selesai
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($todayAttendance && !($todayAttendance['required'] ?? true))
            <div class="mt-5 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                <i data-lucide="coffee" class="h-5 w-5 shrink-0"></i>
                <span>Hari ini tidak termasuk hari attendance wajib.</span>
            </div>
        @endif

        @if($incompleteRows->isNotEmpty())
            <div class="mt-4 flex items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600">
                <i data-lucide="clock-alert" class="mt-0.5 h-5 w-5 shrink-0 text-slate-500"></i>
                <div>
                    <p class="font-semibold text-slate-800">{{ $incompleteRows->count() }} hari belum melakukan Check Out.</p>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">Hari yang sudah lewat tetap tercatat sebagai attendance belum selesai. Mulai sekarang lakukan Check Out pada hari yang sama sebelum batas 23:00 agar angka <strong>Selesai</strong> bertambah.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endif
