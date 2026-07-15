<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    <div class="max-h-[520px] overflow-y-auto overflow-x-auto">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="sticky top-0 z-10 bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Employee
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Office
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Date
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Check In
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

                @forelse($attendances as $attendance)

                    <tr class="hover:bg-slate-50 transition">

                        {{-- Employee --}}
                        <td class="px-6 py-5">

                            <div class="flex items-center gap-3">

                                <x-ui.avatar
                                    :name="$attendance->employee->full_name"
                                />

                                <div>

                                    <div class="font-semibold text-slate-800">

                                        {{ $attendance->employee->full_name }}

                                    </div>

                                    <div class="text-sm text-slate-500">

                                        {{ $attendance->employee->employee_number }}

                                    </div>

                                </div>

                            </div>

                        </td>

                        {{-- Office --}}
                        <td class="px-6 py-5">

                            {{ $attendance->office?->name ?? '-' }}

                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-5">

                            {{ $attendance->attendance_date->format('d M Y') }}

                        </td>

                        {{-- Check In --}}
                        <td class="px-6 py-5">

                            {{ optional($attendance->check_in_time)->format('H:i') ?? '-' }}

                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-5">

                            @php

                                $color = match($attendance->attendance_status){

                                    'Present' => 'green',

                                    'Late' => 'orange',

                                    'Absent' => 'red',

                                    'Leave' => 'purple',

                                    default => 'blue',

                                };

                            @endphp

                            <span
                                class="rounded-full bg-{{ $color }}-100 px-3 py-1 text-sm font-semibold text-{{ $color }}-700">

                                {{ $attendance->attendance_status }}

                            </span>

                        </td>

                        {{-- Action --}}
                        <td class="px-6 py-5 text-center">

                            <a

                                href="{{ route('attendance.show',$attendance->id) }}"

                                class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">

                                Detail

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="py-12 text-center text-slate-400">

                            No attendance data found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="border-t bg-slate-50 px-6 py-4">

        {{ $attendances->links() }}

    </div>

</div>