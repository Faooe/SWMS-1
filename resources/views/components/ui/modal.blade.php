@props([

    'show' => false,

    'maxWidth' => 'lg',

])

@php

$width = [

    'sm' => 'max-w-md',

    'md' => 'max-w-lg',

    'lg' => 'max-w-2xl',

    'xl' => 'max-w-4xl',

][$maxWidth];

@endphp

@if($show)

<div

    class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-5"

>

    <div

        class="w-full {{ $width }} rounded-3xl bg-white shadow-2xl"

    >

        {{ $slot }}

    </div>

</div>

@endif