<div
    class="rounded-3xl bg-white p-8 shadow">

    {{-- Header --}}
    <div class="mb-6 flex items-center gap-3">

        <div
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-100">

            <i
                data-lucide="users"
                class="h-6 w-6 text-purple-600">
            </i>

        </div>

        <div>

            <h2
                class="text-xl font-bold text-slate-800">

                Assignment Team

            </h2>

            <p
                class="text-sm text-slate-500">

                Employees assigned to this assignment.

            </p>

        </div>

    </div>

    <div class="space-y-4">

        @foreach($assignment->employees as $employee)

            @php

                $pivot = $employee->pivot;

                $statusColor = match($pivot->status){

                    'Assigned'
                        => 'bg-blue-100 text-blue-700',

                    'Accepted'
                        => 'bg-cyan-100 text-cyan-700',

                    'In Progress'
                        => 'bg-amber-100 text-amber-700',

                    'Completed'
                        => 'bg-green-100 text-green-700',

                    default
                        => 'bg-slate-100 text-slate-700',

                };

            @endphp

            <div
                class="flex items-center justify-between rounded-2xl border border-slate-200 p-4 transition hover:border-blue-300">

                <div class="flex items-center gap-4">

                    {{-- Avatar --}}
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">

                        {{ strtoupper(substr($employee->full_name,0,1)) }}

                    </div>

                    {{-- Information --}}
                    <div>

                        <h3
                            class="font-semibold text-slate-800">

                            {{ $employee->full_name }}

                        </h3>

                        <p
                            class="text-sm text-slate-500">

                            {{ $employee->currentEmployment?->position?->name ?? '-' }}

                        </p>

                    </div>

                </div>

                {{-- Status --}}
                <span
                    class="rounded-full px-4 py-2 text-sm font-semibold {{ $statusColor }}">

                    {{ $pivot->status }}

                </span>

            </div>

        @endforeach

    </div>

</div>