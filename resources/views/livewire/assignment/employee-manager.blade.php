<div wire:key="employee-manager-{{ $assignment->id }}">
    <x-assignment.section-card
        title="Team & Review"
        description="Pantau progress tiap employee, Daily Attendance, hasil pekerjaan, revisi, dan koreksi Check Out."
        icon="users">

        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-sm text-slate-500">
                <span class="font-semibold text-slate-800">{{ $employees->count() }} employee</span> ditugaskan pada assignment ini.
            </div>
            <button
                type="button"
                wire:click="openPicker"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                <i data-lucide="user-plus" class="h-4 w-4"></i>
                Kelola Employee
            </button>
        </div>

        @if($successMessage)
            <div class="mb-4 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ $successMessage }}
            </div>
        @endif

        @if($errorMessage)
            <div class="mb-4 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ $errorMessage }}
            </div>
        @endif

        @if($employees->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 py-12 text-center">
                <i data-lucide="users" class="mx-auto h-10 w-10 text-slate-300"></i>
                <h3 class="mt-3 font-semibold text-slate-700">Belum ada employee</h3>
                <p class="mt-1 text-sm text-slate-500">Tambahkan employee untuk mulai mendistribusikan assignment.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                @foreach($employees as $employee)
                    @php
                        $status = $employee->pivot?->status ?? 'Assigned';
                        $reviewStatus = $employee->pivot?->review_status;
                        $displayStatus = match($reviewStatus) {
                            'Needs Revision' => 'Needs Revision',
                            'Pending Review' => 'Pending Review',
                            'Approved' => 'Completed',
                            'Not Worked', 'Expired' => 'Not Worked',
                            default => $status,
                        };

                        $statusClass = match($displayStatus) {
                            'Needs Revision', 'Rejected', 'Not Worked' => 'bg-red-50 text-red-700 border-red-100',
                            'Pending Review' => 'bg-amber-50 text-amber-700 border-amber-100',
                            'Completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            default => 'bg-blue-50 text-blue-700 border-blue-100',
                        };

                        $dailyState = $dailyAttendanceByEmployee[(int)$employee->id] ?? null;
                        $dailySummary = $dailyState['summary'] ?? null;
                        $dailyRows = collect($dailyState['calendar'] ?? []);
                        $employeeCorrections = collect($checkoutCorrectionsByEmployee->get($employee->id, collect()));
                        $pendingCorrections = $employeeCorrections->where('status', 'Pending')->count();
                        $problemDays = (int)(($dailySummary['absent_days'] ?? 0) + ($dailySummary['incomplete_days'] ?? 0));
                        $workMinutes = (int)($dailySummary['work_minutes'] ?? 0);
                        $workLabel = $workMinutes > 0
                            ? (intdiv($workMinutes, 60).'j '.($workMinutes % 60).'m')
                            : '0m';
                    @endphp

                    <details wire:key="assigned-{{ $employee->id }}" class="group {{ !$loop->last ? 'border-b border-slate-200' : '' }}">
                        <summary class="cursor-pointer list-none px-5 py-4 transition hover:bg-slate-50 [&::-webkit-details-marker]:hidden">
                            <div class="flex items-center gap-4">
                                @if($employee->photo)
                                    <img src="{{ secure_file_url($employee->photo) }}" class="h-11 w-11 shrink-0 rounded-full object-cover ring-1 ring-slate-200">
                                @else
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                        <i data-lucide="user" class="h-5 w-5"></i>
                                    </div>
                                @endif

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="truncate text-sm font-bold text-slate-900">{{ $employee->full_name }}</h3>
                                        <span class="inline-flex rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $statusClass }}">{{ $displayStatus }}</span>
                                        @if($pendingCorrections > 0)
                                            <span class="inline-flex rounded-full border border-blue-100 bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                                {{ $pendingCorrections }} koreksi menunggu review
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-1 truncate text-xs text-slate-500">
                                        {{ $employee->currentEmployment?->position?->name ?? '-' }} · {{ $employee->currentEmployment?->office?->name ?? '-' }}
                                    </p>
                                </div>

                                @if($assignment->daily_attendance_enabled && $dailySummary)
                                    <div class="hidden shrink-0 items-center gap-6 lg:flex">
                                        <div class="text-right">
                                            <p class="text-[11px] font-medium text-slate-400">Kehadiran</p>
                                            <p class="mt-0.5 text-sm font-bold text-slate-800">{{ number_format((float)($dailySummary['attendance_rate'] ?? 0), 1) }}%</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[11px] font-medium text-slate-400">Selesai</p>
                                            <p class="mt-0.5 text-sm font-bold text-slate-800">{{ $dailySummary['completed_days'] ?? 0 }}/{{ $dailySummary['required_days'] ?? 0 }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[11px] font-medium text-slate-400">Masalah</p>
                                            <p class="mt-0.5 text-sm font-bold {{ $problemDays > 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $problemDays }}</p>
                                        </div>
                                    </div>
                                @endif

                                <i data-lucide="chevron-down" class="h-5 w-5 shrink-0 text-slate-400 transition group-open:rotate-180"></i>
                            </div>
                        </summary>

                        <div class="border-t border-slate-100 bg-slate-50/60 px-5 py-5">
                            @if($assignment->daily_attendance_enabled && $dailySummary)
                                <section>
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-800">Daily Attendance</h4>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $assignment->attendance_day_rule === 'EVERY_DAY' ? 'Setiap hari kalender' : 'Mengikuti Work Calendar company' }}
                                            </p>
                                        </div>
                                        <div class="flex flex-wrap gap-2 text-xs">
                                            <span class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 font-semibold text-slate-600">Hadir {{ $dailySummary['attended_days'] ?? 0 }}/{{ $dailySummary['required_days'] ?? 0 }}</span>
                                            <span class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 font-semibold text-slate-600">Selesai {{ $dailySummary['completed_days'] ?? 0 }}/{{ $dailySummary['required_days'] ?? 0 }}</span>
                                            <span class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 font-semibold text-slate-600">Kerja {{ $workLabel }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200 bg-white">
                                        <table class="min-w-full text-sm">
                                            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                <tr>
                                                    <th class="px-4 py-3">Tanggal</th>
                                                    <th class="px-4 py-3">Status</th>
                                                    <th class="px-4 py-3">Check In / Out</th>
                                                    <th class="px-4 py-3">Ringkasan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                @foreach($dailyRows as $row)
                                                    @php
                                                        $dayLabel = match($row['status'] ?? 'UPCOMING') {
                                                            'PRESENT' => 'Selesai',
                                                            'LATE' => 'Selesai · Terlambat',
                                                            'WORKING' => 'Sedang Bekerja',
                                                            'INCOMPLETE' => 'Belum Check Out',
                                                            'ABSENT' => 'Tidak Hadir',
                                                            'TODAY' => 'Belum Check In',
                                                            'OFF' => 'Libur',
                                                            default => 'Akan Datang',
                                                        };
                                                        $dayClass = match($row['status'] ?? 'UPCOMING') {
                                                            'INCOMPLETE', 'ABSENT' => 'text-red-600',
                                                            'LATE' => 'text-amber-700',
                                                            'PRESENT' => 'text-emerald-700',
                                                            default => 'text-slate-600',
                                                        };
                                                        $metrics = [];
                                                        if (($row['late_minutes'] ?? 0) > 0) $metrics[] = 'Telat '.$row['late_minutes'].'m';
                                                        if (($row['work_minutes'] ?? 0) > 0) $metrics[] = 'Kerja '.intdiv((int)$row['work_minutes'], 60).'j '.((int)$row['work_minutes'] % 60).'m';
                                                        if (($row['early_leave_minutes'] ?? 0) > 0) $metrics[] = 'Pulang awal '.$row['early_leave_minutes'].'m';
                                                        if (($row['overtime_minutes'] ?? 0) > 0) $metrics[] = 'Lembur '.$row['overtime_minutes'].'m';
                                                    @endphp
                                                    <tr class="{{ ($row['date'] ?? '') === today()->toDateString() ? 'bg-blue-50/40' : '' }}">
                                                        <td class="whitespace-nowrap px-4 py-3 font-semibold text-slate-700">{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                                                        <td class="whitespace-nowrap px-4 py-3 text-xs font-semibold {{ $dayClass }}">{{ $dayLabel }}</td>
                                                        <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $row['check_in'] ?: '--:--' }} → {{ $row['check_out'] ?: '--:--' }}</td>
                                                        <td class="px-4 py-3 text-xs text-slate-500">{{ $metrics ? implode(' · ', $metrics) : 'Belum ada metrik kerja' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            @endif

                            @if($employeeCorrections->isNotEmpty())
                                <section class="mt-5 border-t border-slate-200 pt-5">
                                    <h4 class="text-sm font-bold text-slate-800">Koreksi Check Out</h4>
                                    <div class="mt-3 space-y-2">
                                        @foreach($employeeCorrections as $correction)
                                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-800">{{ optional($correction->attendance?->attendance_date)->format('d M Y') }} · usulan {{ substr((string)$correction->requested_check_out_time,0,5) }}</p>
                                                        <p class="mt-1 text-xs text-slate-500">{{ $correction->reason }}</p>
                                                    </div>
                                                    <span class="w-fit rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $correction->status }}</span>
                                                </div>
                                                @if($correction->status === 'Pending')
                                                    <div class="mt-3 flex justify-end gap-2">
                                                        <button type="button" wire:click="rejectCheckoutCorrection({{ $correction->id }})" wire:confirm="Tolak koreksi Check Out ini?" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Tolak</button>
                                                        <button type="button" wire:click="approveCheckoutCorrection({{ $correction->id }})" wire:confirm="Setujui jam Check Out {{ substr((string)$correction->requested_check_out_time,0,5) }}?" class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">Setujui</button>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif

                            @if($employee->pivot?->completion_photo || $employee->pivot?->completion_notes || $reviewStatus)
                                <section class="mt-5 border-t border-slate-200 pt-5">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-800">Hasil Pekerjaan & Review</h4>
                                            <p class="mt-1 text-xs text-slate-500">Bukti pekerjaan, catatan employee, dan keputusan Company.</p>
                                        </div>
                                        @if($reviewStatus)
                                            <span class="rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $displayStatus }}</span>
                                        @endif
                                    </div>

                                    @if($employee->pivot?->completion_photo)
                                        <div class="mt-4 flex flex-wrap gap-3">
                                            <a href="{{ secure_file_url($employee->pivot->completion_photo) }}" target="_blank">
                                                <img src="{{ secure_file_url($employee->pivot->completion_photo) }}" class="h-24 w-24 rounded-xl object-cover ring-1 ring-slate-200">
                                            </a>
                                            @if($employee->pivot->completion_photo_2)
                                                <a href="{{ secure_file_url($employee->pivot->completion_photo_2) }}" target="_blank">
                                                    <img src="{{ secure_file_url($employee->pivot->completion_photo_2) }}" class="h-24 w-24 rounded-xl object-cover ring-1 ring-slate-200">
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    @if($employee->pivot?->completion_notes)
                                        <div class="mt-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-6 text-slate-600">
                                            {{ $employee->pivot->completion_notes }}
                                        </div>
                                    @endif

                                    @if($employee->pivot?->status === 'Rejected' && $employee->pivot?->rejection_reason)
                                        <div class="mt-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-xs text-red-700">
                                            <strong>Alasan penolakan assignment:</strong> {{ $employee->pivot->rejection_reason }}
                                        </div>
                                    @endif

                                    @if($reviewStatus === 'Needs Revision' && $employee->pivot?->review_notes)
                                        <div class="mt-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-xs text-red-700">
                                            <strong>Catatan revisi:</strong> {{ $employee->pivot->review_notes }}
                                        </div>
                                    @endif

                                    @if($employee->pivot?->is_late_revision)
                                        <p class="mt-3 text-xs font-semibold text-amber-700">Revisi dikirim setelah batas waktu revisi.</p>
                                    @endif

                                    @if($reviewStatus === 'Pending Review')
                                        <div class="mt-4 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                                            <button
                                                type="button"
                                                wire:click="openReject({{ $employee->id }})"
                                                class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                Minta Revisi
                                            </button>
                                            <button
                                                type="button"
                                                wire:click="approveCompletion({{ $employee->id }})"
                                                wire:confirm="Setujui hasil kerja {{ $employee->full_name }}?"
                                                class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                                                Setujui Hasil
                                            </button>
                                        </div>
                                    @endif
                                </section>
                            @endif

                            @if(in_array($status, ['Assigned', 'Accepted']))
                                <div class="mt-5 flex justify-end border-t border-slate-200 pt-4">
                                    <button
                                        type="button"
                                        wire:click="removeEmployee({{ $employee->id }})"
                                        wire:confirm="Hapus employee ini dari assignment?"
                                        class="text-xs font-semibold text-red-600 hover:text-red-700">
                                        Hapus dari Assignment
                                    </button>
                                </div>
                            @endif
                        </div>
                    </details>
                @endforeach
            </div>
        @endif
    </x-assignment.section-card>

    @if($reviewingEmployeeId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4">
            <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-bold text-slate-900">Minta Revisi Hasil Kerja</h3>
                <p class="mt-1 text-sm text-slate-500">Employee akan diminta memperbaiki dan submit ulang hasil pekerjaan.</p>

                <div class="mt-5">
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Catatan Revisi *</label>
                    <textarea wire:model="rejectNotes" rows="4" placeholder="Jelaskan bagian yang perlu diperbaiki..." class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100"></textarea>
                    @error('rejectNotes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="mt-4">
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Durasi Revisi (menit, opsional)</label>
                    <input type="number" wire:model="rejectMinutes" placeholder="Gunakan default Company jika kosong" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    @error('rejectMinutes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" wire:click="closeReject" class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="button" wire:click="rejectCompletion" class="flex-1 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Kirim Permintaan Revisi</button>
                </div>
            </div>
        </div>
    @endif

    @if($showPicker)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:click.self="closePicker">
            <div class="max-h-[85vh] w-full max-w-3xl overflow-hidden rounded-3xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Tambah Employee</h3>
                        <p class="mt-1 text-xs text-slate-500">Pilih employee aktif yang ingin ditambahkan ke assignment.</p>
                    </div>
                    <button type="button" wire:click="closePicker" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <div class="grid gap-3 border-b border-slate-200 bg-slate-50/60 px-6 py-4 md:grid-cols-2">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari employee..." class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    <select wire:model.live="busyFilter" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm">
                        <option value="">Semua Employee</option>
                        <option value="free">Tersedia</option>
                        <option value="busy">Punya Assignment Aktif</option>
                    </select>
                </div>

                <div class="max-h-[50vh] overflow-y-auto px-6 py-4">
                    <div wire:loading.class="opacity-40" wire:target="search,busyFilter" class="space-y-2 transition-opacity">
                        @forelse($availableEmployees as $employee)
                            <div wire:key="picker-{{ $employee->id }}" class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 px-4 py-3 hover:border-blue-300 hover:bg-blue-50/30">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 font-bold text-blue-600">{{ strtoupper(substr($employee->full_name, 0, 1)) }}</div>
                                    <div class="min-w-0">
                                        <div class="truncate font-semibold text-slate-800">{{ $employee->full_name }}</div>
                                        <div class="truncate text-xs text-slate-500">{{ $employee->currentEmployment?->position?->name ?? '-' }} · {{ $employee->currentEmployment?->office?->name ?? '-' }}</div>
                                    </div>
                                </div>
                                <button type="button" wire:click="addEmployee({{ $employee->id }})" wire:loading.attr="disabled" class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">Tambah</button>
                            </div>
                        @empty
                            <p class="py-8 text-center text-sm text-slate-400">Employee tidak ditemukan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
