<div class="space-y-5">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Company Workspace</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Employee</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola data, penempatan, akun, dan status employee dalam satu tempat.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('employees.import') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-700">
                <i data-lucide="upload" class="h-4 w-4"></i>
                Import
            </a>
            <a href="{{ route('employees.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                <i data-lucide="user-plus" class="h-4 w-4"></i>
                Tambah Employee
            </a>
        </div>
    </div>

    @if($successMessage)
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ $successMessage }}
        </div>
    @endif

    @if($errorMessage)
        <div class="rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ $errorMessage }}
        </div>
    @endif

    {{-- Satu surface ringkasan, bukan 4 stat-card terpisah. --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 lg:grid-cols-4 lg:divide-y-0">
            <div class="px-5 py-4">
                <p class="text-xs font-medium text-slate-400">Total Employee</p>
                <div class="mt-1 flex items-end gap-2">
                    <span class="text-2xl font-bold text-slate-900">{{ $statistics['total'] }}</span>
                    <span class="pb-1 text-xs text-slate-400">terdaftar</span>
                </div>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-medium text-slate-400">Aktif</p>
                <div class="mt-1 flex items-end gap-2">
                    <span class="text-2xl font-bold text-slate-900">{{ $statistics['active'] }}</span>
                    <span class="pb-1 text-xs text-slate-400">employee</span>
                </div>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-medium text-slate-400">Nonaktif</p>
                <div class="mt-1 flex items-end gap-2">
                    <span class="text-2xl font-bold text-slate-900">{{ $statistics['inactive'] }}</span>
                    <span class="pb-1 text-xs text-slate-400">employee</span>
                </div>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs font-medium text-slate-400">Baru Bulan Ini</p>
                <div class="mt-1 flex items-end gap-2">
                    <span class="text-2xl font-bold text-blue-600">{{ $statistics['new_this_month'] }}</span>
                    <span class="pb-1 text-xs text-slate-400">employee</span>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-[minmax(260px,1.5fr)_1fr_1fr_.8fr_auto]">
            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Cari</label>
                <div class="relative">
                    <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input type="text"
                           wire:model.live.debounce.400ms="search"
                           placeholder="Nama, NIP, atau email..."
                           class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Department</label>
                <select wire:model.live="department" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    <option value="">Semua Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->code }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Office</label>
                <select wire:model.live="office" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    <option value="">Semua Office</option>
                    @foreach($offices as $officeItem)
                        <option value="{{ $officeItem->code }}">{{ $officeItem->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                <select wire:model.live="isActive" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="button" wire:click="resetFilters"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 xl:w-auto">
                    <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                    Reset
                </button>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
         wire:loading.class="opacity-60"
         wire:target="search,department,office,isActive,sortBy,previousPage,nextPage,gotoPage">

        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="font-bold text-slate-900">Daftar Employee</h2>
                <p class="mt-0.5 text-xs text-slate-500">{{ $employees->total() }} employee sesuai filter.</p>
            </div>
            <span class="text-xs font-medium text-slate-400">{{ $employees->firstItem() ?? 0 }}–{{ $employees->lastItem() ?? 0 }} dari {{ $employees->total() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th wire:click="sortBy('full_name')" class="cursor-pointer select-none px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Employee @if($sort === 'full_name') {{ $direction === 'asc' ? '↑' : '↓' }} @endif
                        </th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Penempatan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Employment</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="w-16 px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $employee)
                        <tr wire:key="employee-row-{{ $employee->id }}" class="group transition hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <a href="{{ route('employees.show', $employee) }}" class="flex min-w-[250px] items-center gap-3">
                                    <x-ui.avatar :employee="$employee" size="11" />
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900 group-hover:text-blue-700">{{ $employee->full_name }}</p>
                                        <p class="mt-0.5 truncate text-xs text-slate-500">{{ $employee->employee_number }} · {{ $employee->email }}</p>
                                    </div>
                                </a>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-sm font-medium text-slate-700">{{ $employee->currentEmployment?->department?->name ?? '-' }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $employee->currentEmployment?->office?->name ?? 'Office belum diatur' }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-sm font-medium text-slate-700">{{ $employee->currentEmployment?->position?->name ?? '-' }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $employee->currentEmployment?->employment_type ?? '-' }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <span @class([
                                    'inline-flex items-center gap-2 rounded-full border px-2.5 py-1 text-xs font-semibold',
                                    'border-emerald-100 bg-emerald-50 text-emerald-700' => $employee->is_active,
                                    'border-slate-200 bg-slate-100 text-slate-600' => !$employee->is_active,
                                ])>
                                    <span @class(['h-2 w-2 rounded-full', 'bg-emerald-500' => $employee->is_active, 'bg-slate-400' => !$employee->is_active])></span>
                                    {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('employees.show', $employee) }}"
                                       title="Detail"
                                       class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-blue-50 hover:text-blue-600">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}"
                                       title="Edit"
                                       class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-blue-50 hover:text-blue-600">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>
                                    <button type="button"
                                            wire:click="toggleStatus({{ $employee->id }})"
                                            wire:confirm="{{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }} employee {{ $employee->full_name }}?"
                                            title="{{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                                        <i data-lucide="{{ $employee->is_active ? 'user-x' : 'user-check' }}" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <i data-lucide="users-round" class="h-5 w-5"></i>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-slate-700">Employee tidak ditemukan</p>
                                <p class="mt-1 text-sm text-slate-500">Ubah filter atau tambahkan employee baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="border-t border-slate-100 bg-slate-50/60 px-5 py-4">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>
