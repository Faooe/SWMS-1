<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">Attendance Management</h1>
            <p class="mt-2 text-slate-500">
                Monitor employee attendance, check-in, check-out, GPS validation, and attendance history.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a
                href="{{ $exportUrl }}"
                class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                Export PDF
            </a>
        </div>

    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-6">
        <x-ui.stat-card title="Total" :value="$statistics['total']" icon="calendar-days" color="blue" />
        <x-ui.stat-card title="Present" :value="$statistics['present']" icon="badge-check" color="green" />
        <x-ui.stat-card title="Late" :value="$statistics['late']" icon="clock-3" color="amber" />
        <x-ui.stat-card title="Leave" :value="$statistics['leave']" icon="plane" color="purple" />
        <x-ui.stat-card title="Permission" :value="$statistics['permission']" icon="file-check" color="cyan" />
        <x-ui.stat-card title="Absent" :value="$statistics['absent']" icon="circle-x" color="red" />
    </div>

    {{-- Filters --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-5">

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-600">Search Employee</label>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Employee Name..."
                    class="w-full rounded-xl border-slate-300">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-600">Office</label>
                <select wire:model.live="office" class="w-full rounded-xl border-slate-300">
                    <option value="">All Office</option>
                    @foreach($offices as $off)
                        <option value="{{ $off->id }}">{{ $off->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-600">Status</label>
                <select wire:model.live="status" class="w-full rounded-xl border-slate-300">
                    <option value="">All Status</option>
                    <option value="Present">Present</option>
                    <option value="Late">Late</option>
                    <option value="Leave">Leave</option>
                    <option value="Permission">Permission</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-600">Attendance Date</label>
                <input
                    type="date"
                    wire:model.live="date"
                    class="w-full rounded-xl border-slate-300">
            </div>

            <div class="flex items-end gap-3">
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="rounded-xl border border-slate-300 px-5 py-3 font-semibold">
                    Reset
                </button>
            </div>

        </div>
    </div>

    {{-- Table --}}
    <div
        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
        wire:loading.class="opacity-50"
        wire:target="search,office,status,date,previousPage,nextPage,gotoPage">

        <div class="max-h-[520px] overflow-y-auto overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">

                <thead class="sticky top-0 z-10 bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Employee</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Office</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Check In</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">

                    @forelse($attendances as $attendance)

                        <tr wire:key="attendance-row-{{ $attendance->id }}" class="hover:bg-slate-50 transition">

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar :name="$attendance->employee->full_name" />
                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $attendance->employee->full_name }}</div>
                                        <div class="text-sm text-slate-500">{{ $attendance->employee->employee_number }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">{{ $attendance->office?->name ?? '-' }}</td>

                            <td class="px-6 py-5">{{ $attendance->attendance_date->format('d M Y') }}</td>

                            <td class="px-6 py-5">{{ optional($attendance->check_in_time)->format('H:i') ?? '-' }}</td>

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
                                <span class="rounded-full bg-{{ $color }}-100 px-3 py-1 text-sm font-semibold text-{{ $color }}-700">
                                    {{ $attendance->attendance_status }}
                                </span>
                            </td>

                            <td class="px-6 py-5 text-center">
                                <a
                                    href="{{ route('attendance.show', $attendance->id) }}"
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

</div>
