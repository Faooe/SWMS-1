@php
    $assignmentStatusLabel = !$assignment
        ? 'Tidak ada tugas'
        : (($assignmentAttendance?->isCompleted() ?? false)
            ? 'Selesai'
            : (($assignmentAttendance?->hasCheckedIn() ?? false) ? 'Sedang Bekerja' : 'Belum Check In'));
@endphp

<section class="border-t border-slate-100 px-5 py-5 sm:px-6 sm:py-6 lg:border-l lg:border-t-0">
    <div class="flex items-start gap-3">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
            <i data-lucide="clipboard-list" class="h-5 w-5"></i>
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Assignment Hari Ini</p>
                    <p class="mt-1 text-sm text-slate-500">Attendance assignment dikelola dari detail My Assignment.</p>
                </div>
                @if($assignment)
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $assignmentStatusLabel }}</span>
                @endif
            </div>
        </div>
    </div>

    @if(!$assignment)
        <div class="mt-5 rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-5 py-8 text-center">
            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-white text-slate-400 shadow-sm">
                <i data-lucide="clipboard-check" class="h-5 w-5"></i>
            </div>
            <p class="mt-3 text-sm font-semibold text-slate-700">Tidak ada assignment aktif hari ini</p>
            <p class="mt-1 text-sm text-slate-500">Kamu tetap dapat menggunakan Attendance Office seperti biasa.</p>
        </div>
    @else
        <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
            <p class="truncate text-sm font-semibold text-slate-900">{{ $assignment->title }}</p>
            <div class="mt-2 space-y-2 text-sm text-slate-500">
                <p class="flex items-center gap-2">
                    <i data-lucide="map-pin" class="h-4 w-4 shrink-0"></i>
                    <span class="truncate">{{ $assignment->location_name ?: 'Lokasi belum ditentukan' }}</span>
                </p>
                <p class="flex items-center gap-2">
                    <i data-lucide="clock-3" class="h-4 w-4 shrink-0"></i>
                    <span>{{ optional($assignment->start_datetime)->format('H:i') }}–{{ optional($assignment->end_datetime)->format('H:i') }}</span>
                </p>
                @if($assignment->radius)
                    <p class="flex items-center gap-2">
                        <i data-lucide="scan-line" class="h-4 w-4 shrink-0"></i>
                        <span>Radius attendance {{ $assignment->radius }} m</span>
                    </p>
                @endif
            </div>
        </div>

        @if($assignmentAttendance?->hasCheckedIn())
            <div class="mt-4 grid grid-cols-2 divide-x divide-slate-100 rounded-xl border border-slate-200 bg-white py-3">
                <div class="px-4">
                    <p class="text-xs text-slate-400">Check In</p>
                    <p class="mt-1 font-bold text-slate-900">{{ $assignmentAttendance->check_in_time?->format('H:i') ?? '-' }}</p>
                </div>
                <div class="px-4">
                    <p class="text-xs text-slate-400">Check Out</p>
                    <p class="mt-1 font-bold text-slate-900">{{ $assignmentAttendance->check_out_time?->format('H:i') ?? '-' }}</p>
                </div>
            </div>
        @endif

        <a
            href="{{ route('employee.assignments.show', $assignment->uuid) }}"
            class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-blue-200 bg-white px-4 py-3 text-sm font-semibold text-blue-700 transition hover:bg-blue-50"
        >
            Buka My Assignment
            <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
    @endif
</section>
