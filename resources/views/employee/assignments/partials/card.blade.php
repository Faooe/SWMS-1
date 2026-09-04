@php
    $pivot = $assignment->employees->firstWhere('id', auth()->user()->employee_id)?->pivot;
    $rawStatus = $pivot?->status ?? 'Assigned';
    $reviewStatus = $pivot?->review_status;
    $cardState = $assignment->getAttribute('employee_card_state') ?? [];
    $dailySummary = $cardState['my_daily_attendance_summary'] ?? null;

    $displayStatus = match($reviewStatus) {
        'Approved' => 'Completed',
        'Pending Review' => 'Pending Review',
        'Needs Revision' => 'Needs Revision',
        'Not Worked', 'Expired' => 'Not Worked',
        default => $rawStatus,
    };

    [$statusClass, $statusIcon, $stage, $statusLabel] = match($displayStatus) {
        'Assigned' => ['bg-blue-50 text-blue-700 ring-blue-100', 'clipboard-list', 10, 'Assigned'],
        'Accepted' => ['bg-indigo-50 text-indigo-700 ring-indigo-100', 'thumbs-up', 25, 'Accepted'],
        'In Progress' => ['bg-amber-50 text-amber-700 ring-amber-100', 'loader-circle', 55, 'In Progress'],
        'Pending Review' => ['bg-violet-50 text-violet-700 ring-violet-100', 'scan-search', 80, 'Pending Review'],
        'Needs Revision' => ['bg-rose-50 text-rose-700 ring-rose-100', 'rotate-ccw', 70, 'Needs Revision'],
        'Completed' => ['bg-emerald-50 text-emerald-700 ring-emerald-100', 'badge-check', 100, 'Completed'],
        'Rejected' => ['bg-red-50 text-red-700 ring-red-100', 'circle-x', 0, 'Rejected'],
        'Cancelled' => ['bg-red-50 text-red-700 ring-red-100', 'circle-x', 0, 'Cancelled'],
        'Not Worked' => ['bg-slate-100 text-slate-700 ring-slate-200', 'clock-x', 100, 'Not Worked'],
        default => ['bg-slate-50 text-slate-700 ring-slate-100', 'circle-dot', 0, $displayStatus],
    };

    [$priorityChip, $priorityDot] = match($assignment->priority) {
        'Critical' => ['bg-red-50 text-red-700 ring-red-100', 'bg-red-500'],
        'High' => ['bg-orange-50 text-orange-700 ring-orange-100', 'bg-orange-500'],
        'Medium' => ['bg-amber-50 text-amber-700 ring-amber-100', 'bg-amber-500'],
        default => ['bg-emerald-50 text-emerald-700 ring-emerald-100', 'bg-emerald-500'],
    };

    $remaining = null;
    if ($assignment->end_datetime && now()->lt($assignment->end_datetime)) {
        $minutesLeft = (int) ceil(abs(now()->diffInMinutes($assignment->end_datetime)));
        $remaining = $minutesLeft >= 1440
            ? (int) ceil($minutesLeft / 1440).' hari'
            : (int) max(1, ceil($minutesLeft / 60)).' jam';
    }

    $dailyDone = (int) data_get($dailySummary, 'completed_days', data_get($dailySummary, 'attended_days', 0));
    $dailyRequired = (int) data_get($dailySummary, 'required_days', 0);
    $dailyRate = $dailyRequired > 0 ? min(100, round(($dailyDone / $dailyRequired) * 100)) : 0;

    $contextMessage = match($displayStatus) {
        'Pending Review' => ['Menunggu review company', 'Hasil pekerjaan sudah dikirim dan sedang diperiksa.', 'bg-violet-50 border-violet-100 text-violet-700', 'scan-search'],
        'Needs Revision' => ['Perlu tindakan', 'Company meminta perbaikan. Buka assignment untuk melihat catatan revisi.', 'bg-rose-50 border-rose-100 text-rose-700', 'triangle-alert'],
        'Completed' => ['Pekerjaan selesai', 'Assignment sudah disetujui company.', 'bg-emerald-50 border-emerald-100 text-emerald-700', 'badge-check'],
        'Not Worked' => ['Tidak dikerjakan', 'Periode assignment berakhir tanpa pekerjaan yang memenuhi ketentuan.', 'bg-slate-50 border-slate-200 text-slate-600', 'clock-x'],
        default => null,
    };
@endphp

<a href="{{ route('employee.assignments.show', $assignment->uuid) }}" class="group block overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
    <div class="p-5 sm:p-6">
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">
                <i data-lucide="clipboard-check" class="h-5 w-5"></i>
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 ring-inset {{ $statusClass }}">
                        <i data-lucide="{{ $statusIcon }}" class="h-3.5 w-3.5 {{ $displayStatus === 'In Progress' ? 'animate-spin' : '' }}"></i>
                        {{ $statusLabel }}
                    </span>

                    @if($assignment->daily_attendance_enabled)
                        <span class="inline-flex items-center gap-1 rounded-full bg-cyan-50 px-2.5 py-1 text-[11px] font-bold text-cyan-700 ring-1 ring-inset ring-cyan-100">
                            <i data-lucide="calendar-check-2" class="h-3.5 w-3.5"></i>
                            Attendance Harian
                        </span>
                    @endif

                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 ring-inset {{ $priorityChip }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $priorityDot }}"></span>
                        {{ $assignment->priority }}
                    </span>
                </div>

                <h3 class="mt-3 line-clamp-2 text-lg font-black leading-snug text-slate-900 transition group-hover:text-blue-700">{{ $assignment->title }}</h3>
                <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-semibold text-slate-400">
                    <span>{{ $assignment->assignment_number }}</span>
                    @if($assignment->type)
                        <span class="text-slate-300">•</span>
                        <span>{{ $assignment->type }}</span>
                    @endif
                </div>
            </div>

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition group-hover:bg-blue-50 group-hover:text-blue-600">
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
            </div>
        </div>

        <div class="mt-5 grid gap-x-5 gap-y-3 border-y border-slate-100 py-4 text-sm sm:grid-cols-2">
            <div class="flex min-w-0 items-start gap-2.5">
                <i data-lucide="building-2" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Office</p>
                    <p class="mt-0.5 truncate font-semibold text-slate-700">{{ $assignment->office?->name ?? '-' }}</p>
                </div>
            </div>
            <div class="flex min-w-0 items-start gap-2.5">
                <i data-lucide="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Lokasi</p>
                    <p class="mt-0.5 truncate font-semibold text-slate-700">{{ $assignment->location_name ?: '-' }}</p>
                </div>
            </div>
            <div class="flex min-w-0 items-start gap-2.5">
                <i data-lucide="calendar-range" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Periode</p>
                    <p class="mt-0.5 font-semibold text-slate-700">{{ $assignment->start_datetime->format('d M Y') }} – {{ $assignment->end_datetime->format('d M Y') }}</p>
                </div>
            </div>
            <div class="flex min-w-0 items-start gap-2.5">
                <i data-lucide="clock-3" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Jam</p>
                    <p class="mt-0.5 font-semibold text-slate-700">{{ $assignment->start_datetime->format('H:i') }} – {{ $assignment->end_datetime->format('H:i') }}</p>
                </div>
            </div>
        </div>

        @if($assignment->daily_attendance_enabled && $dailyRequired > 0)
            <div class="mt-4 rounded-2xl bg-cyan-50/70 p-3.5 ring-1 ring-inset ring-cyan-100">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar-check-2" class="h-4 w-4 text-cyan-600"></i>
                        <span class="text-xs font-bold text-cyan-800">Progress Attendance</span>
                    </div>
                    <span class="text-xs font-black text-cyan-800">{{ $dailyDone }}/{{ $dailyRequired }} hari</span>
                </div>
                <div class="mt-2.5 h-1.5 overflow-hidden rounded-full bg-cyan-100">
                    <div class="h-full rounded-full bg-cyan-500" style="width: {{ $dailyRate }}%"></div>
                </div>
            </div>
        @else
            <div class="mt-4">
                <div class="flex items-center justify-between gap-3 text-xs">
                    <span class="font-semibold text-slate-500">Progress workflow</span>
                    <span class="font-black text-slate-700">{{ $stage }}%</span>
                </div>
                <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full {{ in_array($displayStatus, ['Rejected', 'Cancelled', 'Not Worked'], true) ? 'bg-rose-400' : 'bg-blue-600' }}" style="width: {{ $stage }}%"></div>
                </div>
            </div>
        @endif

        @if($contextMessage)
            <div class="mt-4 flex items-start gap-2.5 rounded-2xl border px-3.5 py-3 {{ $contextMessage[2] }}">
                <i data-lucide="{{ $contextMessage[3] }}" class="mt-0.5 h-4 w-4 shrink-0"></i>
                <div>
                    <p class="text-xs font-black">{{ $contextMessage[0] }}</p>
                    <p class="mt-0.5 text-xs leading-5 opacity-80">{{ $contextMessage[1] }}</p>
                </div>
            </div>
        @endif

        <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-400">
            <span class="inline-flex items-center gap-1.5 font-semibold">
                <i data-lucide="users" class="h-3.5 w-3.5"></i>
                {{ $assignment->employees->count() }} employee
            </span>

            @if($remaining && !in_array($displayStatus, ['Completed', 'Rejected', 'Cancelled', 'Not Worked'], true))
                <span class="inline-flex items-center gap-1.5 font-bold text-amber-600">
                    <i data-lucide="timer" class="h-3.5 w-3.5"></i>
                    {{ $remaining }} lagi
                </span>
            @else
                <span class="font-semibold text-slate-400">Buka detail untuk informasi lengkap</span>
            @endif
        </div>
    </div>
</a>
