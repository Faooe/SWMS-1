@php
    $pivot = $assignment->employees->firstWhere('id', auth()->user()->employee_id)?->pivot;
    $rawStatus = $pivot?->status ?? 'Assigned';
    $reviewStatus = $pivot?->review_status;

    $displayStatus = match($reviewStatus) {
        'Approved' => 'Completed',
        'Pending Review' => 'Pending Review',
        'Needs Revision' => 'Needs Revision',
        'Not Worked', 'Expired' => 'Not Worked',
        default => $rawStatus,
    };

    [$statusClass, $statusIcon, $stage] = match($displayStatus) {
        'Assigned' => ['bg-blue-100 text-blue-700', 'clipboard-list', 10],
        'Accepted' => ['bg-indigo-100 text-indigo-700', 'thumbs-up', 25],
        'In Progress' => ['bg-amber-100 text-amber-700', 'loader-circle', 55],
        'Pending Review' => ['bg-violet-100 text-violet-700', 'scan-search', 80],
        'Needs Revision' => ['bg-rose-100 text-rose-700', 'rotate-ccw', 70],
        'Completed' => ['bg-emerald-100 text-emerald-700', 'badge-check', 100],
        'Rejected' => ['bg-red-100 text-red-700', 'circle-x', 0],
        'Not Worked' => ['bg-slate-200 text-slate-700', 'clock-x', 100],
        default => ['bg-slate-100 text-slate-700', 'circle-dot', 0],
    };

    $priorityClass = match($assignment->priority) {
        'Critical' => 'bg-red-500',
        'High' => 'bg-orange-500',
        'Medium' => 'bg-amber-500',
        default => 'bg-emerald-500',
    };

    $remaining = null;
    if (now()->lt($assignment->end_datetime)) {
        $minutesLeft = (int) ceil(abs(now()->diffInMinutes($assignment->end_datetime)));
        $remaining = $minutesLeft >= 1440
            ? (int) ceil($minutesLeft / 1440).' hari'
            : (int) max(1, ceil($minutesLeft / 60)).' jam';
    }
@endphp

<a href="{{ route('employee.assignments.show', $assignment->uuid) }}" class="group block overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-lg">
    <div class="h-1.5 w-full {{ $priorityClass }}"></div>

    <div class="p-5 sm:p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $statusClass }}">
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="{{ $statusIcon }}" class="h-3.5 w-3.5 {{ $displayStatus === 'In Progress' ? 'animate-spin' : '' }}"></i>
                            {{ $displayStatus }}
                        </span>
                    </span>

                    @if($assignment->daily_attendance_enabled)
                        <span class="inline-flex items-center gap-1 rounded-full bg-cyan-50 px-2.5 py-1 text-[11px] font-bold text-cyan-700">
                            <i data-lucide="calendar-check-2" class="h-3.5 w-3.5"></i>
                            Daily Attendance
                        </span>
                    @endif
                </div>

                <h3 class="mt-3 line-clamp-2 text-lg font-black leading-snug text-slate-900 transition group-hover:text-blue-700">{{ $assignment->title }}</h3>
                <p class="mt-1 text-xs font-semibold text-slate-400">{{ $assignment->assignment_number }}</p>
            </div>

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 transition group-hover:bg-blue-50 group-hover:text-blue-600">
                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
            </div>
        </div>

        <div class="mt-5 grid gap-2 text-sm sm:grid-cols-2">
            <div class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-slate-600">
                <i data-lucide="map-pin" class="h-4 w-4 shrink-0 text-slate-400"></i>
                <span class="truncate">{{ $assignment->location_name ?: '-' }}</span>
            </div>
            <div class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-slate-600">
                <i data-lucide="building-2" class="h-4 w-4 shrink-0 text-slate-400"></i>
                <span class="truncate">{{ $assignment->office?->name ?? '-' }}</span>
            </div>
            <div class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-slate-600">
                <i data-lucide="calendar-range" class="h-4 w-4 shrink-0 text-slate-400"></i>
                <span>{{ $assignment->start_datetime->format('d M') }} – {{ $assignment->end_datetime->format('d M Y') }}</span>
            </div>
            <div class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-slate-600">
                <i data-lucide="clock-3" class="h-4 w-4 shrink-0 text-slate-400"></i>
                <span>{{ $assignment->start_datetime->format('H:i') }} – {{ $assignment->end_datetime->format('H:i') }}</span>
            </div>
        </div>

        <div class="mt-5">
            <div class="mb-2 flex items-center justify-between gap-3 text-xs">
                <span class="font-semibold text-slate-500">Tahap workflow</span>
                <span class="font-bold text-slate-700">{{ $stage }}%</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full {{ in_array($displayStatus, ['Rejected', 'Not Worked'], true) ? 'bg-rose-400' : 'bg-blue-600' }}" style="width: {{ $stage }}%"></div>
            </div>
        </div>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-4 text-xs text-slate-400">
            <span class="inline-flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full {{ $priorityClass }}"></span>
                {{ $assignment->priority }} Priority
            </span>
            @if($remaining && !in_array($displayStatus, ['Completed', 'Rejected', 'Not Worked'], true))
                <span>Deadline dalam {{ $remaining }}</span>
            @else
                <span>{{ $assignment->employees->count() }} employee</span>
            @endif
        </div>
    </div>
</a>
