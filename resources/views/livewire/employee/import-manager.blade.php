<div class="mx-auto max-w-4xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Import Employee</h1>
            <p class="mt-1 text-sm text-slate-500">
                Upload file CSV untuk membuat banyak data employee sekaligus.
            </p>
        </div>

        <a href="{{ route('employees.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">
            &larr; Kembali ke Employee List
        </a>
    </div>

    {{-- Step 1: Template --}}
    <x-ui.card>
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                <i data-lucide="download" class="h-6 w-6"></i>
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-bold text-slate-800">1. Download Template</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Isi data employee di file ini. Kolom <strong>department</strong>
                    dan <strong>position</strong> harus persis sama dengan nama yang
                    sudah ada di master data kamu. Office akan otomatis mengikuti
                    Head Office company kamu, jadi tidak perlu diisi. Kolom
                    <strong>employee_number</strong>, <strong>username</strong>,
                    dan <strong>password</strong> boleh dikosongkan &mdash; akan
                    dibuatkan otomatis.
                </p>
                <p class="mt-2 text-xs text-slate-400">
                    File CSV boleh memakai pemisah koma (<code>,</code>) atau titik koma
                    (<code>;</code>) &mdash; keduanya otomatis dikenali saat import.
                </p>
                <button
                    type="button"
                    wire:click="downloadTemplate"
                    class="mt-4 rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Download Template CSV
                </button>
            </div>
        </div>
    </x-ui.card>

    {{-- Step 2: Upload --}}
    <x-ui.card>
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                <i data-lucide="upload" class="h-6 w-6"></i>
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-bold text-slate-800">2. Upload File CSV</h2>
                <p class="mt-1 text-sm text-slate-500">Maksimal 5MB, format .csv</p>

                <form wire:submit="import" class="mt-4 space-y-4">

                    <input
                        type="file"
                        wire:model="file"
                        accept=".csv,text/csv"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">

                    @error('file')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="file" class="text-sm text-slate-400">
                        Mengunggah file...
                    </div>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="import"
                        class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:opacity-60">
                        <span wire:loading.remove wire:target="import">Proses Import</span>
                        <span wire:loading wire:target="import">Memproses...</span>
                    </button>

                </form>
            </div>
        </div>
    </x-ui.card>

    {{-- Step 3: Result --}}
    @if($results !== null)

        <x-ui.card>

            <div class="mb-5 flex flex-wrap items-center justify-between gap-4">

                <div>
                    <h2 class="text-lg font-bold text-slate-800">3. Hasil Import</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        <span class="font-semibold text-green-600">{{ $this->successCount }} berhasil</span>
                        &middot;
                        <span class="font-semibold text-red-600">{{ $this->failedCount }} gagal</span>
                        dari {{ count($results) }} baris.
                    </p>
                </div>

                <div class="flex gap-2">

                    @if($this->successCount > 0)
                        <button
                            type="button"
                            wire:click="downloadResult"
                            class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
                            <i data-lucide="file-down" class="mr-1 inline h-4 w-4"></i>
                            Download Hasil (CSV, termasuk password)
                        </button>
                    @endif

                    <button
                        type="button"
                        wire:click="reset_"
                        class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Import Lagi
                    </button>

                </div>

            </div>

            @if($this->successCount > 0)
                <div class="mb-5 rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-700">
                    <i data-lucide="alert-triangle" class="mr-1 inline h-4 w-4"></i>
                    Password hanya ditampilkan di file hasil download ini &mdash; setelah
                    ini nggak akan bisa dilihat lagi (sudah ter-hash di database). Segera
                    download & simpan/bagikan ke masing-masing employee.
                </div>
            @endif

            <div class="max-h-[420px] overflow-y-auto rounded-2xl border border-slate-200">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500">Row</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($results as $row)
                            <tr>
                                <td class="px-4 py-3">{{ $row['row'] }}</td>
                                <td class="px-4 py-3">
                                    @if($row['status'] === 'success')
                                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Berhasil</span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Gagal</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $row['full_name'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $row['message'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </x-ui.card>

    @endif

</div>