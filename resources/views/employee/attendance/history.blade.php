@extends('layouts.app')

@section('title', 'Attendance History')
@section('page-title', 'Attendance History')

@section('content')
@php
    $totalStatus = array_sum([
        $summary['present'] ?? 0,
        $summary['late'] ?? 0,
        $summary['leave'] ?? 0,
        $summary['permission'] ?? 0,
        $summary['absent'] ?? 0,
    ]);
@endphp
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Riwayat Kehadiran</h2>
            <p class="mt-1 text-sm text-slate-500">Lihat catatan hadir, terlambat, izin, cuti, dan absensi berdasarkan periode.</p>
        </div>
        <a href="{{ route('employee.attendance.index') }}" class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Attendance Hari Ini
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => 'Hadir', 'value' => $summary['present'] ?? 0, 'icon' => 'circle-check'],
            ['label' => 'Terlambat', 'value' => $summary['late'] ?? 0, 'icon' => 'clock-3'],
            ['label' => 'Cuti / Izin', 'value' => ($summary['leave'] ?? 0) + ($summary['permission'] ?? 0), 'icon' => 'calendar-days'],
            ['label' => 'Tidak Hadir', 'value' => $summary['absent'] ?? 0, 'icon' => 'calendar-x-2'],
        ] as $item)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $item['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $item['value'] }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-[1fr_1fr_1fr_auto] xl:items-end">
            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Bulan</label>
                <input type="month" name="month" value="{{ $selectedMonth }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>
            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                <select name="status" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Status</option>
                    @foreach(['Present' => 'Hadir', 'Late' => 'Terlambat', 'Leave' => 'Cuti', 'Permission' => 'Izin', 'Absent' => 'Tidak Hadir'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Tipe Attendance</label>
                <select name="type" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Tipe</option>
                    <option value="OFFICE" @selected(($filters['type'] ?? '') === 'OFFICE')>Office</option>
                    <option value="ASSIGNMENT" @selected(($filters['type'] ?? '') === 'ASSIGNMENT')>Assignment</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Terapkan</button>
                <a href="{{ route('employee.attendance.history') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Reset</a>
            </div>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Sumber</th>
                        <th class="px-5 py-4">Check In / Out</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Ringkasan Kerja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($history as $item)
                        @php
                            $statusLabel = match($item->attendance_status) {
                                'Present' => 'Hadir',
                                'Late' => 'Terlambat',
                                'Leave' => 'Cuti',
                                'Permission' => 'Izin',
                                'Absent' => 'Tidak Hadir',
                                default => $item->attendance_status ?? '-',
                            };
                            $badgeClass = match($item->attendance_status) {
                                'Present' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                                'Late' => 'border-amber-200 bg-amber-50 text-amber-700',
                                'Absent' => 'border-red-200 bg-red-50 text-red-700',
                                default => 'border-slate-200 bg-slate-50 text-slate-700',
                            };
                            $minutesLabel = function ($minutes) {
                                $minutes = (int) ($minutes ?? 0);
                                if ($minutes <= 0) return '0m';
                                $hours = intdiv($minutes, 60);
                                $rest = $minutes % 60;
                                return $hours > 0 ? $hours.'j '.($rest > 0 ? $rest.'m' : '') : $rest.'m';
                            };
                            $source = $item->attendance_type === 'ASSIGNMENT'
                                ? ($item->assignment?->title ?? 'Assignment')
                                : ($item->office?->name ?? 'Office');
                        @endphp
                        <tr class="align-top transition hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-5 py-4">
                                <p class="font-semibold text-slate-900">{{ $item->attendance_date?->translatedFormat('d M Y') }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $item->attendance_date?->translatedFormat('l') }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $item->attendance_type === 'ASSIGNMENT' ? 'Assignment' : 'Office' }}</span>
                                <p class="mt-2 max-w-[220px] truncate text-xs text-slate-500" title="{{ $source }}">{{ $source }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-700">
                                <span class="font-semibold">{{ $item->check_in_time?->format('H:i') ?? '--:--' }}</span>
                                <span class="mx-2 text-slate-300">→</span>
                                <span class="font-semibold">{{ $item->check_out_time?->format('H:i') ?? '--:--' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if(in_array($item->attendance_status, ['Leave', 'Permission', 'Absent'], true))
                                    <span class="text-xs text-slate-500">Tidak ada metrik kerja.</span>
                                @elseif(!$item->check_in_time)
                                    <span class="text-xs text-slate-500">Belum Check In.</span>
                                @else
                                    <div class="flex max-w-md flex-wrap gap-x-4 gap-y-1 text-xs text-slate-600">
                                        @if($item->work_minutes)
                                            <span><span class="font-semibold text-slate-800">Kerja</span> {{ $minutesLabel($item->work_minutes) }}</span>
                                        @endif
                                        @if($item->late_minutes)
                                            <span><span class="font-semibold text-slate-800">Telat</span> {{ $minutesLabel($item->late_minutes) }}</span>
                                        @endif
                                        @if($item->early_leave_minutes)
                                            <span><span class="font-semibold text-slate-800">Pulang awal</span> {{ $minutesLabel($item->early_leave_minutes) }}</span>
                                        @endif
                                        @if($item->overtime_minutes)
                                            <span><span class="font-semibold text-slate-800">Lembur</span> {{ $minutesLabel($item->overtime_minutes) }}</span>
                                        @endif
                                        @if(!$item->check_out_time)
                                            <span class="font-medium text-slate-500">Belum Check Out</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400"><i data-lucide="calendar-search" class="h-6 w-6"></i></div>
                                <h3 class="mt-4 font-bold text-slate-900">Tidak ada riwayat</h3>
                                <p class="mt-1 text-sm text-slate-500">Tidak ada attendance yang cocok dengan filter periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($history->hasPages())
        <div>{{ $history->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
