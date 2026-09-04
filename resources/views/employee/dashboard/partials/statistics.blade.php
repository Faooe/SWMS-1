@php
    $activeAssignments = (int) (($assignmentStatistics['assigned'] ?? 0) + ($assignmentStatistics['progress'] ?? 0));
    $pendingReview = (int) ($assignmentStatistics['pending_review'] ?? 0);
    $needsRevision = (int) ($assignmentStatistics['needs_revision'] ?? 0);
    $completed = (int) ($assignmentStatistics['completed'] ?? 0);
    $totalRaw = (int) ($assignmentStatistics['total'] ?? 0);
    $totalForPercent = max(1, $totalRaw);
    $completionPercent = min(100, (int) round(($completed / $totalForPercent) * 100));

    $summaryRows = [
        ['label' => 'Aktif', 'value' => $activeAssignments],
        ['label' => 'Menunggu Review', 'value' => $pendingReview],
        ['label' => 'Perlu Revisi', 'value' => $needsRevision, 'danger' => $needsRevision > 0],
        ['label' => 'Selesai', 'value' => $completed],
    ];
@endphp

<x-ui.card class="overflow-hidden p-0">
    <div class="px-6 py-5">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Pekerjaan</p>

        <div class="mt-1 flex items-end justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Ringkasan Pekerjaan</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $totalRaw }} assignment tercatat.</p>
            </div>
            <span class="shrink-0 text-2xl font-bold tracking-tight text-blue-600">{{ $completionPercent }}%</span>
        </div>

        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100" aria-label="Progress assignment {{ $completionPercent }} persen">
            <div
                class="h-full rounded-full bg-blue-600 transition-all duration-300"
                style="width: {{ $completionPercent }}%"
            ></div>
        </div>
    </div>

    <div class="px-6 pb-6">
        @if($needsRevision > 0)
            <a
                href="{{ route('employee.assignments.index', ['status' => 'Needs Revision']) }}"
                class="mb-4 flex items-center justify-between gap-3 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-100"
            >
                <span>{{ $needsRevision }} assignment perlu direvisi</span>
                <i data-lucide="arrow-right" class="h-4 w-4 shrink-0"></i>
            </a>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            @foreach($summaryRows as $row)
                <div class="flex items-center justify-between gap-4 px-4 py-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                    <div class="flex min-w-0 items-center gap-2">
                        <span class="truncate text-sm font-medium text-slate-600">{{ $row['label'] }}</span>
                        @if(($row['danger'] ?? false) === true)
                            <span class="h-2 w-2 shrink-0 rounded-full bg-red-500"></span>
                        @endif
                    </div>

                    <span class="shrink-0 text-lg font-bold {{ ($row['danger'] ?? false) ? 'text-red-600' : 'text-slate-900' }}">
                        {{ $row['value'] }}
                    </span>
                </div>
            @endforeach
        </div>

        <a
            href="{{ route('employee.assignments.index') }}"
            class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition hover:text-blue-700"
        >
            Lihat semua assignment
            <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
    </div>
</x-ui.card>
