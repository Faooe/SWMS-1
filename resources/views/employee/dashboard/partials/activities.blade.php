@php
    $activityMeta = [
        'ASSIGNMENT_ACCEPTED' => [
            'label' => 'Assignment diterima',
            'category' => 'Assignment',
            'icon' => 'clipboard-check',
            'iconClass' => 'bg-blue-50 text-blue-600',
            'badgeClass' => 'bg-blue-50 text-blue-700',
        ],
        'ASSIGNMENT_REJECTED' => [
            'label' => 'Assignment ditolak',
            'category' => 'Assignment',
            'icon' => 'clipboard-x',
            'iconClass' => 'bg-slate-100 text-slate-600',
            'badgeClass' => 'bg-slate-100 text-slate-600',
        ],
        'EMPLOYEE_CHECKED_IN' => [
            'label' => 'Check In assignment',
            'category' => 'Attendance',
            'icon' => 'log-in',
            'iconClass' => 'bg-blue-50 text-blue-600',
            'badgeClass' => 'bg-blue-50 text-blue-700',
        ],
        'CHECK_IN' => [
            'label' => 'Check In',
            'category' => 'Attendance',
            'icon' => 'log-in',
            'iconClass' => 'bg-blue-50 text-blue-600',
            'badgeClass' => 'bg-blue-50 text-blue-700',
        ],
        'EMPLOYEE_CHECKED_OUT' => [
            'label' => 'Check Out assignment',
            'category' => 'Attendance',
            'icon' => 'log-out',
            'iconClass' => 'bg-blue-50 text-blue-600',
            'badgeClass' => 'bg-blue-50 text-blue-700',
        ],
        'CHECK_OUT' => [
            'label' => 'Check Out',
            'category' => 'Attendance',
            'icon' => 'log-out',
            'iconClass' => 'bg-blue-50 text-blue-600',
            'badgeClass' => 'bg-blue-50 text-blue-700',
        ],
        'COMPLETION_SUBMITTED' => [
            'label' => 'Hasil dikirim',
            'category' => 'Assignment',
            'icon' => 'send',
            'iconClass' => 'bg-blue-50 text-blue-600',
            'badgeClass' => 'bg-blue-50 text-blue-700',
        ],
        'COMPLETION_RESUBMITTED' => [
            'label' => 'Hasil dikirim ulang',
            'category' => 'Assignment',
            'icon' => 'refresh-cw',
            'iconClass' => 'bg-blue-50 text-blue-600',
            'badgeClass' => 'bg-blue-50 text-blue-700',
        ],
        'NEEDS_REVISION' => [
            'label' => 'Perlu revisi',
            'category' => 'Assignment',
            'icon' => 'rotate-ccw',
            'iconClass' => 'bg-red-50 text-red-600',
            'badgeClass' => 'bg-red-50 text-red-700',
        ],
        'COMPLETION_APPROVED' => [
            'label' => 'Hasil disetujui',
            'category' => 'Assignment',
            'icon' => 'circle-check-big',
            'iconClass' => 'bg-emerald-50 text-emerald-600',
            'badgeClass' => 'bg-emerald-50 text-emerald-700',
        ],
        'CHECKOUT_CORRECTION_REQUESTED' => [
            'label' => 'Koreksi Check Out diajukan',
            'category' => 'Attendance',
            'icon' => 'clock-alert',
            'iconClass' => 'bg-amber-50 text-amber-600',
            'badgeClass' => 'bg-amber-50 text-amber-700',
        ],
        'CHECKOUT_CORRECTION_APPROVED' => [
            'label' => 'Koreksi Check Out disetujui',
            'category' => 'Attendance',
            'icon' => 'circle-check-big',
            'iconClass' => 'bg-emerald-50 text-emerald-600',
            'badgeClass' => 'bg-emerald-50 text-emerald-700',
        ],
        'CHECKOUT_CORRECTION_REJECTED' => [
            'label' => 'Koreksi Check Out ditolak',
            'category' => 'Attendance',
            'icon' => 'circle-x',
            'iconClass' => 'bg-red-50 text-red-600',
            'badgeClass' => 'bg-red-50 text-red-700',
        ],
    ];
@endphp

<x-ui.card class="overflow-hidden p-0">
    <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Aktivitas</p>
            <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-900">Aktivitas Terbaru</h2>
            <p class="mt-1 text-sm text-slate-500">Riwayat terbaru dari attendance dan assignment kamu.</p>
        </div>

        <a
            href="{{ route('employee.assignments.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-700"
        >
            Lihat Assignment
            <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
    </div>

    <div class="px-6 py-2">
        @forelse($recentActivities as $activity)
            @php
                $meta = $activityMeta[$activity->action] ?? [
                    'label' => str($activity->action)->replace('_', ' ')->title(),
                    'category' => 'Aktivitas',
                    'icon' => 'activity',
                    'iconClass' => 'bg-slate-100 text-slate-600',
                    'badgeClass' => 'bg-slate-100 text-slate-600',
                ];
            @endphp

            <div class="group relative flex gap-4 py-5 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                <div class="relative flex shrink-0 flex-col items-center">
                    <div class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full {{ $meta['iconClass'] }} ring-4 ring-white">
                        <i data-lucide="{{ $meta['icon'] }}" class="h-[18px] w-[18px]"></i>
                    </div>

                    @if(!$loop->last)
                        <span class="absolute left-1/2 top-10 h-[calc(100%+0.25rem)] w-px -translate-x-1/2 bg-slate-200"></span>
                    @endif
                </div>

                <div class="min-w-0 flex-1 pb-0.5">
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-start sm:gap-6">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-semibold text-slate-900">{{ $meta['label'] }}</p>
                                <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $meta['badgeClass'] }}">
                                    {{ $meta['category'] }}
                                </span>
                            </div>

                            @if($activity->description)
                                <p class="mt-1.5 max-w-4xl text-sm leading-6 text-slate-500">{{ $activity->description }}</p>
                            @endif
                        </div>

                        <div class="shrink-0 sm:text-right">
                            <p class="text-xs font-semibold text-slate-500">{{ $activity->created_at->format('H:i') }}</p>
                            <p class="mt-0.5 text-xs text-slate-400">
                                {{ $activity->created_at->isToday() ? 'Hari ini' : $activity->created_at->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-12 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <i data-lucide="history" class="h-5 w-5"></i>
                </div>
                <p class="mt-4 text-sm font-semibold text-slate-700">Belum ada aktivitas terbaru.</p>
                <p class="mt-1 text-sm text-slate-500">Aktivitas attendance dan assignment akan tampil di sini.</p>
            </div>
        @endforelse
    </div>
</x-ui.card>
