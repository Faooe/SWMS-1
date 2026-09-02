<div class="space-y-6">
    @if($successMessage)
        <div class="flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-sm text-blue-800">
            <i data-lucide="circle-check" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span class="font-medium">{{ $successMessage }}</span>
        </div>
    @endif

    @if($errorMessage)
        <div class="flex items-start gap-3 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm text-red-700">
            <i data-lucide="circle-alert" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span class="font-medium">{{ $errorMessage }}</span>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => 'Perlu Review', 'value' => $summary['pending'], 'icon' => 'clock-3'],
            ['label' => 'Aktif Hari Ini', 'value' => $summary['active_today'], 'icon' => 'calendar-check-2'],
            ['label' => 'Disetujui', 'value' => $summary['approved'], 'icon' => 'circle-check'],
            ['label' => 'Total Pengajuan', 'value' => $summary['total'], 'icon' => 'files'],
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

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end">
            <div class="min-w-0 flex-1">
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Cari Karyawan</label>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        wire:model.live.debounce.400ms="search"
                        placeholder="Nama karyawan..."
                        class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-4 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 xl:w-[360px]">
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <select wire:model.live="status" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="">Semua Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Jenis</label>
                    <select wire:model.live="type" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="">Semua Jenis</option>
                        <option value="Cuti">Cuti</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Acara">Acara</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 xl:w-[330px]">
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Dari</label>
                    <input wire:model.live="dateFrom" type="date" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Sampai</label>
                    <input wire:model.live="dateTo" type="date" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div class="flex gap-2">
                <button wire:click="resetFilters" type="button" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Pending</button>
                <button wire:click="showAll" type="button" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Semua</button>
            </div>
        </div>
        <p class="mt-3 text-xs text-slate-500">Default menampilkan pengajuan <span class="font-semibold text-slate-700">Pending</span> agar request yang perlu tindakan tidak tertutup histori lama.</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" wire:loading.class="opacity-50" wire:target="search,status,type,dateFrom,dateTo,previousPage,nextPage,gotoPage,approve,confirmReject">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Karyawan</th>
                        <th class="px-5 py-4">Pengajuan</th>
                        <th class="px-5 py-4">Periode</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Keterangan</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
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
                        <tr wire:key="leave-row-{{ $leave->id }}" class="align-top transition hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900">{{ $leave->employee->full_name }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $leave->employee->employee_number ?? 'Employee' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $leave->type }}</span>
                                <p class="mt-2 text-xs text-slate-500">Diajukan {{ $leave->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-700">
                                <div class="font-medium">{{ $leave->start_date->translatedFormat('d M Y') }}</div>
                                <div class="mt-1 text-xs text-slate-500">sampai {{ $leave->end_date->translatedFormat('d M Y') }} • {{ $leave->duration }} hari</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="max-w-sm px-5 py-4">
                                <p class="line-clamp-2 leading-5 text-slate-600">{{ $leave->reason }}</p>
                                @if($leave->isRejected() && $leave->rejection_reason)
                                    <p class="mt-2 line-clamp-2 text-xs text-red-600">Ditolak: {{ $leave->rejection_reason }}</p>
                                @elseif($leave->approver)
                                    <p class="mt-2 text-xs text-slate-400">Diproses oleh {{ $leave->approver->name ?? $leave->approver->username ?? '-' }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($leave->canBeReviewed())
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            type="button"
                                            wire:click="startReject({{ $leave->id }})"
                                            class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">
                                            Tolak
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="approve({{ $leave->id }})"
                                            wire:confirm="Setujui pengajuan {{ $leave->type }} dari {{ $leave->employee->full_name }}?"
                                            class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">
                                            Setujui
                                        </button>
                                    </div>
                                @else
                                    <span class="block text-right text-xs text-slate-400">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                    <i data-lucide="inbox" class="h-6 w-6"></i>
                                </div>
                                <h3 class="mt-4 font-bold text-slate-900">Tidak ada pengajuan</h3>
                                <p class="mt-1 text-sm text-slate-500">Tidak ada data yang cocok dengan filter saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($leaves->hasPages())
        <div>{{ $leaves->links() }}</div>
    @endif

    @if($rejectingLeaveId)
        @php $rejectingLeave = $leaves->getCollection()->firstWhere('id', $rejectingLeaveId); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/40 p-4" wire:click.self="cancelReject">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Tolak Pengajuan</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $rejectingLeave?->employee?->full_name ?? 'Karyawan' }} • {{ $rejectingLeave?->type ?? 'Leave / Permission' }}</p>
                    </div>
                    <button wire:click="cancelReject" type="button" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700"><i data-lucide="x" class="h-5 w-5"></i></button>
                </div>

                <div class="mt-5">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Alasan Penolakan <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea wire:model="rejectionReason" rows="4" maxlength="1000" placeholder="Berikan alasan agar employee memahami keputusan company..." class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"></textarea>
                    @error('rejectionReason') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button wire:click="cancelReject" type="button" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button wire:click="confirmReject" type="button" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Tolak Pengajuan</button>
                </div>
            </div>
        </div>
    @endif
</div>
