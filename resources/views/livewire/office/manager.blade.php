<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Office Management</h1>
            <p class="mt-2 text-slate-500">
                Manage office locations, GPS coordinates, attendance radius, and office information.
            </p>
        </div>

        <div class="flex gap-3">
            <a
                href="{{ route('offices.create') }}"
                class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
                + Add Office
            </a>
        </div>
    </div>

    @if($successMessage)
        <div class="rounded-xl bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ $successMessage }}
        </div>
    @endif

    @if($errorMessage)
        <div class="rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ $errorMessage }}
        </div>
    @endif

    {{-- Statistics --}}
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-5">
        <x-ui.stat-card title="Total Office" :value="$statistics['total']" icon="building-2" color="blue" />
        <x-ui.stat-card title="Active" :value="$statistics['active']" icon="badge-check" color="green" />
        <x-ui.stat-card title="Inactive" :value="$statistics['inactive']" icon="badge-x" color="red" />
        <x-ui.stat-card title="Head Office" :value="$statistics['head_office']" icon="building" color="purple" />
        <x-ui.stat-card title="Employees" :value="$statistics['employees']" icon="users" color="orange" />
    </div>

    {{-- Filters --}}
    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">

            <div class="xl:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-600">Search</label>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Office name, code..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-100">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-600">Province</label>
                <select wire:model.live="province" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    <option value="">All Province</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-600">City</label>
                <select wire:model.live="city" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    <option value="">All City</option>
                    @foreach($cities as $c)
                        <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-600">Status</label>
                <select wire:model.live="status" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-600">Per Page</label>
                <select wire:model.live="perPage" class="w-full rounded-xl border border-slate-300 px-4 py-3">
                    @foreach([10,25,50,100] as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3">
            <button
                type="button"
                wire:click="resetFilters"
                class="rounded-xl border border-slate-300 px-6 py-3 hover:bg-slate-100">
                Reset
            </button>
        </div>
    </x-ui.card>

    {{-- Table --}}
    <x-ui.card>

        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Office List</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $offices->total() }} office(s) found.</p>
            </div>
        </div>

        <div
            class="overflow-hidden rounded-2xl border border-slate-200"
            wire:loading.class="opacity-50"
            wire:target="search,province,city,status,perPage,previousPage,nextPage,gotoPage">

            <div class="max-h-[520px] overflow-y-auto overflow-x-auto">
                <table class="min-w-full whitespace-nowrap">

                    <thead class="sticky top-0 z-10 bg-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">Office</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">Code</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">Province</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase text-slate-500">City</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">Radius</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">Employees</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase text-slate-500">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @forelse($offices as $office)

                            <tr wire:key="office-row-{{ $office->id }}" class="transition hover:bg-slate-50">

                                <td class="px-6 py-4">
                                    <div>
                                        <h3 class="font-semibold text-slate-800">{{ $office->name }}</h3>
                                        @if($office->is_head_office)
                                            <span class="mt-1 inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">
                                                Head Office
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4">{{ $office->code }}</td>
                                <td class="px-6 py-4">{{ $office->province ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $office->city ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">{{ number_format($office->radius) }} m</td>
                                <td class="px-6 py-4 text-center">{{ $office->employees_count }}</td>

                                <td class="px-6 py-4 text-center">
                                    @if($office->is_active)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Active</span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Inactive</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">

                                        <a
                                            href="{{ route('offices.show', $office) }}"
                                            class="rounded-lg bg-sky-100 p-2 text-sky-700 transition hover:bg-sky-200">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>

                                        <a
                                            href="{{ route('offices.edit', $office) }}"
                                            class="rounded-lg bg-amber-100 p-2 text-amber-700 transition hover:bg-amber-200">
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>

                                        <button
                                            type="button"
                                            wire:click="deleteOffice({{ $office->id }})"
                                            wire:confirm="Delete office {{ $office->name }}?"
                                            class="rounded-lg bg-red-100 p-2 text-red-700 transition hover:bg-red-200">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center text-slate-500">
                                    No office data found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $offices->links() }}
        </div>

    </x-ui.card>

</div>
