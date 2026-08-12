<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Leave / Permission</h1>
        <p class="mt-1 text-sm text-slate-500">
            Ajukan Cuti (maksimal 12 hari) atau izin Sakit/Acara (maksimal 3 hari).
            Setelah disetujui admin, kamu tidak akan tercatat Absent pada tanggal tersebut.
        </p>
    </div>

    @if($successMessage)
        <div class="rounded-2xl bg-green-100 px-5 py-4 text-sm font-medium text-green-700">
            {{ $successMessage }}
        </div>
    @endif

    {{-- Kuota Cuti Tahun Berjalan -- lihat App\Services\LeaveQuotaService.
         Sengaja ditaruh di atas (bukan cuma di dalam form) supaya
         kelihatan tanpa harus scroll ke form dulu. --}}
    <div class="rounded-2xl border border-slate-200 bg-white px-6 py-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-slate-800">
                    Kuota Cuti {{ $quota['year'] }}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Sakit &amp; Acara tidak memotong kuota ini -- cuma Cuti.
                </p>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-center">
                    <p class="text-2xl font-bold text-purple-600">{{ $quota['remaining_days'] }}</p>
                    <p class="text-xs text-slate-500">Sisa Hari</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-slate-400">{{ $quota['used_days'] }}</p>
                    <p class="text-xs text-slate-500">Terpakai</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-slate-400">{{ $quota['total_days'] }}</p>
                    <p class="text-xs text-slate-500">Total Jatah</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Form --}}
        <x-ui.card class="lg:col-span-1">

            <h3 class="mb-5 text-lg font-bold text-slate-800">Ajukan Izin Baru</h3>

            <form wire:submit="submit" class="space-y-5">

                <x-ui.select
                    wire:model="type"
                    name="type"
                    label="Jenis Izin"
                    :options="['Cuti' => 'Cuti', 'Sakit' => 'Sakit', 'Acara' => 'Acara / Keperluan Pribadi']"
                    placeholder="Pilih Jenis Izin"
                    required />

                <x-ui.input
                    wire:model="start_date"
                    name="start_date"
                    type="date"
                    label="Tanggal Mulai"
                    required />

                <x-ui.input
                    wire:model="end_date"
                    name="end_date"
                    type="date"
                    label="Tanggal Selesai"
                    required />

                <x-ui.textarea
                    wire:model="reason"
                    name="reason"
                    label="Alasan"
                    placeholder="Jelaskan alasan izin kamu..."
                    required />

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                    class="w-full rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:opacity-60">
                    <span wire:loading.remove wire:target="submit">Kirim Pengajuan</span>
                    <span wire:loading wire:target="submit">Mengirim...</span>
                </button>

            </form>

        </x-ui.card>

        {{-- History --}}
        <div class="space-y-4 lg:col-span-2" wire:loading.class="opacity-50" wire:target="submit">

            @forelse($leaves as $leave)

                <x-ui.card wire:key="leave-{{ $leave->id }}">

                    <div class="flex items-start justify-between gap-4">

                        <div>
                            <p class="font-bold text-slate-800">{{ $leave->type }}</p>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $leave->start_date->format('d M Y') }}
                                &mdash;
                                {{ $leave->end_date->format('d M Y') }}
                                ({{ $leave->duration }} hari)
                            </p>

                            <p class="mt-2 text-sm text-slate-600">{{ $leave->reason }}</p>

                            @if($leave->isRejected() && $leave->rejection_reason)
                                <p class="mt-2 text-sm text-red-600">
                                    Alasan ditolak: {{ $leave->rejection_reason }}
                                </p>
                            @endif
                        </div>

                        <x-ui.badge :color="match($leave->status) {
                            'Approved' => 'green',
                            'Rejected' => 'red',
                            default => 'yellow',
                        }">
                            {{ $leave->status }}
                        </x-ui.badge>

                    </div>

                </x-ui.card>

            @empty

                <x-ui.card>
                    <div class="py-16 text-center">
                        <i data-lucide="file-text" class="mx-auto h-12 w-12 text-slate-300"></i>
                        <h3 class="mt-5 text-lg font-bold text-slate-800">Belum Ada Pengajuan Izin</h3>
                        <p class="mt-2 text-sm text-slate-500">Riwayat pengajuan izin kamu akan muncul di sini.</p>
                    </div>
                </x-ui.card>

            @endforelse

            @if($leaves->hasPages())
                <div>{{ $leaves->links() }}</div>
            @endif

        </div>

    </div>

</div>
