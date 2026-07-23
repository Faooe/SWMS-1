<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Office</h1>
            <p class="mt-2 text-slate-500">
                View your company's office locations and update their information, GPS coordinates, and attendance radius.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search & Filters --}}
    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">

            <div class="xl:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-600">Search</label>
                <div class="relative">
                    <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        wire:model.live.debounce.400ms="search"
                        placeholder="Search office name or code..."
                        class="w-full rounded-xl border border-slate-300 py-3 pl-11 pr-4 focus:border-blue-500 focus:ring focus:ring-blue-100">
                </div>
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

        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3">
            <button
                type="button"
                wire:click="resetFilters"
                class="rounded-xl border border-slate-300 px-6 py-3 text-sm font-medium hover:bg-slate-100">
                Reset Filters
            </button>
        </div>
    </x-ui.card>

    {{-- List --}}
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

            <div class="max-h-[560px] overflow-y-auto overflow-x-auto">
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
                                    <div class="flex justify-center">

                                        <a
                                            href="{{ route('offices.edit', $office) }}"
                                            class="inline-flex items-center gap-2 rounded-lg bg-blue-100 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-200">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                            View / Edit
                                        </a>

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
