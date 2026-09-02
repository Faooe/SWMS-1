@props(['assignment'])

@php
    $companyStatus = $assignment->companyDisplayStatus();
    $statusClass = match($companyStatus) {
        'Needs Revision', 'Rejected', 'Not Worked', 'Cancelled' => 'bg-red-50 text-red-700 border-red-100',
        'Pending Review' => 'bg-amber-50 text-amber-700 border-amber-100',
        'Completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        default => 'bg-blue-50 text-blue-700 border-blue-100',
    };
@endphp

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-5 px-6 py-6 lg:flex-row lg:items-start lg:justify-between">
        <div class="min-w-0">
            <a href="{{ route('assignments.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-blue-700">
                <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Assignment
            </a>
            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500">
                <span>{{ $assignment->assignment_number }}</span><span>•</span><span>{{ $assignment->assignment_type }}</span>
            </div>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $assignment->title }}</h1>
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="inline-flex rounded-full border px-3 py-1.5 text-xs font-semibold {{ $statusClass }}">{{ $companyStatus }}</span>
                <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600">{{ $assignment->priority }} Priority</span>
                @if($assignment->daily_attendance_enabled)
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                        <i data-lucide="calendar-check-2" class="h-3.5 w-3.5"></i>
                        Attendance Harian · {{ $assignment->attendance_day_rule === 'EVERY_DAY' ? 'Setiap Hari' : 'Kalender Kerja' }}
                    </span>
                @endif
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-2">
            <a href="{{ route('assignments.edit', $assignment) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <i data-lucide="square-pen" class="h-4 w-4"></i> Edit
            </a>
            <details class="relative">
                <summary class="cursor-pointer list-none rounded-xl border border-slate-300 bg-white p-2.5 text-slate-600 hover:bg-slate-50 [&::-webkit-details-marker]:hidden"><i data-lucide="more-horizontal" class="h-5 w-5"></i></summary>
                <div class="absolute right-0 z-20 mt-2 w-48 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                    <form action="{{ route('assignments.destroy',$assignment) }}" method="POST" onsubmit="return confirm('Hapus assignment ini?')">
                        @csrf @method('DELETE')
                        <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-semibold text-red-600 hover:bg-red-50"><i data-lucide="trash-2" class="h-4 w-4"></i> Hapus Assignment</button>
                    </form>
                </div>
            </details>
        </div>
    </div>

    <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 border-t border-slate-100 bg-slate-50/60 md:grid-cols-4 md:divide-y-0">
        <div class="px-5 py-4"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Mulai</p><p class="mt-1 text-sm font-bold text-slate-800">{{ optional($assignment->start_datetime)->format('d M Y') }}</p><p class="text-xs text-slate-500">{{ optional($assignment->start_datetime)->format('H:i') }}</p></div>
        <div class="px-5 py-4"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Deadline</p><p class="mt-1 text-sm font-bold text-slate-800">{{ optional($assignment->end_datetime)->format('d M Y') }}</p><p class="text-xs text-slate-500">{{ optional($assignment->end_datetime)->format('H:i') }}</p></div>
        <div class="px-5 py-4"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Office</p><p class="mt-1 truncate text-sm font-bold text-slate-800">{{ $assignment->office?->name ?? '-' }}</p></div>
        <div class="px-5 py-4"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Team</p><p class="mt-1 text-sm font-bold text-slate-800">{{ $assignment->employee_count }} Employee</p></div>
    </div>
</div>
