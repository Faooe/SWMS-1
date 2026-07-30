<div
    class="rounded-3xl bg-white p-8 shadow">

    {{-- Header --}}
    <div class="mb-8 flex items-center gap-3">

        <div
            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100">

            <i
                data-lucide="history"
                class="h-6 w-6 text-amber-600">
            </i>

        </div>

        <div>

            <h2
                class="text-xl font-bold text-slate-800">

                Timeline

            </h2>

            <p
                class="text-sm text-slate-500">

                Assignment activity history.

            </p>

        </div>

    </div>

    @if($assignment->logs->isEmpty())

        <div
            class="py-14 text-center">

            <i
                data-lucide="history"
                class="mx-auto h-12 w-12 text-slate-300">
            </i>

            <h3
                class="mt-4 font-semibold text-slate-700">

                No Timeline

            </h3>

            <p
                class="mt-2 text-sm text-slate-500">

                Activity history will appear here.

            </p>

        </div>

    @else

        <div
            class="relative max-h-[500px] overflow-y-auto pr-4 timeline-scroll">

            {{-- Vertical Line --}}
            <div
                class="absolute left-5 top-0 h-full w-0.5 bg-slate-200">
            </div>

            <div
                class="space-y-6">

                @foreach($assignment->logs->sortByDesc('created_at') as $log)

                    @php

                        $color = match($log->action){

                            'ASSIGNMENT_CREATED'
                                => 'bg-blue-500',

                            'EMPLOYEE_ASSIGNED'
                                => 'bg-indigo-500',

                            'EMPLOYEE_ACCEPTED'
                                => 'bg-cyan-500',

                            'CHECK_IN'
                                => 'bg-green-500',

                            'CHECK_OUT'
                                => 'bg-orange-500',

                            'ASSIGNMENT_COMPLETED'
                                => 'bg-emerald-600',

                            'ASSIGNMENT_CANCELLED'
                                => 'bg-red-600',

                            default
                                => 'bg-slate-500'

                        };

                        $icon = match($log->action){

                            'ASSIGNMENT_CREATED'
                                => 'file-plus',

                            'EMPLOYEE_ASSIGNED'
                                => 'user-plus',

                            'EMPLOYEE_ACCEPTED'
                                => 'check-circle',

                            'CHECK_IN'
                                => 'map-pin',

                            'CHECK_OUT'
                                => 'log-out',

                            'ASSIGNMENT_COMPLETED'
                                => 'badge-check',

                            'ASSIGNMENT_CANCELLED'
                                => 'x-circle',

                            default
                                => 'history'

                        };

                    @endphp

                    <div
                        class="relative flex gap-5">

                        {{-- Dot --}}
                        <div
                            class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $color }} text-white shadow">

                            <i
                                data-lucide="{{ $icon }}"
                                class="h-5 w-5">
                            </i>

                        </div>

                        {{-- Card --}}
                        <div
                            class="flex-1 rounded-2xl border border-slate-200 bg-slate-50 p-5">

                            <div
                                class="flex items-start justify-between">

                                <div>

                                    <h4
                                        class="font-semibold text-slate-800">

                                        {{ str_replace('_',' ',$log->action) }}

                                    </h4>

                                    @if($log->description)

                                        <p
                                            class="mt-2 text-sm text-slate-600">

                                            {{ $log->description }}

                                        </p>

                                    @endif

                                </div>

                                <span
                                    class="text-right text-xs text-slate-500">

                                    {{ $log->created_at->format('d M Y') }}

                                    <br>

                                    {{ $log->created_at->format('H:i') }}

                                </span>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @endif

</div>

<style>

.timeline-scroll::-webkit-scrollbar{

    width:8px;

}

.timeline-scroll::-webkit-scrollbar-track{

    background:#f1f5f9;

}

.timeline-scroll::-webkit-scrollbar-thumb{

    background:#cbd5e1;

    border-radius:999px;

}

.timeline-scroll::-webkit-scrollbar-thumb:hover{

    background:#94a3b8;

}

</style>