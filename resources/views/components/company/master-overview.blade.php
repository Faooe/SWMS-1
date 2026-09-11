@props(['statistics', 'label', 'icon' => 'database'])

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-base font-black text-slate-900">Ringkasan {{ $label }}</h2>
            <p class="mt-0.5 text-xs text-slate-500">Status master data perusahaan saat ini.</p>
        </div>
        <span class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-500">{{ number_format($statistics['total']) }} data</span>
    </div>

    <div class="grid grid-cols-3 divide-x divide-slate-100">
        @foreach([
            ['Total', $statistics['total'], $icon, 'bg-blue-50 text-blue-600'],
            ['Aktif', $statistics['active'], 'circle-check', 'bg-emerald-50 text-emerald-600'],
            ['Nonaktif', $statistics['inactive'], 'circle-off', 'bg-slate-100 text-slate-500'],
        ] as [$metricLabel, $value, $metricIcon, $tone])
            <div class="flex min-w-0 flex-col gap-2 px-3 py-4 sm:flex-row sm:items-center sm:gap-3 sm:px-5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $tone }} sm:h-10 sm:w-10">
                    <i data-lucide="{{ $metricIcon }}" class="h-4 w-4 sm:h-5 sm:w-5"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-black leading-none text-slate-900">{{ number_format($value) }}</div>
                    <div class="mt-1 truncate text-[11px] font-semibold text-slate-500 sm:text-xs">{{ $metricLabel }}</div>
                </div>
            </div>
        @endforeach
    </div>
</section>
