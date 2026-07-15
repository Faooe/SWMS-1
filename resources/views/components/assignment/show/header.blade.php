@props([
    'assignment'
])

<div class="flex flex-col gap-6 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between">

    {{-- Left --}}
    <div>

        <a
            href="{{ route('assignments.index') }}"
            class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition hover:text-blue-700">

            <i
                data-lucide="arrow-left"
                class="h-4 w-4">
            </i>

            Back to Assignment

        </a>

        <h1 class="text-3xl font-bold text-slate-800">

            {{ $assignment->title }}

        </h1>

        <div class="mt-4 flex flex-wrap gap-3">

            <span
                class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">

                {{ $assignment->assignment_type }}

            </span>

            @php

                $priorityColor = match($assignment->priority){

                    'Critical' => 'bg-red-100 text-red-700',

                    'High' => 'bg-orange-100 text-orange-700',

                    'Medium' => 'bg-yellow-100 text-yellow-700',

                    default => 'bg-green-100 text-green-700'

                };

                $statusColor = match($assignment->status){

                    'Completed' => 'bg-green-100 text-green-700',

                    'Assigned' => 'bg-blue-100 text-blue-700',

                    'In Progress' => 'bg-orange-100 text-orange-700',

                    'Cancelled' => 'bg-red-100 text-red-700',

                    default => 'bg-slate-100 text-slate-700'

                };

            @endphp

            <span
                class="rounded-full px-4 py-2 text-sm font-semibold {{ $priorityColor }}">

                {{ $assignment->priority }}

            </span>

            <span
                class="rounded-full px-4 py-2 text-sm font-semibold {{ $statusColor }}">

                {{ $assignment->status }}

            </span>

        </div>

    </div>

    {{-- Right --}}
    <div class="flex items-center gap-3">

    <a
        href="{{ route('assignments.edit', $assignment) }}"
        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 transition hover:bg-amber-200">

        <i
            data-lucide="square-pen"
            class="h-5 w-5">
        </i>

    </a>

    <form
        action="{{ route('assignments.destroy',$assignment) }}"
        method="POST"
        onsubmit="return confirm('Delete this assignment?')">

        @csrf
        @method('DELETE')

        <button
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-700 transition hover:bg-red-200">

            <i
                data-lucide="trash-2"
                class="h-5 w-5">
            </i>

        </button>

    </form>

</div>

</div>