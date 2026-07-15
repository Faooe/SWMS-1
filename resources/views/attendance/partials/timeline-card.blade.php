<x-ui.card>

    {{-- Header --}}
    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            Activity Timeline

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Employee attendance activities.

        </p>

    </div>

    <div
        class="max-h-[380px] overflow-y-auto pr-3">

        <div class="relative ml-4 border-l-2 border-slate-200">

            {{-- Check In --}}
            <div class="relative pb-10 pl-8">

                <div
                    class="absolute -left-[11px] flex h-5 w-5 items-center justify-center rounded-full bg-green-500 ring-4 ring-white">

                </div>

                <h3 class="font-semibold">

                    Employee Check In

                </h3>

                <p class="mt-1 text-sm text-slate-500">

                    {{ $attendance->check_in_time
                        ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i')
                        : '-' }}

                </p>

            </div>

            {{-- GPS --}}
            <div class="relative pb-10 pl-8">

                <div
                    class="absolute -left-[11px] flex h-5 w-5 items-center justify-center rounded-full bg-blue-500 ring-4 ring-white">

                </div>

                <h3 class="font-semibold">

                    GPS Verification

                </h3>

                <p class="mt-1 text-sm text-slate-500">

                    {{ $attendance->location_verified
                        ? 'Location Verified'
                        : 'Location Not Verified' }}

                </p>

                <p class="text-xs text-slate-400">

                    Distance

                    {{ number_format($attendance->check_in_distance ?? 0,2) }}

                    Meter

                </p>

            </div>

            {{-- Status --}}
            <div class="relative pb-10 pl-8">

                <div
                    class="absolute -left-[11px] flex h-5 w-5 items-center justify-center rounded-full bg-purple-500 ring-4 ring-white">

                </div>

                <h3 class="font-semibold">

                    Attendance Status

                </h3>

                <span
                    class="mt-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-sm">

                    {{ $attendance->attendance_status }}

                </span>

            </div>

            {{-- Check Out --}}
            <div class="relative pl-8">

                <div
                    class="absolute -left-[11px] flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 ring-4 ring-white">

                </div>

                <h3 class="font-semibold">

                    Employee Check Out

                </h3>

                <p class="mt-1 text-sm text-slate-500">

                    {{ $attendance->check_out_time
                        ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i')
                        : 'Not Checked Out Yet' }}

                </p>

            </div>

        </div>

    </div>

</x-ui.card>