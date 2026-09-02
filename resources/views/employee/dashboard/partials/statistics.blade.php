@php
    $activeAssignments = (int) (($assignmentStatistics['assigned'] ?? 0) + ($assignmentStatistics['progress'] ?? 0));
    $pendingReview = (int) ($assignmentStatistics['pending_review'] ?? 0);
    $needsRevision = (int) ($assignmentStatistics['needs_revision'] ?? 0);
    $completed = (int) ($assignmentStatistics['completed'] ?? 0);
    $total = max(1, (int) ($assignmentStatistics['total'] ?? 0));
    $completionPercent = min(100, (int) round(($completed / $total) * 100));
@endphp

<x-ui.card class="p-0 overflow-hidden">
    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Pekerjaan</p>
        <div class="mt-1 flex items-end justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Ringkasan Assignment</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $assignmentStatistics['total'] ?? 0 }} assignment tercatat.</p>
            </div>
            <span class="text-sm font-bold text-blue-600">{{ $completionPercent }}%</span>
        </div>
        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full bg-blue-600" style="width: {{ $completionPercent }}%"></div>
        </div>
    </div>

    <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 sm:divide-y-0 xl:grid-cols-2 xl:divide-y">
        <div class="p-5">
            <p class="text-xs font-medium text-slate-400">Aktif</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $activeAssignments }}</p>
        </div>
        <div class="p-5">
            <p class="text-xs font-medium text-slate-400">Menunggu Review</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $pendingReview }}</p>
        </div>
        <div class="p-5">
            <div class="flex items-center gap-2">
                <p class="text-xs font-medium text-slate-400">Perlu Revisi</p>
                @if($needsRevision > 0)
                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                @endif
            </div>
            <p class="mt-1 text-2xl font-bold {{ $needsRevision > 0 ? 'text-red-600' : 'text-slate-900' }}">{{ $needsRevision }}</p>
        </div>
        <div class="p-5">
            <p class="text-xs font-medium text-slate-400">Selesai</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $completed }}</p>
        </div>
    </div>

    @if($needsRevision > 0)
        <a href="{{ route('employee.assignments.index', ['status' => 'Needs Revision']) }}" class="flex items-center justify-between border-t border-slate-100 bg-red-50/60 px-5 py-3 text-sm font-semibold text-red-700 hover:bg-red-50">
            <span>{{ $needsRevision }} assignment perlu segera direvisi</span>
            <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
    @endif
</x-ui.card>
