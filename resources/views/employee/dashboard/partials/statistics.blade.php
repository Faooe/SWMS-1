@php
    $activeAssignments = (int) (($assignmentStatistics['assigned'] ?? 0) + ($assignmentStatistics['progress'] ?? 0));
    $pendingReview = (int) ($assignmentStatistics['pending_review'] ?? 0);
    $needsRevision = (int) ($assignmentStatistics['needs_revision'] ?? 0);
    $completed = (int) ($assignmentStatistics['completed'] ?? 0);
    $total = max(1, (int) ($assignmentStatistics['total'] ?? 0));
    $completionPercent = min(100, (int) round(($completed / $total) * 100));

    $summaryRows = [
        ['label' => 'Aktif', 'value' => $activeAssignments, 'muted' => false],
        ['label' => 'Menunggu Review', 'value' => $pendingReview, 'muted' => false],
        ['label' => 'Perlu Revisi', 'value' => $needsRevision, 'muted' => $needsRevision === 0, 'danger' => $needsRevision > 0],
        ['label' => 'Selesai', 'value' => $completed, 'muted' => false],
    ];

    $quickLinks = [
        [
            'href' => route('employee.attendance.history'),
            'icon' => 'history',
            'title' => 'Riwayat Attendance',
            'desc' => 'Lihat histori kehadiran dan jam kerja.',
        ],
        [
            'href' => route('employee.leaves.index'),
            'icon' => 'file-text',
            'title' => 'Leave / Permission',
            'desc' => 'Ajukan cuti, sakit, atau izin.',
        ],
        [
            'href' => route('employee.profile'),
            'icon' => 'user-round',
            'title' => 'Profil',
            'desc' => 'Kelola data akun dan profil karyawan.',
        ],
    ];
@endphp

<x-ui.card class="overflow-hidden p-0">
    <div class="px-6 py-5">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Pekerjaan</p>
        <div class="mt-1 flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Ringkasan Pekerjaan</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $assignmentStatistics['total'] ?? 0 }} assignment tercatat.</p>
            </div>
            <span class="text-2xl font-bold text-blue-600">{{ $completionPercent }}%</span>
        </div>
        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full bg-blue-600" style="width: {{ $completionPercent }}%"></div>
        </div>
    </div>

    @if($needsRevision > 0)
        <a href="{{ route('employee.assignments.index', ['status' => 'Needs Revision']) }}" class="mx-6 mb-2 flex items-center justify-between rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-100">
            <span>{{ $needsRevision }} assignment perlu segera direvisi</span>
            <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
    @endif

    <div class="px-6 pb-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            @foreach($summaryRows as $row)
                <div class="flex items-center justify-between gap-4 px-4 py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-slate-600">{{ $row['label'] }}</span>
                        @if(($row['danger'] ?? false) === true)
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                        @endif
                    </div>
                    <span class="text-lg font-bold {{ ($row['danger'] ?? false) ? 'text-red-600' : 'text-slate-900' }}">{{ $row['value'] }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-6 border-t border-slate-100 pt-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Navigasi</p>
                    <h3 class="mt-1 text-lg font-bold text-slate-900">Akses Cepat</h3>
                </div>
            </div>

            <nav class="mt-4 space-y-2">
                @foreach($quickLinks as $link)
                    <a href="{{ $link['href'] }}" class="group flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 transition hover:border-blue-200 hover:bg-slate-50">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <i data-lucide="{{ $link['icon'] }}" class="h-4 w-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-700">{{ $link['title'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $link['desc'] }}</p>
                        </div>
                        <i data-lucide="chevron-right" class="h-4 w-4 text-slate-300 transition group-hover:text-blue-500"></i>
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
</x-ui.card>
