<div class="grid gap-6 lg:grid-cols-2">

    {{-- Employee Information --}}
    <x-ui.card>

        <div class="mb-6 flex items-center gap-4">

            <x-ui.avatar
                :name="$attendance->employee->full_name"
                class="h-16 w-16"
            />

            <div>

                <h2 class="text-2xl font-bold text-slate-800">

                    {{ $attendance->employee->full_name }}

                </h2>

                <p class="mt-1 text-slate-500">

                    {{ $attendance->employee->employee_number }}

                </p>

            </div>

        </div>

        <div class="grid gap-5">

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">

                <span class="text-slate-500">

                    Office

                </span>

                <span class="font-semibold">

                    {{ optional($attendance->employee->currentEmployment->office)->name ?? '-' }}

                </span>

            </div>

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">

                <span class="text-slate-500">

                    Position

                </span>

                <span class="font-semibold">

                    {{ optional($attendance->employee->currentEmployment->position)->name ?? '-' }}

                </span>

            </div>

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">

                <span class="text-slate-500">

                    Department

                </span>

                <span class="font-semibold">

                    {{ optional($attendance->employee->currentEmployment->department)->name ?? '-' }}

                </span>

            </div>

            <div class="flex items-center justify-between">

                <span class="text-slate-500">

                    Employment Status

                </span>

                <span
                    class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">

                    Active

                </span>

            </div>

        </div>

    </x-ui.card>

    {{-- Attendance Information --}}
    <x-ui.card>

        <h2 class="mb-6 text-xl font-bold text-slate-800">

            Attendance Information

        </h2>

        @php

            $statusColor = match($attendance->attendance_status){

                'Present' => 'green',

                'Late' => 'orange',

                'Absent' => 'red',

                'Leave' => 'purple',

                'Permission' => 'blue',

                default => 'slate',

            };

        @endphp

        <div class="grid gap-5">

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">

                <span class="text-slate-500">

                    Date

                </span>

                <span class="font-semibold">

                    {{ $attendance->attendance_date->format('d F Y') }}

                </span>

            </div>

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">

                <span class="text-slate-500">

                    Attendance Type

                </span>

                <span class="font-semibold">

                    {{ $attendance->attendance_type }}

                </span>

            </div>

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">

                <span class="text-slate-500">

                    Check In

                </span>

                <span class="font-semibold">

                    {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}

                </span>

            </div>

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">

                <span class="text-slate-500">

                    Check Out

                </span>

                <span class="font-semibold">

                    {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}

                </span>

            </div>

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">

                <span class="text-slate-500">

                    Late Minutes

                </span>

                <span class="font-semibold">

                    {{ $attendance->late_minutes }} Minutes

                </span>

            </div>

            <div class="flex items-center justify-between">

                <span class="text-slate-500">

                    Status

                </span>

                <span
                    class="rounded-full bg-{{ $statusColor }}-100 px-4 py-1 text-sm font-semibold text-{{ $statusColor }}-700">

                    {{ $attendance->attendance_status }}

                </span>

            </div>

        </div>

    </x-ui.card>

</div>