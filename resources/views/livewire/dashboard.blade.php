<div wire:poll.30s>

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>

        <p class="mt-2 text-slate-500">
            Selamat datang kembali,
            <strong>{{ auth()->user()->employee?->full_name ?? auth()->user()->username }}</strong>
        </p>
    </div>

    {{-- Statistics --}}
    <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">

        <x-dashboard.stat-card title="Employee" :value="$total_employee" icon="users" />
        <x-dashboard.stat-card title="Attendance" :value="$attendance_today" icon="calendar-check" />
        <x-dashboard.stat-card title="Late" :value="$late_today" icon="clock-3" />
        <x-dashboard.stat-card title="Assignment" :value="$active_assignment" icon="clipboard-list" />

    </div>

    {{-- Chart --}}
    <div class="mt-8">
        <x-ui.card>

            <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-xl font-bold">Attendance & Assignment Trend</h2>
                    <p class="text-sm text-slate-500">Last 7 Days &middot; auto-refresh setiap 30 detik</p>
                </div>
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
                <canvas
                    id="attendanceChart"
                    data-labels="{{ json_encode($attendance_chart['labels'] ?? []) }}"
                    data-values="{{ json_encode($attendance_chart['data'] ?? []) }}"
                    data-assignment-values="{{ json_encode($attendance_chart['assignment_data'] ?? []) }}">
                </canvas>
            </div>

        </x-ui.card>
    </div>

</div>

@script
<script>
    let attendanceChartInstance = null;

    function renderAttendanceChart() {

        const canvas = document.getElementById('attendanceChart');

        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const values = JSON.parse(canvas.dataset.values || '[]');
        const assignmentValues = JSON.parse(canvas.dataset.assignmentValues || '[]');

        // Destroy the previous instance before re-rendering, otherwise
        // Chart.js throws "Canvas is already in use" and silently fails.
        attendanceChartInstance?.destroy();

        attendanceChartInstance = new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Attendance',
                        data: values,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        cubicInterpolationMode: 'monotone',
                        tension: .4,
                        fill: true,
                    },
                    {
                        label: 'Assignment Selesai',
                        data: assignmentValues,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        borderWidth: 3,
                        pointRadius: 4,
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
                    y: { beginAtZero: true, ticks: { precision: 0 } },
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

    renderAttendanceChart();

    // canvas data-* attributes are updated by Livewire's morph on every
    // poll/refresh - re-render the chart after each one.
    Livewire.hook('morph.updated', () => renderAttendanceChart());
</script>
@endscript
