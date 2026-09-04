@php
    $officeWorkMinutes = (int) ($officeAttendance?->work_minutes ?? 0);
    $officeWorkHours = intdiv($officeWorkMinutes, 60);
    $officeWorkRest = $officeWorkMinutes % 60;
    $officeWorkLabel = $officeWorkMinutes > 0
        ? trim(($officeWorkHours > 0 ? $officeWorkHours.'j ' : '').($officeWorkRest > 0 ? $officeWorkRest.'m' : ''))
        : '-';

    $officeStatusLabel = match ($officeAttendance?->attendance_status) {
        'Present' => 'Hadir',
        'Late' => 'Terlambat',
        'Leave' => 'Cuti',
        'Permission' => 'Izin',
        'Absent' => 'Tidak Hadir',
        default => 'Belum Check In',
    };

    $officeStatusClass = match ($officeAttendance?->attendance_status) {
        'Present' => 'bg-emerald-50 text-emerald-700',
        'Late' => 'bg-amber-50 text-amber-700',
        'Absent' => 'bg-red-50 text-red-700',
        default => 'bg-slate-100 text-slate-600',
    };
@endphp

<section
    id="office-card"
    data-office-lat="{{ $office->latitude ?? '' }}"
    data-office-lng="{{ $office->longitude ?? '' }}"
    data-office-radius="{{ $office->radius ?? '' }}"
    data-office-polygon="{{ $office && $office->polygon ? json_encode($office->polygon) : '' }}"
    class="min-w-0 px-5 py-5 sm:px-6 sm:py-6"
>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                <i data-lucide="building-2" class="h-5 w-5"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-900">Attendance Office</p>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $office?->name ?? 'Office belum ditentukan' }}
                </p>
            </div>
        </div>

        <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $officeStatusClass }}">
            {{ $officeStatusLabel }}
        </span>
    </div>

    @if(!$office)
        <div class="mt-5 rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-5 py-8 text-center">
            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-white text-slate-400 shadow-sm">
                <i data-lucide="building-2" class="h-5 w-5"></i>
            </div>
            <p class="mt-3 text-sm font-semibold text-slate-700">Office belum tersedia</p>
            <p class="mt-1 text-sm text-slate-500">Hubungi Company Admin untuk mengatur penempatan office kamu.</p>
        </div>
    @else
        <div class="mt-5 grid grid-cols-3 divide-x divide-slate-100 rounded-2xl border border-slate-200 bg-slate-50/70 py-4">
            <div class="px-4">
                <p class="text-xs font-medium text-slate-400">Check In</p>
                <p class="mt-1 text-xl font-bold tracking-tight text-slate-900">{{ $officeAttendance?->check_in_time?->format('H:i') ?? '-' }}</p>
            </div>
            <div class="px-4">
                <p class="text-xs font-medium text-slate-400">Check Out</p>
                <p class="mt-1 text-xl font-bold tracking-tight text-slate-900">{{ $officeAttendance?->check_out_time?->format('H:i') ?? '-' }}</p>
            </div>
            <div class="px-4">
                <p class="text-xs font-medium text-slate-400">Jam Kerja</p>
                <p class="mt-1 text-xl font-bold tracking-tight text-slate-900">{{ $officeWorkLabel }}</p>
            </div>
        </div>

        @if(($officeAttendance?->late_minutes ?? 0) > 0)
            <div class="mt-4 flex items-center gap-2 text-sm text-amber-700">
                <i data-lucide="clock-3" class="h-4 w-4"></i>
                <span>Terlambat {{ (int) $officeAttendance->late_minutes }} menit</span>
            </div>
        @endif

        <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-slate-100 pt-4 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1.5">
                <i data-lucide="navigation" class="h-3.5 w-3.5"></i>
                Jarak: <strong id="office-distance" class="font-semibold text-slate-700">-</strong>
            </span>
            <span>Radius: <strong class="font-semibold text-slate-700">{{ $office->radius }} m</strong></span>
            <span class="inline-flex items-center gap-1.5">
                <span id="office-location-status" class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-500">Mencari lokasi...</span>
            </span>
        </div>

        <div class="mt-3 hidden text-[11px] text-slate-400 sm:flex sm:items-center sm:gap-4">
            <span>Lat <span id="current-lat" class="font-mono">-</span></span>
            <span>Lng <span id="current-lng" class="font-mono">-</span></span>
        </div>

        <div id="office-mini-map" class="mt-4 h-52 w-full overflow-hidden rounded-2xl border border-slate-200 bg-slate-50"></div>

        <div class="mt-5">
            @if(!$officeAttendance || !$officeAttendance->hasCheckedIn())
                <button
                    type="button"
                    id="office-check-in-btn"
                    disabled
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <i data-lucide="log-in" class="h-4 w-4"></i>
                    Check In Office
                </button>
            @elseif(!$officeAttendance->hasCheckedOut())
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        Kamu check in pada <span class="font-semibold text-slate-900">{{ $officeAttendance->check_in_time?->format('H:i') }}</span>
                    </div>
                    <button
                        type="button"
                        id="office-check-out-btn"
                        disabled
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-blue-200 bg-white px-4 py-3 text-sm font-semibold text-blue-700 transition hover:bg-blue-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                        Check Out Office
                    </button>
                </div>
            @else
                <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    <i data-lucide="circle-check" class="h-5 w-5"></i>
                    <span class="font-semibold">Attendance office selesai hari ini.</span>
                </div>
            @endif
        </div>
    @endif
</section>
