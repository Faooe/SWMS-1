<div wire:poll.30s>

    {{-- Header --}}
    <div>
        <p class="text-slate-500">
            Selamat datang kembali,
            <strong>{{ auth()->user()->employee?->full_name ?? auth()->user()->username }}</strong>
        </p>
    </div>

    {{-- Statistics --}}
    <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-4">

        <x-dashboard.stat-card title="Employee" :value="$total_employee" icon="users" :change="$stat_changes['employee']" change-label="dibanding 1 bulan lalu" />
        <x-dashboard.stat-card title="Attendance" :value="$attendance_today" icon="calendar-check" :change="$stat_changes['attendance']" change-label="dibanding kemarin" />
        <x-dashboard.stat-card title="Late" :value="$late_today" icon="clock-3" :change="$stat_changes['late']" change-label="dibanding kemarin" />
        <x-dashboard.stat-card title="Assignment" :value="$active_assignment" icon="clipboard-list" :change="$stat_changes['assignment']" change-label="dibanding 1 bulan lalu" />

    </div>

    {{-- Chart --}}
    <div class="mt-8">
        <x-ui.card>

            <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-xl font-bold">Attendance & Assignment Trend</h2>
                    <p class="text-sm text-slate-500">Minggu ini (Senin–Minggu) &middot; auto-refresh setiap 30 detik</p>
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

    {{-- Recent Attendance & Active Assignments --}}
    <div class="mt-8 grid gap-6 lg:grid-cols-2">

        {{-- Recent Attendance --}}
        <x-ui.card>

            <div class="mb-6">
                <h2 class="text-xl font-bold">Aktivitas Absensi Terbaru</h2>
                <p class="text-sm text-slate-500">5 check-in/check-out terakhir</p>
            </div>

            @forelse($recent_attendance as $item)

                <div class="flex items-center gap-3 border-t border-slate-100 py-3 first:border-t-0 first:pt-0">

                    @if($item['employee_photo_url'])
                        <img
                            src="{{ $item['employee_photo_url'] }}"
                            alt="{{ $item['employee_name'] }}"
                            class="h-10 w-10 rounded-full object-cover">
                    @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-600">
                            {{ strtoupper(substr($item['employee_name'] ?? '?', 0, 1)) }}
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-800">
                            {{ $item['employee_name'] ?? '-' }}
                        </p>
                        <p class="truncate text-xs text-slate-500">
                            {{ $item['office_name'] ?? '-' }} &middot;
                            {{ \Illuminate\Support\Carbon::parse($item['attendance_date'])->translatedFormat('d M') }}
                            &middot; {{ $item['check_in_time'] ?? '-' }}
                            @if($item['check_out_time'])
                                - {{ $item['check_out_time'] }}
                            @endif
                        </p>
                    </div>

                    <x-ui.badge :color="$item['status'] === 'Present' ? 'green' : ($item['status'] === 'Late' ? 'yellow' : 'blue')">
                        {{ $item['status'] }}
                    </x-ui.badge>

                </div>

            @empty

                <p class="py-6 text-center text-sm text-slate-400">
                    Belum ada aktivitas absensi.
                </p>

            @endforelse

        </x-ui.card>

        {{-- Active Assignments --}}
        <x-ui.card>

            <div class="mb-6">
                <h2 class="text-xl font-bold">Assignment Aktif</h2>
                <p class="text-sm text-slate-500">5 assignment yang sedang berjalan</p>
            </div>

            @forelse($active_assignments as $item)

                <div class="border-t border-slate-100 py-3 first:border-t-0 first:pt-0">

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-slate-800">
                                {{ $item['title'] }}
                            </p>
                            <p class="truncate text-xs text-slate-500">
                                {{ $item['assignment_number'] }}
                                @if($item['location_name'])
                                    &middot; {{ $item['location_name'] }}
                                @endif
                            </p>
                        </div>

                        <x-ui.badge :color="$item['status'] === 'In Progress' ? 'yellow' : 'blue'">
                            {{ $item['status'] }}
                        </x-ui.badge>

                    </div>

                    @if(!empty($item['employee_names']))
                        <p class="mt-2 truncate text-xs text-slate-500">
                            <i data-lucide="users" class="inline h-3.5 w-3.5 align-text-bottom"></i>
                            {{ implode(', ', $item['employee_names']) }}
                        </p>
                    @endif

                </div>

            @empty

                <p class="py-6 text-center text-sm text-slate-400">
                    Belum ada assignment aktif.
                </p>

            @endforelse

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
