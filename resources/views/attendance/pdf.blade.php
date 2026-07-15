<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Attendance Report</title>

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

        .header{
            margin-bottom:25px;
        }

        .info{
            margin-top:8px;
            font-size:11px;
            color:#555;
        }

        .summary{
            width:100%;
            margin-bottom:20px;
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

        table{

            width:100%;

            border-collapse:collapse;

        }

        th{

            background:#1E40AF;

            color:white;

            padding:8px;

            font-size:11px;

        }

        td{

            border:1px solid #ddd;

            padding:7px;

            font-size:10px;

        }

        tr:nth-child(even){

            background:#f7f7f7;

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

        Attendance Report

    </h2>

    <div class="info">

        Generated :
        {{ now()->format('d F Y H:i') }}

    </div>

</div>

<table class="summary">

<tr>

<td>

<h3>{{ $statistics['total'] }}</h3>

<p>Total Attendance</p>

</td>

<td>

<h3>{{ $statistics['present'] }}</h3>

<p>Present</p>

</td>

<td>

<h3>{{ $statistics['late'] }}</h3>

<p>Late</p>

</td>

<td>

<h3>{{ $statistics['leave'] }}</h3>

<p>Leave</p>

</td>

<td>

<h3>{{ $statistics['permission'] }}</h3>

<p>Permission</p>

</td>

<td>

<h3>{{ $statistics['absent'] }}</h3>

<p>Absent</p>

</td>

</tr>

</table>

<table>

<thead>

<tr>

<th width="40">

No

</th>

<th>

Employee

</th>

<th>

Office

</th>

<th>

Date

</th>

<th>

Check In

</th>

<th>

Check Out

</th>

<th>

Status

</th>

<th>

Location

</th>

</tr>

</thead>

<tbody>

@forelse($attendances as $attendance)

<tr>

<td>

{{ $loop->iteration }}

</td>

<td>

{{ $attendance->employee->full_name }}

</td>

<td>

{{ optional($attendance->employee->currentEmployment->office)->name }}

</td>

<td>

{{ $attendance->attendance_date->format('d/m/Y') }}

</td>

<td>

{{ $attendance->check_in_time }}

</td>

<td>

{{ $attendance->check_out_time }}

</td>

<td>

{{ $attendance->attendance_status }}

</td>

<td>

@if($attendance->location_verified)

Verified

@else

Not Verified

@endif

</td>

</tr>

@empty

<tr>

<td colspan="8" align="center">

No attendance data

</td>

</tr>

@endforelse

</tbody>

</table>

<div class="footer">

Generated automatically by Smart Workforce Management System (SWMS)

</div>

</body>

</html>