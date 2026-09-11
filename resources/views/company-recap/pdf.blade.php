<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Rekapitulasi HR</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        @page { margin: 24px 28px 38px; }
        body { color:#172033; font-size:10px; }
        .header { background:#eff6ff; border:1px solid #bfdbfe; border-left:5px solid #2563eb; padding:15px 17px; }
        .eyebrow { color:#2563eb; font-size:9px; font-weight:bold; letter-spacing:2px; text-transform:uppercase; }
        h1 { color:#0f172a; font-size:23px; margin:4px 0 3px; }
        .subtitle { color:#64748b; font-size:10px; }
        .meta { width:100%; margin-top:12px; }
        .meta td { color:#64748b; padding-right:18px; width:33.33%; }
        .meta strong { color:#172033; }
        .section-title { border-bottom:1px solid #bfdbfe; border-left:4px solid #2563eb; color:#1d4ed8; font-size:14px; font-weight:bold; margin:18px 0 8px; padding:0 0 6px 9px; }
        .section-note { color:#64748b; font-size:9px; margin:-3px 0 9px; }
        .cards { border-collapse:separate; border-spacing:6px; margin:10px -6px 14px; width:100%; }
        .card { background:#f8fafc; border:1px solid #dbe5f1; padding:10px; height:44px; }
        .card-blue { background:#eff6ff; border-color:#bfdbfe; } .card-green { background:#ecfdf5; border-color:#bbf7d0; }
        .card-amber { background:#fffbeb; border-color:#fde68a; } .card-red { background:#fef2f2; border-color:#fecaca; }
        .card .value { color:#0f172a; font-size:18px; font-weight:bold; }
        .card .label { color:#64748b; font-size:9px; margin-top:3px; }
        .green { color:#059669; font-weight:bold; } .amber { color:#d97706; font-weight:bold; }
        .red { color:#dc2626; font-weight:bold; } .blue { color:#2563eb; font-weight:bold; }
        .purple { color:#7c3aed; font-weight:bold; } .muted { color:#64748b; }
        .explain { background:#f8fafc; border:1px solid #dbe5f1; color:#334155; padding:10px 12px; }
        .explain strong { color:#1d4ed8; }
        .explain table { width:100%; } .explain td { padding:3px 10px 3px 0; vertical-align:top; }
        .breakdown { border-collapse:collapse; width:100%; }
        .breakdown th { background:#eaf2ff; color:#334155; font-size:9px; padding:7px; text-align:center; }
        .breakdown td { border:1px solid #dbe5f1; font-size:10px; padding:8px 7px; text-align:center; }
        .breakdown td:first-child { background:#f8fafc; text-align:left; }
        .employee-table { border-collapse:collapse; table-layout:fixed; width:100%; }
        .employee-table thead { display:table-header-group; }
        .employee-table th { background:#1e40af; color:#fff; font-size:8px; padding:9px 7px; text-align:left; text-transform:uppercase; }
        .employee-table td { border:1px solid #dbe5f1; font-size:9px; padding:9px 7px; vertical-align:top; }
        .employee-table tr:nth-child(even) { background:#f8fafc; }
        .name { color:#0f172a; font-size:10px; font-weight:bold; }
        .nip { color:#64748b; font-size:8px; margin-top:2px; }
        .org { color:#1e3a8a; font-weight:bold; } .sub { color:#64748b; font-size:8px; margin-top:2px; }
        .metric-line { line-height:1.55; white-space:nowrap; }
        .rate { background:#fff; border:1px solid #dbe5f1; display:inline-block; font-size:10px; padding:4px 6px; }
        .center { text-align:center; } .empty { color:#94a3b8; padding:20px; text-align:center; }
        .signature { margin:34px 0 0 auto; page-break-inside:avoid; text-align:center; width:230px; }
        .signature-space { height:36px; } .signature-line { border-top:1px solid #172033; margin:0 auto 4px; width:180px; }
        .signature-image { display:block; height:42px; margin:5px auto 2px; max-width:170px; object-fit:contain; }
        .footer { bottom:-20px; color:#94a3b8; font-size:8px; left:0; position:fixed; right:0; text-align:center; }
        .page-break { page-break-before:always; }
    </style>
</head>
<body>
@php
    $summary = $recap['summary'];
    $rateClass = fn ($rate) => $rate >= 90 ? 'green' : ($rate >= 75 ? 'amber' : 'red');
@endphp

<section class="header">
    <div class="eyebrow">Company Analytics</div>
    <h1>Detail Rekapitulasi HR</h1>
    <div class="subtitle">Laporan ringkas attendance dan assignment seluruh employee</div>
    <table class="meta"><tr>
        <td>Perusahaan: <strong>{{ $company->name }}</strong></td>
        <td>Periode: <strong>{{ $recap['range']['label'] }}</strong></td>
        <td>Dicetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong></td>
    </tr></table>
</section>

<div class="section-title">1. Ringkasan Utama</div>
<div class="section-note">Angka di bawah adalah total seluruh employee pada periode yang dipilih.</div>
<table class="cards"><tr>
    <td class="card card-blue"><div class="value">{{ $summary['employees'] }}</div><div class="label">Total employee</div></td>
    <td class="card card-green"><div class="value">{{ $summary['active_employees'] }}</div><div class="label">Employee aktif</div></td>
    <td class="card card-{{ $rateClass($summary['attendance_rate']) }}"><div class="value {{ $rateClass($summary['attendance_rate']) }}">{{ number_format($summary['attendance_rate'], 1) }}%</div><div class="label">Attendance rate</div></td>
    <td class="card card-{{ $rateClass($summary['completion_rate']) }}"><div class="value {{ $rateClass($summary['completion_rate']) }}">{{ number_format($summary['completion_rate'], 1) }}%</div><div class="label">Completion rate</div></td>
    <td class="card card-blue"><div class="value">{{ $summary['assignment_total'] }}</div><div class="label">Total assignment</div></td>
</tr></table>

<div class="explain">
    <strong>Cara membaca laporan</strong>
    <table><tr>
        <td><span class="green">Hijau</span><br>Hadir, selesai, atau rate ≥90%</td>
        <td><span class="amber">Kuning</span><br>Izin, terlambat, belum dikerjakan, atau rate 75–89%</td>
        <td><span class="red">Merah</span><br>Absen, ditolak, atau rate &lt;75%</td>
        <td><span class="purple">Ungu</span><br>Cuti atau perlu revisi</td>
    </tr></table>
</div>

<div class="section-title">2. Rincian Attendance & Assignment</div>
<table class="breakdown">
    <thead><tr><th></th><th class="green">Hadir</th><th class="amber">Terlambat</th><th class="amber">Izin</th><th class="purple">Cuti</th><th class="red">Absen</th><th class="green">Selesai</th><th class="amber">Belum</th><th class="red">Ditolak</th></tr></thead>
    <tbody><tr>
        <td><strong>Total</strong></td><td class="green">{{ $summary['present'] }}</td><td class="amber">{{ $summary['late'] }}</td><td class="amber">{{ $summary['permission'] }}</td><td class="purple">{{ $summary['leave'] }}</td><td class="red">{{ $summary['absent'] }}</td><td class="green">{{ $summary['assignment_completed'] }}</td><td class="amber">{{ $summary['assignment_not_worked'] }}</td><td class="red">{{ $summary['assignment_rejected'] }}</td>
    </tr></tbody>
</table>

<div class="page-break"></div>
<div class="section-title" style="margin-top:0">3. Rekap per Employee</div>
<div class="section-note">Attendance rate = (hadir + terlambat + izin + cuti) ÷ hari kerja employee. Completion rate = assignment selesai ÷ total assignment.</div>
<table class="employee-table">
    <thead><tr>
        <th width="18%">Employee</th><th width="18%">Organisasi</th><th width="27%">Attendance</th><th width="27%">Assignment</th><th width="6%">Skor</th><th width="7%">Status</th>
    </tr></thead>
    <tbody>
    @forelse($recap['rows'] as $row)
        <tr>
            <td><div class="name">{{ $row['employee_name'] }}</div><div class="nip">{{ $row['employee_number'] }}</div></td>
            <td><div class="org">{{ $row['department'] }}</div><div class="sub">{{ $row['position'] }} · {{ $row['team'] }}</div><div class="sub">{{ $row['office'] }}</div></td>
            <td><div class="metric-line"><span class="green">Hadir {{ $row['attended'] }}/{{ $row['working_days'] }}</span> <span class="amber">· Telat {{ $row['late'] }}</span></div><div class="metric-line"><span class="amber">Izin {{ $row['permission'] }}</span> <span class="purple">· Cuti {{ $row['leave'] }}</span> <span class="red">· Absen {{ $row['absent'] }}</span></div><div class="metric-line"><span class="rate {{ $rateClass($row['attendance_rate']) }}">{{ number_format($row['attendance_rate'], 1) }}% attendance rate</span></div></td>
            <td><div class="metric-line">Total <strong>{{ $row['assignment_total'] }}</strong> · <span class="green">Selesai {{ $row['assignment_completed'] }}</span></div><div class="metric-line"><span class="amber">Belum {{ $row['assignment_not_worked'] }}</span> · <span class="red">Ditolak {{ $row['assignment_rejected'] }}</span></div><div class="metric-line"><span class="rate {{ $rateClass($row['completion_rate']) }}">{{ number_format($row['completion_rate'], 1) }}% completion rate</span></div></td>
            <td class="center"><span class="rate {{ $rateClass($row['performance_score']) }}">{{ number_format($row['performance_score'], 1) }}</span></td>
            <td class="center"><span class="{{ $row['is_active'] ? 'green' : 'muted' }}">{{ $row['is_active'] ? 'Aktif' : 'Nonaktif' }}</span></td>
        </tr>
    @empty
        <tr><td colspan="6" class="empty">Tidak ada employee pada filter ini.</td></tr>
    @endforelse
    </tbody>
</table>

<div class="signature">
    <div>{{ now()->format('d F Y') }}</div>
    <div class="muted">Mengetahui,</div>
    @if(filled($hrSignature['data_uri'] ?? null))
        <img src="{{ $hrSignature['data_uri'] }}" alt="Tanda tangan HR" class="signature-image">
    @else
        <div class="signature-space"></div>
    @endif
    <div class="signature-line"></div>
    <strong>{{ $hrSignature['name'] ?? 'HR Manager' }}</strong><br><span class="muted">{{ $hrSignature['title'] ?? 'HR Manager' }} · {{ $company->name }}</span>
</div>
<div class="footer">SWMS · Detail Rekapitulasi HR · {{ $recap['range']['label'] }}</div>
</body>
</html>
