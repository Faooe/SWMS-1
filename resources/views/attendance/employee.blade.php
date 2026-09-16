@extends('layouts.app')

@section('title', 'Detail Absensi Employee')

@section('content')
<div class="space-y-6 pb-20">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm font-medium text-slate-500">
                <a href="{{ route('attendance.index') }}" class="transition hover:text-blue-600">Attendance</a>
                <span>/</span>
                <span>Rekap Employee</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Detail Absensi Employee</h1>
            <p class="mt-1 text-sm text-slate-500">Log attendance {{ $periodLabel }} untuk employee yang dipilih.</p>
        </div>

        <a href="{{ route('attendance.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke Rekap
        </a>
    </div>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-5 border-b border-slate-100 bg-gradient-to-br from-blue-50/70 via-white to-white px-6 py-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                @if($employee->photo)
                    <img src="{{ secure_file_url($employee->photo) }}" alt="{{ $employee->full_name }}" class="h-16 w-16 rounded-2xl border border-blue-100 object-cover shadow-sm">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-xl font-bold text-blue-700">
                        {{ strtoupper(substr($employee->full_name ?? '?', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $employee->full_name }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $employee->employee_number ?: '-' }}</p>
                    <p class="mt-1 text-xs text-slate-500">
                        {{ $employee->currentEmployment?->position?->name ?: 'Position belum diatur' }}
                        <span class="mx-1 text-slate-300">•</span>
                        {{ $employee->currentEmployment?->office?->name ?: 'Office belum diatur' }}
                    </p>
                </div>
            </div>
            <div class="inline-flex items-center gap-2 self-start rounded-xl border border-blue-100 bg-white px-4 py-3 text-sm font-semibold text-slate-700 sm:self-center">
                <i data-lucide="calendar-range" class="h-4 w-4 text-blue-600"></i>
                <span>{{ $periodLabel }}</span>
            </div>
        </div>

        <div class="border-b border-slate-100 px-6 py-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-slate-900">Log Attendance</h3>
                    <p class="mt-1 text-xs text-slate-500">{{ $attendances->total() }} catatan pada periode terpilih.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">
                    <i data-lucide="list-checks" class="h-3.5 w-3.5"></i>
                    Terbaru di atas
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/80">
                    <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Check In</th>
                        <th class="px-4 py-3">Check Out</th>
                        <th class="px-4 py-3">Office / Assignment</th>
                        <th class="px-6 py-3 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($attendances as $attendance)
                        @php
                            $statusMeta = match($attendance->attendance_status) {
                                'Present' => ['label' => 'Hadir', 'class' => 'bg-emerald-50 text-emerald-700', 'icon' => 'circle-check'],
                                'Late' => ['label' => 'Terlambat', 'class' => 'bg-amber-50 text-amber-700', 'icon' => 'clock-3'],
                                'Leave' => ['label' => 'Cuti', 'class' => 'bg-violet-50 text-violet-700', 'icon' => 'calendar-days'],
                                'Permission' => ['label' => 'Izin', 'class' => 'bg-blue-50 text-blue-700', 'icon' => 'file-check-2'],
                                'Absent' => ['label' => 'Absen', 'class' => 'bg-red-50 text-red-700', 'icon' => 'circle-x'],
                                default => ['label' => $attendance->attendance_status ?: '-', 'class' => 'bg-slate-100 text-slate-700', 'icon' => 'circle-help'],
                            };
                        @endphp
                        <tr class="transition hover:bg-blue-50/40">
                            <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-800">
                                {{ $attendance->attendance_date?->format('d M Y') ?: '-' }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1.5 text-xs font-bold {{ $statusMeta['class'] }}">
                                    <i data-lucide="{{ $statusMeta['icon'] }}" class="h-3.5 w-3.5"></i>
                                    {{ $statusMeta['label'] }}
                                </span>
                                @if(($attendance->late_minutes ?? 0) > 0)
                                    <div class="mt-1 text-xs font-semibold text-amber-600">+{{ $attendance->late_minutes }} menit</div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ $attendance->check_in_time?->format('H:i') ?: '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ $attendance->check_out_time?->format('H:i') ?: '-' }}</td>
                            <td class="px-4 py-4">
                                <div class="font-semibold text-slate-700">{{ $attendance->office?->name ?: '-' }}</div>
                                @if($attendance->assignment?->title)
                                    <div class="mt-1 text-xs text-blue-600">Assignment · {{ $attendance->assignment->title }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('attendance.show', $attendance->id) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                    Buka
                                    <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><i data-lucide="calendar-off" class="h-6 w-6"></i></div>
                                <p class="mt-3 font-semibold text-slate-700">Belum ada log attendance</p>
                                <p class="mt-1 text-sm text-slate-500">Tidak ada data pada periode yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $attendances->withQueryString()->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
