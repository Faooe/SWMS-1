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
</x-assignment.section-card>
