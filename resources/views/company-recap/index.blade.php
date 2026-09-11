@extends('layouts.app')

@section('title', 'Rekapitulasi HR')

@section('content')
@php
    $summary = $recap['summary'];
    $range = $recap['range'];
    $rows = $recap['rows'];
    $exportQuery = http_build_query(request()->except('page'));
    $period = request()->has('period') && in_array(request('period'), ['day', 'month', 'year', 'all'], true) ? request('period') : 'month';
    $fromMonth = request('from_month', substr($range['from'], 0, 7));
    $toMonth = request('to_month', substr($range['to'], 0, 7));
    $day = request('day', $range['from']);
    $fromYear = (int) request('from_year', substr($range['from'], 0, 4));
    $toYear = (int) request('to_year', substr($range['to'], 0, 4));
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
                    <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Detail Rekapitulasi HR</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">Lihat rincian attendance dan assignment setiap employee setelah meninjau ringkasan di dashboard.</p>
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

    <form method="GET" action="{{ route('company-recap.index') }}" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm lg:p-6">
        <div class="mb-5 flex items-start gap-3">
            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="calendar-range" class="h-5 w-5"></i></span>
            <div><h3 class="font-bold text-slate-900">Periode dan Filter</h3><p class="mt-0.5 text-xs text-slate-500">Pilih hari, bulan, tahun, atau seluruh data untuk laporan.</p></div>
        </div>
        <div class="space-y-5">
            <div><p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.16em] text-blue-600">Periode laporan</p><div class="grid gap-4 lg:grid-cols-[220px_1fr]"><div><label class="mb-1.5 block text-xs font-bold text-slate-500">Tampilkan berdasarkan</label><select name="period" id="recap-period" class="w-full rounded-xl border-slate-300 text-sm font-semibold focus:border-blue-500 focus:ring-blue-500"><option value="day" @selected($period === 'day')>Hari</option><option value="month" @selected($period === 'month')>Bulan</option><option value="year" @selected($period === 'year')>Tahun</option><option value="all" @selected($period === 'all')>Semua</option></select></div><div id="recap-period-fields" class="grid gap-4 sm:grid-cols-2"></div></div></div>
            <div class="border-t border-slate-100 pt-5"><p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.16em] text-blue-600">Cari employee</p><input type="search" name="search" value="{{ request('search') }}" placeholder="Nama atau NIP employee..." class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div class="border-t border-slate-100 pt-5"><p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.16em] text-blue-600">Organisasi</p><div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4"><div><label class="mb-1.5 block text-xs font-bold text-slate-500">Office</label><select name="office_id" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Semua Office</option>@foreach($options['offices'] as $item)<option value="{{ $item->id }}" @selected((string)request('office_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div><div><label class="mb-1.5 block text-xs font-bold text-slate-500">Department</label><select name="department_id" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Semua Department</option>@foreach($options['departments'] as $item)<option value="{{ $item->id }}" @selected((string)request('department_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div><div><label class="mb-1.5 block text-xs font-bold text-slate-500">Position</label><select name="position_id" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Semua Position</option>@foreach($options['positions'] as $item)<option value="{{ $item->id }}" @selected((string)request('position_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div><div><label class="mb-1.5 block text-xs font-bold text-slate-500">Team</label><select name="team_id" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Semua Team</option>@foreach($options['teams'] as $item)<option value="{{ $item->id }}" @selected((string)request('team_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></div></div></div>
            <div class="border-t border-slate-100 pt-5"><div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4"><div><label class="mb-1.5 block text-xs font-bold text-slate-500">Status employee</label><select name="active" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Semua Status</option><option value="1" @selected(request('active') === '1')>Aktif</option><option value="0" @selected(request('active') === '0')>Nonaktif</option></select></div><div><label class="mb-1.5 block text-xs font-bold text-slate-500">Urutkan</label><select name="sort" class="w-full rounded-xl border-slate-300 text-sm"><option value="name">Nama Employee</option><option value="attendance_low" @selected(request('sort') === 'attendance_low')>Attendance Terendah</option><option value="absent_high" @selected(request('sort') === 'absent_high')>Absent Terbanyak</option><option value="completion_low" @selected(request('sort') === 'completion_low')>Completion Terendah</option><option value="not_worked_high" @selected(request('sort') === 'not_worked_high')>Not Worked Terbanyak</option></select></div><div class="flex items-end gap-2 sm:col-span-2"><button class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700"><i data-lucide="filter" class="h-4 w-4"></i>Terapkan filter</button><a href="{{ route('company-recap.index', ['period' => 'month', 'from_month' => now()->format('Y-m'), 'to_month' => now()->format('Y-m')]) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Reset</a></div></div></div>
        </div>
    </form>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between"><div><h3 class="font-bold text-slate-900">Rekap per Employee</h3><p class="mt-1 text-xs text-slate-500">{{ $rows->total() }} employee · {{ $range['label'] }} · maksimal 10 per halaman</p></div><div class="flex flex-wrap gap-2 text-[11px] font-bold"><span class="rounded-full bg-amber-50 px-3 py-1.5 text-amber-700">Review {{ $summary['assignment_pending_review'] }}</span><span class="rounded-full bg-violet-50 px-3 py-1.5 text-violet-700">Revisi {{ $summary['assignment_needs_revision'] }}</span><span class="rounded-full bg-red-50 px-3 py-1.5 text-red-700">Not Worked {{ $summary['assignment_not_worked'] }}</span></div></div>
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full min-w-[1060px] divide-y divide-slate-100">
                <thead class="bg-slate-50"><tr class="text-left text-[11px] font-bold uppercase tracking-wide text-slate-500"><th class="px-5 py-4">Employee</th><th class="px-4 py-4">Organisasi</th><th class="px-4 py-4">Attendance</th><th class="px-4 py-4">Assignment</th><th class="px-4 py-4 text-center">Skor</th><th class="px-5 py-4 text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rows as $row)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-4"><div class="flex items-center gap-3">@if($row['employee_photo_url'])<img src="{{ $row['employee_photo_url'] }}" class="h-10 w-10 rounded-full object-cover">@else<div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">{{ strtoupper(substr($row['employee_name'], 0, 1)) }}</div>@endif<div><p class="font-bold text-slate-900">{{ $row['employee_name'] }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $row['employee_number'] }}</p></div></div></td>
                            <td class="px-4 py-4"><p class="max-w-[170px] text-sm font-semibold text-slate-700">{{ $row['department'] }}</p><p class="mt-1 max-w-[170px] text-xs text-slate-400">{{ $row['position'] }} · {{ $row['team'] }}</p></td>
                            <td class="px-4 py-4"><div class="grid grid-cols-4 gap-2 text-center text-[11px]"><div><p class="font-bold text-emerald-600">{{ $row['attended'] }}/{{ $row['working_days'] }}</p><p class="mt-1 text-slate-400">Hadir</p></div><div><p class="font-bold text-amber-600">{{ $row['late'] }}</p><p class="mt-1 text-slate-400">Telat</p></div><div><p class="font-bold text-red-600">{{ $row['absent'] }}</p><p class="mt-1 text-slate-400">Absen</p></div><div><p class="font-bold {{ $row['attendance_rate'] >= 90 ? 'text-emerald-600' : ($row['attendance_rate'] >= 75 ? 'text-amber-600' : 'text-red-600') }}">{{ number_format($row['attendance_rate'], 1) }}%</p><p class="mt-1 text-slate-400">Rate</p></div></div></td>
                            <td class="px-4 py-4"><div class="grid grid-cols-4 gap-2 text-center text-[11px]"><div><p class="font-bold text-slate-700">{{ $row['assignment_total'] }}</p><p class="mt-1 text-slate-400">Total</p></div><div><p class="font-bold text-emerald-600">{{ $row['assignment_completed'] }}</p><p class="mt-1 text-slate-400">Selesai</p></div><div><p class="font-bold text-red-600">{{ $row['assignment_rejected'] }}</p><p class="mt-1 text-slate-400">Ditolak</p></div><div><p class="font-bold text-amber-600">{{ $row['assignment_not_worked'] }}</p><p class="mt-1 text-slate-400">Belum</p></div></div><p class="mt-2 text-center text-xs font-bold text-blue-600">{{ number_format($row['completion_rate'], 1) }}% selesai</p></td>
                            <td class="px-4 py-4 text-center"><span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 text-sm font-extrabold text-slate-700">{{ number_format($row['performance_score'], 1) }}</span></td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('employees.show', $row['employee_id']) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-2 text-sm font-bold text-slate-600 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">Detail <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-400">Tidak ada employee yang cocok dengan filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="space-y-3 p-4 md:hidden">
            @forelse($rows as $row)
                <article class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-3">@if($row['employee_photo_url'])<img src="{{ $row['employee_photo_url'] }}" class="h-10 w-10 shrink-0 rounded-full object-cover">@else<div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">{{ strtoupper(substr($row['employee_name'], 0, 1)) }}</div>@endif<div class="min-w-0"><p class="truncate font-bold text-slate-900">{{ $row['employee_name'] }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $row['employee_number'] }}</p></div></div>
                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-bold {{ $row['attendance_rate'] >= 90 ? 'bg-emerald-50 text-emerald-700' : ($row['attendance_rate'] >= 75 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">{{ number_format($row['attendance_rate'], 1) }}%</span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">{{ $row['department'] }} · {{ $row['position'] }} · {{ $row['team'] }}</p>
                    <div class="mt-3 grid grid-cols-4 gap-2 rounded-xl bg-white p-3 text-center text-[11px]"><div><p class="font-bold text-emerald-600">{{ $row['attended'] }}/{{ $row['working_days'] }}</p><p class="mt-1 text-slate-400">Hadir</p></div><div><p class="font-bold text-amber-600">{{ $row['late'] }}</p><p class="mt-1 text-slate-400">Telat</p></div><div><p class="font-bold text-red-600">{{ $row['absent'] }}</p><p class="mt-1 text-slate-400">Absen</p></div><div><p class="font-bold text-blue-600">{{ number_format($row['completion_rate'], 1) }}%</p><p class="mt-1 text-slate-400">Selesai</p></div></div>
                    <div class="mt-3 flex items-center justify-between gap-3"><p class="text-xs text-slate-500">Assignment {{ $row['assignment_total'] }} · <span class="font-semibold text-emerald-600">{{ $row['assignment_completed'] }} selesai</span> · <span class="font-semibold text-amber-600">{{ $row['assignment_not_worked'] }} belum</span></p><a href="{{ route('employees.show', $row['employee_id']) }}" class="inline-flex shrink-0 items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600">Detail <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i></a></div>
                </article>
            @empty
                <div class="px-4 py-12 text-center text-sm text-slate-400">Tidak ada employee yang cocok dengan filter.</div>
            @endforelse
        </div>
        @if($rows->hasPages())<div class="border-t border-slate-100 bg-slate-50/70 px-6 py-4">{{ $rows->links() }}</div>@endif
    </section>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const period = document.getElementById('recap-period');
    const fields = document.getElementById('recap-period-fields');
    const values = {
        day: @json($day),
        fromMonth: @json($fromMonth),
        toMonth: @json($toMonth),
        fromYear: @json($fromYear),
        toYear: @json($toYear),
    };
    const inputClass = 'w-full rounded-xl border-slate-300 text-sm font-semibold focus:border-blue-500 focus:ring-blue-500';
    const label = (text) => `<label class="block"><span class="mb-1.5 block text-xs font-bold text-slate-500">${text}</span>`;
    function renderPeriodFields() {
        const selected = period.value;
        if (selected === 'day') {
            fields.innerHTML = `${label('Hari yang dipilih')}<input type="date" name="day" value="${values.day}" max="{{ today()->toDateString() }}" class="${inputClass}"></label>`;
        } else if (selected === 'month') {
            fields.innerHTML = `${label('Dari bulan')}<input type="month" name="from_month" value="${values.fromMonth}" max="{{ today()->format('Y-m') }}" class="${inputClass}"></label>${label('Sampai bulan')}<input type="month" name="to_month" value="${values.toMonth}" max="{{ today()->format('Y-m') }}" class="${inputClass}"></label>`;
        } else if (selected === 'year') {
            const current = new Date().getFullYear();
            const options = (selectedYear) => Array.from({length: 16}, (_, index) => current - index).map(year => `<option value="${year}" ${Number(selectedYear) === year ? 'selected' : ''}>${year}</option>`).join('');
            fields.innerHTML = `${label('Dari tahun')}<select name="from_year" class="${inputClass}">${options(values.fromYear)}</select></label>${label('Sampai tahun')}<select name="to_year" class="${inputClass}">${options(values.toYear)}</select></label>`;
        } else {
            fields.innerHTML = '<div class="flex min-h-10 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-600 sm:col-span-2"><i data-lucide="database" class="h-4 w-4 text-blue-600"></i>Seluruh data yang tersedia</div>';
            if (window.lucide) window.lucide.createIcons();
        }
    }
    period?.addEventListener('change', renderPeriodFields);
    renderPeriodFields();
});
</script>
@endpush
@endsection
