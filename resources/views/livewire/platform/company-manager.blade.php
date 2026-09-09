<div class="space-y-5">
    <section class="flex flex-col gap-4 px-1 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm font-bold text-blue-600">
                <i data-lucide="building-2" class="h-4 w-4"></i>
                <span>Platform Workspace</span>
            </div>
            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Companies</h1>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">Kelola tenant, kapasitas employee, status operasional, dan paket subscription dari satu tempat.</p>
        </div>
        <a href="{{ route('platform.companies.create') }}" class="inline-flex w-fit items-center justify-center gap-2 rounded-2xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
            <i data-lucide="plus" class="h-4 w-4"></i>
            Tambah Company
        </a>
    </section>

    @if($successMessage)
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            <i data-lucide="circle-check-big" class="mt-0.5 h-4 w-4 shrink-0"></i>{{ $successMessage }}
        </div>
    @endif
    @if($errorMessage)
        <div class="flex items-start gap-3 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
            <i data-lucide="circle-alert" class="mt-0.5 h-4 w-4 shrink-0"></i>{{ $errorMessage }}
        </div>
    @endif

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-black text-slate-900">Ringkasan Company</h2>
                <p class="mt-0.5 text-xs text-slate-500">Kondisi tenant SWMS saat ini.</p>
            </div>
            <span class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-500">{{ $statistics['total'] }} tenant</span>
        </div>
        <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 sm:grid-cols-5 sm:divide-y-0">
            @foreach([
                ['Total', $statistics['total'], 'building-2', 'bg-blue-50 text-blue-600'],
                ['Aktif', $statistics['active'], 'circle-check', 'bg-emerald-50 text-emerald-600'],
                ['Free', $statistics['free'], 'package', 'bg-slate-100 text-slate-600'],
                ['Premium', $statistics['premium'], 'gem', 'bg-violet-50 text-violet-600'],
                ['Employee', $statistics['employees'], 'users', 'bg-amber-50 text-amber-600'],
            ] as [$label, $value, $icon, $tone])
                <div class="flex items-center gap-3 px-4 py-4 sm:px-5">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $tone }}"><i data-lucide="{{ $icon }}" class="h-5 w-5"></i></div>
                    <div class="min-w-0"><div class="text-xl font-black leading-none text-slate-900">{{ $value }}</div><div class="mt-1 truncate text-xs font-semibold text-slate-500">{{ $label }}</div></div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-3 flex items-center justify-between gap-3">
            <div><h2 class="text-sm font-black text-slate-900">Cari & Filter</h2><p class="mt-0.5 text-xs text-slate-500">Temukan company berdasarkan nama, kode, email, status, atau paket.</p></div>
            @if($search || $status !== '' || $plan)
                <button type="button" wire:click="resetFilters" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold text-slate-500 hover:bg-slate-100 hover:text-slate-700"><i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i>Reset</button>
            @endif
        </div>
        <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_180px_190px]">
            <label class="relative block">
                <span class="sr-only">Cari company</span>
                <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari nama, kode, atau email..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
            </label>
            <select wire:model.live="status" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
                <option value="">Semua Status</option><option value="1">Aktif</option><option value="0">Nonaktif</option>
            </select>
            <select wire:model.live="plan" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-600 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
                <option value="">Semua Paket</option><option value="Free">Free</option><option value="Premium Go">Premium Go</option><option value="Premium Plus">Premium Plus</option><option value="Premium Max">Premium Max</option>
            </select>
        </div>
    </section>

    <div class="flex items-end justify-between gap-4 px-1">
        <div><h2 class="text-base font-black text-slate-900">Daftar Company</h2><p class="mt-0.5 text-xs text-slate-500">{{ $companies->total() }} company ditemukan.</p></div>
    </div>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm" wire:loading.class="opacity-50" wire:target="search,status,plan,previousPage,nextPage,gotoPage">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Company</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Super Admin</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Paket</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Kapasitas</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Status</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($companies as $company)
                        @php
                            $admin = $company->users->firstWhere('role.code', 'SUPER_ADMIN');
                            $employeeRatio = $company->max_employee > 0 ? $company->employees_count / $company->max_employee : 0;
                            $planTone = match($company->subscription_plan) {
                                'Free' => 'border-slate-200 bg-slate-100 text-slate-600',
                                'Premium Go' => 'border-blue-100 bg-blue-50 text-blue-700',
                                'Premium Plus' => 'border-violet-100 bg-violet-50 text-violet-700',
                                default => 'border-rose-100 bg-rose-50 text-rose-700',
                            };
                        @endphp
                        <tr wire:key="company-row-{{ $company->id }}" class="group transition hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <a href="{{ route('platform.companies.show', $company) }}" class="flex min-w-56 items-center gap-3">
                                    @if($company->logo)
                                        <img src="{{ secure_file_url($company->logo) }}" class="h-11 w-11 shrink-0 rounded-xl border border-slate-200 object-cover">
                                    @else
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-black text-blue-600">{{ strtoupper(substr($company->name, 0, 1)) }}</div>
                                    @endif
                                    <div class="min-w-0"><p class="truncate text-sm font-bold text-slate-900 group-hover:text-blue-700">{{ $company->name }}</p><p class="mt-0.5 truncate text-xs font-medium text-slate-400">{{ $company->code }} · {{ $company->email ?: 'Email belum diisi' }}</p></div>
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                @if($admin)
                                    <p class="text-sm font-semibold text-slate-700">{{ $admin->employee?->full_name ?: $admin->username }}</p><p class="mt-0.5 text-xs text-slate-400">{{ $admin->email }}</p>
                                @else
                                    <span class="text-xs font-semibold text-slate-400">Belum tersedia</span>
                                @endif
                            </td>
                            <td class="px-5 py-4"><span class="inline-flex rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $planTone }}">{{ $company->subscription_plan }}</span></td>
                            <td class="px-5 py-4">
                                <div class="w-28"><div class="flex items-center justify-between text-xs"><span class="font-bold text-slate-700">{{ $company->employees_count }}/{{ $company->max_employee ?: '∞' }}</span><span class="text-slate-400">{{ $company->max_employee ? round($employeeRatio*100).'%' : '∞' }}</span></div><div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $employeeRatio >= .9 ? 'bg-amber-500' : 'bg-blue-500' }}" style="width: {{ min($employeeRatio*100,100) }}%"></div></div></div>
                            </td>
                            <td class="px-5 py-4">
                                <button type="button" wire:click="toggleStatus({{ $company->id }})" wire:confirm="{{ $company->is_active ? 'Nonaktifkan' : 'Aktifkan' }} company {{ $company->name }}?" class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold transition {{ $company->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'border-slate-200 bg-slate-100 text-slate-600 hover:bg-slate-200' }}"><span class="h-1.5 w-1.5 rounded-full {{ $company->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $company->is_active ? 'Aktif' : 'Nonaktif' }}</button>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('platform.companies.show', $company) }}" title="Detail" class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-blue-50 hover:text-blue-600"><i data-lucide="eye" class="h-4 w-4"></i></a>
                                    <a href="{{ route('platform.companies.edit', $company) }}" title="Edit" class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"><i data-lucide="square-pen" class="h-4 w-4"></i></a>
                                    <button type="button" title="Hapus" wire:click="deleteCompany({{ $company->id }})" wire:confirm="Yakin ingin menghapus company {{ $company->name }}?" class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-300 transition hover:bg-rose-50 hover:text-rose-600"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-300"><i data-lucide="building-2" class="h-7 w-7"></i></div><h3 class="mt-4 font-black text-slate-700">Company tidak ditemukan</h3><p class="mx-auto mt-1 max-w-md text-sm leading-6 text-slate-500">Belum ada company atau tidak ada data yang cocok dengan filter saat ini.</p><a href="{{ route('platform.companies.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-50 px-4 py-2.5 text-sm font-bold text-blue-700 hover:bg-blue-100"><i data-lucide="plus" class="h-4 w-4"></i>Tambah Company</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($companies->hasPages())<div class="border-t border-slate-100 px-5 py-3">{{ $companies->links() }}</div>@endif
    </section>
</div>