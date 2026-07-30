use Illuminate\Support\Str;
@props([
    'assignment'
])

@php

use Carbon\Carbon;

$groupedLogs = $assignment->logs
    ->sortByDesc('created_at')
    ->groupBy(function ($log) {
        return $log->created_at->format('Y-m-d');
    });

@endphp

<x-assignment.section-card
    title="Assignment Timeline"
    description="History of assignment activities."
    icon="history">

    @if($assignment->logs->isEmpty())

        <div class="py-16 text-center">

            <i
                data-lucide="history"
                class="mx-auto h-14 w-14 text-slate-300">
            </i>

            <h3 class="mt-4 text-lg font-semibold text-slate-700">
                No Activity
            </h3>

            <p class="mt-2 text-slate-500">
                Timeline will appear after activity is recorded.
            </p>

        </div>

    @else

        <div
            class="relative max-h-[650px] overflow-y-auto pr-3 scroll-smooth timeline-scroll">

            {{-- Vertical Line --}}
            <div
                class="absolute left-5 top-0 h-full w-0.5 bg-slate-200">
            </div>

            <div class="space-y-10">

                @foreach($groupedLogs as $date => $logs)

                    @php

                        $carbon = Carbon::parse($date);

                        if($carbon->isToday()){

                            $title = 'Today';

                        }elseif($carbon->isYesterday()){

                            $title = 'Yesterday';

                        }else{

                            $title = $carbon->translatedFormat('d F Y');

                        }

                    @endphp

                    {{-- Date Header --}}
                    <div
                        class="sticky top-0 z-20 bg-white pb-5 pt-1">

                        <div
                            class="flex items-center gap-4">

                            <div
                                class="h-px flex-1 bg-slate-200">
                            </div>

                            <span
                                class="rounded-full bg-slate-100 px-4 py-1 text-xs font-bold uppercase tracking-widest text-slate-600">

                                {{ $title }}

                            </span>

                            <div
                                class="h-px flex-1 bg-slate-200">
                            </div>

                        </div>

                    </div>

                    <div class="space-y-8">

                        @foreach($logs as $log)

                            @php

                                $color = match($log->action){

                                    'ASSIGNMENT_CREATED'
                                        => 'bg-blue-500',

                                    'ASSIGNMENT_UPDATED'
                                        => 'bg-amber-500',

                                    'EMPLOYEE_ASSIGNED'
                                        => 'bg-indigo-500',

                                    'EMPLOYEE_ACCEPTED'
                                        => 'bg-cyan-500',

                                    'CHECK_IN'
                                        => 'bg-green-500',

                                    'CHECK_OUT'
                                        => 'bg-orange-500',

                                    'PROGRESS_UPDATED'
                                        => 'bg-violet-500',

                                    'PHOTO_UPLOADED'
                                        => 'bg-pink-500',

                                    'ASSIGNMENT_COMPLETED'
                                        => 'bg-emerald-600',

                                    'ASSIGNMENT_CANCELLED'
                                        => 'bg-red-600',

                                    default
                                        => 'bg-slate-500',

                                };

                                $icon = match($log->action){

                                    'ASSIGNMENT_CREATED'
                                        => 'plus-circle',

                                    'ASSIGNMENT_UPDATED'
                                        => 'square-pen',

                                    'EMPLOYEE_ASSIGNED'
                                        => 'users',

                                    'EMPLOYEE_ACCEPTED'
                                        => 'thumbs-up',

                                    'CHECK_IN'
                                        => 'map-pin',

                                    'CHECK_OUT'
                                        => 'map-pin-check',

                                    'PROGRESS_UPDATED'
                                        => 'hourglass',

                                    'PHOTO_UPLOADED'
                                        => 'camera',

                                    'ASSIGNMENT_COMPLETED'
                                        => 'badge-check',

                                    'ASSIGNMENT_CANCELLED'
                                        => 'circle-x',

                                    default
                                        => 'history',

                                };
                                $action = match($log->action){

                                'ASSIGNMENT_CREATED'
                                    => 'Assignment Created',

                                'ASSIGNMENT_UPDATED'
                                    => 'Assignment Updated',

                                'EMPLOYEE_ASSIGNED'
                                    => 'Employee Assigned',

                                'EMPLOYEE_ACCEPTED'
                                    => 'Employee Accepted Assignment',

                                'CHECK_IN'
                                    => 'Employee Check In',

                                'CHECK_OUT'
                                    => 'Employee Check Out',

                                'PROGRESS_UPDATED'
                                    => 'Progress Updated',

                                'PHOTO_UPLOADED'
                                    => 'Photo Uploaded',

                                'ASSIGNMENT_COMPLETED'
                                    => 'Assignment Completed',

                                'ASSIGNMENT_CANCELLED'
                                    => 'Assignment Cancelled',

                                default
                                    => Str::headline($log->action),

                            };
                            $badge = match($log->action){

                                'CHECK_IN'
                                    => 'bg-green-100 text-green-700',

                                'CHECK_OUT'
                                    => 'bg-orange-100 text-orange-700',

                                'ASSIGNMENT_COMPLETED'
                                    => 'bg-emerald-100 text-emerald-700',

                                'ASSIGNMENT_CANCELLED'
                                    => 'bg-red-100 text-red-700',

                                'EMPLOYEE_ASSIGNED'
                                    => 'bg-indigo-100 text-indigo-700',

                                default
                                    => 'bg-slate-100 text-slate-700',

                            };

                            @endphp

                            <div
                                class="relative flex gap-5">

                                {{-- Timeline Dot --}}
                                <div
                                    class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $color }} text-white shadow">

                                    <i
                                        data-lucide="{{ $icon }}"
                                        class="h-5 w-5">
                                    </i>

                                </div>

                                {{-- Timeline Card --}}
                                <div
                                    class="flex-1 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:border-blue-300 hover:shadow-md">

                                    <div
                                        class="flex items-start justify-between gap-5">

                                        <div>

                                            <h4
                                                class="font-bold tracking-wide text-slate-800">

                                                {{ $action }}

                                            </h4>

                                            @if($log->description)

                                                <p
                                                    class="mt-2 leading-relaxed text-slate-600">

                                                    {{ $log->description }}

                                                </p>

                                            @endif

                                        </div>

                                        <div class="shrink-0 text-right">

                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">

                                            {{ $action }}

                                        </span>

                                        <div
                                            class="mt-3 text-sm text-slate-500">

                                            {{ $log->created_at->format('d M Y') }}

                                            <br>

                                            {{ $log->created_at->format('H:i') }}

                                        </div>

                                    </div>

                                    </div>

                                    @if($log->employee || $log->user)

                                        <div
                                            class="mt-5 flex flex-wrap gap-6 border-t border-slate-100 pt-4 text-sm">

                                            @if($log->employee)

                                                <div>

                                                    <span
                                                        class="font-semibold text-slate-700">

                                                        Employee

                                                    </span>

                                                    <br>

                                                    <span
                                                        class="text-slate-500">

                                                        {{ $log->employee->full_name }}

                                                    </span>

                                                </div>

                                            @endif

                                            @if($log->user)

                                                <div>

                                                    <span
                                                        class="font-semibold text-slate-700">

                                                        By

                                                    </span>

                                                    <br>

                                                    <span
                                                        class="text-slate-500">

                                                        {{ $log->user->employee?->full_name }}

                                                    </span>

                                                </div>

                                            @endif

                                        </div>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endforeach

            </div>

        </div>

    @endif

</x-assignment.section-card>

<style>

.timeline-scroll{

    scroll-behavior:smooth;

}

.timeline-scroll::-webkit-scrollbar{

    width:8px;

}

.timeline-scroll::-webkit-scrollbar-track{

    background:#f1f5f9;

    border-radius:999px;

}

.timeline-scroll::-webkit-scrollbar-thumb{

    background:#cbd5e1;

    border-radius:999px;

}

.timeline-scroll::-webkit-scrollbar-thumb:hover{

    background:#94a3b8;

}

</style>