<x-ui.card>

    <h3 class="mb-5 text-lg font-semibold text-slate-800">
        Recent Activities
    </h3>

    @forelse($recentActivities as $activity)

        <div class="relative border-l-2 border-blue-200 py-3 pl-5 last:pb-0">

            <span class="absolute -left-[7px] top-4 h-3 w-3 rounded-full border-2 border-white bg-blue-500"></span>

            <p class="font-semibold text-slate-800">
                {{ $activity->action }}
            </p>

            <p class="mt-1 text-sm text-slate-500">
                {{ $activity->description }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                {{ $activity->created_at->diffForHumans() }}
            </p>

        </div>

    @empty

        <div class="flex flex-col items-center justify-center py-10 text-center">

            <i data-lucide="history" class="mb-3 h-10 w-10 text-slate-300"></i>

            <p class="text-slate-500">Belum ada aktivitas.</p>

        </div>

    @endforelse

</x-ui.card>
