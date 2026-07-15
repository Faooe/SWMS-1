<x-ui.card>

    <div class="mb-5 flex items-center justify-between">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100">

                <i data-lucide="map-pinned" class="h-5 w-5 text-amber-600"></i>

            </div>

            <h3 class="text-lg font-bold text-slate-800">

                Assignment Attendance

            </h3>

        </div>

    </div>

    @if(!$assignment)

        <p class="text-sm text-slate-500">

            Tidak ada assignment aktif untuk hari ini.

        </p>

    @else

        <div class="rounded-2xl bg-slate-50 p-4">

            <p class="font-semibold text-slate-800">

                {{ $assignment->title }}

            </p>

            <p class="mt-1 text-sm text-slate-500">

                {{ $assignment->location_name }}

            </p>

        </div>

        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">

            <div>

                <p class="text-slate-500">Radius</p>

                <p class="font-semibold text-slate-800">{{ $assignment->radius }} m</p>

            </div>

            <div>

                <p class="text-slate-500">Status</p>

                <p class="font-semibold text-slate-800">

                    @if($assignmentAttendance && $assignmentAttendance->isCompleted())

                        Completed

                    @elseif($assignmentAttendance && $assignmentAttendance->hasCheckedIn())

                        Checked In

                    @else

                        Not Checked In

                    @endif

                </p>

            </div>

        </div>

           <a href="{{ route('employee.assignments.show', $assignment->uuid) }}" class="mt-5 block rounded-xl bg-amber-600 py-3 text-center font-semibold text-white transition hover:bg-amber-700">

                  Open Assignment

            </a>

    @endif

</x-ui.card>