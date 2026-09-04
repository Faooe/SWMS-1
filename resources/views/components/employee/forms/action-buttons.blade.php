@props(['employee' => null])

<div class="sticky bottom-4 z-20 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-lg backdrop-blur sm:flex-row sm:items-center sm:justify-between">
    <p class="hidden text-sm text-slate-500 lg:block">Pastikan data identitas, penempatan, dan akun sudah benar sebelum disimpan.</p>
    <div class="flex gap-2 sm:ml-auto">
        <a href="{{ $employee ? route('employees.show', $employee) : route('employees.index') }}" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50 sm:flex-none">Batal</a>
        <button type="submit" class="flex-1 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50 sm:flex-none">
            {{ $employee ? 'Simpan Perubahan' : 'Tambah Employee' }}
        </button>
    </div>
</div>
