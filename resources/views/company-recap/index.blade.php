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

    <form id="hr-signature-form" method="POST" action="{{ route('company-recap.signature.update') }}" enctype="multipart/form-data" class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        @csrf
        @method('PUT')
        <div class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50/70 px-5 py-5 sm:flex-row sm:items-start sm:justify-between lg:px-6">
            <div class="flex items-start gap-3">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600"><i data-lucide="signature" class="h-5 w-5"></i></span>
                <div><h3 class="font-bold text-slate-900">Tanda tangan laporan HR</h3><p class="mt-1 text-xs leading-5 text-slate-500">Tanda tangan ini ditampilkan pada bagian akhir PDF Detail Rekapitulasi HR dan Rekap HR Employee.</p></div>
            </div>
            @if($signature['url'])<div class="rounded-xl border border-slate-200 bg-white px-3 py-2"><img src="{{ $signature['url'] }}" alt="Tanda tangan HR saat ini" class="h-9 w-28 object-contain"></div>@endif
        </div>
        <div class="grid gap-6 p-5 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:p-6">
            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block"><span class="mb-1.5 block text-xs font-bold text-slate-500">Nama penandatangan</span><input type="text" name="signer_name" value="{{ old('signer_name', $signature['name']) }}" maxlength="100" placeholder="Contoh: Nita Pratiwi" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></label>
                    <label class="block"><span class="mb-1.5 block text-xs font-bold text-slate-500">Jabatan</span><input type="text" name="signer_title" value="{{ old('signer_title', $signature['title']) }}" maxlength="100" placeholder="Contoh: HR Manager" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></label>
                </div>
                <label class="block"><span class="mb-1.5 flex items-center justify-between text-xs font-bold text-slate-500"><span>Skala tanda tangan pada PDF</span><output id="signature-scale-output" class="rounded-full bg-blue-50 px-2 py-0.5 text-blue-700">{{ old('signature_scale', $signature['scale']) }}%</output></span><input id="signature-scale" type="range" name="signature_scale" min="50" max="200" step="5" value="{{ old('signature_scale', $signature['scale']) }}" class="h-2 w-full cursor-pointer accent-blue-600"><span class="mt-1.5 flex justify-between text-[11px] text-slate-400"><span>50% lebih kecil</span><span>100% normal</span><span>200% lebih besar</span></span></label>
                <label class="block"><span class="mb-1.5 block text-xs font-bold text-slate-500">Unggah gambar tanda tangan</span><input id="signature-file" type="file" name="signature_file" accept="image/png,image/jpeg,image/webp" class="block w-full rounded-xl border border-slate-300 bg-white text-sm file:mr-3 file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-sm file:font-bold file:text-blue-700"><span class="mt-1.5 block text-xs text-slate-400">PNG, JPG, atau WEBP. Maksimal 1 MB.</span></label>
                <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remove_signature" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">Hapus gambar tanda tangan yang tersimpan</label>
                @error('signature_file')<p class="text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                @error('signature_data')<p class="text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="rounded-2xl border border-dashed border-blue-200 bg-blue-50/40 p-4">
                <div class="flex items-start justify-between gap-3"><div><p class="font-bold text-slate-800">Atau tanda tangan langsung</p><p class="mt-1 text-xs leading-5 text-slate-500">Gunakan mouse, touchpad, atau layar sentuh. Tanda tangan digital akan disimpan sebagai gambar aman.</p></div><button id="clear-signature" type="button" class="shrink-0 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50">Bersihkan</button></div>
                <canvas id="signature-pad" width="760" height="220" class="mt-4 h-40 w-full touch-none rounded-xl border border-slate-200 bg-white"></canvas>
                <input id="signature-data" type="hidden" name="signature_data">
                <p class="mt-2 text-xs text-slate-400">Jika gambar dan tanda tangan langsung diisi bersamaan, gambar unggahan yang dipakai.</p>
                <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
                    <div class="flex items-center justify-between gap-3"><p class="text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">Pratinjau di PDF</p><span id="signature-preview-scale" class="rounded-full bg-blue-50 px-2 py-1 text-[11px] font-bold text-blue-700">{{ $signature['scale'] }}%</span></div>
                    <div class="mt-3 rounded-lg border border-slate-100 bg-slate-50/60 px-4 py-3 text-right"><p class="text-[11px] text-slate-700">{{ now()->format('d F Y') }}</p><p class="mt-0.5 text-[11px] text-slate-400">Mengetahui,</p><div class="relative mx-auto mt-1 h-12 w-48 border-b border-slate-700"><img id="signature-live-preview" src="{{ $signature['url'] ?? '' }}" alt="Pratinjau tanda tangan" class="{{ $signature['url'] ? '' : 'hidden' }} absolute bottom-0 left-1/2 h-10 w-40 -translate-x-1/2 origin-bottom object-contain"></div><p id="signature-preview-name" class="mt-1 text-xs font-bold text-slate-800">{{ $signature['name'] }}</p><p id="signature-preview-title" class="text-[11px] text-slate-400">{{ $signature['title'] }} · {{ $company->name }}</p></div>
                </div>
            </div>
        </div>
        <div class="flex justify-end border-t border-slate-100 bg-slate-50/70 px-5 py-4 lg:px-6"><button class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700"><i data-lucide="save" class="h-4 w-4"></i>Simpan tanda tangan</button></div>
    </form>

    <form method="GET" action="{{ route('company-recap.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
        <div class="mb-4 flex items-center gap-3">
            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="sliders-horizontal" class="h-4 w-4"></i></span>
            <div><h3 class="font-bold text-slate-900">Periode dan Filter</h3><p class="mt-0.5 text-xs text-slate-500">Atur rentang dan organisasi untuk menampilkan data yang diperlukan.</p></div>
        </div>

        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-2"><label class="mb-1.5 block text-[11px] font-bold text-slate-500">Periode</label><select name="period" id="recap-period" class="w-full rounded-xl border-slate-300 py-2.5 text-sm font-semibold focus:border-blue-500 focus:ring-blue-500"><option value="day" @selected($period === 'day')>Hari</option><option value="month" @selected($period === 'month')>Bulan</option><option value="year" @selected($period === 'year')>Tahun</option><option value="all" @selected($period === 'all')>Semua data</option></select></div>
            <div id="recap-period-fields" class="grid gap-3 sm:grid-cols-2 lg:col-span-4"></div>
            <label class="block lg:col-span-6"><span class="mb-1.5 block text-[11px] font-bold text-slate-500">Cari employee</span><input type="search" name="search" value="{{ request('search') }}" placeholder="Nama atau NIP employee..." class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"></label>
        </div>

        <div class="mt-4 border-t border-slate-100 pt-4">
            <p class="mb-2.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-blue-600">Organisasi</p>
            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <label class="block"><span class="mb-1.5 block text-[11px] font-bold text-slate-500">Office</span><select name="office_id" class="w-full rounded-xl border-slate-300 py-2.5 text-sm"><option value="">Semua Office</option>@foreach($options['offices'] as $item)<option value="{{ $item->id }}" @selected((string)request('office_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></label>
                <label class="block"><span class="mb-1.5 block text-[11px] font-bold text-slate-500">Department</span><select name="department_id" class="w-full rounded-xl border-slate-300 py-2.5 text-sm"><option value="">Semua Department</option>@foreach($options['departments'] as $item)<option value="{{ $item->id }}" @selected((string)request('department_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></label>
                <label class="block"><span class="mb-1.5 block text-[11px] font-bold text-slate-500">Position</span><select name="position_id" class="w-full rounded-xl border-slate-300 py-2.5 text-sm"><option value="">Semua Position</option>@foreach($options['positions'] as $item)<option value="{{ $item->id }}" @selected((string)request('position_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></label>
                <label class="block"><span class="mb-1.5 block text-[11px] font-bold text-slate-500">Team</span><select name="team_id" class="w-full rounded-xl border-slate-300 py-2.5 text-sm"><option value="">Semua Team</option>@foreach($options['teams'] as $item)<option value="{{ $item->id }}" @selected((string)request('team_id') === (string)$item->id)>{{ $item->name }}</option>@endforeach</select></label>
            </div>
        </div>

        <div class="mt-4 flex flex-col gap-3 border-t border-slate-100 pt-4 lg:flex-row lg:items-end">
            <label class="block lg:w-52"><span class="mb-1.5 block text-[11px] font-bold text-slate-500">Status employee</span><select name="active" class="w-full rounded-xl border-slate-300 py-2.5 text-sm"><option value="">Semua Status</option><option value="1" @selected(request('active') === '1')>Aktif</option><option value="0" @selected(request('active') === '0')>Nonaktif</option></select></label>
            <label class="block lg:w-64"><span class="mb-1.5 block text-[11px] font-bold text-slate-500">Urutkan</span><select name="sort" class="w-full rounded-xl border-slate-300 py-2.5 text-sm"><option value="name">Nama Employee</option><option value="attendance_low" @selected(request('sort') === 'attendance_low')>Attendance Terendah</option><option value="absent_high" @selected(request('sort') === 'absent_high')>Absent Terbanyak</option><option value="completion_low" @selected(request('sort') === 'completion_low')>Completion Terendah</option><option value="not_worked_high" @selected(request('sort') === 'not_worked_high')>Not Worked Terbanyak</option></select></label>
            <div class="flex flex-1 gap-2 lg:justify-end"><button class="inline-flex min-h-[43px] flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 lg:max-w-sm"><i data-lucide="filter" class="h-4 w-4"></i>Terapkan filter</button><a href="{{ route('company-recap.index', ['period' => 'month', 'from_month' => now()->format('Y-m'), 'to_month' => now()->format('Y-m')]) }}" class="inline-flex min-h-[43px] items-center justify-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Reset</a></div>
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
    const signatureCanvas = document.getElementById('signature-pad');
    const signatureData = document.getElementById('signature-data');
    const signatureForm = document.getElementById('hr-signature-form');
    const clearSignature = document.getElementById('clear-signature');
    const signatureScale = document.getElementById('signature-scale');
    const signatureScaleOutput = document.getElementById('signature-scale-output');
    const signaturePreviewScale = document.getElementById('signature-preview-scale');
    const signatureLivePreview = document.getElementById('signature-live-preview');
    const signaturePreviewName = document.getElementById('signature-preview-name');
    const signaturePreviewTitle = document.getElementById('signature-preview-title');
    const signatureFile = document.getElementById('signature-file');
    const signatureName = document.querySelector('[name="signer_name"]');
    const signatureTitle = document.querySelector('[name="signer_title"]');
    const initialSignatureUrl = @json($signature['url'] ?? null);
    let signatureHasInk = false;

    const updateSignatureScale = () => {
        if (!signatureScale) return;
        const value = Number(signatureScale.value || 100);
        if (signatureScaleOutput) signatureScaleOutput.textContent = `${value}%`;
        if (signaturePreviewScale) signaturePreviewScale.textContent = `${value}%`;
        if (signatureLivePreview) signatureLivePreview.style.transform = `translateX(-50%) scale(${value / 100})`;
    };
    signatureScale?.addEventListener('input', updateSignatureScale);
    updateSignatureScale();

    const showSignaturePreview = (source) => {
        if (!signatureLivePreview) return;
        if (source) {
            signatureLivePreview.src = source;
            signatureLivePreview.classList.remove('hidden');
        } else {
            signatureLivePreview.removeAttribute('src');
            signatureLivePreview.classList.add('hidden');
        }
    };
    signatureFile?.addEventListener('change', () => {
        const file = signatureFile.files?.[0];
        if (!file) return;
        const reader = new FileReader();
        reader.addEventListener('load', () => showSignaturePreview(reader.result));
        reader.readAsDataURL(file);
    });
    signatureName?.addEventListener('input', () => { if (signaturePreviewName) signaturePreviewName.textContent = signatureName.value || 'Nama penandatangan'; });
    signatureTitle?.addEventListener('input', () => { if (signaturePreviewTitle) signaturePreviewTitle.textContent = `${signatureTitle.value || 'HR Manager'} · {{ $company->name }}`; });

    if (signatureCanvas && signatureData && signatureForm) {
        const context = signatureCanvas.getContext('2d');
        let drawing = false;
        let previousPoint = null;

        const point = (event) => {
            const bounds = signatureCanvas.getBoundingClientRect();
            return {
                x: (event.clientX - bounds.left) * (signatureCanvas.width / bounds.width),
                y: (event.clientY - bounds.top) * (signatureCanvas.height / bounds.height),
            };
        };
        const start = (event) => {
            drawing = true;
            previousPoint = point(event);
            signatureCanvas.setPointerCapture?.(event.pointerId);
        };
        const draw = (event) => {
            if (!drawing) return;
            const nextPoint = point(event);
            context.beginPath();
            context.moveTo(previousPoint.x, previousPoint.y);
            context.lineTo(nextPoint.x, nextPoint.y);
            context.strokeStyle = '#172033';
            context.lineWidth = 3.5;
            context.lineCap = 'round';
            context.lineJoin = 'round';
            context.stroke();
            previousPoint = nextPoint;
            signatureHasInk = true;
            showSignaturePreview(signatureCanvas.toDataURL('image/png'));
        };
        const stop = () => { drawing = false; previousPoint = null; };
        const clear = () => {
            context.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
            signatureData.value = '';
            signatureHasInk = false;
            showSignaturePreview(initialSignatureUrl);
        };

        signatureCanvas.addEventListener('pointerdown', start);
        signatureCanvas.addEventListener('pointermove', draw);
        signatureCanvas.addEventListener('pointerup', stop);
        signatureCanvas.addEventListener('pointerleave', stop);
        clearSignature?.addEventListener('click', clear);
        signatureForm.addEventListener('submit', () => {
            signatureData.value = signatureHasInk ? signatureCanvas.toDataURL('image/png') : '';
        });
    }

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
