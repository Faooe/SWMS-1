<div class="space-y-6">

    {{-- Stats --}}
    <x-employee.stats :statistics="$statistics" />

    {{-- Toolbar --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">Employee Management</h2>
            <a
                href="{{ route('employees.create') }}"
                class="rounded-xl bg-blue-600 px-5 py-3 text-white">
                Add Employee
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

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-4">

            <div>
                <label class="mb-2 block text-sm font-semibold">Search</label>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Employee name..."
                    class="w-full rounded-2xl border border-slate-300 px-4 py-3">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Department</label>
                <select wire:model.live="department" class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                    <option value="">All Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->code }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Status</label>
                <select wire:model.live="isActive" class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="rounded-xl border px-6 py-3">
                    Reset
                </button>
            </div>

        </div>
    </div>

    {{-- Table --}}
    <div
        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        wire:loading.class="opacity-50"
        wire:target="search,department,isActive,sortBy,previousPage,nextPage,gotoPage">

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">
            <table class="min-w-full">

                <thead class="sticky top-0 z-10 bg-slate-50">
                    <tr>
                        <th
                            wire:click="sortBy('full_name')"
                            class="cursor-pointer select-none px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Employee
                            @if($sort === 'full_name') {{ $direction === 'asc' ? '↑' : '↓' }} @endif
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Department</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Position</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">

                    @forelse($employees as $employee)

                        <tr wire:key="employee-row-{{ $employee->id }}" class="transition duration-300 hover:bg-slate-50">

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <x-ui.avatar :employee="$employee" size="12" />
                                    <div>
                                        <h3 class="font-semibold text-slate-800">{{ $employee->full_name }}</h3>
                                        <p class="text-sm text-slate-500">{{ $employee->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <span class="font-medium">{{ $employee->currentEmployment?->department?->name ?? '-' }}</span>
                            </td>

                            <td class="px-6 py-5">
                                <span class="font-medium">{{ $employee->currentEmployment?->position?->name ?? '-' }}</span>
                            </td>

                            <td class="px-6 py-5">
                                <button
                                    type="button"
                                    wire:click="toggleStatus({{ $employee->id }})"
                                    wire:confirm="{{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }} employee {{ $employee->full_name }}?"
                                    title="Klik untuk {{ $employee->is_active ? 'menonaktifkan' : 'mengaktifkan' }}"
                                    @class([
                                        'inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-semibold transition',
                                        'bg-green-100 text-green-700 hover:bg-green-200' => $employee->is_active,
                                        'bg-red-100 text-red-700 hover:bg-red-200' => !$employee->is_active,
                                    ])>
                                    <span @class([
                                        'h-2.5 w-2.5 rounded-full',
                                        'bg-green-500' => $employee->is_active,
                                        'bg-red-500' => !$employee->is_active,
                                    ])></span>
                                    {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">

                                    <a
                                        href="{{ route('employees.show', $employee) }}"
                                        title="View Employee"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-600 transition hover:bg-sky-600 hover:text-white">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </a>

                                    <a
                                        href="{{ route('employees.edit', $employee) }}"
                                        title="Edit Employee"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition hover:bg-amber-500 hover:text-white">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>

                                    <button
                                        type="button"
                                        wire:click="deleteEmployee({{ $employee->id }})"
                                        wire:confirm="Delete employee {{ $employee->full_name }}?"
                                        title="Delete Employee"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-100 text-red-600 transition hover:bg-red-600 hover:text-white">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="py-20">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="users-round" class="mb-4 h-14 w-14 text-slate-300"></i>
                                    <h3 class="text-lg font-semibold text-slate-700">No Employee Found</h3>
                                    <p class="mt-2 text-sm text-slate-400">There are no employee records available.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
            {{ $employees->links() }}
        </div>

    </div>

</div>
