@props([
    'assignment'
])

<div
    class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

    <div class="mb-6 flex items-center justify-between">

        <div>

            <h2 class="text-xl font-bold text-slate-800">

                Today's Assignment

            </h2>

            <p class="mt-1 text-sm text-slate-500">

                Your assignment scheduled for today.

            </p>

        </div>

        <div
            class="rounded-2xl bg-blue-100 p-3">

            <i
                data-lucide="clipboard-list"
                class="h-6 w-6 text-blue-600">
            </i>

        </div>

    </div>

    @if($assignment)

        @php

            $badge = match($assignment->pivot->status){

                'Assigned'
                    => 'bg-yellow-100 text-yellow-700',

                'Accepted'
                    => 'bg-blue-100 text-blue-700',

                'In Progress'
                    => 'bg-indigo-100 text-indigo-700',

                'Completed'
                    => 'bg-green-100 text-green-700',

                default
                    => 'bg-slate-100 text-slate-700',

            };

        @endphp

        <div class="space-y-6">

            <div>

                <h3
                    class="text-2xl font-bold text-slate-800">

                    {{ $assignment->title }}

                </h3>

                <p
                    class="mt-2 text-slate-500">

                    {{ $assignment->description }}

                </p>

            </div>

            <div
                class="grid gap-4 md:grid-cols-2">

                <div>

                    <p
                        class="text-sm text-slate-500">

                        Schedule

                    </p>

                    <h4
                        class="font-semibold">

                        {{ $assignment->start_datetime->format('d M Y H:i') }}

                    </h4>

                    <span
                        class="text-slate-400">

                        until

                    </span>

                    <h4
                        class="font-semibold">

                        {{ $assignment->end_datetime->format('d M Y H:i') }}

                    </h4>

                </div>

                <div>

                    <p
                        class="text-sm text-slate-500">

                        Office

                    </p>

                    <h4
                        class="font-semibold">

                        {{ $assignment->office->name }}

                    </h4>

                </div>

            </div>

            <div
                class="flex items-center justify-between">

                <span
                    class="rounded-full px-4 py-2 text-sm font-semibold {{ $badge }}">

                    {{ $assignment->pivot->status }}

                </span>

                <a
                    href="{{ route('employee.assignments.show',$assignment) }}"
                    class="rounded-2xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">

                    View Detail

                </a>

            </div>

        </div>

    @else

        <div
            class="py-12 text-center">

            <i
                data-lucide="calendar-days"
                class="mx-auto h-14 w-14 text-slate-300">
            </i>

            <h3
                class="mt-5 text-lg font-semibold text-slate-700">

                No Assignment Today

            </h3>

            <p
                class="mt-2 text-slate-500">

                There are no assignments scheduled for today.

            </p>

        </div>

    @endif

</div>