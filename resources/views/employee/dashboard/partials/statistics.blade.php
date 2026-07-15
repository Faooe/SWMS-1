<div class="grid grid-cols-2 gap-5 lg:grid-cols-4">

    <x-ui.stat-card
        title="Total Assignment"
        :value="$assignmentStatistics['total']"
        icon="clipboard-list"
        color="blue" />

    <x-ui.stat-card
        title="Assigned"
        :value="$assignmentStatistics['assigned']"
        icon="calendar-clock"
        color="orange" />

    <x-ui.stat-card
        title="Completed"
        :value="$assignmentStatistics['completed']"
        icon="circle-check-big"
        color="green" />

    <x-ui.stat-card
        title="Total Attendance"
        :value="$attendanceStatistics['summary']['total']"
        icon="calendar-check"
        color="purple" />

</div>
