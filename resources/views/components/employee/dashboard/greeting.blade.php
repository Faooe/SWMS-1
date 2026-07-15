@props([
    'employee'
])

@php

$hour = now()->hour;

$greeting = match(true){

    $hour < 11 => 'Good Morning',

    $hour < 15 => 'Good Afternoon',

    $hour < 18 => 'Good Evening',

    default => 'Good Night',

};

@endphp

<div
    class="rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-white shadow-lg">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="text-3xl font-bold">

                {{ $greeting }},

                {{ $employee->full_name }}

                👋

            </h2>

            <p class="mt-2 text-blue-100">

                {{ now()->translatedFormat('l, d F Y') }}

            </p>

        </div>

        <div
            class="hidden rounded-full bg-white/10 p-5 lg:block">

            <i
                data-lucide="sun"
                class="h-14 w-14">

            </i>

        </div>

    </div>

</div>