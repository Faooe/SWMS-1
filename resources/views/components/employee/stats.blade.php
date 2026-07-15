@props([
    'statistics'
])

<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

    <x-dashboard.stat-card
        title="Total Employee"
        :value="$statistics['total']"
        icon="users" />

    <x-dashboard.stat-card
        title="Active Employee"
        :value="$statistics['active']"
        icon="badge-check" />

    <x-dashboard.stat-card
        title="Inactive Employee"
        :value="$statistics['inactive']"
        icon="user-x" />

    <x-dashboard.stat-card
        title="New This Month"
        :value="$statistics['new_this_month']"
        icon="user-plus" />

</div>