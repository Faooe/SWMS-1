@extends('layouts.app')

@section('title','Employee Detail')
@section('page-title','Employee')

@section('content')
<div class="mx-auto max-w-7xl space-y-5">

    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-4">
            <x-ui.avatar :employee="$employee" size="18" />
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="truncate text-2xl font-bold tracking-tight text-slate-900">{{ $employee->full_name }}</h1>
                    <span class="inline-flex items-center gap-2 rounded-full border {{ $employee->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }} px-2.5 py-1 text-xs font-semibold">
                        <span class="h-2 w-2 rounded-full {{ $employee->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                        {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-slate-500">{{ $employee->employee_number }} · {{ $employee->currentEmployment?->position?->name ?? 'Position belum diatur' }}</p>
                <p class="mt-0.5 text-sm text-slate-400">{{ $employee->currentEmployment?->department?->name ?? '-' }} · {{ $employee->currentEmployment?->office?->name ?? '-' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali
            </a>
            <form method="POST" action="{{ route('employees.toggle-status', $employee) }}" onsubmit="return confirm('{{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }} employee ini?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    <i data-lucide="{{ $employee->is_active ? 'user-x' : 'user-check' }}" class="h-4 w-4"></i>
                    {{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            <a href="{{ route('employees.edit',$employee) }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                <i data-lucide="pencil" class="h-4 w-4"></i> Edit Employee
            </a>
            <form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Hapus employee {{ addslashes($employee->full_name) }} secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" title="Hapus Employee" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-red-100 text-red-600 transition hover:bg-red-50">
                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="grid gap-5 xl:grid-cols-2">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="font-bold text-slate-900">Informasi Pribadi</h2>
                <p class="mt-1 text-sm text-slate-500">Identitas dan kontak employee.</p>
            </div>
            <dl class="grid gap-x-6 px-6 py-2 md:grid-cols-2">
                @foreach([
                    ['Email', $employee->email],
                    ['Telepon', $employee->phone ?: '-'],
                    ['Gender', $employee->gender ?: '-'],
                    ['Tempat Lahir', $employee->birth_place ?: '-'],
                    ['Tanggal Lahir', optional($employee->birth_date)->format('d M Y') ?: '-'],
                    ['Status Pernikahan', $employee->marital_status ?: '-'],
                    ['Kontak Darurat', $employee->emergency_contact_name ?: '-'],
                    ['Telepon Darurat', $employee->emergency_contact_phone ?: '-'],
                ] as [$label, $value])
                    <div class="border-b border-slate-100 py-3 last:border-b-0 md:last:border-b">
                        <dt class="text-xs font-medium text-slate-400">{{ $label }}</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
            <div class="border-t border-slate-100 px-6 py-4">
                <p class="text-xs font-medium text-slate-400">Alamat</p>
                <p class="mt-1 text-sm leading-6 text-slate-700">{{ $employee->address ?: '-' }}</p>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="font-bold text-slate-900">Pekerjaan & Penempatan</h2>
                <p class="mt-1 text-sm text-slate-500">Informasi employment aktif saat ini.</p>
            </div>
            <dl class="grid gap-x-6 px-6 py-2 md:grid-cols-2">
                @foreach([
                    ['Office', $employee->currentEmployment?->office?->name ?? '-'],
                    ['Department', $employee->currentEmployment?->department?->name ?? '-'],
                    ['Position', $employee->currentEmployment?->position?->name ?? '-'],
                    ['Team', $employee->currentEmployment?->team?->name ?? '-'],
                    ['Supervisor', $employee->currentEmployment?->supervisor?->full_name ?? '-'],
                    ['Employment Type', $employee->currentEmployment?->employment_type ?? '-'],
                    ['Employment Status', $employee->currentEmployment?->employment_status ?? '-'],
                    ['Tanggal Mulai', optional($employee->currentEmployment?->start_date)->format('d M Y') ?: '-'],
                ] as [$label, $value])
                    <div class="border-b border-slate-100 py-3">
                        <dt class="text-xs font-medium text-slate-400">{{ $label }}</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-slate-900">Akun & Login</h2>
                <p class="mt-1 text-sm text-slate-500">Akses employee ke SWMS dan identitas login.</p>
            </div>
            <span class="inline-flex w-fit items-center gap-2 rounded-full border {{ ($employee->user?->is_active ?? false) ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }} px-2.5 py-1 text-xs font-semibold">
                <span class="h-2 w-2 rounded-full {{ ($employee->user?->is_active ?? false) ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                Akun {{ ($employee->user?->is_active ?? false) ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        <div class="grid gap-x-6 px-6 py-2 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['Username', $employee->user?->username ?? '-'],
                ['Login Email', $employee->user?->email ?? '-'],
                ['Company Code', $employee->company?->code ?? '-'],
                ['Last Login', optional($employee->user?->last_login_at)->format('d M Y H:i') ?: '-'],
            ] as [$label, $value])
                <div class="border-b border-slate-100 py-3 lg:border-b-0">
                    <p class="text-xs font-medium text-slate-400">{{ $label }}</p>
                    <p class="mt-1 truncate text-sm font-semibold text-slate-800">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================= Rekap HR Employee ================= --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" id="employee-hr-recap">
        <div class="border-b border-slate-100 px-6 py-5">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <i data-lucide="chart-no-axes-combined" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Rekap HR Employee</h2>
                            <p class="mt-0.5 text-sm text-slate-500">Attendance dan assignment berdasarkan Work Calendar perusahaan.</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700 ring-1 ring-blue-100">
                            Work Calendar Company
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2" id="hr-period-tabs">
                    @foreach([
                        ['today', 'Hari Ini'],
                        ['month', 'Bulan'],
                        ['range', 'Rentang Bulan'],
                        ['year', 'Tahun'],
                    ] as [$value, $label])
                        <button type="button" data-period="{{ $value }}" class="hr-period-btn rounded-xl px-3.5 py-2 text-sm font-semibold transition">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                <div class="grid gap-3 lg:grid-cols-[1fr_auto] lg:items-end">
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div id="hr-field-month">
                            <label for="hr-month" class="mb-1.5 block text-xs font-semibold text-slate-500">Pilih Bulan</label>
                            <input id="hr-month" type="month" value="{{ now()->format('Y-m') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div id="hr-field-from" class="hidden">
                            <label for="hr-from" class="mb-1.5 block text-xs font-semibold text-slate-500">Dari Bulan</label>
                            <input id="hr-from" type="month" value="{{ now()->copy()->subMonths(2)->format('Y-m') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div id="hr-field-to" class="hidden">
                            <label for="hr-to" class="mb-1.5 block text-xs font-semibold text-slate-500">Sampai Bulan</label>
                            <input id="hr-to" type="month" value="{{ now()->format('Y-m') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div id="hr-field-year" class="hidden">
                            <label for="hr-year" class="mb-1.5 block text-xs font-semibold text-slate-500">Pilih Tahun</label>
                            <select id="hr-year" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                                @for($year = now()->year; $year >= now()->year - 7; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="sm:col-span-2 lg:col-span-1">
                            <p class="mb-1.5 text-xs font-semibold text-slate-500">Periode Aktif</p>
                            <div id="hr-range-label" class="flex min-h-[42px] items-center rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700">-</div>
                        </div>
                    </div>
                    <button id="hr-apply" type="button" class="inline-flex h-[42px] items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700">
                        <i data-lucide="filter" class="h-4 w-4"></i>
                        Terapkan
                    </button>
                </div>
            </div>
        </div>

        <div id="hr-loading" class="hidden px-6 py-10 text-center">
            <div class="mx-auto h-7 w-7 animate-spin rounded-full border-2 border-slate-200 border-t-blue-600"></div>
            <p class="mt-3 text-sm text-slate-500">Memuat rekap employee...</p>
        </div>

        <div id="hr-error" class="hidden px-6 py-6">
            <div class="rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                <div class="flex items-start gap-3">
                    <i data-lucide="circle-alert" class="mt-0.5 h-5 w-5 shrink-0"></i>
                    <div><p class="font-semibold">Rekap gagal dimuat</p><p id="hr-error-message" class="mt-1 text-red-600">Silakan coba lagi.</p></div>
                </div>
            </div>
        </div>

        <div id="hr-content" class="space-y-6 p-6">
            {{-- Attendance: mirror mobile HR recap --}}
            <div>
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <p id="hr-period-title" class="text-sm font-bold text-slate-900">-</p>
                        <p class="mt-0.5 text-xs text-slate-500">Attendance</p>
                    </div>
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-[11px] font-semibold text-blue-700 ring-1 ring-blue-100">Work Calendar</span>
                </div>

                <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm ring-1 ring-blue-100">
                                <i data-lucide="user-check" class="h-5 w-5"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-800">Attendance Rate</p>
                                <p id="hr-attendance-caption" class="mt-0.5 text-xs text-slate-500">0/0 hari kerja hadir</p>
                            </div>
                        </div>
                        <p class="shrink-0 text-2xl font-bold text-blue-600"><span id="hr-attendance-rate">0</span>%</p>
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-8">
                    @foreach([
                        ['hr-attended', 'Hadir', 'text-blue-600'],
                        ['hr-present', 'Tepat', 'text-emerald-600'],
                        ['hr-late', 'Telat', 'text-amber-600'],
                        ['hr-absent', 'Absen', 'text-red-600'],
                        ['hr-leave', 'Leave', 'text-violet-600'],
                        ['hr-permission', 'Izin', 'text-blue-600'],
                        ['hr-overtime-time', 'Lembur', 'text-emerald-600'],
                        ['hr-work-time', 'Kerja', 'text-slate-700'],
                    ] as [$id, $label, $tone])
                        <div class="rounded-xl border border-slate-200 bg-white px-3 py-3 text-center shadow-sm shadow-slate-100/50">
                            <p id="{{ $id }}" class="text-base font-bold {{ $tone }}">0</p>
                            <p class="mt-1 text-[11px] font-medium text-slate-500">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[11px] font-medium text-slate-500">Ketepatan <span id="hr-punctuality-rate">0</span>%</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[11px] font-medium text-slate-500">Telat <span id="hr-late-time">0m</span></span>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[11px] font-medium text-slate-500">Pulang awal <span id="hr-early-time">0m</span></span>
                </div>
            </div>

            {{-- Assignment: mirror mobile HR recap --}}
            <div class="border-t border-slate-100 pt-6">
                <p class="mb-3 text-sm font-bold text-slate-900">Assignment</p>
                <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm ring-1 ring-blue-100">
                                <i data-lucide="clipboard-check" class="h-5 w-5"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-800">Completion Rate</p>
                                <p id="hr-assignment-caption" class="mt-0.5 text-xs text-slate-500">0/0 assignment selesai</p>
                            </div>
                        </div>
                        <p class="shrink-0 text-2xl font-bold text-blue-600"><span id="hr-completion-rate">0</span>%</p>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[11px] font-semibold text-emerald-700">Approved <span id="hr-assignment-approved">0</span></span>
                    <span class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700">Progress <span id="hr-assignment-progress">0</span></span>
                    <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-[11px] font-semibold text-amber-700">Review <span id="hr-assignment-pending">0</span></span>
                    <span class="rounded-full border border-violet-200 bg-violet-50 px-3 py-1.5 text-[11px] font-semibold text-violet-700">Revisi <span id="hr-assignment-revision">0</span></span>
                    <span class="rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-[11px] font-semibold text-red-700">Rejected <span id="hr-assignment-rejected">0</span></span>
                    <span class="rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-[11px] font-semibold text-red-700">Not Worked <span id="hr-assignment-not-worked">0</span></span>
                    <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-[11px] font-semibold text-amber-700">Late Revision <span id="hr-assignment-late-revision">0</span></span>
                </div>
                <span id="hr-assignment-total" class="hidden">0</span>
                <span id="hr-assignment-completed" class="hidden">0</span>
            </div>

            <div class="grid gap-5 border-t border-slate-100 pt-6 xl:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h3 class="text-sm font-bold text-slate-900">Grafik Attendance</h3>
                        <span id="hr-chart-granularity" class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-500">-</span>
                    </div>
                    <div class="relative h-56"><canvas id="hrAttendanceChart"></canvas></div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="mb-4"><h3 class="text-sm font-bold text-slate-900">Grafik Assignment Selesai</h3></div>
                    <div class="relative h-56"><canvas id="hrAssignmentChart"></canvas></div>
                </div>
            </div>

            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2"><i data-lucide="download" class="h-4 w-4 text-blue-600"></i><p class="text-sm font-bold text-slate-800">Download Rekap HR</p></div>
                    <p class="mt-1 text-xs text-slate-500">PDF untuk dokumen HR; Excel untuk pengolahan data lanjutan.</p>
                    @unless($isPremium)
                        <p class="mt-1 text-xs font-semibold text-blue-600">Premium Go+</p>
                    @endunless
                </div>
                <div class="flex flex-wrap gap-2">
                    @if($isPremium)
                        <a id="hr-export-pdf" href="#" class="inline-flex min-w-28 items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            <i data-lucide="file-text" class="h-4 w-4"></i> PDF
                        </a>
                        <a id="hr-export-excel" href="#" class="inline-flex min-w-28 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800">
                            <i data-lucide="table-2" class="h-4 w-4"></i> Excel
                        </a>
                    @else
                        <a href="{{ route('subscription.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                            <i data-lucide="lock" class="h-4 w-4"></i> Upgrade Premium Go
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>


</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const baseUrl = @json(route('employees.performance', $employee));
    const pdfBaseUrl = @json(route('employees.performance.export.pdf', $employee));
    const excelBaseUrl = @json(route('employees.performance.export.excel', $employee));
    const isPremium = @json((bool) $isPremium);
    const serverToday = @json(now()->toDateString());
    const serverMonth = @json(now()->format('Y-m'));
    const serverYear = @json(now()->year);

    const tabs = [...document.querySelectorAll('.hr-period-btn')];
    const monthField = document.getElementById('hr-field-month');
    const fromField = document.getElementById('hr-field-from');
    const toField = document.getElementById('hr-field-to');
    const yearField = document.getElementById('hr-field-year');
    const monthInput = document.getElementById('hr-month');
    const fromInput = document.getElementById('hr-from');
    const toInput = document.getElementById('hr-to');
    const yearInput = document.getElementById('hr-year');
    const applyButton = document.getElementById('hr-apply');
    const loading = document.getElementById('hr-loading');
    const content = document.getElementById('hr-content');
    const errorBox = document.getElementById('hr-error');
    const errorMessage = document.getElementById('hr-error-message');
    const rangeLabel = document.getElementById('hr-range-label');
    const pdfLink = document.getElementById('hr-export-pdf');
    const excelLink = document.getElementById('hr-export-excel');

    let activePeriod = 'month';
    let attendanceChart = null;
    let assignmentChart = null;

    const activeClasses = ['bg-blue-600', 'text-white', 'shadow-sm'];
    const inactiveClasses = ['bg-slate-100', 'text-slate-600', 'hover:bg-slate-200'];

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value ?? 0;
    }

    function formatMinutes(value) {
        return `${Number(value || 0)}m`;
    }

    function monthLabel(ym) {
        if (!ym) return '-';
        const [year, month] = ym.split('-').map(Number);
        return new Intl.DateTimeFormat('id-ID', { month: 'long', year: 'numeric' }).format(new Date(year, month - 1, 1));
    }

    function currentQuery() {
        const p = new URLSearchParams({ period: activePeriod });
        if (activePeriod === 'month') p.set('month', monthInput.value || serverMonth);
        if (activePeriod === 'range') {
            p.set('from', fromInput.value || serverMonth);
            p.set('to', toInput.value || serverMonth);
        }
        if (activePeriod === 'year') p.set('year', yearInput.value || serverYear);
        return p;
    }

    function updateRangeLabel() {
        let label = '';
        if (activePeriod === 'today') {
            label = new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }).format(new Date(`${serverToday}T00:00:00`));
        } else if (activePeriod === 'month') {
            label = monthLabel(monthInput.value || serverMonth);
        } else if (activePeriod === 'range') {
            label = `${monthLabel(fromInput.value || serverMonth)} — ${monthLabel(toInput.value || serverMonth)}`;
        } else {
            label = yearInput.value || serverYear;
        }
        rangeLabel.textContent = label;
    }

    function setPeriod(period) {
        activePeriod = period;
        tabs.forEach(btn => {
            const active = btn.dataset.period === period;
            btn.classList.remove(...activeClasses, ...inactiveClasses);
            btn.classList.add(...(active ? activeClasses : inactiveClasses));
        });
        monthField.classList.toggle('hidden', period !== 'month');
        fromField.classList.toggle('hidden', period !== 'range');
        toField.classList.toggle('hidden', period !== 'range');
        yearField.classList.toggle('hidden', period !== 'year');
        updateRangeLabel();
    }

    function updateExportLinks() {
        if (!isPremium) return;
        const query = currentQuery().toString();
        if (pdfLink) pdfLink.href = `${pdfBaseUrl}?${query}`;
        if (excelLink) excelLink.href = `${excelBaseUrl}?${query}`;
    }

    function destroyCharts() {
        attendanceChart?.destroy();
        assignmentChart?.destroy();
        attendanceChart = null;
        assignmentChart = null;
    }

    function renderCharts(chart) {
        if (typeof Chart === 'undefined') return;
        const points = chart?.points || [];
        const labels = points.map(row => row.label);
        const attended = points.map(row => Number(row.attendance_present || 0) + Number(row.attendance_late || 0));
        const completed = points.map(row => Number(row.assignment_completed || 0));
        setText('hr-chart-granularity', chart?.granularity === 'daily' ? 'Harian' : 'Bulanan');
        destroyCharts();

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false }, ticks: { autoSkip: true, maxTicksLimit: 10 } },
            },
            plugins: { legend: { labels: { usePointStyle: true, boxWidth: 8 } } },
        };

        attendanceChart = new Chart(document.getElementById('hrAttendanceChart'), {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Attendance', data: attended, backgroundColor: 'rgba(37,99,235,.90)', borderRadius: 4, maxBarThickness: 18 }] },
            options: { ...commonOptions, plugins: { ...commonOptions.plugins, legend: { display: false } } },
        });

        assignmentChart = new Chart(document.getElementById('hrAssignmentChart'), {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Assignment Selesai', data: completed, backgroundColor: 'rgba(22,163,74,.90)', borderRadius: 4, maxBarThickness: 18 }] },
            options: { ...commonOptions, plugins: { ...commonOptions.plugins, legend: { display: false } } },
        });
    }

    function renderSummary(data) {
        const a = data.attendance_summary || {};
        const s = data.assignment_summary || {};
        setText('hr-attended', a.attended);
        setText('hr-present', a.present);
        setText('hr-late', a.late);
        setText('hr-leave', a.leave);
        setText('hr-permission', a.permission);
        setText('hr-absent', a.absent);
        setText('hr-attendance-rate', a.attendance_rate);
        setText('hr-punctuality-rate', a.punctuality_rate);
        setText('hr-work-time', formatMinutes(a.work_minutes));
        setText('hr-late-time', formatMinutes(a.late_minutes));
        setText('hr-early-time', formatMinutes(a.early_leave_minutes));
        setText('hr-overtime-time', formatMinutes(a.overtime_minutes));
        setText('hr-attendance-caption', `${a.attended || 0}/${a.working_days || 0} hari kerja hadir`);
        setText('hr-period-title', rangeLabel.textContent || '-');

        setText('hr-assignment-total', s.total);
        setText('hr-assignment-completed', s.completed);
        setText('hr-assignment-progress', s.in_progress);
        setText('hr-assignment-approved', s.approved);
        setText('hr-assignment-pending', s.pending_review);
        setText('hr-assignment-revision', s.needs_revision);
        setText('hr-assignment-rejected', s.rejected);
        setText('hr-assignment-not-worked', s.not_worked);
        setText('hr-assignment-late-revision', s.late_revision);
        setText('hr-completion-rate', s.completion_rate);
        setText('hr-assignment-caption', `${s.completed || 0}/${s.total || 0} assignment selesai`);
        renderCharts(data.chart || {});
    }

    async function loadRecap() {
        updateRangeLabel();
        updateExportLinks();
        loading.classList.remove('hidden');
        errorBox.classList.add('hidden');
        content.classList.add('opacity-50', 'pointer-events-none');
        try {
            const response = await fetch(`${baseUrl}?${currentQuery().toString()}`, { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error(`Server mengembalikan status ${response.status}.`);
            renderSummary(await response.json());
        } catch (error) {
            errorMessage.textContent = error?.message || 'Silakan coba lagi.';
            errorBox.classList.remove('hidden');
        } finally {
            loading.classList.add('hidden');
            content.classList.remove('opacity-50', 'pointer-events-none');
            if (window.lucide) window.lucide.createIcons();
        }
    }

    tabs.forEach(btn => btn.addEventListener('click', () => { setPeriod(btn.dataset.period); loadRecap(); }));
    applyButton.addEventListener('click', loadRecap);
    [monthInput, fromInput, toInput, yearInput].forEach(el => el?.addEventListener('change', updateRangeLabel));

    setPeriod('month');
    loadRecap();
});
</script>
@endpush


@endsection
