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