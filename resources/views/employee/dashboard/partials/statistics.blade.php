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

{{--
    Card tambahan: status review hasil kerja assignment (Pending Review /
    Needs Revision / Approved / Expired) -- lihat App\Services\
    EmployeeAssignmentService::statistics() & migration
    2026_08_12_090000_add_review_fields_to_assignment_employees_table
    untuk penjelasan alur review_status.
--}}
@if(($assignmentStatistics['pending_review'] ?? 0) + ($assignmentStatistics['needs_revision'] ?? 0) + ($assignmentStatistics['approved'] ?? 0) + ($assignmentStatistics['expired'] ?? 0) > 0)

    <div class="grid grid-cols-2 gap-5 lg:grid-cols-4">

        <x-ui.stat-card
            title="Perlu Revisi"
            :value="$assignmentStatistics['needs_revision']"
            icon="alert-triangle"
            color="red"
            :description="($assignmentStatistics['late_revision_count'] ?? 0) > 0 ? $assignmentStatistics['late_revision_count'].'x pernah telat revisi' : null" />

        <x-ui.stat-card
            title="Menunggu Review"
            :value="$assignmentStatistics['pending_review']"
            icon="hourglass"
            color="amber" />

        <x-ui.stat-card
            title="Disetujui"
            :value="$assignmentStatistics['approved']"
            icon="badge-check"
            color="green" />

        <x-ui.stat-card
            title="Batas Waktu Lewat"
            :value="$assignmentStatistics['expired']"
            icon="clock-x"
            color="slate" />

    </div>

@endif
