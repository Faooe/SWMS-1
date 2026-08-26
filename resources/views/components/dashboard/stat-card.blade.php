@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'change' => null,
    'changeLabel' => null,
])

<x-ui.card>
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm text-slate-500">{{ $title }}</p>

            <h2 class="mt-3 text-4xl font-bold">
                {{ number_format($value) }}
            </h2>

            @if($change !== null && $changeLabel)
                @php
                    $numericChange = is_numeric($change) ? (float) $change : 0;
                    $changeClass = $numericChange > 0
                        ? 'text-green-600'
                        : ($numericChange < 0 ? 'text-red-600' : 'text-slate-500');
                    $changeIcon = $numericChange > 0 ? 'trending-up' : ($numericChange < 0 ? 'trending-down' : 'minus');
                @endphp

                <p class="mt-4 flex items-center gap-1 text-sm {{ $changeClass }}">
                    <i data-lucide="{{ $changeIcon }}" class="h-4 w-4"></i>
                    <span>
                        {{ $numericChange > 0 ? '+' : '' }}{{ number_format($numericChange, 1) }}%
                        {{ $changeLabel }}
                    </span>
                </p>
            @endif
        </div>

        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100">
            <i data-lucide="{{ $icon }}" class="h-7 w-7 text-blue-600"></i>
        </div>
    </div>
</x-ui.card>
