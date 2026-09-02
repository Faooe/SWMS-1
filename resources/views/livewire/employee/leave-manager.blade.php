<div class="space-y-6">
    @if($successMessage)
        <div class="flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-sm text-blue-800">
            <i data-lucide="circle-check" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span class="font-medium">{{ $successMessage }}</span>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => 'Pengajuan Tahun Ini', 'value' => $summary['total'], 'icon' => 'files'],
            ['label' => 'Menunggu Review', 'value' => $summary['pending'], 'icon' => 'clock-3'],
            ['label' => 'Disetujui', 'value' => $summary['approved'], 'icon' => 'circle-check'],
            ['label' => 'Ditolak', 'value' => $summary['rejected'], 'icon' => 'circle-x'],
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

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="calendar-days" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Kuota Cuti {{ $quota['year'] }}</h3>
                        <p class="mt-1 text-sm text-slate-500">Hanya jenis Cuti yang mengurangi kuota tahunan.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 text-center sm:min-w-[340px]">
                <div>
                    <p class="text-2xl font-bold text-blue-600">{{ $quota['remaining_days'] }}</p>
                    <p class="text-xs text-slate-500">Sisa</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $quota['used_days'] }}</p>
                    <p class="text-xs text-slate-500">Terpakai</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $quota['total_days'] }}</p>
                    <p class="text-xs text-slate-500">Total</p>
                </div>
            </div>
        </div>

        @php
            $quotaPercent = $quota['total_days'] > 0
                ? min(100, round(($quota['used_days'] / $quota['total_days']) * 100))
                : 0;
        @endphp
        <div class="mt-5 h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full bg-blue-600" style="width: {{ $quotaPercent }}%"></div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[380px_minmax(0,1fr)]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-start gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="file-plus-2" class="h-5 w-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Ajukan Leave / Permission</h3>
                    <p class="mt-1 text-xs leading-5 text-slate-500">Cuti maksimal 12 hari. Sakit dan Acara maksimal 3 hari per pengajuan.</p>
                </div>
            </div>

            <form wire:submit="submit" class="space-y-4">
                <x-ui.select
                    wire:model="type"
                    name="type"
                    label="Jenis Pengajuan"
                    :options="['Cuti' => 'Cuti', 'Sakit' => 'Sakit', 'Acara' => 'Acara / Keperluan Pribadi']"
                    placeholder="Pilih jenis"
                    required />

                <div class="grid grid-cols-2 gap-3">
                    <x-ui.input wire:model="start_date" name="start_date" type="date" label="Mulai" required />
                    <x-ui.input wire:model="end_date" name="end_date" type="date" label="Selesai" required />
                </div>

                <x-ui.textarea
                    wire:model="reason"
                    name="reason"
                    label="Alasan"
                    placeholder="Jelaskan alasan pengajuan secara singkat dan jelas..."
                    required />

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60">
                    <i data-lucide="send" class="h-4 w-4"></i>
                    <span wire:loading.remove wire:target="submit">Kirim Pengajuan</span>
                    <span wire:loading wire:target="submit">Mengirim...</span>
                </button>
            </form>
        </div>

        <div class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900">Riwayat Pengajuan</h3>
                        <p class="mt-1 text-sm text-slate-500">Pantau status dan hasil review pengajuan kamu.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <select wire:model.live="statusFilter" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            <option value="">Semua Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                        <select wire:model.live="typeFilter" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            <option value="">Semua Jenis</option>
                            <option value="Cuti">Cuti</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Acara">Acara</option>
                        </select>
                        @if($statusFilter || $typeFilter)
                            <button wire:click="resetFilters" type="button" class="rounded-xl border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Reset</button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-3" wire:loading.class="opacity-50" wire:target="submit,statusFilter,typeFilter,previousPage,nextPage,gotoPage">
                @forelse($leaves as $leave)
                    @php
                        $isAutoRejected = $leave->isRejected() && $leave->approved_by === null;
                        $statusLabel = $isAutoRejected ? 'Auto Rejected' : $leave->status;
                        $badgeClass = match($leave->status) {
                            'Approved' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                            'Rejected' => 'border-red-200 bg-red-50 text-red-700',
                            default => 'border-amber-200 bg-amber-50 text-amber-700',
                        };
                    @endphp
                    <div wire:key="leave-{{ $leave->id }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $leave->type }}</span>
                                    <span class="rounded-full border px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">{{ $statusLabel }}</span>
                                </div>
                                <p class="mt-3 font-semibold text-slate-900">
                                    {{ $leave->start_date->translatedFormat('d M Y') }}
                                    <span class="font-normal text-slate-400">—</span>
                                    {{ $leave->end_date->translatedFormat('d M Y') }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">{{ $leave->duration }} hari • Diajukan {{ $leave->created_at->diffForHumans() }}</p>
                                <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">{{ $leave->reason }}</p>
                            </div>
                            <div class="flex shrink-0 items-center gap-2 text-xs text-slate-500">
                                @if($leave->approved_at)
                                    <i data-lucide="history" class="h-4 w-4"></i>
                                    <span>Diproses {{ $leave->approved_at->diffForHumans() }}</span>
                                @else
                                    <i data-lucide="clock-3" class="h-4 w-4"></i>
                                    <span>Menunggu review</span>
                                @endif
                            </div>
                        </div>

                        @if($leave->isRejected() && $leave->rejection_reason)
                            <div class="mt-4 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <span class="font-semibold">Alasan penolakan:</span> {{ $leave->rejection_reason }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                            <i data-lucide="inbox" class="h-6 w-6"></i>
                        </div>
                        <h3 class="mt-4 font-bold text-slate-900">Tidak ada pengajuan</h3>
                        <p class="mt-1 text-sm text-slate-500">Belum ada data yang cocok dengan filter saat ini.</p>
                    </div>
                @endforelse
            </div>

            @if($leaves->hasPages())
                <div>{{ $leaves->links() }}</div>
            @endif
        </div>
    </div>
</div>
