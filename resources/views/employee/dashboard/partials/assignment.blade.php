<x-ui.card>

    <div class="mb-5 flex items-center justify-between">

        <h3 class="text-lg font-semibold text-slate-800">
            Assignment Hari Ini
        </h3>

        <a
            href="{{ route('employee.assignments.index') }}"
            class="text-sm font-medium text-blue-600 hover:underline">
            Lihat Semua &rarr;
        </a>

    </div>

    @if($todayAssignment)

        @php
            $statusColor = match($todayAssignment->status) {
                'Draft' => 'bg-slate-100 text-slate-700',
                'Assigned' => 'bg-blue-100 text-blue-700',
                'In Progress' => 'bg-orange-100 text-orange-700',
                'Completed' => 'bg-green-100 text-green-700',
                'Cancelled' => 'bg-red-100 text-red-700',
                default => 'bg-slate-100 text-slate-700',
            };
        @endphp

        <div class="rounded-2xl border border-slate-200 p-5">

            <div class="flex items-start justify-between gap-3">

                <h4 class="font-bold text-slate-800">
                    {{ $todayAssignment->title }}
                </h4>

                <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor }}">
                    {{ $todayAssignment->status }}
                </span>

            </div>

            <p class="mt-2 flex items-center gap-1.5 text-sm text-slate-500">
                <i data-lucide="map-pin" class="h-4 w-4"></i>
                {{ $todayAssignment->location_name }}
            </p>

            <a
                href="{{ route('employee.assignments.show', $todayAssignment->uuid) }}"
                class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:underline">
                Lihat Detail &rarr;
            </a>

        </div>

    @else

        <div class="flex flex-col items-center justify-center py-10 text-center">

            <i data-lucide="clipboard-x" class="mb-3 h-10 w-10 text-slate-300"></i>

            <p class="text-slate-500">Tidak ada assignment hari ini.</p>

        </div>

    @endif

</x-ui.card>
