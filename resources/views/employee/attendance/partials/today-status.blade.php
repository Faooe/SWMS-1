<x-ui.card>

    <div class="flex items-center justify-between">

        <div>

            <p class="text-sm text-slate-500">

                {{ now()->translatedFormat('l, d F Y') }}

            </p>

            <h2 class="mt-1 text-2xl font-bold text-slate-800">

                Today's Status

            </h2>

        </div>

        @if($officeAttendance && $officeAttendance->isCompleted())

            <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

                Completed

            </span>

        @elseif($officeAttendance && $officeAttendance->hasCheckedIn())

            <span class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

                {{ $officeAttendance->attendance_status }}

            </span>

        @else

            <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-500">

                Not Checked In

            </span>

        @endif

    </div>

</x-ui.card>