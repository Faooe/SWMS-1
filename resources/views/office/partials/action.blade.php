<section class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-5">
    <div><h2 class="text-sm font-black text-slate-900">Simpan perubahan office?</h2><p class="mt-0.5 text-xs leading-5 text-slate-500">Pastikan identitas, titik lokasi, radius, dan status sudah sesuai.</p></div>
    <div class="flex shrink-0 flex-col-reverse gap-2 sm:flex-row">
        <a href="{{ route('offices.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Batal</a>
        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100"><i data-lucide="save" class="h-4 w-4"></i>Simpan Perubahan</button>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});
</script>
@endpush
