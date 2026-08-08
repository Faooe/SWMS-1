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
    <div
        class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

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

        {{-- Stat Cards --}}
        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl border bg-slate-50 p-5">
                <p class="text-sm text-slate-500">Total Attendance</p>
                <h3 id="perf-attendance-total" class="mt-2 text-2xl font-bold text-slate-800">-</h3>
            </div>

            <div class="rounded-2xl border bg-slate-50 p-5">
                <p class="text-sm text-slate-500">Present</p>
                <h3 id="perf-attendance-present" class="mt-2 text-2xl font-bold text-green-600">-</h3>
            </div>

            <div class="rounded-2xl border bg-slate-50 p-5">
                <p class="text-sm text-slate-500">Late</p>
                <h3 id="perf-attendance-late" class="mt-2 text-2xl font-bold text-amber-600">-</h3>
            </div>

            <div class="rounded-2xl border bg-blue-50 p-5">
                <p class="text-sm text-blue-600">Assignment Selesai</p>
                <h3 id="perf-assignment-completed" class="mt-2 text-2xl font-bold text-blue-700">-</h3>
            </div>

        </div>

        {{-- Chart --}}
        <div class="relative mt-8 h-72 w-full">
            <canvas id="performanceChart"></canvas>
        </div>

        {{-- Export --}}
        <div class="mt-8 flex flex-wrap gap-3">

            <a
                id="performance-export-pdf"
                href="{{ route('employees.performance.export.pdf', $employee) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-700">

                <i data-lucide="file-text" class="h-5 w-5"></i>

                Export PDF

            </a>

            <a
                id="performance-export-excel"
                href="{{ route('employees.performance.export.excel', $employee) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 font-semibold text-white hover:bg-emerald-700">

                <i data-lucide="table" class="h-5 w-5"></i>

                Export Excel

            </a>

        </div>

        <p class="mt-3 text-xs text-slate-400">
            Export mengikuti rentang tanggal yang dipilih di atas & berisi ringkasan per bulan
            beserta detail attendance dan assignment yang diselesaikan.
        </p>

    </div>

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
        excelLink.href = excelBaseUrl + query;
    }

    function renderChart(labels, attendanceData, assignmentData) {

        if (typeof Chart === 'undefined' || !canvas) {
            return;
        }

        chartInstance?.destroy();

        chartInstance = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Attendance',
                        data: attendanceData,
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                    },
                    {
                        label: 'Assignment Selesai',
                        data: assignmentData,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
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
                    legend: { display: true, position: 'top' },
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