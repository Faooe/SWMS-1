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

            <div class="flex flex-wrap items-center gap-3">

                <label class="text-sm font-semibold text-slate-600">
                    Dari
                </label>

                <input
                    type="month"
                    id="performance-from"
                    value="{{ now()->format('Y-m') }}"
                    class="rounded-xl border border-slate-300 px-3 py-2 text-sm">

                <label class="text-sm font-semibold text-slate-600">
                    Sampai
                </label>

                <input
                    type="month"
                    id="performance-to"
                    value="{{ now()->format('Y-m') }}"
                    class="rounded-xl border border-slate-300 px-3 py-2 text-sm">

                <button
                    type="button"
                    id="performance-apply"
                    class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">

                    Terapkan

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
                <h3 class="text-sm font-semibold text-slate-600">Trend per Bulan</h3>
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

        {{-- Export --}}
        <div class="mt-8 flex flex-wrap gap-3">

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

        <p class="mt-3 text-xs text-slate-400">
            Export mengikuti rentang tanggal yang dipilih di atas & berisi ringkasan per bulan
            beserta detail attendance dan assignment yang diselesaikan.
        </p>

    </x-ui.card>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const fromInput = document.getElementById('performance-from');
    const toInput = document.getElementById('performance-to');
    const applyBtn = document.getElementById('performance-apply');
    const pdfLink = document.getElementById('performance-export-pdf');
    const excelLink = document.getElementById('performance-export-excel');
    const canvas = document.getElementById('performanceChart');

    const baseUrl = @json(route('employees.performance', $employee));
    const pdfBaseUrl = @json(route('employees.performance.export.pdf', $employee));
    const excelBaseUrl = @json(route('employees.performance.export.excel', $employee));

    let chartInstance = null;

    function updateExportLinks() {
        const query = `?from=${fromInput.value}&to=${toInput.value}`;
        pdfLink.href = pdfBaseUrl + query;
        // excelLink bisa null kalau company belum Premium (tombol
        // diganti <span> terkunci di blade kalau company belum Premium.
        if (excelLink) {
            excelLink.href = excelBaseUrl + query;
        }
    }

    function renderChart(labels, attendanceData, assignmentData) {

        if (typeof Chart === 'undefined' || !canvas) {
            return;
        }

        chartInstance?.destroy();

        // Gaya sama seperti "Attendance Trend" di Dashboard (line chart,
        // smooth curve/tension .4) -- dataset Attendance dikasih
        // gradient fill sebagai garis utama, Assignment Selesai garis
        // solid tanpa fill supaya dua-duanya tetap gampang dibedakan.
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
                        pointRadius: 4,
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
                        pointRadius: 4,
                        tension: .4,
                        fill: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                },
                plugins: {
                    legend: { display: false },
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

            renderChart(
                data.monthly.map(row => row.label),
                data.monthly.map(row => row.attendance_total),
                data.monthly.map(row => row.assignment_completed)
            );

        } catch (error) {
            console.error(error);
        }

    }

    applyBtn.addEventListener('click', loadPerformance);

    loadPerformance();

});
</script>
@endpush

@endsection