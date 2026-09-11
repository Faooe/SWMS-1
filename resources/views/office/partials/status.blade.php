<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i data-lucide="sliders-horizontal" class="h-5 w-5"></i></span>
        <div><h2 class="text-sm font-black text-slate-900">Status Office</h2><p class="mt-0.5 text-xs text-slate-500">Ketersediaan dan tipe office.</p></div>
    </div>
    <div class="divide-y divide-slate-100 px-5">
        <label class="flex cursor-pointer items-center justify-between gap-4 py-4">
            <span><span class="block text-sm font-bold text-slate-800">Office aktif</span><span class="mt-0.5 block text-xs text-slate-500">Dapat digunakan untuk penempatan dan attendance.</span></span>
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $office->is_active)) class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
        </label>
        <label class="flex cursor-pointer items-center justify-between gap-4 py-4">
            <span><span class="block text-sm font-bold text-slate-800">Kantor pusat</span><span class="mt-0.5 block text-xs text-slate-500">Jadikan office sebagai lokasi utama perusahaan.</span></span>
            <input type="checkbox" name="is_head_office" value="1" @checked(old('is_head_office', $office->is_head_office)) class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
        </label>
    </div>
</section>
