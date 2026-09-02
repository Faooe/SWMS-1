@props(['assignment' => null])

<div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white px-6 py-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-start gap-3">
        <a href="{{ $assignment ? route('assignments.show', $assignment) : route('assignments.index') }}" class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">{{ $assignment ? 'Edit Assignment' : 'Assignment Baru' }}</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">{{ $assignment ? $assignment->title : 'Buat Assignment' }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $assignment ? 'Perbarui informasi, lokasi, attendance, dan anggota team.' : 'Atur pekerjaan, lokasi, jadwal, attendance, dan employee yang ditugaskan.' }}</p>
        </div>
    </div>
    @if($assignment)
        <span class="w-fit rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">{{ $assignment->assignment_number }}</span>
    @endif
</div>
