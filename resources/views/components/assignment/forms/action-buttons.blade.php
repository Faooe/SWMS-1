@props(['assignment' => null])
<div class="sticky bottom-4 z-20 flex flex-col-reverse gap-2 rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-lg backdrop-blur sm:flex-row sm:items-center sm:justify-end">
    <a href="{{ $assignment ? route('assignments.show', $assignment) : route('assignments.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
        <i data-lucide="save" class="h-4 w-4"></i>
        {{ $assignment ? 'Simpan Perubahan' : 'Buat Assignment' }}
    </button>
</div>
