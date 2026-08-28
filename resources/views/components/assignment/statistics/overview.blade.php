@props([
    'statistics'
])

<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

    {{-- Total Assignment --}}
    <x-ui.stat-card
        title="Total Assignment"
        :value="$statistics['total']"
        icon="clipboard-list"
        color="blue"
    />

    {{-- Draft --}}
    <x-ui.stat-card
        title="Draft"
        :value="$statistics['draft']"
        icon="file-text"
        color="slate"
    />

    {{-- Active --}}
    <x-ui.stat-card
        title="Active"
        :value="$statistics['active']"
        icon="activity"
        color="green"
    />

    {{-- Completed --}}
    <x-ui.stat-card
        title="Completed"
        :value="$statistics['completed']"
        icon="check-circle-2"
        color="emerald"
    />

</div>

<div class="mt-3 flex flex-wrap gap-2">
    <span class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-sm font-semibold text-red-700">
        <i data-lucide="user-x" class="h-4 w-4"></i>
        {{ $statistics['rejected'] ?? 0 }} rejected employee
    </span>
    <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-semibold text-slate-600">
        <i data-lucide="ban" class="h-4 w-4"></i>
        {{ $statistics['cancelled'] ?? 0 }} cancelled assignment
    </span>
</div>