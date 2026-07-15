@props([
    'assignment'
])

<x-assignment.section-card
    title="Assigned Employee"
    description="Employees assigned to this assignment."
    icon="users">

    @if($assignment->employees->isEmpty())

        <div class="rounded-2xl border border-dashed border-slate-300 py-12 text-center">

            <i
                data-lucide="users"
                class="mx-auto h-12 w-12 text-slate-300">
            </i>

            <h3 class="mt-4 text-lg font-semibold text-slate-700">

                No Employee Assigned

            </h3>

            <p class="mt-2 text-slate-500">

                Assign employees from Edit Assignment.

            </p>

        </div>

    @else

        <div class="grid gap-5 lg:grid-cols-2">

            @foreach($assignment->employees as $employee)

                @php

                    $status = $employee->pivot?->status ?? 'Assigned';

                    $badge = match($status){

                        'Completed'
                            => 'bg-green-100 text-green-700',

                        'In Progress'
                            => 'bg-blue-100 text-blue-700',

                        'Rejected'
                            => 'bg-red-100 text-red-700',

                        'Accepted'
                            => 'bg-cyan-100 text-cyan-700',

                        default
                            => 'bg-yellow-100 text-yellow-700',

                    };

                @endphp

                <div
                    class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-400 hover:shadow-md">

                    <div class="flex items-center gap-4">

                        {{-- Avatar --}}
                        <div>

                            @if($employee->photo)

                                <img
                                    src="{{ asset('storage/'.$employee->photo) }}"
                                    class="h-16 w-16 rounded-full object-cover">

                            @else

                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">

                                    <i
                                        data-lucide="user"
                                        class="h-8 w-8 text-blue-600">
                                    </i>

                                </div>

                            @endif

                        </div>

                        {{-- Employee Information --}}
                        <div class="flex-1">

                            <h3 class="text-lg font-bold text-slate-800">

                                {{ $employee->full_name }}

                            </h3>

                            <p class="text-sm text-slate-500">

                                {{ $employee->currentEmployment?->position?->name ?? '-' }}

                            </p>

                            <p class="mt-1 text-xs text-slate-400">

                                {{ $employee->currentEmployment?->office?->name ?? '-' }}

                            </p>

                        </div>

                        {{-- Assignment Status --}}
                        <div>

                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">

                                {{ $status }}

                            </span>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</x-assignment.section-card>