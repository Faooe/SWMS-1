@php
    $state = $assignmentState ?? [];
    $myStatus = $state['my_status'] ?? 'Assigned';
    $reviewStatus = $state['my_review_status'] ?? null;

    $displayStatus = match($reviewStatus) {
        'Approved' => 'Completed',
        'Pending Review' => 'Pending Review',
        'Needs Revision' => 'Needs Revision',
        'Not Worked', 'Expired' => 'Not Worked',
        default => $myStatus,
    };

    [$statusClass, $statusIcon] = match($displayStatus) {
        'Assigned' => ['bg-blue-100 text-blue-700', 'clipboard-list'],
        'Accepted' => ['bg-indigo-100 text-indigo-700', 'thumbs-up'],
        'In Progress' => ['bg-amber-100 text-amber-700', 'loader-circle'],
        'Pending Review' => ['bg-violet-100 text-violet-700', 'scan-search'],
        'Needs Revision' => ['bg-rose-100 text-rose-700', 'rotate-ccw'],
        'Completed' => ['bg-emerald-100 text-emerald-700', 'badge-check'],
        'Rejected' => ['bg-red-100 text-red-700', 'circle-x'],
        'Not Worked' => ['bg-slate-200 text-slate-700', 'clock-x'],
        default => ['bg-slate-100 text-slate-700', 'circle-dot'],
    };

    $priorityClass = match($assignment->priority) {
        'Critical' => 'bg-red-100 text-red-700',
        'High' => 'bg-orange-100 text-orange-700',
        'Medium' => 'bg-amber-100 text-amber-700',
        default => 'bg-emerald-100 text-emerald-700',
    };
@endphp

<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 px-6 py-7 text-white sm:px-8 sm:py-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0">
                <div class="mb-3 flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-300">
                    <span>{{ $assignment->assignment_number }}</span>
                    <span class="h-1 w-1 rounded-full bg-slate-500"></span>
                    <span>{{ $assignment->assignment_type }}</span>
                </div>

                <h1 class="max-w-4xl text-2xl font-black leading-tight sm:text-3xl">
                    {{ $assignment->title }}
                </h1>

                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold {{ $statusClass }}">
                        <i data-lucide="{{ $statusIcon }}" class="h-3.5 w-3.5 {{ $displayStatus === 'In Progress' ? 'animate-spin' : '' }}"></i>
                        {{ $displayStatus }}
                    </span>

                    <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ $priorityClass }}">
                        {{ $assignment->priority }} Priority
                    </span>

                    @if($assignment->daily_attendance_enabled)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-cyan-100 px-3 py-1.5 text-xs font-bold text-cyan-700">
                            <i data-lucide="calendar-check-2" class="h-3.5 w-3.5"></i>
                            Attendance Harian
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid min-w-0 grid-cols-2 gap-2 sm:min-w-[300px]">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-3 backdrop-blur">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Mulai</p>
                    <p class="mt-1 text-sm font-bold">{{ $assignment->start_datetime->format('d M Y') }}</p>
                    <p class="text-xs text-slate-300">{{ $assignment->start_datetime->format('H:i') }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-3 backdrop-blur">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Deadline</p>
                    <p class="mt-1 text-sm font-bold">{{ $assignment->end_datetime->format('d M Y') }}</p>
                    <p class="text-xs text-slate-300">{{ $assignment->end_datetime->format('H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-px bg-slate-200 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white px-5 py-4 sm:px-6">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                <i data-lucide="building-2" class="h-4 w-4"></i>
                Office
            </div>
            <p class="mt-1.5 truncate font-bold text-slate-800">{{ $assignment->office?->name ?? '-' }}</p>
        </div>

        <div class="bg-white px-5 py-4 sm:px-6">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                <i data-lucide="map-pin" class="h-4 w-4"></i>
                Lokasi
            </div>
            <p class="mt-1.5 truncate font-bold text-slate-800">{{ $assignment->location_name ?: '-' }}</p>
        </div>

        <div class="bg-white px-5 py-4 sm:px-6">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                <i data-lucide="calendar-days" class="h-4 w-4"></i>
                Mode Attendance
            </div>
            <p class="mt-1.5 font-bold text-slate-800">
                {{ $assignment->daily_attendance_enabled ? ($assignment->attendance_day_rule === 'EVERY_DAY' ? 'Setiap Hari' : 'Kalender Kerja') : 'Sekali' }}
            </p>
        </div>

        <div class="bg-white px-5 py-4 sm:px-6">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                <i data-lucide="users" class="h-4 w-4"></i>
                Tim
            </div>
            <p class="mt-1.5 font-bold text-slate-800">{{ $assignment->employees->count() }} Employee</p>
        </div>
    </div>
</div>
