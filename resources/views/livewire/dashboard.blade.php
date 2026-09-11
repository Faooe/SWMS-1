<div wire:poll.30s class="space-y-6">
    {{-- Header / greeting --}}
    <section class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500">Dashboard Company</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">
                Halo, {{ auth()->user()->employee?->full_name ?? auth()->user()->username }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">Pantau kondisi operasional perusahaan dari satu tempat.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-100">
            <span class="h-2 w-2 rounded-full bg-blue-600"></span>
            Live · refresh 30 detik
        </span>
    </section>

    {{-- Ringkasan operasional hari ini --}}
    <section>
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i data-lucide="building-2" class="h-5 w-5"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-900">Operasional Perusahaan</p>
                    <p class="text-xs text-slate-500">Ringkasan kondisi hari ini</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-px bg-slate-200 lg:grid-cols-4">
                @php
                    $summaryItems = [
                        ['label' => 'Karyawan Aktif', 'value' => $total_employee, 'icon' => 'users', 'iconClass' => 'bg-blue-50 text-blue-600'],
                        ['label' => 'Hadir Hari Ini', 'value' => $attendance_today, 'icon' => 'user-check', 'iconClass' => 'bg-emerald-50 text-emerald-600'],
                        ['label' => 'Telat Hari Ini', 'value' => $late_today, 'icon' => 'clock-3', 'iconClass' => 'bg-amber-50 text-amber-600'],
                        ['label' => 'Assignment Aktif', 'value' => $active_assignment, 'icon' => 'clipboard-list', 'iconClass' => 'bg-blue-50 text-blue-600'],
                    ];
                @endphp

                @foreach($summaryItems as $item)
                    <div class="bg-white p-4 sm:p-5">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $item['iconClass'] }}">
                                <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-2xl font-bold leading-none text-slate-950">{{ $item['value'] }}</p>
                                <p class="mt-1.5 truncate text-xs font-medium text-slate-500">{{ $item['label'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Tren Mingguan --}}
    <section>
        <div class="mb-3">
            <h2 class="text-lg font-bold text-slate-950">Tren Mingguan</h2>
            <p class="text-sm text-slate-500">Attendance dan assignment selesai minggu ini.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-bold text-slate-900">Tren Attendance & Assignment</h3>
                    <p class="text-xs text-slate-500">Minggu ini · Senin–Minggu</p>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-slate-500">
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>Attendance</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>Assignment Selesai</span>
                </div>
            </div>

            <div class="relative h-64 w-full sm:h-72">
                <canvas
                    id="attendanceChart"
                    data-labels="{{ json_encode($attendance_chart['labels'] ?? []) }}"
                    data-values="{{ json_encode($attendance_chart['data'] ?? []) }}"
                    data-assignment-values="{{ json_encode($attendance_chart['assignment_data'] ?? []) }}">
                </canvas>
            </div>
        </div>
    </section>

    {{-- Rekapitulasi HR ringkas --}}
    @if(!empty($hr_recap))
        @php
            $hrSummary = $hr_recap['summary'];
            $hrRange = $hr_recap['range'];
        @endphp
        <section>
            <div class="mb-3 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-950">Rekapitulasi HR</h2>
                    <p class="text-sm text-slate-500">Ringkasan seluruh data employee · {{ $hrRange['label'] }}</p>
                </div>
                <a href="{{ route('company-recap.index', ['from' => $hrRange['from'], 'to' => $hrRange['to']]) }}" class="inline-flex w-fit items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700">
                    Lihat semua <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach([
                    ['label' => 'Total Employee', 'value' => $hrSummary['employees'], 'icon' => 'users', 'tone' => 'bg-blue-50 text-blue-600'],
                    ['label' => 'Attendance Rate', 'value' => number_format($hrSummary['attendance_rate'], 1).'%', 'icon' => 'user-check', 'tone' => 'bg-emerald-50 text-emerald-600'],
                    ['label' => 'Total Absen', 'value' => $hrSummary['absent'], 'icon' => 'user-x', 'tone' => 'bg-red-50 text-red-600'],
                    ['label' => 'Assignment', 'value' => $hrSummary['assignment_total'], 'icon' => 'clipboard-list', 'tone' => 'bg-violet-50 text-violet-600'],
                ] as $item)
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $item['tone'] }}"><i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i></span>
                            <div class="min-w-0"><p class="text-2xl font-extrabold leading-none text-slate-950">{{ $item['value'] }}</p><p class="mt-1.5 text-xs font-semibold text-slate-500">{{ $item['label'] }}</p></div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-4 grid gap-4 xl:grid-cols-2">
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div><h3 class="font-bold text-slate-900">Status Attendance</h3><p class="mt-1 text-xs text-slate-500">Jumlah status seluruh employee pada periode ini.</p></div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600">{{ $hrRange['working_days'] }} hari kerja</span>
                    </div>
                    <div class="mt-5 grid items-center gap-5 sm:grid-cols-[170px_1fr]">
                        <div class="relative mx-auto h-40 w-40"><canvas id="hrAttendanceBreakdownChart" data-labels="{{ json_encode(collect($hr_recap['attendance_breakdown'])->pluck('label')->all()) }}" data-values="{{ json_encode(collect($hr_recap['attendance_breakdown'])->pluck('value')->all()) }}" data-colors="{{ json_encode(collect($hr_recap['attendance_breakdown'])->pluck('color')->all()) }}"></canvas></div>
                        <div class="space-y-2">
                            @foreach($hr_recap['attendance_breakdown'] as $item)
                                <div class="flex items-center justify-between gap-3 text-xs"><span class="inline-flex items-center gap-2 text-slate-600"><span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $item['color'] }}"></span>{{ $item['label'] }}</span><strong class="text-slate-900">{{ $item['value'] }}</strong></div>
                            @endforeach
                        </div>
                    </div>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div><h3 class="font-bold text-slate-900">Status Assignment</h3><p class="mt-1 text-xs text-slate-500">Distribusi pekerjaan berdasarkan status pengerjaan.</p></div>
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-[11px] font-bold text-blue-700">{{ number_format($hrSummary['completion_rate'], 1) }}% selesai</span>
                    </div>
                    <div class="mt-5 grid items-center gap-5 sm:grid-cols-[170px_1fr]">
                        <div class="relative mx-auto h-40 w-40"><canvas id="hrAssignmentBreakdownChart" data-labels="{{ json_encode(collect($hr_recap['assignment_breakdown'])->pluck('label')->all()) }}" data-values="{{ json_encode(collect($hr_recap['assignment_breakdown'])->pluck('value')->all()) }}" data-colors="{{ json_encode(collect($hr_recap['assignment_breakdown'])->pluck('color')->all()) }}"></canvas></div>
                        <div class="space-y-2">
                            @foreach($hr_recap['assignment_breakdown'] as $item)
                                <div class="flex items-center justify-between gap-3 text-xs"><span class="inline-flex items-center gap-2 text-slate-600"><span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $item['color'] }}"></span>{{ $item['label'] }}</span><strong class="text-slate-900">{{ $item['value'] }}</strong></div>
                            @endforeach
                        </div>
                    </div>
                </article>
            </div>

            <div class="mt-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs leading-5 text-slate-600">
                <span class="font-bold text-slate-800">Panduan warna:</span>
                <span class="text-emerald-700">hijau = hadir/selesai</span>,
                <span class="text-amber-700">kuning = telat/belum dikerjakan</span>,
                <span class="text-blue-700">biru = izin/berjalan</span>,
                <span class="text-violet-700">ungu = cuti</span>,
                <span class="text-red-700">merah = absen/ditolak</span>.
            </div>
        </section>
    @endif

    {{-- Aktivitas Operasional --}}
    <section>
        <div class="mb-3">
            <h2 class="text-lg font-bold text-slate-950">Aktivitas Operasional</h2>
            <p class="text-sm text-slate-500">Pergerakan terbaru yang perlu dipantau HRD.</p>
        </div>

        <div class="grid gap-5 xl:grid-cols-2">
            {{-- Attendance terbaru --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h3 class="font-bold text-slate-900">Attendance Terbaru</h3>
                        <p class="text-xs text-slate-500">5 aktivitas attendance terbaru</p>
                    </div>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="calendar-check-2" class="h-5 w-5"></i>
                    </span>
                </div>

                <div class="divide-y divide-slate-100 px-5">
                    @forelse($recent_attendance as $item)
                        @php
                            $statusLabel = match($item['status'] ?? null) {
                                'Present' => 'Tepat',
                                'Late' => 'Telat',
                                'Absent' => 'Absen',
                                'Permission' => 'Izin',
                                default => $item['status'] ?? '-',
                            };
                            $statusClass = match($item['status'] ?? null) {
                                'Present' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                                'Late' => 'bg-amber-50 text-amber-700 ring-amber-100',
                                'Absent' => 'bg-red-50 text-red-700 ring-red-100',
                                default => 'bg-blue-50 text-blue-700 ring-blue-100',
                            };
                        @endphp
                        <div class="flex items-center gap-3 py-4">
                            @if($item['employee_photo_url'])
                                <img src="{{ $item['employee_photo_url'] }}" alt="{{ $item['employee_name'] }}" class="h-10 w-10 shrink-0 rounded-full object-cover">
                            @else
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-blue-600">
                                    {{ strtoupper(substr($item['employee_name'] ?? '?', 0, 1)) }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $item['employee_name'] ?? '-' }}</p>
                                <p class="mt-0.5 truncate text-xs text-slate-500">
                                    {{ $item['office_name'] ?? '-' }} ·
                                    {{ \Illuminate\Support\Carbon::parse($item['attendance_date'])->translatedFormat('d M') }} ·
                                    {{ $item['check_in_time'] ?? '-' }}@if($item['check_out_time']) - {{ $item['check_out_time'] }}@endif
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 ring-inset {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <i data-lucide="calendar-x-2" class="h-5 w-5"></i>
                            </span>
                            <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada aktivitas attendance</p>
                            <p class="mt-1 text-xs text-slate-400">Aktivitas check-in/check-out terbaru akan tampil di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Assignment aktif --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h3 class="font-bold text-slate-900">Assignment Aktif</h3>
                        <p class="text-xs text-slate-500">5 assignment aktif yang sedang berjalan</p>
                    </div>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                    </span>
                </div>

                <div class="divide-y divide-slate-100 px-5">
                    @forelse($active_assignments as $item)
                        @php
                            $assignmentStatusClass = ($item['status'] ?? '') === 'In Progress'
                                ? 'bg-amber-50 text-amber-700 ring-amber-100'
                                : 'bg-blue-50 text-blue-700 ring-blue-100';
                        @endphp
                        <div class="py-4">
                            <div class="flex items-start gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $item['title'] }}</p>
                                    <p class="mt-0.5 truncate text-xs text-slate-500">
                                        {{ $item['assignment_number'] }}@if($item['location_name']) · {{ $item['location_name'] }}@endif
                                    </p>
                                </div>
                                <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 ring-inset {{ $assignmentStatusClass }}">{{ $item['status'] ?? '-' }}</span>
                            </div>

                            @if(!empty($item['employee_names']))
                                <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500">
                                    <i data-lucide="users" class="h-3.5 w-3.5 shrink-0"></i>
                                    <span class="truncate">{{ implode(', ', $item['employee_names']) }}</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <i data-lucide="clipboard-x" class="h-5 w-5"></i>
                            </span>
                            <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada assignment aktif</p>
                            <p class="mt-1 text-xs text-slate-400">Assignment berjalan akan muncul di bagian ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- Akses cepat --}}
    <section>
        <div class="mb-3">
            <h2 class="text-lg font-bold text-slate-950">Akses Cepat</h2>
            <p class="text-sm text-slate-500">Masuk ke pekerjaan HR yang paling sering digunakan.</p>
        </div>

        <div class="grid overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('employees.index') }}" class="group flex items-center gap-3 border-b border-slate-100 px-5 py-4 transition hover:bg-slate-50 sm:border-r xl:border-b-0">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="users" class="h-5 w-5"></i></span>
                <div class="min-w-0 flex-1"><p class="text-sm font-bold text-slate-900">Employee</p><p class="truncate text-xs text-slate-500">Kelola data karyawan</p></div>
                <i data-lucide="chevron-right" class="h-4 w-4 text-slate-300 transition group-hover:text-blue-600"></i>
            </a>
            <a href="{{ route('attendance.index') }}" class="group flex items-center gap-3 border-b border-slate-100 px-5 py-4 transition hover:bg-slate-50 xl:border-b-0 xl:border-r">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i data-lucide="calendar-check" class="h-5 w-5"></i></span>
                <div class="min-w-0 flex-1"><p class="text-sm font-bold text-slate-900">Attendance</p><p class="truncate text-xs text-slate-500">Pantau kehadiran</p></div>
                <i data-lucide="chevron-right" class="h-4 w-4 text-slate-300 transition group-hover:text-blue-600"></i>
            </a>
            <a href="{{ route('assignments.index') }}" class="group flex items-center gap-3 border-b border-slate-100 px-5 py-4 transition hover:bg-slate-50 sm:border-b-0 sm:border-r">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="clipboard-list" class="h-5 w-5"></i></span>
                <div class="min-w-0 flex-1"><p class="text-sm font-bold text-slate-900">Assignment</p><p class="truncate text-xs text-slate-500">Kelola pekerjaan</p></div>
                <i data-lucide="chevron-right" class="h-4 w-4 text-slate-300 transition group-hover:text-blue-600"></i>
            </a>
            <a href="{{ route('leaves.index') }}" class="group flex items-center gap-3 px-5 py-4 transition hover:bg-slate-50">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600"><i data-lucide="file-text" class="h-5 w-5"></i></span>
                <div class="min-w-0 flex-1"><p class="text-sm font-bold text-slate-900">Leave / Permission</p><p class="truncate text-xs text-slate-500">Kelola cuti dan izin</p></div>
                <i data-lucide="chevron-right" class="h-4 w-4 text-slate-300 transition group-hover:text-blue-600"></i>
            </a>
        </div>
    </section>
</div>

@script
<script>
    let attendanceChartInstance = null;
    let hrAttendanceBreakdownChartInstance = null;
    let hrAssignmentBreakdownChartInstance = null;

    function renderAttendanceChart() {
        const canvas = document.getElementById('attendanceChart');
        if (!canvas || typeof Chart === 'undefined') return;

        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const values = JSON.parse(canvas.dataset.values || '[]');
        const assignmentValues = JSON.parse(canvas.dataset.assignmentValues || '[]');

        attendanceChartInstance?.destroy();
        attendanceChartInstance = new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Attendance',
                        data: values,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.10)',
                        borderWidth: 3,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        cubicInterpolationMode: 'monotone',
                        tension: .35,
                        fill: true,
                    },
                    {
                        label: 'Assignment Selesai',
                        data: assignmentValues,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.08)',
                        borderWidth: 3,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        cubicInterpolationMode: 'monotone',
                        tension: .35,
                        fill: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: '#94a3b8' },
                        grid: { color: '#e2e8f0' },
                        border: { display: false },
                    },
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0f172a', padding: 10, cornerRadius: 10 },
                },
            },
        });
    }

    function renderHrBreakdownCharts() {
        const render = (id, instanceName) => {
            const canvas = document.getElementById(id);
            if (!canvas || typeof Chart === 'undefined') return;

            const labels = JSON.parse(canvas.dataset.labels || '[]');
            const values = JSON.parse(canvas.dataset.values || '[]');
            const colors = JSON.parse(canvas.dataset.colors || '[]');
            if (instanceName === 'attendance') {
                hrAttendanceBreakdownChartInstance?.destroy();
                hrAttendanceBreakdownChartInstance = new Chart(canvas, {
                    type: 'doughnut',
                    data: { labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 0 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '68%', plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', padding: 10, cornerRadius: 10 } } },
                });
            } else {
                hrAssignmentBreakdownChartInstance?.destroy();
                hrAssignmentBreakdownChartInstance = new Chart(canvas, {
                    type: 'doughnut',
                    data: { labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 0 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '68%', plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', padding: 10, cornerRadius: 10 } } },
                });
            }
        };

        render('hrAttendanceBreakdownChart', 'attendance');
        render('hrAssignmentBreakdownChart', 'assignment');
    }

    renderAttendanceChart();
    renderHrBreakdownCharts();
    Livewire.hook('morph.updated', () => {
        renderAttendanceChart();
        renderHrBreakdownCharts();
    });
</script>
@endscript
