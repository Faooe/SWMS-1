@props(['placeholder', 'search' => '', 'isActive' => ''])

<section class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
    <div class="mb-3 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-black text-slate-900">Cari &amp; Filter</h2>
            <p class="mt-0.5 text-xs text-slate-500">Temukan data berdasarkan nama, kode, atau status.</p>
        </div>
        @if($search || $isActive !== '')
            <button type="button" wire:click="resetFilters"
                    class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i>
                Reset
            </button>
        @endif
    </div>

    <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_190px]">
        <label class="relative block">
            <span class="sr-only">Cari data</span>
            <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="{{ $placeholder }}"
                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
        </label>

        <select wire:model.live="isActive"
                class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
            <option value="">Semua Status</option>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
    </div>
</section>
