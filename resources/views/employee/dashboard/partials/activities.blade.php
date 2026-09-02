@php
    $activityLabels = [
        'ASSIGNMENT_ACCEPTED' => 'Assignment diterima',
        'ASSIGNMENT_REJECTED' => 'Assignment ditolak',
        'EMPLOYEE_CHECKED_IN' => 'Check In assignment',
        'CHECK_IN' => 'Check In',
        'EMPLOYEE_CHECKED_OUT' => 'Check Out assignment',
        'CHECK_OUT' => 'Check Out',
        'COMPLETION_SUBMITTED' => 'Hasil dikirim',
        'COMPLETION_RESUBMITTED' => 'Hasil dikirim ulang',
        'NEEDS_REVISION' => 'Perlu revisi',
        'COMPLETION_APPROVED' => 'Hasil disetujui',
        'CHECKOUT_CORRECTION_REQUESTED' => 'Koreksi Check Out diajukan',
        'CHECKOUT_CORRECTION_APPROVED' => 'Koreksi Check Out disetujui',
        'CHECKOUT_CORRECTION_REJECTED' => 'Koreksi Check Out ditolak',
    ];
@endphp

<x-ui.card class="overflow-hidden p-0">
    <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Aktivitas</p>
            <h2 class="mt-1 text-xl font-bold text-slate-900">Aktivitas Terbaru</h2>
            <p class="mt-1 text-sm text-slate-500">Perubahan terbaru pada assignment dan attendance kamu.</p>
        </div>
        <a href="{{ route('employee.assignments.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-700">
            Buka Assignment
        </a>
    </div>

    <div class="px-6 py-3">
        @forelse($recentActivities as $activity)
            <div class="flex gap-4 py-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                <div class="relative mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">
                                {{ $activityLabels[$activity->action] ?? str($activity->action)->replace('_', ' ')->title() }}
                            </p>
                            @if($activity->description)
                                <p class="mt-1 text-sm leading-6 text-slate-500">{{ $activity->description }}</p>
                            @endif
                        </div>
                        <span class="shrink-0 text-xs font-medium text-slate-400">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-5 py-10 text-center">
                <i data-lucide="history" class="mx-auto h-10 w-10 text-slate-300"></i>
                <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada aktivitas terbaru.</p>
            </div>
        @endforelse
    </div>
</x-ui.card>
