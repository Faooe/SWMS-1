@extends('layouts.app')

@section('title', 'Rekapitulasi HR')

@section('content')
@php
    $summary = $recap['summary'];
    $range = $recap['range'];
    $rows = $recap['rows'];
    $exportQuery = http_build_query(request()->except('page'));
@endphp

<div class="mx-auto max-w-[1680px] space-y-6">
    <section class="overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-white via-blue-50/50 to-indigo-50 p-6 shadow-sm lg:p-8">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex items-start gap-4">
                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-200">
                    <i data-lucide="chart-no-axes-combined" class="h-6 w-6"></i>
                </span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Company Analytics</p>
                    <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Rekapitulasi HR Perusahaan</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">Pantau attendance dan assignment seluruh employee dalam satu laporan yang mengikuti kalender kerja perusahaan.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($isPremium)
                    <a href="{{ route('company-recap.export.pdf').($exportQuery ? '?'.$exportQuery : '') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-red-200 hover:text-red-600">
                        <i data-lucide="file-text" class="h-4 w-4 text-red-500"></i> PDF
                    </a>
                    <a href="{{ route('company-recap.export.excel').($exportQuery ? '?'.$exportQuery : '') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">
                        <i data-lucide="file-spreadsheet" class="h-4 w-4"></i> Excel Lengkap
                    </a>
                @else
                    <a href="{{ route('subscription.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white"><i data-lucide="lock" class="h-4 w-4"></i> Upgrade untuk Export</a>
                @endif
            </div>
        </div>
    </section>

    <form method="GET" action="{{ route('company-recap.index') }}" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center gap-3">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="list-filter" class="h-5 w-5"></i></span>
            <div><h3 class="font-bold text-slate-900">Periode dan Filter</h3><p class="text-xs text-slate-500">Tanggal akhir dihitung tepat sesuai pilihan, tidak dibulatkan ke akhir bulan.</p></div>
        </div>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div><label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Dari Tanggal</label><input type="date" name="from" value="{{ $range['from'] }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div><label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Sampai Tanggal</label><input type="date" name="to" value="{{ $range['to'] }}" max="{{ today()->toDateString() }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div class="md:col-span-2"><label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Cari Employee</label><input type="search" name="search" value="{{ request('search') }}" placeholder="Nama atau NIP employee..." class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div><label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Office</label><select name="office_id" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Semua Office</option>@foreach($options['offices'] as $item)<option value="{{ $item->id }}" @selected((string)request('office_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
            <div><label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Department</label><select name="department_id" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Semua Department</option>@foreach($options['departments'] as $item)<option value="{{ $item->id }}" @selected((string)request('department_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
            <div><label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Position</label><select name="position_id" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Semua Position</option>@foreach($options['positions'] as $item)<option value="{{ $item->id }}" @selected((string)request('position_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
            <div><label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Team</label><select name="team_id" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Semua Team</option>@foreach($options['teams'] as $item)<option value="{{ $item->id }}" @selected((string)request('team_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div>
            <div><label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Status Employee</label><select name="active" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Semua Status</option><option value="1" @selected(request('active') === '1')>Aktif</option><option value="0" @selected(request('active') === '0')>Nonaktif</option></select></div>
            <div><label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Urutkan</label><select name="sort" class="w-full rounded-xl border-slate-300 text-sm"><option value="name">Nama Employee</option><option value="attendance_low" @selected(request('sort') === 'attendance_low')>Attendance Terendah</option><option value="absent_high" @selected(request('sort') === 'absent_high')>Absent Terbanyak</option><option value="completion_low" @selected(request('sort') === 'completion_low')>Completion Terendah</option><option value="not_worked_high" @selected(request('sort') === 'not_worked_high')>Not Worked Terbanyak</option></select></div>
            <div class="flex items-end gap-2 md:col-span-2"><button class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white hover:bg-blue-700"><i data-lucide="filter" class="h-4 w-4"></i>Terapkan</button><a href="{{ route('company-recap.index', ['from' => $range['from'], 'to' => $range['to']]) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50">Reset</a></div>
        </div>
    </form>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        @foreach([
            ['Employee', $summary['employees'], 'users', 'text-blue-600', 'bg-blue-50'],
            ['Attendance Rate', number_format($summary['attendance_rate'], 1).'%', 'user-check', 'text-emerald-600', 'bg-emerald-50'],
            ['Absent', $summary['absent'], 'user-x', 'text-red-600', 'bg-red-50'],
            ['Assignment', $summary['assignment_total'], 'clipboard-list', 'text-violet-600', 'bg-violet-50'],
            ['Completion Rate', number_format($summary['completion_rate'], 1).'%', 'circle-check-big', 'text-blue-600', 'bg-blue-50'],
        ] as [$label, $value, $icon, $tone, $background])
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><span class="inline-flex h-10 w-10 items-center justify-center rounded-xl {{ $background }} {{ $tone }}"><i data-lucide="{{ $icon }}" class="h-5 w-5"></i></span><span class="text-[11px] font-bold uppercase tracking-wide text-slate-400">{{ $range['working_days'] }} hari kerja</span></div><p class="mt-4 text-2xl font-extrabold text-slate-900">{{ $value }}</p><p class="mt-1 text-xs font-semibold text-slate-500">{{ $label }}</p></article>
        @endforeach
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between"><div><h3 class="font-bold text-slate-900">Rekap per Employee</h3><p class="mt-1 text-xs text-slate-500">{{ $rows->total() }} employee · {{ $range['label'] }} · maksimal 10 per halaman</p></div><div class="flex flex-wrap gap-2 text-[11px] font-bold"><span class="rounded-full bg-amber-50 px-3 py-1.5 text-amber-700">Review {{ $summary['assignment_pending_review'] }}</span><span class="rounded-full bg-violet-50 px-3 py-1.5 text-violet-700">Revisi {{ $summary['assignment_needs_revision'] }}</span><span class="rounded-full bg-red-50 px-3 py-1.5 text-red-700">Not Worked {{ $summary['assignment_not_worked'] }}</span></div></div>
        <div class="overflow-x-auto">
            <table class="min-w-[1450px] w-full divide-y divide-slate-100">
                <thead class="bg-slate-50"><tr class="text-left text-[11px] font-bold uppercase tracking-wide text-slate-500"><th class="px-5 py-4">Employee</th><th class="px-4 py-4">Organisasi</th><th class="px-4 py-4 text-center">Hadir</th><th class="px-4 py-4 text-center">Telat</th><th class="px-4 py-4 text-center">Absent</th><th class="px-4 py-4 text-center">Attendance</th><th class="px-4 py-4 text-center">Assignment</th><th class="px-4 py-4 text-center">Completed</th><th class="px-4 py-4 text-center">Rejected</th><th class="px-4 py-4 text-center">Not Worked</th><th class="px-4 py-4 text-center">Completion</th><th class="px-4 py-4 text-center">Skor</th><th class="px-5 py-4 text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rows as $row)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-4"><div class="flex items-center gap-3">@if($row['employee_photo_url'])<img src="{{ $row['employee_photo_url'] }}" class="h-10 w-10 rounded-full object-cover">@else<div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">{{ strtoupper(substr($row['employee_name'], 0, 1)) }}</div>@endif<div><p class="font-bold text-slate-900">{{ $row['employee_name'] }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $row['employee_number'] }}</p></div></div></td>
                            <td class="px-4 py-4"><p class="text-sm font-semibold text-slate-700">{{ $row['department'] }}</p><p class="mt-1 text-xs text-slate-400">{{ $row['position'] }} · {{ $row['team'] }}</p></td>
                            <td class="px-4 py-4 text-center font-bold text-emerald-600">{{ $row['attended'] }}/{{ $row['working_days'] }}</td><td class="px-4 py-4 text-center font-semibold text-amber-600">{{ $row['late'] }}</td><td class="px-4 py-4 text-center font-semibold text-red-600">{{ $row['absent'] }}</td>
                            <td class="px-4 py-4 text-center"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $row['attendance_rate'] >= 90 ? 'bg-emerald-50 text-emerald-700' : ($row['attendance_rate'] >= 75 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">{{ number_format($row['attendance_rate'], 1) }}%</span></td>
                            <td class="px-4 py-4 text-center font-bold text-slate-700">{{ $row['assignment_total'] }}</td><td class="px-4 py-4 text-center font-semibold text-emerald-600">{{ $row['assignment_completed'] }}</td><td class="px-4 py-4 text-center font-semibold text-red-600">{{ $row['assignment_rejected'] }}</td><td class="px-4 py-4 text-center font-semibold text-red-600">{{ $row['assignment_not_worked'] }}</td><td class="px-4 py-4 text-center font-bold text-blue-600">{{ number_format($row['completion_rate'], 1) }}%</td><td class="px-4 py-4 text-center"><span class="rounded-lg bg-slate-100 px-2.5 py-1 font-extrabold text-slate-700">{{ number_format($row['performance_score'], 1) }}</span></td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('employees.show', $row['employee_id']) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-2 text-sm font-bold text-slate-600 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">Detail <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="13" class="px-6 py-16 text-center text-sm text-slate-400">Tidak ada employee yang cocok dengan filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rows->hasPages())<div class="border-t border-slate-100 bg-slate-50/70 px-6 py-4">{{ $rows->links() }}</div>@endif
    </section>
</div>
@endsection
