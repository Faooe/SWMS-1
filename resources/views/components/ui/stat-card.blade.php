@props([

    'title',

    'value' => 0,

    'icon' => 'layout-dashboard',

    'color' => 'blue',

    'description' => null,

])

@php

$colors = [

    'blue' => [

        'card' => 'from-blue-500 to-blue-600',

        'icon' => 'bg-blue-100 text-blue-600',

    ],

    'green' => [

        'card' => 'from-green-500 to-green-600',

        'icon' => 'bg-green-100 text-green-600',

    ],

    'emerald' => [

        'card' => 'from-emerald-500 to-emerald-600',

        'icon' => 'bg-emerald-100 text-emerald-600',

    ],

    'red' => [

        'card' => 'from-red-500 to-red-600',

        'icon' => 'bg-red-100 text-red-600',

    ],

    'orange' => [

        'card' => 'from-orange-500 to-orange-600',

        'icon' => 'bg-orange-100 text-orange-600',

    ],

    'purple' => [

        'card' => 'from-purple-500 to-purple-600',

        'icon' => 'bg-purple-100 text-purple-600',

    ],

    'slate' => [

        'card' => 'from-slate-500 to-slate-600',

        'icon' => 'bg-slate-100 text-slate-600',

    ],

];

$current = $colors[$color] ?? $colors['blue'];

@endphp

<div
    class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">

    <div class="flex items-center justify-between p-6">

        <div>

            <p class="text-sm font-medium text-slate-500">

                {{ $title }}

            </p>

            <h2 class="mt-2 text-4xl font-bold text-slate-800">

                {{ $value }}

            </h2>

            @if($description)

                <p class="mt-3 text-sm text-slate-500">

                    {{ $description }}

                </p>

            @endif

        </div>

        <div
            class="flex h-16 w-16 items-center justify-center rounded-2xl {{ $current['icon'] }}">

            <i
                data-lucide="{{ $icon }}"
                class="h-8 w-8">

            </i>

        </div>

    </div>

    <div
        class="h-2 bg-gradient-to-r {{ $current['card'] }}">

    </div>

</div>