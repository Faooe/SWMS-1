<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Team Management</h1>
            <p class="mt-2 text-slate-500">
                Kelola data team di bawah setiap department sebagai master data pengelompokan Employee.
            </p>
        </div>

        <a
            href="{{ route('teams.create') }}"
            class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
            + Add Team
        </a>
    </div>

    @if($successMessage)
        <div class="rounded-2xl bg-green-100 px-5 py-4 text-sm font-medium text-green-700">
            {{ $successMessage }}
        </div>
    @endif

    @if($errorMessage)
        <div class="rounded-2xl bg-red-100 px-5 py-4 text-sm font-medium text-red-700">
            {{ $errorMessage }}
        </div>
    @endif

    {{-- Statistics --}}
    <div class="grid gap-6 md:grid-cols-3">
        <x-ui.card>
            <p class="text-sm text-slate-500">Total Team</p>
            <h2 class="mt-3 text-4xl font-bold">{{ number_format($statistics['total']) }}</h2>
        </x-ui.card>

        <x-ui.card>
            <p class="text-sm text-slate-500">Active</p>
            <h2 class="mt-3 text-4xl font-bold text-green-600">{{ number_format($statistics['active']) }}</h2>
        </x-ui.card>

        <x-ui.card>
            <p class="text-sm text-slate-500">Inactive</p>
            <h2 class="mt-3 text-4xl font-bold text-red-600">{{ number_format($statistics['inactive']) }}</h2>
        </x-ui.card>
    </div>

    {{-- Filter --}}
    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-5">

            <div class="relative md:col-span-2">
                <i data-lucide="search" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Cari nama atau kode team..."
                    class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            </div>

            <select
                wire:model.live="department"
                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">All Department</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>

            <select
                wire:model.live="isActive"
                class="rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <button
                type="button"
                wire:click="resetFilters"
                class="rounded-2xl border border-slate-300 py-3 text-sm font-semibold transition hover:bg-slate-100">
                Reset
            </button>

        </div>
    </x-ui.card>

    {{-- Table --}}
    <div
        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        wire:loading.class="opacity-50"
        wire:target="search,department,isActive,previousPage,nextPage,gotoPage">

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">
            <table class="min-w-full">

                <thead class="sticky top-0 z-10 bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Code</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Department</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Description</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Employees</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse($teams as $team)

                        <tr wire:key="team-row-{{ $team->id }}" class="transition hover:bg-slate-50">

                            <td class="px-6 py-5 font-semibold text-slate-800">{{ $team->code }}</td>
                            <td class="px-6 py-5">{{ $team->name }}</td>
                            <td class="px-6 py-5 text-slate-600">{{ $team->department?->name ?? '-' }}</td>
                            <td class="max-w-xs truncate px-6 py-5 text-slate-500">{{ $team->description ?? '-' }}</td>
                            <td class="px-6 py-5 text-center">{{ $team->employment_histories_count }}</td>

                            <td class="px-6 py-5 text-center">
                                <button
                                    type="button"
                                    wire:click="toggleStatus({{ $team->id }})"
                                    wire:confirm="{{ $team->is_active ? 'Nonaktifkan' : 'Aktifkan' }} team {{ $team->name }}?"
                                    @class([
                                        'inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-semibold transition',
                                        'bg-green-100 text-green-700 hover:bg-green-200' => $team->is_active,
                                        'bg-red-100 text-red-700 hover:bg-red-200' => !$team->is_active,
                                    ])>
                                    <span @class([
                                        'h-2.5 w-2.5 rounded-full',
                                        'bg-green-500' => $team->is_active,
                                        'bg-red-500' => !$team->is_active,
                                    ])></span>
                                    {{ $team->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">

                                    <a
                                        href="{{ route('teams.edit', $team) }}"
                                        title="Edit Team"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-500 hover:text-white">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>

                                    <button
                                        type="button"
                                        wire:click="deleteTeam({{ $team->id }})"
                                        wire:confirm="Delete team {{ $team->name }}?"
                                        title="Delete Team"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-600 hover:text-white">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="py-20">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="briefcase-business" class="mb-4 h-14 w-14 text-slate-300"></i>
                                    <h3 class="text-lg font-semibold text-slate-700">No Team Found</h3>
                                    <p class="mt-2 text-sm text-slate-400">There are no team records available.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
            {{ $teams->links() }}
        </div>

    </div>

</div>
