<x-ui.card>

    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            Attendance Timeline

        </h2>

        <p class="mt-1 text-slate-500">

            Employee attendance activity history.

        </p>

    </div>

    <div class="max-h-[350px] overflow-y-auto pr-2">

        <div class="relative border-l-2 border-blue-200 ml-3">

            {{-- Check In --}}
            <div class="relative mb-8 pl-8">

                <span
                    class="absolute -left-[11px] top-1 flex h-5 w-5 items-center justify-center rounded-full bg-green-500 ring-4 ring-white">
                </span>

                <h3 class="font-semibold text-slate-800">

                    Check In

                </h3>

                <p class="text-sm text-slate-500">

                    {{ $attendance->check_in_time
                        ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i')
                        : '-' }}

                </p>

            </div>

            {{-- GPS --}}
            <div class="relative mb-8 pl-8">

                <span
                    class="absolute -left-[11px] top-1 flex h-5 w-5 items-center justify-center rounded-full bg-blue-500 ring-4 ring-white">
                </span>

                <h3 class="font-semibold text-slate-800">

                    GPS Validation

                </h3>

                <p class="text-sm text-slate-500">

                    {{ $attendance->location_verified
                        ? 'Location Verified'
                        : 'Location Not Verified' }}

                </p>

            </div>

            {{-- Check Out --}}
            <div class="relative pl-8">

                <span
                    class="absolute -left-[11px] top-1 flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 ring-4 ring-white">
                </span>

                <h3 class="font-semibold text-slate-800">

                    Check Out

                </h3>

                <p class="text-sm text-slate-500">

                    {{ $attendance->check_out_time
                        ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i')
                        : 'Not Checked Out Yet' }}

                </p>

            </div>

        </div>

    </div>

</x-ui.card>