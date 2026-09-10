@props(['assignment'])

<x-assignment.section-card title="Informasi Assignment" description="Detail pekerjaan dan aturan operasional assignment." icon="clipboard-list">
    @if($assignment->description)
        <div class="mb-5 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-600">{{ $assignment->description }}</div>
    @endif

    <dl class="grid gap-x-6 gap-y-5 sm:grid-cols-2">
        <div><dt class="text-xs font-medium text-slate-400">Nomor Assignment</dt><dd class="mt-1 text-sm font-semibold text-slate-800">{{ $assignment->assignment_number }}</dd></div>
        <div><dt class="text-xs font-medium text-slate-400">Jenis Pekerjaan</dt><dd class="mt-1 text-sm font-semibold text-slate-800">{{ $assignment->assignment_type }}</dd></div>
        <div><dt class="text-xs font-medium text-slate-400">Attendance Mode</dt><dd class="mt-1 text-sm font-semibold text-slate-800">{{ $assignment->daily_attendance_enabled ? 'Attendance Harian' : 'Attendance Sekali' }}</dd>@if($assignment->daily_attendance_enabled)<p class="mt-1 text-xs text-blue-600">{{ $assignment->attendance_day_rule === 'EVERY_DAY' ? 'Wajib setiap hari kalender' : 'Mengikuti Work Calendar Company' }}</p>@endif</div>
        <div><dt class="text-xs font-medium text-slate-400">Dibuat Oleh</dt><dd class="mt-1 text-sm font-semibold text-slate-800">{{ $assignment->creator?->employee?->full_name ?? $assignment->creator?->username ?? '-' }}</dd></div>
        <div><dt class="text-xs font-medium text-slate-400">Mulai</dt><dd class="mt-1 text-sm font-semibold text-slate-800">{{ optional($assignment->start_datetime)->format('d M Y · H:i') }}</dd></div>
        <div><dt class="text-xs font-medium text-slate-400">Selesai / Deadline</dt><dd class="mt-1 text-sm font-semibold text-slate-800">{{ optional($assignment->end_datetime)->format('d M Y · H:i') }}</dd></div>
    </dl>

    @if($assignment->employees->isNotEmpty())
        <div class="mt-6 border-t border-slate-200 pt-5">
            <div class="mb-3 flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-bold text-slate-900">Sesi Assignment Employee</p>
                    <p class="mt-0.5 text-xs text-slate-500">Waktu mulai dan selesai pengerjaan assignment. Ini terpisah dari Check Out attendance harian.</p>
                </div>
            </div>

            <div class="space-y-3">
                @foreach($assignment->employees as $employee)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-slate-900">{{ $employee->full_name }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $employee->employee_number ?? '-' }}@if($employee->currentEmployment?->position?->name) · {{ $employee->currentEmployment->position->name }}@endif</p>
                            </div>
                            <span class="inline-flex w-fit items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $employee->pivot->status }}</span>
                        </div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-slate-200 bg-white px-3.5 py-3">
                                <p class="text-[11px] font-medium text-slate-400">Check In Assignment</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">{{ optional($employee->pivot->work_check_in_at)->format('d M Y · H:i') ?? '-' }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-white px-3.5 py-3">
                                <p class="text-[11px] font-medium text-slate-400">Check Out Assignment</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">{{ optional($employee->pivot->work_check_out_at)->format('d M Y · H:i') ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</x-assignment.section-card>
