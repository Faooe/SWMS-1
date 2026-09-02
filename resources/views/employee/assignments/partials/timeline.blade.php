@php
    $logs = collect($visibleLogs ?? []);
    $formatMinutes = static function (int $minutes): string {
        if ($minutes <= 0) return '0m';
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;
        return $hours > 0 ? $hours.'j '.($mins > 0 ? $mins.'m' : '') : $mins.'m';
    };
@endphp

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                <i data-lucide="history" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-900">Riwayat Aktivitas</h2>
                <p class="text-xs text-slate-500">Aktivitas assignment dan attendance milik kamu.</p>
            </div>
        </div>
        @if($logs->isNotEmpty())
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">{{ $logs->count() }} aktivitas</span>
        @endif
    </div>

    @if($logs->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center">
            <i data-lucide="history" class="mx-auto h-9 w-9 text-slate-300"></i>
            <p class="mt-3 font-semibold text-slate-600">Belum ada aktivitas</p>
            <p class="mt-1 text-sm text-slate-400">Check In, Check Out, submit, dan review akan muncul di sini.</p>
        </div>
    @else
        <div class="relative space-y-0">
            <div class="absolute bottom-5 left-[19px] top-5 w-px bg-slate-200"></div>

            @foreach($logs->sortByDesc('created_at')->values() as $log)
                @php
                    $action = $log['action'] ?? 'ACTIVITY';
                    $properties = (array)($log['properties'] ?? []);

                    [$label, $icon, $dotClass] = match($action) {
                        'ASSIGNMENT_CREATED' => ['Assignment dibuat', 'file-plus-2', 'bg-blue-500'],
                        'ASSIGNMENT_UPDATED' => ['Assignment diperbarui', 'square-pen', 'bg-slate-500'],
                        'EMPLOYEE_ASSIGNED' => ['Ditugaskan ke assignment', 'user-plus', 'bg-indigo-500'],
                        'EMPLOYEE_ACCEPTED' => ['Assignment diterima', 'thumbs-up', 'bg-cyan-500'],
                        'EMPLOYEE_REJECTED' => ['Assignment ditolak', 'thumbs-down', 'bg-rose-500'],
                        'EMPLOYEE_CHECKED_IN' => ['Check In', 'log-in', 'bg-emerald-500'],
                        'EMPLOYEE_AUTO_CHECKED_IN' => ['Check In otomatis', 'log-in', 'bg-emerald-500'],
                        'EMPLOYEE_CHECKED_OUT' => ['Check Out', 'log-out', 'bg-blue-600'],
                        'EMPLOYEE_COMPLETED' => ['Hasil pekerjaan dikirim', 'send', 'bg-violet-500'],
                        'EMPLOYEE_RESUBMITTED' => ['Hasil revisi dikirim', 'send-horizontal', 'bg-violet-600'],
                        'COMPLETION_APPROVED' => ['Hasil disetujui', 'badge-check', 'bg-emerald-600'],
                        'AUTO_APPROVED' => ['Disetujui otomatis', 'badge-check', 'bg-emerald-600'],
                        'COMPLETION_REJECTED' => ['Perlu revisi', 'rotate-ccw', 'bg-amber-500'],
                        'ASSIGNMENT_NOT_WORKED', 'REVISION_NOT_WORKED' => ['Tidak dikerjakan', 'clock-x', 'bg-slate-600'],
                        default => [\Illuminate\Support\Str::headline(strtolower($action)), 'circle-dot', 'bg-slate-400'],
                    };

                    $metrics = [];
                    if (!empty($properties['attendance_date'])) $metrics[] = 'Tanggal '.\Carbon\Carbon::parse($properties['attendance_date'])->format('d M Y');
                    if (($properties['late_minutes'] ?? 0) > 0) $metrics[] = 'Telat '.$formatMinutes((int)$properties['late_minutes']);
                    if (($properties['work_minutes'] ?? 0) > 0) $metrics[] = 'Kerja '.$formatMinutes((int)$properties['work_minutes']);
                    if (($properties['early_leave_minutes'] ?? 0) > 0) $metrics[] = 'Pulang awal '.$formatMinutes((int)$properties['early_leave_minutes']);
                    if (($properties['overtime_minutes'] ?? 0) > 0) $metrics[] = 'Lembur '.$formatMinutes((int)$properties['overtime_minutes']);
                    if (($properties['evidence_count'] ?? 0) > 0) $metrics[] = $properties['evidence_count'].' bukti foto';
                    if (!empty($properties['late_revision'])) $metrics[] = 'Revisi terlambat';
                    $createdAt = !empty($log['created_at']) ? \Carbon\Carbon::parse($log['created_at']) : null;
                @endphp

                <div class="relative flex gap-4 pb-6 last:pb-0">
                    <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $dotClass }} text-white shadow-sm ring-4 ring-white">
                        <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
                    </div>

                    <div class="min-w-0 flex-1 rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="font-bold text-slate-800">{{ $label }}</p>
                                @if(!empty($log['description']))
                                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $log['description'] }}</p>
                                @endif
                            </div>
                            @if($createdAt)
                                <span class="shrink-0 text-xs font-medium text-slate-400">{{ $createdAt->format('d M Y • H:i') }}</span>
                            @endif
                        </div>

                        @if($metrics)
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach($metrics as $metric)
                                    <span class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-600">{{ $metric }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
