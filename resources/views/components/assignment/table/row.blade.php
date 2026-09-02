@props(['assignment'])

@php
    $displayStatus = $assignment->companyDisplayStatus();
    $statusClass = match($displayStatus) {
        'Needs Revision', 'Rejected', 'Not Worked', 'Cancelled' => 'bg-red-50 text-red-700 border-red-100',
        'Pending Review' => 'bg-amber-50 text-amber-700 border-amber-100',
        'Completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        default => 'bg-blue-50 text-blue-700 border-blue-100',
    };
    $priorityClass = match($assignment->priority) {
        'Critical', 'High' => 'text-red-600',
        'Medium' => 'text-amber-700',
        default => 'text-slate-500',
    };
@endphp

<tr class="transition hover:bg-slate-50/70">
    <td class="px-5 py-4">
        <div class="max-w-[320px]">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('assignments.show', $assignment) }}" class="truncate font-semibold text-slate-900 hover:text-blue-700">{{ $assignment->title }}</a>
                @if($assignment->daily_attendance_enabled)
                    <span class="rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">Attendance Harian</span>
                @endif
            </div>
            <p class="mt-1 text-xs text-slate-500">{{ $assignment->assignment_number }} · {{ $assignment->assignment_type }} · <span class="font-medium {{ $priorityClass }}">{{ $assignment->priority }}</span></p>
        </div>
    </td>
    <td class="px-5 py-4 text-sm text-slate-600">{{ $assignment->office?->name ?? '-' }}</td>
    <td class="px-5 py-4"><span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $displayStatus }}</span></td>
    <td class="px-5 py-4">
        <p class="text-sm font-semibold text-slate-800">{{ $assignment->employee_count }} employee</p>
        @if($assignment->rejectedEmployeeCount() > 0)
            <p class="mt-0.5 text-xs text-red-600">{{ $assignment->rejectedEmployeeCount() }} menolak</p>
        @endif
    </td>
    <td class="px-5 py-4 text-sm text-slate-600">
        <p class="font-medium text-slate-700">{{ optional($assignment->start_datetime)->format('d M Y') }}</p>
        <p class="mt-0.5 text-xs text-slate-500">{{ optional($assignment->start_datetime)->format('H:i') }} → {{ optional($assignment->end_datetime)->format('d M Y H:i') }}</p>
    </td>
    <td class="px-5 py-4">
        <div class="flex justify-end gap-1">
            <a href="{{ route('assignments.show',$assignment) }}" class="rounded-lg p-2 text-slate-500 hover:bg-blue-50 hover:text-blue-700" title="Detail"><i data-lucide="eye" class="h-4 w-4"></i></a>
            <a href="{{ route('assignments.edit',$assignment) }}" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-800" title="Edit"><i data-lucide="pencil" class="h-4 w-4"></i></a>
            <details class="relative">
                <summary class="cursor-pointer list-none rounded-lg p-2 text-slate-500 hover:bg-slate-100 [&::-webkit-details-marker]:hidden"><i data-lucide="more-horizontal" class="h-4 w-4"></i></summary>
                <div class="absolute right-0 z-20 mt-1 w-44 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                    <form method="POST" action="{{ route('assignments.destroy',$assignment) }}" onsubmit="return confirm('Hapus assignment ini?')">
                        @csrf @method('DELETE')
                        <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-red-600 hover:bg-red-50"><i data-lucide="trash-2" class="h-4 w-4"></i>Hapus Assignment</button>
                    </form>
                </div>
            </details>
        </div>
    </td>
</tr>
