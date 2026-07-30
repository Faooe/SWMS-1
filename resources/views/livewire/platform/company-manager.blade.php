<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Company Management</h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola seluruh perusahaan yang menggunakan Smart Workforce Management System.
            </p>
        </div>

        <a href="{{ route('platform.companies.create') }}">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 hover:shadow-md">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Add Company
            </button>
        </a>
    </div>

    @if($successMessage)
        <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
            <i data-lucide="circle-check-big" class="h-5 w-5 shrink-0"></i>
            {{ $successMessage }}
        </div>
    @endif

    @if($errorMessage)
        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
            <i data-lucide="circle-x" class="h-5 w-5 shrink-0"></i>
            {{ $errorMessage }}
        </div>
    @endif

    {{-- Statistics --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-5">

        <div class="group overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Total Company</p>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 transition group-hover:bg-blue-600">
                    <i data-lucide="building-2" class="h-5 w-5 text-blue-600 transition group-hover:text-white"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">{{ $statistics['total'] }}</h2>
        </div>

        <div class="group overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Active Company</p>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 transition group-hover:bg-green-600">
                    <i data-lucide="circle-check" class="h-5 w-5 text-green-600 transition group-hover:text-white"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">{{ $statistics['active'] }}</h2>
        </div>

        <div class="group overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Free Plan</p>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 transition group-hover:bg-slate-600">
                    <i data-lucide="package" class="h-5 w-5 text-slate-600 transition group-hover:text-white"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">{{ $statistics['free'] }}</h2>
        </div>

        <div class="group overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Premium Plan</p>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 transition group-hover:bg-amber-500">
                    <i data-lucide="gem" class="h-5 w-5 text-amber-600 transition group-hover:text-white"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">{{ $statistics['premium'] }}</h2>
        </div>

        <div class="group overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Employees</p>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-100 transition group-hover:bg-purple-600">
                    <i data-lucide="users" class="h-5 w-5 text-purple-600 transition group-hover:text-white"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">{{ $statistics['employees'] }}</h2>
        </div>

    </div>

    {{-- Search & Filter --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-3 md:flex-row">

            <div class="relative flex-1">
                <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Cari nama, kode, atau email company..."
                    class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            </div>

            <select
                wire:model.live="status"
                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <select
                wire:model.live="plan"
                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">All Plan</option>
                <option value="Free">Free</option>
                <option value="Premium Go">Premium Go</option>
                <option value="Premium Plus">Premium Plus</option>
            </select>

            <button
                type="button"
                wire:click="resetFilters"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                Reset
            </button>

        </div>
    </div>

    {{-- Table --}}
    <div
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
        wire:loading.class="opacity-50"
        wire:target="search,status,plan,previousPage,nextPage,gotoPage">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">

                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Company</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Super Admin</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Plan</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Employee</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($companies as $company)

                        @php
                            $admin = $company->users->firstWhere('role.code', 'SUPER_ADMIN');
                            $employeeRatio = $company->max_employee > 0
                                ? $company->employees_count / $company->max_employee
                                : 0;
                        @endphp

                        <tr wire:key="company-row-{{ $company->id }}" class="group transition hover:bg-slate-50/70">

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    @if($company->logo)
                                        <img src="{{ asset('storage/'.$company->logo) }}" class="h-12 w-12 rounded-xl object-cover ring-1 ring-slate-200">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 font-bold text-white shadow-sm">
                                            {{ strtoupper(substr($company->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <h3 class="truncate font-semibold text-slate-800">{{ $company->name }}</h3>
                                        <p class="flex items-center gap-1 text-sm text-slate-500">
                                            <i data-lucide="hash" class="h-3.5 w-3.5"></i>
                                            {{ $company->code }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if($admin)
                                    <div class="font-medium text-slate-800">{{ $admin->employee?->full_name }}</div>
                                    <div class="flex items-center gap-1 text-sm text-slate-500">
                                        <i data-lucide="mail" class="h-3.5 w-3.5"></i>
                                        {{ $admin->email }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-sm text-slate-400">
                                        <i data-lucide="user-x" class="h-4 w-4"></i>
                                        Belum ada
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($company->subscription_plan === 'Free')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                        <i data-lucide="package" class="h-3.5 w-3.5"></i>
                                        Free
                                    </span>
                                @elseif($company->subscription_plan === 'Premium Go')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                        <i data-lucide="zap" class="h-3.5 w-3.5"></i>
                                        Premium Go
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-100 px-3 py-1.5 text-xs font-semibold text-purple-700">
                                        <i data-lucide="crown" class="h-3.5 w-3.5"></i>
                                        {{ $company->subscription_plan }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="mx-auto flex max-w-[110px] flex-col items-center gap-1.5">
                                    <span class="text-sm font-semibold {{ $employeeRatio >= 0.9 ? 'text-amber-600' : 'text-slate-800' }}">
                                        {{ $company->employees_count }}
                                        <span class="font-normal text-slate-400">/ {{ $company->max_employee }}</span>
                                    </span>
                                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                        <div
                                            class="h-full rounded-full {{ $employeeRatio >= 0.9 ? 'bg-amber-500' : 'bg-blue-500' }}"
                                            style="width: {{ min($employeeRatio * 100, 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <button
                                    type="button"
                                    wire:click="toggleStatus({{ $company->id }})"
                                    wire:confirm="{{ $company->is_active ? 'Nonaktifkan' : 'Aktifkan' }} company {{ $company->name }}?"
                                    @class([
                                        'inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold transition',
                                        'bg-green-100 text-green-700 hover:bg-green-200' => $company->is_active,
                                        'bg-red-100 text-red-700 hover:bg-red-200' => !$company->is_active,
                                    ])>
                                    <span @class([
                                        'h-1.5 w-1.5 rounded-full',
                                        'bg-green-600' => $company->is_active,
                                        'bg-red-600' => !$company->is_active,
                                    ])></span>
                                    {{ $company->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2 opacity-80 transition group-hover:opacity-100">

                                    <a href="{{ route('platform.companies.show', $company) }}" title="Detail">
                                        <button type="button" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </button>
                                    </a>

                                    <a href="{{ route('platform.companies.edit', $company) }}" title="Edit">
                                        <button type="button" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-600">
                                            <i data-lucide="square-pen" class="h-4 w-4"></i>
                                        </button>
                                    </a>

                                    <button
                                        type="button"
                                        title="Delete"
                                        wire:click="deleteCompany({{ $company->id }})"
                                        wire:confirm="Yakin ingin menghapus company {{ $company->name }}?"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-transparent bg-red-50 text-red-600 transition hover:bg-red-100">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                                    <i data-lucide="building-2" class="h-8 w-8 text-slate-300"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-bold text-slate-800">Belum Ada Company</h3>
                                <p class="mt-1 text-sm text-slate-500">Silakan tambahkan company baru.</p>
                                <a href="{{ route('platform.companies.create') }}" class="mt-4 inline-block">
                                    <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                                        <i data-lucide="plus" class="h-4 w-4"></i>
                                        Add Company
                                    </button>
                                </a>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        @if($companies->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $companies->links() }}
            </div>
        @endif

    </div>

</div>
