<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Rekapitulasi HR</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        @page { margin: 28px 30px 34px; }
        body { color:#172033; font-size:10px; }
        .top { border-bottom:3px solid #2563eb; padding-bottom:14px; margin-bottom:16px; }
        .eyebrow { color:#2563eb; font-size:9px; font-weight:bold; letter-spacing:2px; text-transform:uppercase; }
        h1 { font-size:22px; margin:4px 0 3px; color:#0f172a; }
        .subtitle { color:#64748b; font-size:10px; }
        .meta { width:100%; margin-top:12px; }
        .meta td { color:#64748b; padding:2px 14px 2px 0; }
        .meta strong { color:#172033; }
        .cards { width:100%; border-collapse:separate; border-spacing:6px; margin:0 -6px 12px; }
        .card { border:1px solid #dbe5f1; background:#f8fafc; padding:9px 10px; height:42px; }
        .card .value { font-size:17px; font-weight:bold; color:#0f172a; }
        .card .label { color:#64748b; font-size:9px; margin-top:3px; }
        .section-title { color:#0f172a; font-size:13px; margin:14px 0 7px; padding-bottom:5px; border-bottom:1px solid #dbe5f1; }
        .legend { color:#64748b; font-size:9px; margin:4px 0 9px; }
        .legend span { margin-right:16px; font-weight:bold; }
        .green { color:#059669; font-weight:bold; } .amber { color:#d97706; font-weight:bold; }
        .red { color:#dc2626; font-weight:bold; } .blue { color:#2563eb; font-weight:bold; }
        .purple { color:#7c3aed; font-weight:bold; } .muted { color:#64748b; }
        table.data { width:100%; border-collapse:collapse; table-layout:fixed; }
        table.data th { background:#1d4ed8; color:#fff; padding:7px 6px; text-align:left; font-size:8px; text-transform:uppercase; }
        table.data td { border:1px solid #e2e8f0; padding:6px; font-size:8px; vertical-align:top; word-wrap:break-word; }
        table.data tr:nth-child(even) { background:#f8fafc; }
        .employee { font-weight:bold; font-size:9px; color:#0f172a; } .nip { color:#64748b; margin-top:2px; }
        .center { text-align:center; } .empty { color:#94a3b8; text-align:center; padding:16px; }
        .signature { margin-top:28px; page-break-inside:avoid; }
        .signature table { width:100%; } .signature td { width:50%; text-align:center; vertical-align:top; }
        .signature-space { height:46px; } .signature-line { border-top:1px solid #172033; width:180px; margin:0 auto 4px; }
        .footer { position:fixed; bottom:-18px; left:0; right:0; text-align:center; color:#94a3b8; font-size:8px; }
    </style>
</head>
<body>
@php
    $summary = $recap['summary'];
    $rateClass = fn ($rate) => $rate >= 90 ? 'green' : ($rate >= 75 ? 'amber' : 'red');
@endphp
<div class="top">
    <div class="eyebrow">Company Analytics</div>
    <h1>Detail Rekapitulasi HR</h1>
    <div class="subtitle">Ringkasan attendance dan assignment seluruh employee</div>
    <table class="meta"><tr>
        <td>Perusahaan: <strong>{{ $company->name }}</strong></td>
        <td>Periode: <strong>{{ $recap['range']['label'] }}</strong></td>
        <td>Dicetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong></td>
    </tr></table>
</div>

<table class="cards"><tr>
    <td class="card"><div class="value">{{ $summary['employees'] }}</div><div class="label">Total Employee</div></td>
    <td class="card"><div class="value">{{ $summary['active_employees'] }}</div><div class="label">Employee Aktif</div></td>
    <td class="card"><div class="value {{ $rateClass($summary['attendance_rate']) }}">{{ number_format($summary['attendance_rate'], 1) }}%</div><div class="label">Attendance Rate</div></td>
    <td class="card"><div class="value {{ $rateClass($summary['completion_rate']) }}">{{ number_format($summary['completion_rate'], 1) }}%</div><div class="label">Completion Rate</div></td>
    <td class="card"><div class="value">{{ $summary['assignment_total'] }}</div><div class="label">Total Assignment</div></td>
</tr></table>

<div class="legend"><span class="green">Hijau: hadir / selesai / rate ≥90%</span><span class="amber">Kuning: izin / terlambat / perlu perhatian</span><span class="red">Merah: absen / ditolak / rate &lt;75%</span></div>
<div class="section-title">Rekap per Employee</div>
<table class="data">
    <thead><tr>
        <th width="17%">Employee</th><th width="15%">Organisasi</th><th width="20%">Attendance</th><th width="20%">Assignment</th><th width="10%">Rate</th><th width="8%">Skor</th><th width="10%">Status</th>
    </tr></thead>
    <tbody>
    @forelse($recap['rows'] as $row)
        <tr>
            <td><div class="employee">{{ $row['employee_name'] }}</div><div class="nip">{{ $row['employee_number'] }}</div></td>
            <td>{{ $row['department'] }}<br><span class="muted">{{ $row['position'] }} · {{ $row['team'] }}</span></td>
            <td><span class="green">Hadir {{ $row['attended'] }}/{{ $row['working_days'] }}</span> · <span class="amber">Telat {{ $row['late'] }}</span><br><span class="amber">Izin {{ $row['permission'] }}</span> · <span class="red">Absen {{ $row['absent'] }}</span></td>
            <td>Total {{ $row['assignment_total'] }} · <span class="green">Selesai {{ $row['assignment_completed'] }}</span><br><span class="red">Ditolak {{ $row['assignment_rejected'] }}</span> · <span class="amber">Belum {{ $row['assignment_not_worked'] }}</span></td>
            <td class="center"><span class="{{ $rateClass($row['attendance_rate']) }}">{{ number_format($row['attendance_rate'], 1) }}%</span><br><span class="{{ $rateClass($row['completion_rate']) }}">{{ number_format($row['completion_rate'], 1) }}% selesai</span></td>
            <td class="center"><span class="{{ $rateClass($row['performance_score']) }}">{{ number_format($row['performance_score'], 1) }}</span></td>
            <td><span class="{{ $row['is_active'] ? 'green' : 'muted' }}">{{ $row['is_active'] ? 'Aktif' : 'Nonaktif' }}</span></td>
        </tr>
    @empty
        <tr><td colspan="7" class="empty">Tidak ada employee pada periode/filter ini.</td></tr>
    @endforelse
    </tbody>
</table>

<div class="signature">
    <table><tr><td></td><td>
        <div>{{ now()->format('d F Y') }}</div>
        <div class="muted">Mengetahui,</div>
        <div class="signature-space"></div>
        <div class="signature-line"></div>
        <strong>HR Manager</strong><br><span class="muted">{{ $company->name }}</span>
    </td></tr></table>
</div>
<div class="footer">SWMS · Detail Rekapitulasi HR · {{ $recap['range']['label'] }}</div>
</body>
</html>
