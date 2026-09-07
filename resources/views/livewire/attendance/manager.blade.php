<div class="space-y-6 pb-20">

    {{-- Intro --}}
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <p class="text-slate-500">
            Monitor attendance karyawan, validasi GPS, dan ringkasan kehadiran langsung tanpa harus export laporan.
        </p>
        <a href="{{ route('attendance.calendar') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-bold text-blue-700 hover:bg-blue-100">
            <i data-lucide="calendar-days" class="h-4 w-4"></i> Work Calendar / Hari Libur
        </a>
    </div>

    {{-- Premium Analytics --}}
    @if($isPremium)
        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                <i data-lucide="chart-no-axes-combined" class="h-5 w-5"></i>
                            </span>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Ringkasan Attendance</h2>
                                <p class="text-sm text-slate-500">Ringkasan {{ $analytics['label'] ?? '-' }} · Premium</p>
                            </div>
                        </div>
                    </div>

                    <div class="inline-flex rounded-2xl border border-slate-200 bg-slate-100 p-1">
                        @foreach(['day' => 'Hari', 'month' => 'Bulan', 'year' => 'Tahun', 'all' => 'Semua'] as $period => $label)
                            <button
                                type="button"
                                wire:click="$set('analyticsPeriod', '{{ $period }}')"
                                class="rounded-xl px-4 py-2 text-sm font-semibold transition {{ $analyticsPeriod === $period ? 'bg-white text-blue-700 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-800' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-3 lg:flex-row lg:items-end">
                    @if($analyticsPeriod === 'day')
                        <label class="block min-w-64 cursor-pointer">
                            <span class="mb-1.5 block text-xs font-semibold text-slate-500">Tanggal Analytics</span>
                            <span class="relative block">
                                <i data-lucide="calendar-days" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-blue-600"></i>
                                <input type="date" wire:model.live="analyticsDate" class="w-full rounded-xl border-slate-300 bg-white py-2.5 pl-10 pr-3 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-blue-500">
                            </span>
                        </label>
                    @elseif($analyticsPeriod === 'month')
                        <label class="block min-w-64 cursor-pointer">
                            <span class="mb-1.5 block text-xs font-semibold text-slate-500">Bulan Analytics</span>
                            <span class="relative block">
                                <i data-lucide="calendar-range" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-blue-600"></i>
                                <input type="month" wire:model.live="analyticsMonth" class="w-full rounded-xl border-slate-300 bg-white py-2.5 pl-10 pr-3 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-blue-500">
                            </span>
                        </label>
                    @elseif($analyticsPeriod === 'year')
                        <label class="block min-w-64 cursor-pointer">
                            <span class="mb-1.5 block text-xs font-semibold text-slate-500">Tahun Analytics</span>
                            <span class="relative block">
                                <i data-lucide="calendar-clock" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-blue-600"></i>
                                <select wire:model.live="analyticsYear" class="w-full rounded-xl border-slate-300 bg-white py-2.5 pl-10 pr-9 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-blue-500">
                                    @for($year = today()->year; $year >= today()->year - 10; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </span>
                        </label>
                    @else
                        <div class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-600">
                            <i data-lucide="database" class="h-4 w-4 text-blue-600"></i>
                            Seluruh periode attendance
                        </div>
                    @endif

                    <div class="lg:ml-auto flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-2.5 text-sm text-slate-600">
                        <i data-lucide="users" class="h-4 w-4 text-blue-600"></i>
                        <span><strong class="text-slate-900">{{ $analytics['summary']['employees_covered'] ?? 0 }}</strong> employee tercakup</span>
                    </div>
                </div>
            </div>

            @php
                $summary = $analytics['summary'] ?? [];
            @endphp
            <div class="p-6">
                <div class="grid gap-4 lg:grid-cols-[1.35fr_.9fr]">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                        <div class="grid grid-cols-2 divide-x divide-y divide-slate-200 sm:grid-cols-4 sm:divide-y-0">
                            @foreach([
                                ['Total', $summary['total'] ?? 0, 'text-slate-900'],
                                ['Hadir', $summary['attended'] ?? 0, 'text-blue-700'],
                                ['Tepat', $summary['present'] ?? 0, 'text-emerald-600'],
                                ['Telat', $summary['late'] ?? 0, 'text-amber-600'],
                            ] as [$label, $value, $tone])
                                <div class="px-4 py-4 text-center">
                                    <div class="text-2xl font-bold {{ $tone }}">{{ $value }}</div>
                                    <div class="mt-1 text-xs font-semibold text-slate-500">{{ $label }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex flex-wrap gap-2 border-t border-slate-200 bg-white px-4 py-3">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-700">
                                <i data-lucide="plane" class="h-3.5 w-3.5"></i> Leave {{ $summary['leave'] ?? 0 }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                <i data-lucide="file-check" class="h-3.5 w-3.5"></i> Izin {{ $summary['permission'] ?? 0 }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700">
                                <i data-lucide="circle-x" class="h-3.5 w-3.5"></i> Absen {{ $summary['absent'] ?? 0 }}
                            </span>
                            @if(array_key_exists('working_days', $summary))
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                    <i data-lucide="calendar-check-2" class="h-3.5 w-3.5"></i> Hari Kerja {{ $summary['working_days'] ?? 0 }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Attendance Rate</p>
                                <p class="mt-1 text-xs leading-5 text-slate-500">Present + Late pada periode terpilih.</p>
                            </div>
                            <span class="text-3xl font-bold text-slate-900">{{ number_format($summary['attendance_rate'] ?? 0, 1) }}%</span>
                        </div>
                        <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-blue-100">
                            <div class="h-full rounded-full bg-blue-600 transition-all" style="width: {{ min(100, max(0, $summary['attendance_rate'] ?? 0)) }}%"></div>
                        </div>
                        <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                            <i data-lucide="users" class="h-3.5 w-3.5 text-blue-600"></i>
                            <span><strong class="text-slate-700">{{ $summary['employees_covered'] ?? 0 }}</strong> employee tercakup</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                    <div class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900">Rekap per Employee</h3>
                            <p class="text-xs text-slate-500">Bisa dibaca langsung tanpa download PDF/Excel.</p>
                        </div>
                        <div class="relative w-full lg:w-80">
                            <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                            <input
                                type="search"
                                wire:model.live.debounce.300ms="analyticsEmployeeSearch"
                                placeholder="Cari nama / ID employee..."
                                class="w-full rounded-xl border-slate-300 bg-white py-2.5 pl-9 pr-9 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:ring-blue-500"
                            >
                            @if($analyticsEmployeeSearch !== '')
                                <button type="button" wire:click="$set('analyticsEmployeeSearch', '')" class="absolute right-2.5 top-1/2 -translate-y-1/2 rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" title="Hapus pencarian">
                                    <i data-lucide="x" class="h-4 w-4"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-white">
                                <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-3">Employee</th>
                                    <th class="px-4 py-3 text-center">Total</th>
                                    <th class="px-4 py-3 text-center">Tepat</th>
                                    <th class="px-4 py-3 text-center">Telat</th>
                                    <th class="px-4 py-3 text-center">Leave</th>
                                    <th class="px-4 py-3 text-center">Izin</th>
                                    <th class="px-4 py-3 text-center">Absen</th>
                                    <th class="px-5 py-3 text-right">Rate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($analytics['by_employee'] ?? [] as $row)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                @if(!empty($row['employee_photo_url']))
                                                    <img src="{{ $row['employee_photo_url'] }}" alt="{{ $row['employee_name'] }}" class="h-9 w-9 rounded-full object-cover border border-slate-200">
                                                @else
                                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-600">
                                                        {{ strtoupper(substr($row['employee_name'] ?? '?', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-semibold text-slate-800">{{ $row['employee_name'] }}</div>
                                                    <div class="text-xs text-slate-500">{{ $row['employee_number'] ?: '-' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center font-semibold">{{ $row['total'] }}</td>
                                        <td class="px-4 py-4 text-center text-emerald-600">{{ $row['present'] }}</td>
                                        <td class="px-4 py-4 text-center text-amber-600">{{ $row['late'] }}</td>
                                        <td class="px-4 py-4 text-center text-purple-600">{{ $row['leave'] }}</td>
                                        <td class="px-4 py-4 text-center text-cyan-600">{{ $row['permission'] }}</td>
                                        <td class="px-4 py-4 text-center text-red-600">{{ $row['absent'] }}</td>
                                        <td class="px-5 py-4 text-right font-bold text-slate-800">{{ number_format($row['attendance_rate'], 1) }}%</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="px-5 py-10 text-center text-slate-400">{{ $analyticsEmployeeSearch !== '' ? 'Employee tidak ditemukan.' : 'Belum ada data pada periode ini.' }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-indigo-50 p-6 shadow-sm">
            <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                <div class="flex items-start gap-4">
                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-sm">
                        <i data-lucide="lock-keyhole" class="h-6 w-6"></i>
                    </span>
                    <div>
                        <div class="mb-1 flex items-center gap-2">
                            <h2 class="text-lg font-bold text-slate-900">Ringkasan Attendance</h2>
                            <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-700">Premium</span>
                        </div>
                        <p class="max-w-2xl text-sm leading-6 text-slate-600">
                            Lihat rekap hari, bulan, tahun, semua data, attendance rate, serta breakdown setiap employee langsung di SWMS tanpa export file.
                        </p>
                    </div>
                </div>
                <a href="{{ route('subscription.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
                    <i data-lucide="sparkles" class="h-4 w-4"></i>
                    Upgrade Plan
                </a>
            </div>
        </section>
    @endif

    {{-- Operational list controls --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="font-bold text-slate-900">Data Attendance</h2>
                <p class="text-sm text-slate-500">Cari, filter, dan buka detail check-in/check-out employee.</p>
            </div>
            @if($isToday)
                <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                    <i data-lucide="calendar-check" class="h-3.5 w-3.5"></i>
                    Hari ini · {{ today()->translatedFormat('d M Y') }}
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Cari Employee</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Nama / NIP..." class="w-full rounded-xl border-slate-300 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Office</label>
                <select wire:model.live="office" class="w-full rounded-xl border-slate-300 px-4 py-3">
                    <option value="">Semua Office</option>
                    @foreach($offices as $off)<option value="{{ $off->id }}">{{ $off->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                <select wire:model.live="status" class="w-full rounded-xl border-slate-300 px-4 py-3">
                    <option value="">Semua Status</option>
                    <option value="Present">Present</option><option value="Late">Late</option><option value="Leave">Leave</option><option value="Permission">Permission</option><option value="Absent">Absent</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal</label>
                <input type="date" wire:model.live="date" class="w-full rounded-xl border-slate-300 px-4 py-3">
            </div>
            <div class="flex items-end gap-2">
                <button type="button" wire:click="resetFilters" class="rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Hari Ini</button>
                <button type="button" wire:click="showAllDates" class="rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Semua</button>
            </div>
        </div>

        <div class="mt-5 flex flex-wrap items-end gap-3 border-t border-slate-100 pt-5">
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Export Bulan</label>
                <input type="month" wire:model="exportMonth" class="rounded-xl border-slate-300 text-sm">
            </div>
            <a href="{{ $exportPdfUrl }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <i data-lucide="file-text" class="h-4 w-4 text-red-500"></i> PDF
            </a>
            @if($isPremium)
                <a href="{{ $exportExcelUrl }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    <i data-lucide="file-spreadsheet" class="h-4 w-4 text-emerald-600"></i> Excel
                </a>
            @else
                <span title="Upgrade Premium untuk export Excel" class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-400">
                    <i data-lucide="lock" class="h-4 w-4"></i> Excel Premium
                </span>
            @endif
        </div>
    </section>

    {{-- Table --}}
    <div
        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        wire:loading.class="opacity-50"
        wire:target="search,office,status,date,previousPage,nextPage,gotoPage">

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">

                <thead class="sticky top-0 z-10 bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Employee</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Office</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Check In</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">

                    @forelse($attendances as $attendance)

                        <tr wire:key="attendance-row-{{ $attendance->id }}" class="hover:bg-slate-50 transition">

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar :employee="$attendance->employee" size="12" />
                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $attendance->employee->full_name }}</div>
                                        <div class="text-sm text-slate-500">{{ $attendance->employee->employee_number }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">{{ $attendance->office?->name ?? '-' }}</td>

                            <td class="px-6 py-5">{{ $attendance->attendance_date->format('d M Y') }}</td>

                            <td class="px-6 py-5">{{ optional($attendance->check_in_time)->format('H:i') ?? '-' }}</td>

                            <td class="px-6 py-5">
                                @php
                                    $color = match($attendance->attendance_status){
                                        'Present' => 'green',
                                        'Late' => 'orange',
                                        'Absent' => 'red',
                                        'Leave' => 'purple',
                                        default => 'blue',
                                    };
                                @endphp
                                <span class="rounded-full bg-{{ $color }}-100 px-3 py-1 text-sm font-semibold text-{{ $color }}-700">
                                    {{ $attendance->attendance_status }}
                                </span>
                            </td>

                            <td class="px-6 py-5 text-center">
                                <a
                                    href="{{ route('attendance.show', $attendance->id) }}"
                                    class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                    Detail
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Belum ada data attendance untuk filter ini.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        <div class="border-t bg-slate-50 px-6 py-4">
            {{ $attendances->links() }}
        </div>

    </div>

</div>
