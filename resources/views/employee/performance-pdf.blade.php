<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Rekap HR Employee</title>

    <style>

        *{
            font-family: DejaVu Sans, sans-serif;
        }

        body{
            font-size:12px;
            color:#172033;
        }

        h1{
            margin:0;
            font-size:24px;
        }

        h2{
            margin:0;
            font-size:16px;
            font-weight:normal;
            color:#666;
        }

        h3.section{
            margin:24px 0 10px;
            font-size:14px;
            color:#1E40AF;
            border-bottom:2px solid #1E40AF;
            padding-bottom:4px;
        }

        .header{
            margin-bottom:20px;
        }

        .info{
            margin-top:8px;
            font-size:11px;
            color:#555;
        }

        .summary{
            width:100%;
            margin-bottom:10px;
            border-collapse:collapse;
        }

        .summary td{
            border:1px solid #ddd;
            padding:10px;
            text-align:center;
        }

        .summary h3{
            margin:0;
            font-size:24px;
        }

        .summary p{
            margin:4px 0 0;
            font-size:11px;
        }

        table.data{
            width:100%;
            border-collapse:collapse;
        }

        table.data th{
            background:#1E40AF;
            color:white;
            padding:8px;
            font-size:11px;
            text-align:left;
        }

        table.data td{
            border:1px solid #ddd;
            padding:7px;
            font-size:10px;
        }

        table.data tr:nth-child(even){
            background:#f7f7f7;
        }

        table.data tr.total-row{
            background:#dbeafe;
            font-weight:bold;
        }

        .metric-green{color:#059669;font-weight:bold}.metric-amber{color:#d97706;font-weight:bold}
        .metric-red{color:#dc2626;font-weight:bold}.metric-purple{color:#7c3aed;font-weight:bold}
        .metric-blue{color:#2563eb;font-weight:bold}.muted{color:#64748b}
        .legend{font-size:10px;color:#64748b;margin:5px 0 10px}.legend span{margin-right:16px;font-weight:bold}
        .signature{margin-top:28px;page-break-inside:avoid;text-align:center}.signature-space{height:44px}
        .signature-line{border-top:1px solid #172033;width:180px;margin:0 auto 4px}
        .calendar-wrap{page-break-inside:avoid;margin:10px 0 16px}
        .calendar-title{color:#172033;font-size:12px;font-weight:bold;margin:12px 0 6px}
        table.calendar{border-collapse:collapse;table-layout:fixed;width:100%}
        table.calendar th{background:#1e40af;color:#fff;font-size:8px;padding:5px 2px;text-align:center}
        table.calendar td{border:1px solid #dbe5f1;font-size:8px;padding:5px 2px;text-align:center}
        table.calendar .label{background:#f8fafc;color:#64748b;font-weight:bold;text-align:left;width:105px}
        .status-present{background:#dcfce7;color:#047857;font-weight:bold}.status-late{background:#fef3c7;color:#b45309;font-weight:bold}
        .status-permission{background:#fef3c7;color:#b45309;font-weight:bold}.status-leave{background:#ede9fe;color:#6d28d9;font-weight:bold}
        .status-absent{background:#fee2e2;color:#b91c1c;font-weight:bold}
        .calendar-legend{color:#64748b;font-size:9px;margin:5px 0 8px}.calendar-legend span{display:inline-block;margin-right:14px;font-weight:bold}

        .empty-note{
            padding:10px;
            font-size:11px;
            color:#888;
            font-style:italic;
        }

        .footer{
            position:fixed;
            bottom:-15px;
            left:0;
            right:0;
            text-align:center;
            font-size:10px;
            color:#777;
        }

    </style>

</head>

<body>
@php
    $rateClass = fn ($rate) => $rate >= 90 ? 'metric-green' : ($rate >= 75 ? 'metric-amber' : 'metric-red');
    $statusClass = fn ($status) => match (strtolower(trim((string) $status))) {
        'present', 'hadir', 'completed', 'approved' => 'metric-green',
        'late', 'telat', 'permission', 'izin', 'pending review', 'not worked', 'expired' => 'metric-amber',
        'leave', 'cuti', 'needs revision' => 'metric-purple',
        'absent', 'absen', 'rejected' => 'metric-red', default => 'muted',
    };
    $statusCode = fn ($status) => match (strtolower(trim((string) $status))) {
        'present', 'hadir' => 'H', 'late', 'telat' => 'T', 'permission', 'izin' => 'I',
        'leave', 'cuti' => 'C', 'absent', 'absen' => 'A', default => '-',
    };
    $statusCss = fn ($status) => match (strtolower(trim((string) $status))) {
        'present', 'hadir' => 'status-present', 'late', 'telat' => 'status-late',
        'permission', 'izin' => 'status-permission', 'leave', 'cuti' => 'status-leave',
        'absent', 'absen' => 'status-absent', default => '',
    };
@endphp

<div class="header">

    <h1>
        Smart Workforce Management System
    </h1>

    <h2>
        Rekap HR Employee
    </h2>

    <div class="info">

        Employee :
        <strong>{{ $employee->full_name }}</strong>
        ({{ $employee->employee_number }})

        <br>

        Periode :
        {{ $export->title() }}

        <br>

        Generated :
        {{ now()->format('d F Y H:i') }}

    </div>

</div>

<table class="summary">

<tr>

<td>
<h3>{{ $summary['attendance_total'] }}</h3>
<p>Total Attendance</p>
</td>

<td>
<h3>{{ $summary['attendance_present'] }}</h3>
<p>Present</p>
</td>

<td>
<h3>{{ $summary['attendance_late'] }}</h3>
<p>Late</p>
</td>

<td>
<h3>{{ $summary['assignment_completed'] }}</h3>
<p>Assignment Selesai</p>
</td>

</tr>

</table>

@if(isset($attendanceSummary) && isset($assignmentSummary))
<h3 class="section">Standar Kehadiran Perusahaan</h3>
<table class="summary"><tr>
<td><h3>{{ $attendanceSummary['working_days'] }}</h3><p>Hari Kerja Efektif</p></td>
<td><h3 class="{{ $rateClass($attendanceSummary['attendance_rate']) }}">{{ $attendanceSummary['attendance_rate'] }}%</h3><p>Attendance Rate</p></td>
<td><h3 class="{{ $rateClass($attendanceSummary['punctuality_rate']) }}">{{ $attendanceSummary['punctuality_rate'] }}%</h3><p>Punctuality</p></td>
<td><h3>{{ round($attendanceSummary['work_minutes']/60, 1) }}j</h3><p>Total Jam Kerja</p></td>
<td><h3>{{ $attendanceSummary['overtime_minutes'] }}m</h3><p>Overtime</p></td>
</tr></table>
<table class="summary"><tr>
<td><h3 class="metric-purple">{{ $attendanceSummary['leave'] }}</h3><p>Leave</p></td>
<td><h3 class="metric-amber">{{ $attendanceSummary['permission'] }}</h3><p>Permission</p></td>
<td><h3 class="metric-red">{{ $attendanceSummary['absent'] }}</h3><p>Absent</p></td>
<td><h3 class="metric-amber">{{ $attendanceSummary['late_minutes'] }}m</h3><p>Total Telat</p></td>
<td><h3>{{ $attendanceSummary['early_leave_minutes'] }}m</h3><p>Pulang Awal</p></td>
</tr></table>
<h3 class="section">Ringkasan Assignment</h3>
<table class="summary"><tr>
<td><h3>{{ $assignmentSummary['total'] }}</h3><p>Total</p></td>
<td><h3 class="metric-green">{{ $assignmentSummary['completed'] }}</h3><p>Completed</p></td>
<td><h3 class="{{ $rateClass($assignmentSummary['completion_rate']) }}">{{ $assignmentSummary['completion_rate'] }}%</h3><p>Completion Rate</p></td>
<td><h3 class="metric-amber">{{ $assignmentSummary['not_worked'] }}</h3><p>Not Worked</p></td>
<td><h3 class="metric-amber">{{ $assignmentSummary['late_revision'] }}</h3><p>Late Revision</p></td>
</tr></table>
@endif

{{-- ================= Ringkasan Review Assignment ================= --}}
<table class="summary">

<tr>

<td>
<h3 class="metric-green">{{ $reviewSummary['approved'] ?? 0 }}</h3>
<p>Approved</p>
</td>

<td>
<h3 class="metric-amber">{{ $reviewSummary['pending_review'] ?? 0 }}</h3>
<p>Pending Review</p>
</td>

<td>
<h3 class="metric-purple">{{ $reviewSummary['needs_revision'] ?? 0 }}</h3>
<p>Needs Revision</p>
</td>

<td>
<h3 class="metric-amber">{{ $reviewSummary['expired'] ?? 0 }}</h3>
<p>Expired</p>
</td>

<td>
<h3 class="metric-amber">{{ $reviewSummary['late_revision_count'] ?? 0 }}</h3>
<p>Late Pengerjaan</p>
</td>

<td>
<h3 class="metric-red">{{ $reviewSummary['rejected'] ?? 0 }}</h3>
<p>Rejected Assignment</p>
</td>

</tr>

</table>

<div class="legend"><span class="metric-green">Hijau: hadir / selesai / rate ≥90%</span><span class="metric-amber">Kuning: izin / terlambat</span><span class="metric-red">Merah: absen / ditolak / rate &lt;75%</span></div>

{{-- ================= Kalender Attendance ================= --}}
@if(isset($attendanceCalendar))
<h3 class="section">Kalender Attendance</h3>
<div class="empty-note">Setiap kolom adalah hari kerja. H = Hadir, T = Terlambat, I = Izin, C = Cuti, A = Absen.</div>
<div class="calendar-legend"><span class="status-present">H Hadir</span><span class="status-late">T Terlambat</span><span class="status-permission">I Izin</span><span class="status-leave">C Cuti</span><span class="status-absent">A Absen</span></div>
@forelse($attendanceCalendar->groupBy(fn ($item) => $item['date']->format('Y-m')) as $month => $days)
    <div class="calendar-wrap">
        <div class="calendar-title">{{ $days->first()['date']->translatedFormat('F Y') }}</div>
        <table class="calendar">
            <thead><tr><th class="label">Tanggal</th>@foreach($days as $item)<th>{{ $item['date']->format('d') }}</th>@endforeach</tr><tr><th class="label">Hari</th>@foreach($days as $item)<th>{{ $item['date']->format('D') }}</th>@endforeach</tr></thead>
            <tbody><tr><td class="label">Status</td>@foreach($days as $item)<td class="{{ $statusCss($item['status']) }}">{{ $statusCode($item['status']) }}</td>@endforeach</tr></tbody>
        </table>
    </div>
@empty
    <div class="empty-note">Tidak ada hari kerja pada periode ini.</div>
@endforelse
@endif

{{-- ================= Tren Periode ================= --}}
<h3 class="section">Tren Periode</h3>

<table class="data">

<thead>
<tr>
<th>Periode</th>
<th>Total Attendance</th>
<th>Present</th>
<th>Late</th>
<th>Assignment Selesai</th>
</tr>
</thead>

<tbody>

@foreach($monthlyChart as $row)
<tr>
<td>{{ $row['label'] }}</td>
<td>{{ $row['attendance_total'] }}</td>
<td>{{ $row['attendance_present'] }}</td>
<td>{{ $row['attendance_late'] }}</td>
<td>{{ $row['assignment_completed'] }}</td>
</tr>
@endforeach

<tr class="total-row">
<td>TOTAL</td>
<td>{{ $summary['attendance_total'] }}</td>
<td>{{ $summary['attendance_present'] }}</td>
<td>{{ $summary['attendance_late'] }}</td>
<td>{{ $summary['assignment_completed'] }}</td>
</tr>

</tbody>

</table>

{{-- ================= Detail Attendance ================= --}}
<h3 class="section">Detail Attendance</h3>

@if($attendanceDetail->isEmpty())

    <div class="empty-note">
        Tidak ada data attendance pada periode ini.
    </div>

@else

<table class="data">

<thead>
<tr>
<th width="30">No</th>
<th>Tanggal</th>
<th>Check In</th>
<th>Check Out</th>
<th>Office</th>
<th>Status</th>
<th>Terlambat (menit)</th>
</tr>
</thead>

<tbody>

@foreach($attendanceDetail as $i => $attendance)
<tr>
<td>{{ $i + 1 }}</td>
<td>{{ $attendance->attendance_date->format('d/m/Y') }}</td>
<td>{{ $attendance->check_in_time ?? '-' }}</td>
<td>{{ $attendance->check_out_time ?? '-' }}</td>
<td>{{ $attendance->office->name ?? '-' }}</td>
<td class="{{ $statusClass($attendance->attendance_status) }}">{{ $attendance->attendance_status }}</td>
<td>{{ $attendance->late_minutes ?? 0 }}</td>
</tr>
@endforeach

</tbody>

</table>

@endif

{{-- ================= Detail Assignment ================= --}}
<h3 class="section">Detail Assignment</h3>

@if($assignmentDetail->isEmpty())

    <div class="empty-note">
        Tidak ada assignment pada periode ini.
    </div>

@else

<table class="data">

<thead>
<tr>
<th width="30">No</th>
<th>No. Assignment</th>
<th>Judul</th>
<th>Tipe</th>
<th>Lokasi</th>
<th>Ditugaskan</th>
<th>Selesai</th>
<th>Status</th>
<th>Status Review</th>
<th>Late?</th>
</tr>
</thead>

<tbody>

@foreach($assignmentDetail as $i => $assignment)
<tr>
<td>{{ $i + 1 }}</td>
<td>{{ $assignment->assignment_number }}</td>
<td>{{ $assignment->title }}</td>
<td>{{ $assignment->assignment_type }}</td>
<td>{{ $assignment->location_name ?? '-' }}</td>
<td>{{ optional($assignment->pivot->assigned_at)->format('d/m/Y H:i') ?? '-' }}</td>
<td>{{ optional($assignment->pivot->finished_at)->format('d/m/Y H:i') ?? '-' }}</td>
<td class="{{ $statusClass($assignment->pivot->status ?? '') }}">{{ $assignment->pivot->status ?? '-' }}</td>
<td class="{{ $statusClass($assignment->pivot->review_status ?? '') }}">{{ $assignment->pivot->review_status ?? '-' }}</td>
<td class="{{ $assignment->pivot->is_late_revision ? 'metric-amber' : 'muted' }}">{{ $assignment->pivot->is_late_revision ? 'Ya' : 'Tidak' }}</td>
</tr>
@endforeach

</tbody>

</table>

@endif

<div class="signature">
    <div>{{ now()->format('d F Y') }}</div>
    <div class="muted">Mengetahui,</div>
    <div class="signature-space"></div>
    <div class="signature-line"></div>
    <strong>HR Manager</strong>
</div>

</body>

</html>
