<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:10px;color:#222}
h1{font-size:18px;margin:0 0 4px}
.meta{width:100%;border-collapse:collapse;margin:12px 0}
.meta td{padding:3px 5px;vertical-align:top}
.day{border:1px solid #ddd;margin:0 0 10px;padding:9px;page-break-inside:avoid}
.day-title{font-size:12px;font-weight:bold;margin-bottom:6px}
.metrics{width:100%;border-collapse:collapse;margin-bottom:6px}
.metrics td{border:1px solid #e5e5e5;padding:4px}
.label{color:#666;font-size:8px;text-transform:uppercase}
.notes{white-space:pre-wrap;line-height:1.4}
.photos img{max-width:150px;max-height:115px;margin:5px 5px 0 0;border:1px solid #ddd}
.summary{width:100%;border-collapse:collapse;margin-top:12px}
.summary th,.summary td{border:1px solid #ddd;padding:6px;text-align:left}
.muted{color:#777}
</style>
</head>
<body>
<h1>Daily Assignment Report</h1>
<div class="muted">{{ $assignment->assignment_number }} • {{ $assignment->title }}</div>

<table class="meta">
<tr><td><strong>Employee</strong></td><td>{{ $employee->full_name }}</td></tr>
<tr><td><strong>Period</strong></td><td>{{ optional($assignment->start_datetime)->format('d M Y') }} - {{ optional($assignment->end_datetime)->format('d M Y') }}</td></tr>
<tr><td><strong>Location</strong></td><td>{{ $assignment->location_name ?: $assignment->address }}</td></tr>
</table>

@foreach($rows as $row)
<div class="day">
  <div class="day-title">{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}
    @if(!($row['required'] ?? true)) <span class="muted">• Libur</span> @endif
  </div>
  <table class="metrics">
    <tr>
      <td><div class="label">Status</div>{{ $row['status'] ?? '-' }}</td>
      <td><div class="label">Check In</div>{{ $row['check_in'] ?? '--:--' }}</td>
      <td><div class="label">Check Out</div>{{ $row['check_out'] ?? '--:--' }}</td>
      <td><div class="label">Work</div>{{ intdiv((int)($row['work_minutes'] ?? 0),60) }}j {{ (int)($row['work_minutes'] ?? 0)%60 }}m</td>
    </tr>
  </table>
  @if(!empty($row['work_description']))
    <div class="label">Detail Pekerjaan</div>
    <div class="notes">{{ $row['work_description'] }}</div>
  @elseif($row['required'] ?? true)
    <div class="muted">Tidak ada laporan pekerjaan.</div>
  @endif
  @if(!empty($row['photo_data_uris']))
    <div class="photos">
      @foreach($row['photo_data_uris'] as $photo)
        <img src="{{ $photo }}" alt="Evidence">
      @endforeach
    </div>
  @endif
</div>
@endforeach

<h2>Summary</h2>
<table class="summary">
<tr><th>Required Days</th><td>{{ $summary['required_days'] ?? 0 }}</td><th>Completed Days</th><td>{{ $summary['completed_days'] ?? 0 }}</td></tr>
<tr><th>Absent Days</th><td>{{ $summary['absent_days'] ?? 0 }}</td><th>Late Days</th><td>{{ $summary['late_days'] ?? 0 }}</td></tr>
<tr><th>Total Work</th><td>{{ intdiv((int)($summary['work_minutes'] ?? 0),60) }}j {{ (int)($summary['work_minutes'] ?? 0)%60 }}m</td><th>Attendance Rate</th><td>{{ $summary['attendance_rate'] ?? 0 }}%</td></tr>
</table>
</body>
</html>
