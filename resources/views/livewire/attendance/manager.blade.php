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
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="list-filter" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <h2 class="font-bold text-slate-900">Data Attendance</h2>
                        <p class="mt-0.5 text-sm text-slate-500">Cari employee, saring data, lalu buka detail check-in dan check-out.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if($isToday)
                        <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                            <i data-lucide="calendar-check" class="h-3.5 w-3.5"></i>
                            Hari ini · {{ today()->translatedFormat('d M Y') }}
                        </span>
                    @elseif($date)
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                            <i data-lucide="calendar-days" class="h-3.5 w-3.5"></i>
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                            <i data-lucide="calendar-range" class="h-3.5 w-3.5"></i>
                            Semua tanggal
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-6 py-5">
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                <div class="xl:col-span-5">
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Cari Employee</label>
                    <div class="relative">
                        <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input
                            type="text"
                            wire:model.live.debounce.400ms="search"
                            placeholder="Cari nama atau NIP employee..."
                            class="w-full rounded-xl border-slate-300 py-3 pl-11 pr-4 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="xl:col-span-2">
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Office</label>
                    <select wire:model.live="office" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Office</option>
                        @foreach($offices as $off)<option value="{{ $off->id }}">{{ $off->name }}</option>@endforeach
                    </select>
                </div>

                <div class="xl:col-span-2">
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Status</label>
                    <select wire:model.live="status" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="Present">Tepat</option>
                        <option value="Late">Telat</option>
                        <option value="Leave">Leave</option>
                        <option value="Permission">Izin</option>
                        <option value="Absent">Absen</option>
                    </select>
                </div>

                <div class="xl:col-span-3">
                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Tanggal</label>
                    <input type="date" wire:model.live="date" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-4 flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="mr-1 text-xs font-semibold text-slate-500">Quick filter</span>
                    <button type="button" wire:click="resetFilters" class="inline-flex items-center gap-2 rounded-xl border px-3.5 py-2 text-sm font-semibold transition {{ $isToday ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                        <i data-lucide="calendar-check" class="h-4 w-4"></i>
                        Hari Ini
                    </button>
                    <button type="button" wire:click="showAllDates" class="inline-flex items-center gap-2 rounded-xl border px-3.5 py-2 text-sm font-semibold transition {{ !$date ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                        <i data-lucide="calendar-range" class="h-4 w-4"></i>
                        Semua
                    </button>
                </div>

                @if($search || $office || $status || (!$isToday && $date))
                    <button type="button" wire:click="resetFilters" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-blue-600">
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                        Reset filter
                    </button>
                @endif
            </div>
        </div>

        <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-4">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="text-sm font-bold text-slate-800">Export Rekap Bulanan</div>
                    <div class="mt-0.5 text-xs text-slate-500">Unduh data attendance untuk bulan yang dipilih.</div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <input type="month" wire:model="exportMonth" class="rounded-xl border-slate-300 bg-white text-sm focus:border-blue-500 focus:ring-blue-500">
                    <a href="{{ $exportPdfUrl }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                        <i data-lucide="file-text" class="h-4 w-4 text-red-500"></i>
                        PDF
                    </a>
                    @if($isPremium)
                        <a href="{{ $exportExcelUrl }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                            <i data-lucide="file-spreadsheet" class="h-4 w-4 text-emerald-600"></i>
                            Excel
                        </a>
                    @else
                        <span title="Upgrade Premium untuk export Excel" class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-400">
                            <i data-lucide="lock" class="h-4 w-4"></i>
                            Excel Premium
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Attendance table --}}
    <section
        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        wire:loading.class="opacity-50"
        wire:target="search,office,status,date,previousPage,nextPage,gotoPage">

        <div class="flex flex-col gap-2 border-b border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Daftar Attendance</h3>
                <p class="mt-0.5 text-xs text-slate-500">{{ $attendances->total() }} record sesuai filter aktif.</p>
            </div>
            <div wire:loading wire:target="search,office,status,date" class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600">
                <span class="h-3.5 w-3.5 animate-spin rounded-full border-2 border-blue-200 border-t-blue-600"></span>
                Memuat data...
            </div>
        </div>

        <div class="max-h-[560px] overflow-y-auto overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="sticky top-0 z-10 bg-slate-50/95 backdrop-blur">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Employee</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Office</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Jam</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($attendances as $attendance)
                        @php
                            $statusMeta = match($attendance->attendance_status) {
                                'Present' => ['label' => 'Tepat', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
                                'Late' => ['label' => 'Telat', 'class' => 'bg-amber-50 text-amber-700 ring-amber-200'],
                                'Absent' => ['label' => 'Absen', 'class' => 'bg-red-50 text-red-700 ring-red-200'],
                                'Leave' => ['label' => 'Leave', 'class' => 'bg-violet-50 text-violet-700 ring-violet-200'],
                                'Permission' => ['label' => 'Izin', 'class' => 'bg-sky-50 text-sky-700 ring-sky-200'],
                                default => ['label' => $attendance->attendance_status, 'class' => 'bg-slate-50 text-slate-700 ring-slate-200'],
                            };
                        @endphp
                        <tr wire:key="attendance-row-{{ $attendance->id }}" class="group transition hover:bg-slate-50/70">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar :employee="$attendance->employee" size="10" />
                                    <div class="min-w-0">
                                        <div class="truncate font-semibold text-slate-900">{{ $attendance->employee->full_name }}</div>
                                        <div class="mt-0.5 text-xs text-slate-500">{{ $attendance->employee->employee_number ?: 'NIP -' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $attendance->office?->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-slate-800">{{ $attendance->attendance_date->format('d M Y') }}</div>
                                <div class="mt-0.5 text-xs text-slate-400">{{ $attendance->attendance_date->translatedFormat('l') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                                    <span>{{ optional($attendance->check_in_time)->format('H:i') ?? '--:--' }}</span>
                                    <i data-lucide="arrow-right" class="h-3.5 w-3.5 text-slate-300"></i>
                                    <span>{{ optional($attendance->check_out_time)->format('H:i') ?? '--:--' }}</span>
                                </div>
                                <div class="mt-0.5 text-[11px] text-slate-400">Check In → Check Out</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $statusMeta['class'] }}">
                                    {{ $statusMeta['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('attendance.show', $attendance->id) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                    Detail
                                    <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16">
                                <div class="mx-auto flex max-w-sm flex-col items-center text-center">
                                    <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <i data-lucide="calendar-x-2" class="h-6 w-6"></i>
                                    </span>
                                    <div class="font-bold text-slate-800">Belum ada data attendance</div>
                                    <p class="mt-1 text-sm leading-6 text-slate-500">Tidak ada record yang cocok dengan filter yang sedang digunakan.</p>
                                    <button type="button" wire:click="resetFilters" class="mt-4 inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                                        Kembali ke Hari Ini
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
            <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                {{ $attendances->links() }}
            </div>
        @endif
    </section>

</div>
