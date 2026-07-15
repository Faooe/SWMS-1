<x-ui.card>

    {{-- Header --}}
    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            Employee Information

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Employee identity and placement.

        </p>

    </div>

    {{-- Body --}}
    <div class="space-y-5">

        {{-- Name --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Full Name

            </span>

            <span class="font-semibold">

                {{ $attendance->employee?->full_name ?? '-' }}

            </span>

        </div>

        <hr>

        {{-- Employee Number --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Employee Number

            </span>

            <span class="font-semibold">

                {{ $attendance->employee?->employee_number ?? '-' }}

            </span>

        </div>

        <hr>

        {{-- Position --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Position

            </span>

            <span class="font-semibold">

                {{ $attendance->employee?->currentEmployment?->position?->name ?? '-' }}

            </span>

        </div>

        <hr>

        {{-- Office --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Office

            </span>

            <span class="font-semibold">

                {{ $attendance->employee?->currentEmployment?->office?->name ?? '-' }}

            </span>

        </div>

        <hr>

        {{-- Attendance Type --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Attendance Type

            </span>

            <span class="rounded-full bg-slate-100 px-4 py-1 text-sm font-semibold">

                {{ $attendance->attendance_type }}

            </span>

        </div>

        @if($attendance->attendance_type === 'ASSIGNMENT' && $attendance->assignment)

            <hr>

            <div class="flex items-center justify-between">

                <span class="text-slate-500">

                    Assignment

                </span>

                <span class="font-semibold">

                    {{ $attendance->assignment->title }}

                </span>

            </div>

        @endif

    </div>

</x-ui.card>