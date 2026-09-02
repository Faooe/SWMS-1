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

<x-ui.card class="p-0 overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Aktivitas</p>
            <h2 class="mt-1 text-lg font-bold text-slate-900">Terbaru</h2>
        </div>
        <a href="{{ route('employee.assignments.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Buka assignment</a>
    </div>

    <div class="divide-y divide-slate-100 px-5 sm:px-6">
        @forelse($recentActivities as $activity)
            <div class="flex gap-3 py-4">
                <div class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-blue-500 ring-4 ring-blue-50"></div>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm font-semibold text-slate-800">
                            {{ $activityLabels[$activity->action] ?? str($activity->action)->replace('_', ' ')->title() }}
                        </p>
                        <span class="shrink-0 text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                    @if($activity->description)
                        <p class="mt-1 text-sm leading-5 text-slate-500">{{ $activity->description }}</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-10 text-center">
                <i data-lucide="history" class="mx-auto h-9 w-9 text-slate-300"></i>
                <p class="mt-3 text-sm font-medium text-slate-600">Belum ada aktivitas terbaru.</p>
            </div>
        @endforelse
    </div>
</x-ui.card>
