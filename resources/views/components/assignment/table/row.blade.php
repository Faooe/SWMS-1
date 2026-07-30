@props([
    'assignment'
])

<tr class="transition hover:bg-slate-50">

    {{-- Assignment Number --}}
    <td class="px-6 py-5">

        <div class="font-semibold">

            {{ $assignment->assignment_number }}

        </div>

    </td>

    {{-- Title --}}
    <td class="px-6 py-5">

        <div class="font-semibold">

            {{ $assignment->title }}

        </div>

        <div class="mt-1 text-sm text-slate-500">

            {{ $assignment->assignment_type }}

        </div>

    </td>

    {{-- Office --}}
    <td class="px-6 py-5">

        {{ $assignment->office?->name }}

    </td>

    {{-- Priority --}}
    <td class="px-6 py-5">

        @php

            $priorityColor = match($assignment->priority){

                'Critical' => 'bg-red-100 text-red-700',

                'High' => 'bg-orange-100 text-orange-700',

                'Medium' => 'bg-yellow-100 text-yellow-700',

                default => 'bg-green-100 text-green-700'

            };

        @endphp

        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $priorityColor }}">

            {{ $assignment->priority }}

        </span>

    </td>

    {{-- Status --}}
    <td class="px-6 py-5">

        @php

            $statusColor = match($assignment->status){

                'Completed' => 'bg-green-100 text-green-700',

                'Assigned' => 'bg-blue-100 text-blue-700',

                'In Progress' => 'bg-orange-100 text-orange-700',

                'Cancelled' => 'bg-red-100 text-red-700',

                default => 'bg-slate-100 text-slate-700'

            };

        @endphp

        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor }}">

            {{ $assignment->status }}

        </span>

    </td>

    {{-- Employee --}}
    <td class="px-6 py-5">

        {{ $assignment->employees->count() }}

    </td>

    {{-- Schedule --}}
    <td class="px-6 py-5 text-sm">

        <div>

            {{ $assignment->start_datetime->format('d M Y') }}

        </div>

        <div class="text-slate-500">

            {{ $assignment->start_datetime->format('H:i') }}

            -

            {{ $assignment->end_datetime->format('H:i') }}

        </div>

    </td>

    {{-- Action --}}
    <td class="px-6 py-5">

        <div class="flex items-center gap-2">

            <a
                href="{{ route('assignments.show',$assignment) }}"
                class="rounded-xl bg-blue-100 p-2 text-blue-700 transition hover:bg-blue-200">

                <i data-lucide="eye" class="h-4 w-4"></i>

            </a>

            <a
                href="{{ route('assignments.edit',$assignment) }}"
                class="rounded-xl bg-yellow-100 p-2 text-yellow-700 transition hover:bg-yellow-200">

                <i data-lucide="pencil" class="h-4 w-4"></i>

            </a>

            <form
                method="POST"
                action="{{ route('assignments.destroy',$assignment) }}"
                onsubmit="return confirm('Delete this assignment?')">

                @csrf
                @method('DELETE')

                <button
                    class="rounded-xl bg-red-100 p-2 text-red-700 transition hover:bg-red-200">

                    <i data-lucide="trash-2" class="h-4 w-4"></i>

                </button>

            </form>

        </div>

    </td>

</tr>