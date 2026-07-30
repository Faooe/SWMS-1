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

    <div class="grid gap-5 sm:grid-cols-2">

        <x-ui.detail-item
            label="Attendance Date"
            :value="$attendance->attendance_date->format('d F Y')" />

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-500">
                Status
            </label>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor }}">
                    {{ $attendance->attendance_status }}
                </span>
            </div>
        </div>

        <x-ui.detail-item
            label="Check In"
            :value="$attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : null" />

        <x-ui.detail-item
            label="Check Out"
            :value="$attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : null" />

        <x-ui.detail-item
            label="Late Minutes"
            :value="$attendance->late_minutes . ' Minute'" />

        <x-ui.detail-item
            label="Allowed Radius"
            :value="$attendance->allowed_radius !== null ? $attendance->allowed_radius . ' m' : null" />

        <x-ui.detail-item
            label="Employee Distance"
            :value="$attendance->check_in_distance !== null ? number_format($attendance->check_in_distance, 2) . ' m' : null" />

    </div>

</x-ui.card>
