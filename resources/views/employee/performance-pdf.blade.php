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
            color:#222;
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
<td><h3>{{ $attendanceSummary['attendance_rate'] }}%</h3><p>Attendance Rate</p></td>
<td><h3>{{ $attendanceSummary['punctuality_rate'] }}%</h3><p>Punctuality</p></td>
<td><h3>{{ round($attendanceSummary['work_minutes']/60, 1) }}j</h3><p>Total Jam Kerja</p></td>
<td><h3>{{ $attendanceSummary['overtime_minutes'] }}m</h3><p>Overtime</p></td>
</tr></table>
<table class="summary"><tr>
<td><h3>{{ $attendanceSummary['leave'] }}</h3><p>Leave</p></td>
<td><h3>{{ $attendanceSummary['permission'] }}</h3><p>Permission</p></td>
<td><h3>{{ $attendanceSummary['absent'] }}</h3><p>Absent</p></td>
<td><h3>{{ $attendanceSummary['late_minutes'] }}m</h3><p>Total Telat</p></td>
<td><h3>{{ $attendanceSummary['early_leave_minutes'] }}m</h3><p>Pulang Awal</p></td>
</tr></table>
<h3 class="section">Ringkasan Assignment</h3>
<table class="summary"><tr>
<td><h3>{{ $assignmentSummary['total'] }}</h3><p>Total</p></td>
<td><h3>{{ $assignmentSummary['completed'] }}</h3><p>Completed</p></td>
<td><h3>{{ $assignmentSummary['completion_rate'] }}%</h3><p>Completion Rate</p></td>
<td><h3>{{ $assignmentSummary['not_worked'] }}</h3><p>Not Worked</p></td>
<td><h3>{{ $assignmentSummary['late_revision'] }}</h3><p>Late Revision</p></td>
</tr></table>
@endif

{{-- ================= Ringkasan Review Assignment ================= --}}
<table class="summary">

<tr>

<td>
<h3>{{ $reviewSummary['approved'] ?? 0 }}</h3>
<p>Approved</p>
</td>

<td>
<h3>{{ $reviewSummary['pending_review'] ?? 0 }}</h3>
<p>Pending Review</p>
</td>

<td>
<h3>{{ $reviewSummary['needs_revision'] ?? 0 }}</h3>
<p>Needs Revision</p>
</td>

<td>
<h3>{{ $reviewSummary['expired'] ?? 0 }}</h3>
<p>Expired</p>
</td>

<td>
<h3>{{ $reviewSummary['late_revision_count'] ?? 0 }}</h3>
<p>Late Pengerjaan</p>
</td>

<td>
<h3>{{ $reviewSummary['rejected'] ?? 0 }}</h3>
<p>Rejected Assignment</p>
</td>

</tr>

</table>

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
<td>{{ $attendance->attendance_status }}</td>
<td>{{ $attendance->late_minutes ?? 0 }}</td>
</tr>
@endforeach

</tbody>

</table>

@endif

{{-- ================= Detail Assignment Selesai ================= --}}
<h3 class="section">Detail Assignment Selesai</h3>

@if($assignmentDetail->isEmpty())

    <div class="empty-note">
        Tidak ada assignment yang diselesaikan pada periode ini.
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
<th>Selesai Pada</th>
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
<td>{{ optional($assignment->pivot->finished_at)->format('d/m/Y H:i') ?? '-' }}</td>
<td>{{ $assignment->pivot->review_status ?? '-' }}</td>
<td>{{ $assignment->pivot->is_late_revision ? 'Ya' : 'Tidak' }}</td>
</tr>
@endforeach

</tbody>

</table>

@endif

</body>

</html>
