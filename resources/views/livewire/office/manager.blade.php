<div class="space-y-5">
    <x-company.master-header
        title="Office"
        subtitle="Kelola lokasi, radius attendance, dan status kantor perusahaan."
        icon="building-2" />

    @if(session('success'))
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            <i data-lucide="circle-check-big" class="mt-0.5 h-4 w-4 shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    <x-company.master-overview :statistics="$statistics" label="Office" icon="building-2" />
    <x-company.master-filter :search="$search" :is-active="$isActive" placeholder="Cari nama, kode, kota, atau provinsi..." />

    <div class="flex items-end justify-between gap-4 px-1">
        <div>
            <h2 class="text-base font-black text-slate-900">Daftar Office</h2>
            <p class="mt-0.5 text-xs text-slate-500">{{ $offices->total() }} office sesuai filter. Penambahan office dikelola oleh platform admin.</p>
        </div>
        <span class="shrink-0 text-xs font-semibold text-slate-400">{{ $offices->firstItem() ?? 0 }}–{{ $offices->lastItem() ?? 0 }} dari {{ $offices->total() }}</span>
    </div>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
             wire:loading.class="opacity-50"
             wire:target="search,isActive,previousPage,nextPage,gotoPage">
        <div class="divide-y divide-slate-100 md:hidden">
            @forelse($offices as $office)
                <article wire:key="office-card-{{ $office->id }}" class="p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"><i data-lucide="building-2" class="h-5 w-5"></i></div>
                        <div class="min-w-0 flex-1">
                            <div class="flex min-w-0 items-center gap-2">
                                <p class="truncate text-sm font-bold text-slate-900">{{ $office->name }}</p>
                                @if($office->is_head_office)<span class="shrink-0 rounded-full bg-violet-50 px-2 py-0.5 text-[9px] font-black text-violet-700">PUSAT</span>@endif
                            </div>
                            <p class="mt-0.5 truncate text-xs font-medium text-slate-400">{{ $office->code }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $office->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}"><span class="h-1.5 w-1.5 rounded-full {{ $office->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $office->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2 rounded-2xl bg-slate-50 p-3 text-xs">
                        <div><p class="font-semibold text-slate-400">Lokasi</p><p class="mt-1 truncate font-bold text-slate-700">{{ collect([$office->city, $office->province])->filter()->join(', ') ?: 'Belum diatur' }}</p></div>
                        <div><p class="font-semibold text-slate-400">Radius</p><p class="mt-1 font-bold text-slate-700">{{ number_format($office->radius) }} meter</p></div>
                    </div>
                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-xs font-semibold text-slate-400">{{ $office->employees_count }} employee</span>
                        <a href="{{ route('offices.edit', $office) }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 transition hover:bg-blue-100"><i data-lucide="eye" class="h-3.5 w-3.5"></i>Lihat / Edit</a>
                    </div>
                </article>
            @empty
                <div class="px-6 py-16 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300"><i data-lucide="building-2" class="h-7 w-7"></i></div><h3 class="mt-4 font-black text-slate-700">Office tidak ditemukan</h3><p class="mt-1 text-sm text-slate-500">Ubah kata kunci atau filter status.</p></div>
            @endforelse
        </div>

        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Office</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Lokasi</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Area Attendance</th>
                        <th class="px-5 py-3.5 text-center text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Employee</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Status</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($offices as $office)
                        <tr wire:key="office-row-{{ $office->id }}" class="group transition hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <a href="{{ route('offices.edit', $office) }}" class="flex min-w-52 items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="building-2" class="h-4 w-4"></i></div>
                                    <div class="min-w-0"><div class="flex items-center gap-2"><p class="truncate text-sm font-bold text-slate-900 group-hover:text-blue-700">{{ $office->name }}</p>@if($office->is_head_office)<span class="shrink-0 rounded-full bg-violet-50 px-2 py-0.5 text-[9px] font-black text-violet-700">PUSAT</span>@endif</div><p class="mt-0.5 text-xs font-medium text-slate-400">{{ $office->code }}</p></div>
                                </a>
                            </td>
                            <td class="px-5 py-4"><p class="text-sm font-semibold text-slate-700">{{ $office->city ?: 'Kota belum diatur' }}</p><p class="mt-0.5 text-xs text-slate-400">{{ $office->province ?: 'Provinsi belum diatur' }}</p></td>
                            <td class="px-5 py-4"><p class="text-sm font-semibold text-slate-700">{{ number_format($office->radius) }} meter</p><p class="mt-0.5 text-xs text-slate-400">{{ $office->polygon ? 'Polygon aktif' : 'Radius titik kantor' }}</p></td>
                            <td class="px-5 py-4 text-center"><span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">{{ $office->employees_count }}</span></td>
                            <td class="px-5 py-4"><span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $office->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}"><span class="h-1.5 w-1.5 rounded-full {{ $office->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $office->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('offices.edit', $office) }}" title="Lihat / Edit" class="ml-auto flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-blue-50 hover:text-blue-600"><i data-lucide="square-pen" class="h-4 w-4"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300"><i data-lucide="building-2" class="h-7 w-7"></i></div><h3 class="mt-4 font-black text-slate-700">Office tidak ditemukan</h3><p class="mt-1 text-sm text-slate-500">Ubah kata kunci atau filter status.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($offices->hasPages())
            <div class="border-t border-slate-100 px-5 py-3">{{ $offices->links() }}</div>
        @endif
    </section>
</div>
