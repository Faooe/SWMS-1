<div wire:key="employee-manager-{{ $assignment->id }}">

    <x-assignment.section-card
        title="Assigned Employee"
        description="Employees assigned to this assignment."
        icon="users">

        <div class="mb-6 flex justify-end">
            <button
                type="button"
                wire:click="openPicker"
                class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                + Manage Employees
            </button>
        </div>

        @if($successMessage)
            <div class="mb-4 rounded-xl bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ $successMessage }}
            </div>
        @endif

        @if($errorMessage)
            <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ $errorMessage }}
            </div>
        @endif

        @if($employees->isEmpty())

            <div class="rounded-2xl border border-dashed border-slate-300 py-12 text-center">
                <i data-lucide="users" class="mx-auto h-12 w-12 text-slate-300"></i>
                <h3 class="mt-4 text-lg font-semibold text-slate-700">No Employee Assigned</h3>
                <p class="mt-2 text-slate-500">Click "Manage Employees" to assign someone.</p>
            </div>

        @else

            <div class="grid gap-5 lg:grid-cols-2">

                @foreach($employees as $employee)

                    @php
                        $status = $employee->pivot?->status ?? 'Assigned';
                        $badge = match($status){
                            'Completed' => 'bg-green-100 text-green-700',
                            'In Progress' => 'bg-blue-100 text-blue-700',
                            'Rejected' => 'bg-red-100 text-red-700',
                            'Accepted' => 'bg-cyan-100 text-cyan-700',
                            default => 'bg-yellow-100 text-yellow-700',
                        };
                    @endphp

                    <div
                        wire:key="assigned-{{ $employee->id }}"
                        class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md">

                        <div class="flex items-center gap-4">

                            <div>
                                @if($employee->photo)
                                    <img src="{{ asset('storage/'.$employee->photo) }}" class="h-16 w-16 rounded-full object-cover">
                                @else
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">
                                        <i data-lucide="user" class="h-8 w-8 text-blue-600"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-slate-800">{{ $employee->full_name }}</h3>
                                <p class="text-sm text-slate-500">{{ $employee->currentEmployment?->position?->name ?? '-' }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $employee->currentEmployment?->office?->name ?? '-' }}</p>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">
                                    {{ $status }}
                                </span>

                                @if(in_array($status, ['Assigned', 'Accepted']))
                                    <button
                                        type="button"
                                        wire:click="removeEmployee({{ $employee->id }})"
                                        wire:confirm="Hapus employee ini dari assignment?"
                                        class="text-xs font-semibold text-red-500 hover:text-red-700">
                                        Remove
                                    </button>
                                @endif
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </x-assignment.section-card>

    {{-- Picker Modal --}}
    @if($showPicker)

        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
            wire:click.self="closePicker">

            <div class="max-h-[85vh] w-full max-w-3xl overflow-hidden rounded-3xl bg-white shadow-xl">

                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-slate-800">Add Employee</h3>
                    <button type="button" wire:click="closePicker" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <div class="grid gap-3 border-b border-slate-200 px-6 py-4 md:grid-cols-3">

                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search employee..."
                        class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

                    <select wire:model.live="statusFilter" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    <select wire:model.live="busyFilter" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm">
                        <option value="">All Employee</option>
                        <option value="free">Available</option>
                        <option value="busy">Has Assignment</option>
                    </select>

                </div>

                <div class="max-h-[50vh] overflow-y-auto px-6 py-4">

                    <div wire:loading.class="opacity-40" wire:target="search,statusFilter,busyFilter" class="space-y-3 transition-opacity">

                        @forelse($availableEmployees as $employee)

                            <div
                                wire:key="picker-{{ $employee->id }}"
                                class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 hover:border-blue-400">

                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">
                                        {{ strtoupper(substr($employee->full_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $employee->full_name }}</div>
                                        <div class="text-xs text-slate-500">{{ $employee->currentEmployment?->position?->name ?? '-' }}</div>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    wire:click="addEmployee({{ $employee->id }})"
                                    wire:loading.attr="disabled"
                                    class="rounded-xl bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                                    Add
                                </button>

                            </div>

                        @empty

                            <p class="py-8 text-center text-sm text-slate-400">No employee found.</p>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>
