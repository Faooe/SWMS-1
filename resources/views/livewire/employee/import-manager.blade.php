<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Company Workspace · Employee</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Import employee</h1>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">Tambahkan banyak employee secara aman menggunakan satu file CSV yang mengikuti template resmi.</p>
        </div>
        <a href="{{ route('employees.index') }}"
           class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke Employee
        </a>
    </div>

    <div class="overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-white shadow-sm">
        <div class="grid gap-6 p-5 sm:p-7 lg:grid-cols-[1fr_auto] lg:items-center">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-200">
                    <i data-lucide="file-spreadsheet" class="h-6 w-6"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Import massal</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-900">Siapkan data employee dalam beberapa langkah</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Department, position, dan team akan dicocokkan dengan master data company yang sedang aktif (tidak peka huruf besar-kecil). Office otomatis mengikuti Head Office company.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 text-xs font-semibold">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-2 text-slate-600 shadow-sm ring-1 ring-slate-200"><i data-lucide="file-text" class="h-3.5 w-3.5 text-blue-600"></i> CSV / TXT</span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-2 text-slate-600 shadow-sm ring-1 ring-slate-200"><i data-lucide="hard-drive" class="h-3.5 w-3.5 text-blue-600"></i> Maks. 5 MB</span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-2 text-emerald-700 ring-1 ring-emerald-100"><i data-lucide="shield-check" class="h-3.5 w-3.5"></i> Validasi per baris</span>
            </div>
        </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-2">
        <x-ui.card>
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><span class="text-sm font-extrabold">01</span></div>
                <div class="min-w-0"><h2 class="font-bold text-slate-900">Download template</h2><p class="mt-1 text-sm leading-6 text-slate-500">Gunakan template agar nama kolom dan format data tidak tertukar.</p></div>
            </div>
            <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Kolom wajib</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach(\App\Services\Employee\EmployeeImportService::REQUIRED_HEADERS as $column)
                        <code class="rounded-lg bg-white px-2.5 py-1.5 text-[11px] font-semibold text-slate-600 ring-1 ring-slate-200">{{ $column }}</code>
                    @endforeach
                </div>
            </div>
            <p class="mt-4 text-xs leading-5 text-slate-500">Employee number, username, password, dan team boleh dikosongkan. Jika password dikosongkan, sistem membuat password kuat otomatis dan hanya menampilkannya di hasil download.</p>
            <button type="button" wire:click="downloadTemplate" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-blue-200 bg-white px-4 py-3 text-sm font-bold text-blue-700 transition hover:bg-blue-50">
                <i data-lucide="download" class="h-4 w-4"></i> Download template CSV
            </button>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><span class="text-sm font-extrabold">02</span></div>
                <div class="min-w-0"><h2 class="font-bold text-slate-900">Upload dan proses</h2><p class="mt-1 text-sm leading-6 text-slate-500">Pilih CSV yang sudah diisi, lalu jalankan validasi.</p></div>
            </div>
            <form wire:submit="import" class="mt-5 space-y-4">
                <label class="group flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/70 px-5 py-7 text-center transition hover:border-blue-300 hover:bg-blue-50/60">
                    <input type="file" wire:model="file" accept=".csv,.txt,text/csv,text/plain" class="sr-only">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm ring-1 ring-slate-200 transition group-hover:bg-blue-600 group-hover:text-white"><i data-lucide="upload-cloud" class="h-5 w-5"></i></span>
                    <span class="mt-3 text-sm font-bold text-slate-700">Pilih file CSV atau TXT</span>
                    <span class="mt-1 text-xs text-slate-500">Pemisah koma dan titik koma terdeteksi otomatis</span>
                </label>
                @if($file)
                    <div class="flex items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 px-3.5 py-3">
                        <i data-lucide="file-check-2" class="h-5 w-5 shrink-0 text-blue-600"></i>
                        <div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-800">{{ $file->getClientOriginalName() }}</p><p class="text-xs text-slate-500">File siap divalidasi</p></div>
                        <button type="button" wire:click="$set('file', null)" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-white hover:text-red-600" aria-label="Hapus file"><i data-lucide="x" class="h-4 w-4"></i></button>
                    </div>
                @endif
                @error('file')
                    <div class="flex items-start gap-2 rounded-xl border border-red-100 bg-red-50 px-3.5 py-3 text-sm text-red-700"><i data-lucide="circle-alert" class="mt-0.5 h-4 w-4 shrink-0"></i><span>{{ $message }}</span></div>
                @enderror
                <div wire:loading wire:target="file" class="flex items-center gap-2 text-xs font-medium text-slate-500"><span class="h-3.5 w-3.5 animate-spin rounded-full border-2 border-blue-200 border-t-blue-600"></span>Mengunggah file...</div>
                <button type="submit" wire:loading.attr="disabled" wire:target="import,file" @disabled(! $file || $isProcessing) class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                    <i wire:loading.remove wire:target="import" data-lucide="play" class="h-4 w-4"></i>
                    <span wire:loading.remove wire:target="import">Proses import</span>
                    <span wire:loading wire:target="import" class="inline-flex items-center gap-2"><span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>Memproses...</span>
                </button>
            </form>
        </x-ui.card>
    </div>

    @if($results !== null)
        <x-ui.card>
            <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-start gap-3"><div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><span class="text-sm font-extrabold">03</span></div><div><h2 class="font-bold text-slate-900">Hasil import</h2><p class="mt-1 text-sm text-slate-500">Setiap baris diproses terpisah agar data valid tetap tersimpan.</p></div></div>
                <div class="flex flex-wrap gap-2">
                    @if($this->successCount > 0)
                        <button type="button" wire:click="downloadResult" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3.5 py-2.5 text-xs font-bold text-white transition hover:bg-emerald-700"><i data-lucide="file-down" class="h-4 w-4"></i> Download hasil</button>
                    @endif
                    <button type="button" wire:click="reset_" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50"><i data-lucide="rotate-ccw" class="h-4 w-4"></i> Import lagi</button>
                </div>
            </div>
            <div class="mt-5 grid grid-cols-3 overflow-hidden rounded-2xl border border-slate-100 bg-slate-50">
                <div class="border-r border-slate-100 px-3 py-3 text-center sm:px-5"><p class="text-2xl font-bold text-slate-900">{{ count($results) }}</p><p class="mt-0.5 text-[11px] font-semibold text-slate-500">Total baris</p></div>
                <div class="border-r border-slate-100 bg-emerald-50/50 px-3 py-3 text-center sm:px-5"><p class="text-2xl font-bold text-emerald-600">{{ $this->successCount }}</p><p class="mt-0.5 text-[11px] font-semibold text-emerald-700">Berhasil</p></div>
                <div class="bg-red-50/50 px-3 py-3 text-center sm:px-5"><p class="text-2xl font-bold text-red-600">{{ $this->failedCount }}</p><p class="mt-0.5 text-[11px] font-semibold text-red-700">Gagal</p></div>
            </div>
            @if($this->successCount > 0)
                <div class="mt-4 flex items-start gap-2 rounded-xl border border-amber-100 bg-amber-50 px-3.5 py-3 text-xs leading-5 text-amber-800"><i data-lucide="key-round" class="mt-0.5 h-4 w-4 shrink-0"></i><span>Password employee hanya tersedia di file hasil download ini. Simpan dengan aman sebelum menutup halaman.</span></div>
            @endif
            <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200"><div class="max-h-[420px] overflow-auto"><table class="min-w-full text-sm"><thead class="sticky top-0 z-10 bg-slate-50"><tr><th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Baris</th><th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Status</th><th class="whitespace-nowrap px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Employee</th><th class="min-w-[280px] px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Keterangan</th></tr></thead><tbody class="divide-y divide-slate-100">
                @foreach($results as $row)
                    <tr wire:key="import-result-{{ $row['row'] }}" class="transition hover:bg-slate-50/70"><td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $row['row'] }}</td><td class="px-4 py-3">@if($row['status'] === 'success')<span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Berhasil</span>@else<span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-bold text-red-700"><span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>Gagal</span>@endif</td><td class="whitespace-nowrap px-4 py-3 font-semibold text-slate-700">{{ $row['full_name'] ?? '-' }}</td><td class="px-4 py-3 text-slate-500">{{ $row['message'] ?: '—' }}</td></tr>
                @endforeach
            </tbody></table></div></div>
        </x-ui.card>
    @endif
</div>
