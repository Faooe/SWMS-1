<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-6">

    {{-- Total Attendance --}}
    <x-ui.stat-card
        title="Total"
        :value="$statistics['total']"
        icon="calendar-days"
        color="blue"
    />

    {{-- Present --}}
    <x-ui.stat-card
        title="Present"
        :value="$statistics['present']"
        icon="badge-check"
        color="green"
    />

    {{-- Late --}}
    <x-ui.stat-card
        title="Late"
        :value="$statistics['late']"
        icon="clock-3"
        color="amber"
    />

    {{-- Leave --}}
    <x-ui.stat-card
        title="Leave"
        :value="$statistics['leave']"
        icon="plane"
        color="purple"
    />

    {{-- Permission --}}
    <x-ui.stat-card
        title="Permission"
        :value="$statistics['permission']"
        icon="file-check"
        color="cyan"
    />

    {{-- Absent --}}
    <x-ui.stat-card
        title="Absent"
        :value="$statistics['absent']"
        icon="circle-x"
        color="red"
    />

</div>