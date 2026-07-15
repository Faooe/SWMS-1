@props([
    'assignment'
])

<x-assignment.section-card
    title="Assignment Information"
    description="General information about this assignment."
    icon="clipboard-list">

    <div class="grid gap-6 md:grid-cols-2">

        <div>

            <p class="text-sm text-slate-500">

                Assignment Number

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                {{ $assignment->assignment_number }}

            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Office

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                {{ $assignment->office?->name }}

            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Assignment Type

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                {{ $assignment->assignment_type }}

            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Priority

            </p>

            <span class="mt-2 inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">

                {{ $assignment->priority }}

            </span>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Status

            </p>

            <span class="mt-2 inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">

                {{ $assignment->status }}

            </span>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Created By

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                {{ $assignment->creator?->employee?->full_name }}

            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                Start Date

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                {{ $assignment->start_datetime->format('d M Y H:i') }}

            </h3>

        </div>

        <div>

            <p class="text-sm text-slate-500">

                End Date

            </p>

            <h3 class="mt-1 text-lg font-semibold">

                {{ $assignment->end_datetime->format('d M Y H:i') }}

            </h3>

        </div>

    </div>

</x-assignment.section-card>