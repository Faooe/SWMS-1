@extends('layouts.app')

@section('title','Employee Detail')

@section('page-title','Employee Detail')

@section('content')

<div class="space-y-8">

    {{-- ================= Header ================= --}}
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <div
            class="flex flex-col justify-between gap-6 lg:flex-row lg:items-center">

            <div class="flex items-center gap-6">

                <div>
                    <x-ui.avatar :employee="$employee" size="24"/>
                </div>

                <div>

                    <h1
                        class="text-3xl font-bold text-slate-800">

                        {{ $employee->full_name }}

                    </h1>

                    <p class="mt-2 text-slate-500">

                        Employee Number :
                        <strong>{{ $employee->employee_number }}</strong>

                    </p>

                    <div class="mt-4">

                        @if($employee->is_active)

                            <span
                                class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

                                Active

                            </span>

                        @else

                            <span
                                class="rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-700">

                                Inactive

                            </span>

                        @endif

                    </div>

                </div>

            </div>

            <div class="flex gap-3">

                <a
                    href="{{ route('employees.edit',$employee) }}"
                    class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">

                    Edit Employee

                </a>

                <a
                    href="{{ route('employees.index') }}"
                    class="rounded-xl border border-slate-300 px-6 py-3 font-semibold hover:bg-slate-100">

                    Back

                </a>

            </div>

        </div>

    </div>

    {{-- ================= Personal ================= --}}
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <h2
            class="mb-8 text-xl font-bold">

            Personal Information

        </h2>

        <div class="grid gap-6 md:grid-cols-2">

            <x-ui.detail-item
                label="Employee Number"
                :value="$employee->employee_number"/>

            <x-ui.detail-item
                label="Full Name"
                :value="$employee->full_name"/>

            <x-ui.detail-item
                label="Email"
                :value="$employee->email"/>

            <x-ui.detail-item
                label="Phone"
                :value="$employee->phone"/>

            <x-ui.detail-item
                label="Gender"
                :value="$employee->gender"/>

            <x-ui.detail-item
                label="Birth Place"
                :value="$employee->birth_place"/>

            <x-ui.detail-item
                label="Birth Date"
                :value="optional($employee->birth_date)->format('d M Y')"/>

            <x-ui.detail-item
                label="Marital Status"
                :value="$employee->marital_status"/>

            <x-ui.detail-item
                label="Emergency Contact"
                :value="$employee->emergency_contact_name"/>

            <x-ui.detail-item
                label="Emergency Phone"
                :value="$employee->emergency_contact_phone"/>

        </div>

        <div class="mt-6">

            <label
                class="mb-2 block text-sm font-semibold text-slate-500">

                Address

            </label>

            <div
                class="rounded-2xl border border-slate-200 bg-slate-50 p-4">

                {{ $employee->address ?: '-' }}

            </div>

        </div>

    </div>

    {{-- ================= Employment ================= --}}
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <h2
            class="mb-8 text-xl font-bold">

            Employment Information

        </h2>

        <div class="grid gap-6 md:grid-cols-2">

            <x-ui.detail-item
                label="Office"
                :value="$employee->currentEmployment?->office?->name"/>

            <x-ui.detail-item
                label="Department"
                :value="$employee->currentEmployment?->department?->name"/>

            <x-ui.detail-item
                label="Position"
                :value="$employee->currentEmployment?->position?->name"/>

            <x-ui.detail-item
                label="Team"
                :value="$employee->currentEmployment?->team?->name"/>

            <x-ui.detail-item
                label="Supervisor"
                :value="$employee->currentEmployment?->supervisor?->full_name"/>

            <x-ui.detail-item
                label="Employment Type"
                :value="$employee->currentEmployment?->employment_type"/>

            <x-ui.detail-item
                label="Employment Status"
                :value="$employee->currentEmployment?->employment_status"/>

            <x-ui.detail-item
                label="Start Date"
                :value="optional($employee->currentEmployment?->start_date)->format('d M Y')"/>

            <x-ui.detail-item
                label="End Date"
                :value="optional($employee->currentEmployment?->end_date)->format('d M Y')"/>

            <x-ui.detail-item
                label="Current Employment"
                :value="$employee->currentEmployment?->is_current ? 'Yes' : 'No'"/>

        </div>

    </div>

    {{-- ================= Account / Login ================= --}}
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <h2
            class="mb-1 text-xl font-bold">

            Account &amp; Login Information

        </h2>

        <p class="mb-8 text-sm text-slate-500">

            Employee ini bisa login pakai Email di bawah, atau pakai kombinasi
            NIP + Kode Company
            @if($employee->company)
                (<strong>{{ $employee->company->code }}</strong>)
            @endif
            .

        </p>

        <div class="grid gap-6 md:grid-cols-2">

            <x-ui.detail-item
                label="Username"
                :value="$employee->user->username ?? '-'"/>

            <x-ui.detail-item
                label="Login Email"
                :value="$employee->user->email ?? '-'"/>

            <x-ui.detail-item
                label="Employee Number (NIP)"
                :value="$employee->employee_number"/>

            @if($employee->company)

                <x-ui.detail-item
                    label="Company Code"
                    :value="$employee->company->code"/>

            @endif

            <x-ui.detail-item
                label="Account Status"
                :value="($employee->user->is_active ?? false) ? 'Active' : 'Inactive'"/>

            <x-ui.detail-item
                label="Last Login"
                :value="optional($employee->user->last_login_at ?? null)->format('d M Y H:i') ?: '-'"/>

        </div>

    </div>

    {{-- ================= Kuota Cuti ================= --}}
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

        <div class="flex flex-wrap items-center justify-between gap-4">

            <div>

                <h2 class="text-xl font-bold">
                    Kuota Cuti {{ $leaveQuota['year'] }}
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Sakit &amp; Acara tidak memotong kuota ini -- cuma Cuti.
                    Untuk menyesuaikan jatah, gunakan
                    <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs">PUT /api/v1/leave-quotas/{{ $employee->id }}</code>.
                </p>

            </div>

            <div class="flex items-center gap-8">

                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-600">{{ $leaveQuota['remaining_days'] }}</p>
                    <p class="text-xs text-slate-500">Sisa Hari</p>
                </div>

                <div class="text-center">
                    <p class="text-3xl font-bold text-slate-400">{{ $leaveQuota['used_days'] }}</p>
                    <p class="text-xs text-slate-500">Terpakai</p>
                </div>

                <div class="text-center">
                    <p class="text-3xl font-bold text-slate-400">{{ $leaveQuota['total_days'] }}</p>
                    <p class="text-xs text-slate-500">Total Jatah</p>
                </div>

            </div>

        </div>

    </div>

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

        {{-- Stat Cards -- konsisten dengan x-ui.stat-card yang dipakai
        di Dashboard/Assignment/Attendance --}}
        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">

            <x-ui.stat-card
                title="Total Attendance"
                value="0"
                icon="calendar-days"
                color="blue"
                valueId="perf-attendance-total"/>

            <x-ui.stat-card
                title="Present"
                value="0"
                icon="badge-check"
                color="green"
                valueId="perf-attendance-present"/>

            <x-ui.stat-card
                title="Late"
                value="0"
                icon="clock-3"
                color="amber"
                valueId="perf-attendance-late"/>

            <x-ui.stat-card
                title="Assignment Selesai"
                value="0"
                icon="clipboard-check"
                color="purple"
                valueId="perf-assignment-completed"/>

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