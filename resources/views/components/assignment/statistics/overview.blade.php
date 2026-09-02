@props(['statistics'])

@php
    $items = [
        ['label' => 'Aktif', 'value' => $statistics['active'] ?? 0, 'icon' => 'activity'],
        ['label' => 'Menunggu Review', 'value' => $statistics['pending_review'] ?? 0, 'icon' => 'inbox'],
        ['label' => 'Perlu Revisi', 'value' => $statistics['needs_revision'] ?? 0, 'icon' => 'rotate-ccw', 'danger' => ($statistics['needs_revision'] ?? 0) > 0],
        ['label' => 'Selesai', 'value' => $statistics['completed'] ?? 0, 'icon' => 'circle-check-big'],
        ['label' => 'Draft', 'value' => $statistics['draft'] ?? 0, 'icon' => 'file-text'],
    ];
@endphp

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Ringkasan</p>
            <p class="mt-1 text-sm text-slate-500"><strong class="text-slate-800">{{ $statistics['total'] ?? 0 }}</strong> total assignment perusahaan.</p>
        </div>
        @if(($statistics['needs_revision'] ?? 0) > 0 || ($statistics['pending_review'] ?? 0) > 0)
            <div class="text-xs font-medium text-slate-500">
                {{ ($statistics['pending_review'] ?? 0) + ($statistics['needs_revision'] ?? 0) }} assignment membutuhkan perhatian.
            </div>
        @endif
    </div>

    <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 sm:grid-cols-5 sm:divide-y-0">
        @foreach($items as $item)
            <a href="{{ route('assignments.index', ['status' => match($item['label']) { 'Aktif' => 'Active', 'Menunggu Review' => 'Pending Review', 'Perlu Revisi' => 'Needs Revision', 'Selesai' => 'Completed', default => 'Draft' }]) }}"
               class="group flex items-center gap-3 px-4 py-4 transition hover:bg-slate-50">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ ($item['danger'] ?? false) ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-bold {{ ($item['danger'] ?? false) ? 'text-red-600' : 'text-slate-900' }}">{{ $item['value'] }}</p>
                    <p class="truncate text-[11px] font-medium text-slate-500">{{ $item['label'] }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
