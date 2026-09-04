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

    {{-- ================= Performance ================= --}}
    <x-ui.card>

        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">

            <div>

                <h2 class="text-xl font-bold">
                    Performance
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Ringkasan attendance & assignment yang diselesaikan per bulan.
                </p>

            </div>

            <div class="flex flex-wrap items-center gap-2" id="performance-range-buttons">

                <input type="hidden" id="performance-from" value="{{ now()->format('Y-m') }}">
                <input type="hidden" id="performance-to" value="{{ now()->format('Y-m') }}">

                <button type="button" data-range="month" class="perf-range-btn rounded-xl px-4 py-2 text-sm font-semibold transition">
                    Bulan Ini
                </button>

                <button type="button" data-range="3" class="perf-range-btn rounded-xl px-4 py-2 text-sm font-semibold transition">
                    3 Bulan Terakhir
                </button>

            </div>

        </div>

        {{-- Ringkasan performance dalam satu surface agar tidak menjadi tumpukan stat-card. --}}
        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/60">
            <div class="grid grid-cols-2 divide-x divide-y divide-slate-200 lg:grid-cols-4 lg:divide-y-0">
                <div class="px-4 py-4">
                    <p class="text-xs font-medium text-slate-400">Total Attendance</p>
                    <p id="perf-attendance-total" class="mt-1 text-2xl font-bold text-slate-900">0</p>
                </div>
                <div class="px-4 py-4">
                    <p class="text-xs font-medium text-slate-400">Hadir</p>
                    <p id="perf-attendance-present" class="mt-1 text-2xl font-bold text-slate-900">0</p>
                </div>
                <div class="px-4 py-4">
                    <p class="text-xs font-medium text-slate-400">Terlambat</p>
                    <p id="perf-attendance-late" class="mt-1 text-2xl font-bold text-slate-900">0</p>
                </div>
                <div class="px-4 py-4">
                    <p class="text-xs font-medium text-slate-400">Assignment Selesai</p>
                    <p id="perf-assignment-completed" class="mt-1 text-2xl font-bold text-blue-600">0</p>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Review Assignment</p>
            <div class="grid grid-cols-2 overflow-hidden rounded-2xl border border-slate-200 sm:grid-cols-3 lg:grid-cols-6" id="performance-review-chips">
                @foreach([
                    ['perf-review-approved', 'Approved'],
                    ['perf-review-pending', 'Pending Review'],
                    ['perf-review-needs-revision', 'Needs Revision'],
                    ['perf-review-expired', 'Expired'],
                    ['perf-review-late', 'Late'],
                    ['perf-review-rejected', 'Rejected'],
                ] as [$id, $label])
                    <div class="border-b border-r border-slate-100 px-3 py-3 last:border-r-0 lg:border-b-0">
                        <p id="{{ $id }}" class="text-lg font-bold text-slate-900">0</p>
                        <p class="mt-0.5 text-[11px] font-medium text-slate-500">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Chart -- gaya sama seperti "Attendance Trend" di Dashboard
        (line chart, smooth curve, gradient fill) --}}
        <div class="mt-8">

            <div class="mb-4 flex items-center justify-between">
                <h3 id="performance-chart-title" class="text-sm font-semibold text-slate-600">Trend</h3>
                <div class="flex items-center gap-4 text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                        Attendance
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-green-600"></span>
                        Assignment Selesai
                    </span>
                </div>
            </div>

            <div class="relative h-72 w-full">
                <canvas id="performanceChart"></canvas>
            </div>

        </div>

        {{-- Export -- pilih rentang laporan (1 atau 3 bulan terakhir),
        terpisah dari rentang tombol grafik di atas --}}
        <div class="mt-8">

            <div class="mb-3 flex items-center gap-2" id="performance-export-range-buttons">

                <span class="text-xs font-semibold text-slate-500">Rentang Laporan:</span>

                <button type="button" data-export-months="1" class="perf-export-range-btn rounded-lg px-3 py-1.5 text-xs font-semibold transition">
                    1 Bulan Terakhir
                </button>

                <button type="button" data-export-months="3" class="perf-export-range-btn rounded-lg px-3 py-1.5 text-xs font-semibold transition">
                    3 Bulan Terakhir
                </button>

            </div>

            <div class="flex flex-wrap gap-3">

                <a
                    id="performance-export-pdf"
                    href="{{ route('employees.performance.export.pdf', $employee) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">

                    <i data-lucide="file-text" class="h-4 w-4"></i>

                    Export PDF

                </a>

                @if($isPremium)

                    <a
                        id="performance-export-excel"
                        href="{{ route('employees.performance.export.excel', $employee) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-700">

                        <i data-lucide="file-spreadsheet" class="h-4 w-4"></i>

                        Export Excel

                    </a>

                @else

                    <span
                        title="Upgrade ke paket Premium untuk export Excel"
                        class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-400">

                        <i data-lucide="lock" class="h-4 w-4"></i>

                        Export Excel (Premium)

                    </span>

                @endif

            </div>

        </div>

        <p class="mt-3 text-xs text-slate-400">
            Export berisi ringkasan per bulan beserta detail attendance dan assignment yang
            diselesaikan, sesuai rentang laporan yang dipilih di atas.
        </p>

    </x-ui.card>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const fromInput = document.getElementById('performance-from');
    const toInput = document.getElementById('performance-to');
    const rangeButtons = document.querySelectorAll('.perf-range-btn');
    const exportRangeButtons = document.querySelectorAll('.perf-export-range-btn');
    const pdfLink = document.getElementById('performance-export-pdf');
    const excelLink = document.getElementById('performance-export-excel');
    const canvas = document.getElementById('performanceChart');

    const baseUrl = @json(route('employees.performance', $employee));
    const pdfBaseUrl = @json(route('employees.performance.export.pdf', $employee));
    const excelBaseUrl = @json(route('employees.performance.export.excel', $employee));

    // Bulan berjalan versi SERVER (bukan `new Date()` di browser) --
    // supaya perhitungan rentang "3 Bulan Terakhir" konsisten dengan
    // default bulan yang dipakai backend, tidak tergantung
    // timezone/jam di perangkat user.
    const nowYm = @json(now()->format('Y-m'));

    let chartInstance = null;
    // Default grafik: 3 Bulan Terakhir (bukan "Bulan Ini"/harian) --
    // grafik harian fetch ~30 baris data per hari (lebih berat & lebih
    // lambat dimuat), dan label tanggalnya gampang numpuk/tabrakan di
    // layar sempit (mobile). 3 Bulan cuma 3 titik data bulanan: tetap
    // cepat dimuat & label bulannya jelas terbaca, tapi user masih bisa
    // pilih "Bulan Ini" manual kalau butuh detail harian.
    let activeRange = '3';
    // Default rentang laporan export: 1 Bulan Terakhir.
    let activeExportMonths = '1';

    function shiftMonth(ym, delta) {
        const [y, m] = ym.split('-').map(Number);
        const d = new Date(y, (m - 1) + delta, 1);
        return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`;
    }

    const RANGE_ACTIVE = ['bg-blue-600', 'text-white'];
    const RANGE_INACTIVE = ['bg-slate-100', 'text-slate-600', 'hover:bg-slate-200'];

    function setActiveButton(range) {
        rangeButtons.forEach((btn) => {
            const isActive = btn.dataset.range === range;
            btn.classList.remove(...RANGE_ACTIVE, ...RANGE_INACTIVE);
            btn.classList.add(...(isActive ? RANGE_ACTIVE : RANGE_INACTIVE));
        });
    }

    function setActiveExportButton(months) {
        exportRangeButtons.forEach((btn) => {
            const isActive = btn.dataset.exportMonths === months;
            btn.classList.remove(...RANGE_ACTIVE, ...RANGE_INACTIVE);
            btn.classList.add(...(isActive ? RANGE_ACTIVE : RANGE_INACTIVE));
        });
    }

    // "Bulan Ini" -> from = to = bulan berjalan (backend otomatis
    // pecah jadi grafik HARIAN kalau from & to bulan yang sama).
    // "3 Bulan Terakhir" -> mundur 2 bulan dari bulan berjalan, jadi
    // selalu dapat 3 titik data bulanan -- tidak akan pernah cuma dapat
    // 2 titik yang bikin grafik kelihatan kosong/rata.
    function applyRange(range) {
        activeRange = range;
        const monthsBack = range === 'month' ? 0 : Number(range) - 1;
        fromInput.value = shiftMonth(nowYm, -monthsBack);
        toInput.value = nowYm;
        setActiveButton(range);
        loadPerformance();
    }

    // Rentang laporan EXPORT (1/3 Bulan Terakhir) SENGAJA terpisah dari
    // rentang tombol grafik (Bulan Ini/3 Bulan Terakhir) di atas -- user
    // bisa saja lagi lihat grafik harian bulan ini, tapi tetap mau
    // export laporan 3 bulan terakhir, atau sebaliknya.
    function applyExportRange(months) {
        activeExportMonths = months;
        setActiveExportButton(months);
        updateExportLinks();
    }

    function updateExportLinks() {
        const query = `?months=${activeExportMonths}`;
        pdfLink.href = pdfBaseUrl + query;
        // excelLink bisa null kalau company belum Premium (tombolnya
        // diganti <span> terkunci di blade kalau company belum Premium).
        if (excelLink) {
            excelLink.href = excelBaseUrl + query;
        }
    }

    function renderChart(labels, attendanceData, assignmentData) {

        if (typeof Chart === 'undefined' || !canvas) {
            return;
        }

        chartInstance?.destroy();

        const maxValue = Math.max(1, ...attendanceData, ...assignmentData);

        // Sama persis gaya "Attendance Trend" di Dashboard (line chart,
        // garis melengkung + gradient fill). Bedanya cuma
        // `cubicInterpolationMode: 'monotone'` -- ini mode interpolasi
        // Chart.js yang menjamin kurva tidak pernah "meluap"
        // (overshoot) melebihi nilai tetangganya. Attendance Trend di
        // Dashboard selalu 7 titik data harian yang naik-turun halus
        // jadi kurva default (bezier biasa) sudah mulus & aman. Tapi di
        // sini titiknya bisa naik-turun tajam -- Attendance biasanya
        // cuma 0/1 per hari (hadir/tidak, satu record per hari),
        // sementara Assignment Selesai bisa 0 sampai beberapa sekaligus
        // kalau beberapa assignment selesai di hari yang sama -- atau
        // cuma 3/6/12 titik total kalau lagi lihat rentang bulanan.
        // Kurva bezier biasa di kondisi begitu suka "menggelembung"/dip
        // di bawah 0 di antara titik yang datar. Mode monotone
        // menghilangkan efek itu tapi tetap melengkung, jadi hasilnya
        // tetap mulus & rapi apa pun pola datanya.
        chartInstance = new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Attendance',
                        data: attendanceData,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: labels.length > 20 ? 2 : 4,
                        pointHoverRadius: 6,
                        cubicInterpolationMode: 'monotone',
                        tension: .4,
                        fill: true,
                    },
                    {
                        label: 'Assignment Selesai',
                        data: assignmentData,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#16a34a',
                        pointRadius: labels.length > 20 ? 2 : 4,
                        pointHoverRadius: 6,
                        cubicInterpolationMode: 'monotone',
                        tension: .4,
                        fill: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        // Beri sedikit ruang di atas titik tertinggi
                        // supaya garis/area tidak mepet ke pinggir atas.
                        suggestedMax: Math.ceil(maxValue * 1.2),
                        ticks: { precision: 0 },
                        grid: { color: '#f1f5f9' },
                    },
                    x: {
                        // offset: true -- titik pertama & terakhir
                        // digeser sedikit ke dalam, nggak nempel persis
                        // di ujung kiri/kanan chart. Bikin grafik dengan
                        // sedikit titik (mis. cuma 2 bulan) tetap
                        // terlihat "diisi" dengan baik, bukan cuma
                        // garis lurus yang dipepetkan ke ujung-ujung.
                        offset: true,
                        grid: { display: false },
                        ticks: {
                            // Grafik harian bisa sampai 31 titik --
                            // autoSkip + maxTicksLimit supaya label
                            // tanggal di sumbu X tidak numpuk/tumpang
                            // tindih (grafik bulanan biasanya cuma
                            // sedikit titik jadi tidak kena skip).
                            autoSkip: true,
                            maxTicksLimit: 10,
                        },
                    },
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 8,
                    },
                },
            },
        });
    }

    async function loadPerformance() {

        updateExportLinks();

        try {

            const response = await fetch(
                `${baseUrl}?from=${fromInput.value}&to=${toInput.value}`,
                { headers: { 'Accept': 'application/json' } }
            );

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            document.getElementById('perf-attendance-total').innerText = data.summary.attendance_total;
            document.getElementById('perf-attendance-present').innerText = data.summary.attendance_present;
            document.getElementById('perf-attendance-late').innerText = data.summary.attendance_late;
            document.getElementById('perf-assignment-completed').innerText = data.summary.assignment_completed;

            const review = data.review_summary || {};
            document.getElementById('perf-review-approved').innerText = review.approved ?? 0;
            document.getElementById('perf-review-pending').innerText = review.pending_review ?? 0;
            document.getElementById('perf-review-needs-revision').innerText = review.needs_revision ?? 0;
            document.getElementById('perf-review-expired').innerText = review.expired ?? 0;
            document.getElementById('perf-review-late').innerText = review.late_revision_count ?? 0;
            document.getElementById('perf-review-rejected').innerText = review.rejected ?? 0;

            document.getElementById('performance-chart-title').innerText =
                data.chart.granularity === 'daily' ? 'Trend Harian' : 'Trend per Bulan';

            renderChart(
                data.chart.points.map(row => row.label),
                data.chart.points.map(row => row.attendance_total),
                data.chart.points.map(row => row.assignment_completed)
            );

        } catch (error) {
            console.error(error);
        }

    }

    rangeButtons.forEach((btn) => {
        btn.addEventListener('click', () => applyRange(btn.dataset.range));
    });

    exportRangeButtons.forEach((btn) => {
        btn.addEventListener('click', () => applyExportRange(btn.dataset.exportMonths));
    });

    applyExportRange(activeExportMonths);
    applyRange(activeRange);

});
</script>
@endpush

@endsection
