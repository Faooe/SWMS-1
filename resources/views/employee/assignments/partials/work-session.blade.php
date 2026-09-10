@php
    $workCheckInRaw = data_get($assignmentState ?? [], 'my_work_check_in_at');
    $workCheckOutRaw = data_get($assignmentState ?? [], 'my_work_check_out_at');

    $workCheckIn = $workCheckInRaw ? \Carbon\Carbon::parse($workCheckInRaw) : null;
    $workCheckOut = $workCheckOutRaw ? \Carbon\Carbon::parse($workCheckOutRaw) : null;

    $sessionStatus = 'Belum Dimulai';
    $sessionBadge = 'bg-slate-100 text-slate-600 ring-slate-200';
    $sessionIcon = 'clock-3';

    if ($workCheckIn && !$workCheckOut) {
        $sessionStatus = 'Sedang Dikerjakan';
        $sessionBadge = 'bg-amber-50 text-amber-700 ring-amber-100';
        $sessionIcon = 'loader-circle';
    } elseif ($workCheckIn && $workCheckOut) {
        $sessionStatus = 'Selesai';
        $sessionBadge = 'bg-emerald-50 text-emerald-700 ring-emerald-100';
        $sessionIcon = 'badge-check';
    }

    $sessionDuration = null;
    if ($workCheckIn && $workCheckOut) {
        $minutes = max(0, $workCheckIn->diffInMinutes($workCheckOut));
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;
        $sessionDuration = $hours > 0
            ? $hours.' jam'.($mins > 0 ? ' '.$mins.' menit' : '')
            : $mins.' menit';
    }
@endphp

<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i data-lucide="timer" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="font-black text-slate-900">Sesi Pekerjaan Saya</h2>
                <p class="mt-0.5 text-sm text-slate-500">Waktu aktual saat kamu mulai dan menyelesaikan assignment.</p>
            </div>
        </div>

        <span class="inline-flex w-fit items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold ring-1 ring-inset {{ $sessionBadge }}">
            <i data-lucide="{{ $sessionIcon }}" class="h-3.5 w-3.5 {{ $sessionStatus === 'Sedang Dikerjakan' ? 'animate-spin' : '' }}"></i>
            {{ $sessionStatus }}
        </span>
    </div>

    <div class="grid gap-px bg-slate-100 sm:grid-cols-2">
        <div class="bg-white px-5 py-5 sm:px-6">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="log-in" class="h-4 w-4"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">Mulai Pekerjaan</p>
                    @if($workCheckIn)
                        <p class="mt-1 font-bold text-slate-800">{{ $workCheckIn->format('d M Y') }}</p>
                        <p class="mt-0.5 text-sm font-semibold text-slate-500">{{ $workCheckIn->format('H:i') }}</p>
                    @else
                        <p class="mt-1 font-semibold text-slate-400">Belum Check In Assignment</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white px-5 py-5 sm:px-6">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i data-lucide="log-out" class="h-4 w-4"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">Selesai Pekerjaan</p>
                    @if($workCheckOut)
                        <p class="mt-1 font-bold text-slate-800">{{ $workCheckOut->format('d M Y') }}</p>
                        <p class="mt-0.5 text-sm font-semibold text-slate-500">{{ $workCheckOut->format('H:i') }}</p>
                    @elseif($workCheckIn)
                        <p class="mt-1 font-semibold text-amber-600">Masih dikerjakan</p>
                    @else
                        <p class="mt-1 font-semibold text-slate-400">Belum selesai</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <p class="text-xs leading-relaxed text-slate-500">
            Check In Assignment menandai mulai pekerjaan. Menyelesaikan assignment menandai akhir sesi pekerjaan dan tidak sama dengan Check Out Attendance harian.
        </p>
        @if($sessionDuration)
            <div class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700">
                <i data-lucide="timer-reset" class="h-4 w-4"></i>
                Durasi {{ $sessionDuration }}
            </div>
        @endif
    </div>
</div>
