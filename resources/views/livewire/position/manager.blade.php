<div class="space-y-5">
    <x-company.master-header
        title="Position"
        subtitle="Kelola jabatan dan peran employee dalam struktur perusahaan."
        icon="badge-check"
        :add-route="route('positions.create')"
        add-label="Tambah Position" />

    @if($successMessage)
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700"><i data-lucide="circle-check-big" class="mt-0.5 h-4 w-4 shrink-0"></i>{{ $successMessage }}</div>
    @endif
    @if($errorMessage)
        <div class="flex items-start gap-3 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700"><i data-lucide="circle-alert" class="mt-0.5 h-4 w-4 shrink-0"></i>{{ $errorMessage }}</div>
    @endif

    <x-company.master-overview :statistics="$statistics" label="Position" icon="badge-check" />
    <x-company.master-filter :search="$search" :is-active="$isActive" placeholder="Cari nama atau kode position..." />

    <div class="flex items-end justify-between gap-4 px-1">
        <div><h2 class="text-base font-black text-slate-900">Daftar Position</h2><p class="mt-0.5 text-xs text-slate-500">{{ $positions->total() }} position sesuai filter.</p></div>
        <span class="text-xs font-semibold text-slate-400">{{ $positions->firstItem() ?? 0 }}–{{ $positions->lastItem() ?? 0 }} dari {{ $positions->total() }}</span>
    </div>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm" wire:loading.class="opacity-50" wire:target="search,isActive,previousPage,nextPage,gotoPage">
        <div class="divide-y divide-slate-100 md:hidden">
            @forelse($positions as $position)
                <article wire:key="position-card-{{ $position->id }}" class="p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"><i data-lucide="badge-check" class="h-5 w-5"></i></div>
                        <div class="min-w-0 flex-1"><p class="truncate text-sm font-bold text-slate-900">{{ $position->name }}</p><p class="mt-0.5 truncate text-xs font-medium text-slate-400">{{ $position->code }} · {{ $position->employment_histories_count }} employee</p></div>
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $position->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}"><span class="h-1.5 w-1.5 rounded-full {{ $position->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $position->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    </div>
                    <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-500">{{ $position->description ?: 'Belum ada deskripsi position.' }}</p>
                    <div class="mt-3 flex justify-end gap-1 border-t border-slate-100 pt-3">
                        <a href="{{ route('positions.edit', $position) }}" title="Edit" class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-blue-50 hover:text-blue-600"><i data-lucide="square-pen" class="h-4 w-4"></i></a>
                        <button type="button" wire:click="toggleStatus({{ $position->id }})" wire:confirm="{{ $position->is_active ? 'Nonaktifkan' : 'Aktifkan' }} position {{ $position->name }}?" title="Ubah status" class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"><i data-lucide="power" class="h-4 w-4"></i></button>
                        <button type="button" wire:click="deletePosition({{ $position->id }})" wire:confirm="Yakin ingin menghapus position {{ $position->name }}?" title="Hapus" class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-300 transition hover:bg-rose-50 hover:text-rose-600"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                    </div>
                </article>
            @empty
                <div class="px-6 py-16 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300"><i data-lucide="badge-check" class="h-7 w-7"></i></div><h3 class="mt-4 font-black text-slate-700">Position tidak ditemukan</h3><p class="mt-1 text-sm text-slate-500">Ubah filter atau tambahkan position baru.</p></div>
            @endforelse
        </div>

        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Position</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Deskripsi</th>
                        <th class="px-5 py-3.5 text-center text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Employee</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Status</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($positions as $position)
                        <tr wire:key="position-row-{{ $position->id }}" class="group transition hover:bg-slate-50/70">
                            <td class="px-5 py-4"><a href="{{ route('positions.edit', $position) }}" class="flex min-w-48 items-center gap-3"><div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="badge-check" class="h-4 w-4"></i></div><div class="min-w-0"><p class="truncate text-sm font-bold text-slate-900 group-hover:text-blue-700">{{ $position->name }}</p><p class="mt-0.5 text-xs font-medium text-slate-400">{{ $position->code }}</p></div></a></td>
                            <td class="max-w-sm px-5 py-4"><p class="truncate text-sm text-slate-500">{{ $position->description ?: 'Belum ada deskripsi' }}</p></td>
                            <td class="px-5 py-4 text-center"><span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">{{ $position->employment_histories_count }}</span></td>
                            <td class="px-5 py-4"><button type="button" wire:click="toggleStatus({{ $position->id }})" wire:confirm="{{ $position->is_active ? 'Nonaktifkan' : 'Aktifkan' }} position {{ $position->name }}?" class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold transition {{ $position->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'border-slate-200 bg-slate-100 text-slate-600 hover:bg-slate-200' }}"><span class="h-1.5 w-1.5 rounded-full {{ $position->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $position->is_active ? 'Aktif' : 'Nonaktif' }}</button></td>
                            <td class="px-5 py-4"><div class="flex justify-end gap-1"><a href="{{ route('positions.edit', $position) }}" title="Edit" class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-blue-50 hover:text-blue-600"><i data-lucide="square-pen" class="h-4 w-4"></i></a><button type="button" wire:click="deletePosition({{ $position->id }})" wire:confirm="Yakin ingin menghapus position {{ $position->name }}?" title="Hapus" class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-300 transition hover:bg-rose-50 hover:text-rose-600"><i data-lucide="trash-2" class="h-4 w-4"></i></button></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300"><i data-lucide="badge-check" class="h-7 w-7"></i></div><h3 class="mt-4 font-black text-slate-700">Position tidak ditemukan</h3><p class="mt-1 text-sm text-slate-500">Ubah filter atau tambahkan position baru.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($positions->hasPages())
            <div class="border-t border-slate-100 px-5 py-3">{{ $positions->links() }}</div>
        @endif
    </section>
</div>
