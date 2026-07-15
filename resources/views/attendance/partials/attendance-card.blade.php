<x-ui.card>

    {{-- Header --}}
    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            Attendance Information

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Employee attendance summary.

        </p>

    </div>

    {{-- Body --}}
    <div class="space-y-5">

        {{-- Date --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Attendance Date

            </span>

            <span class="font-semibold">

                {{ $attendance->attendance_date->format('d F Y') }}

            </span>

        </div>

        <hr>

        {{-- Status --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Status

            </span>

            @php

                $statusColor = match($attendance->attendance_status){

                    'Present' => 'bg-green-100 text-green-700',

                    'Late' => 'bg-orange-100 text-orange-700',

                    'Leave' => 'bg-blue-100 text-blue-700',

                    'Permission' => 'bg-yellow-100 text-yellow-700',

                    'Absent' => 'bg-red-100 text-red-700',

                    default => 'bg-slate-100 text-slate-700',

                };

            @endphp

            <span
                class="rounded-full px-4 py-1 text-sm font-semibold {{ $statusColor }}">

                {{ $attendance->attendance_status }}

            </span>

        </div>

        <hr>

        {{-- Check In --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Check In

            </span>

            <span class="font-semibold">

                {{ $attendance->check_in_time
                    ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i')
                    : '-' }}

            </span>

        </div>

        <hr>

        {{-- Check Out --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Check Out

            </span>

            <span class="font-semibold">

                {{ $attendance->check_out_time
                    ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i')
                    : '-' }}

            </span>

        </div>

        <hr>

        {{-- Late --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Late Minutes

            </span>

            <span class="font-semibold">

                {{ $attendance->late_minutes }} Minute

            </span>

        </div>

        <hr>

        {{-- Radius --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Allowed Radius

            </span>

            <span class="font-semibold">

                {{ $attendance->allowed_radius }} m

            </span>

        </div>

        <hr>

        {{-- Distance --}}
        <div class="flex items-center justify-between">

            <span class="text-slate-500">

                Employee Distance

            </span>

            <span class="font-semibold">

                {{ $attendance->check_in_distance }} m

            </span>

        </div>

    </div>

</x-ui.card>