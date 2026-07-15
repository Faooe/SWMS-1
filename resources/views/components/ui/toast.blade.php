@props([

    'type' => 'success',

    'message' => '',

])

@php

$styles = [

    'success' => [

        'bg' => 'bg-green-600',

        'icon' => 'circle-check-big',

    ],

    'error' => [

        'bg' => 'bg-red-600',

        'icon' => 'circle-x',

    ],

    'warning' => [

        'bg' => 'bg-amber-500',

        'icon' => 'triangle-alert',

    ],

    'info' => [

        'bg' => 'bg-blue-600',

        'icon' => 'info',

    ],

];

@endphp

<div

    id="toast"

    class="fixed right-6 top-6 z-[9999] hidden"

>

    <div

        class="flex items-center gap-4 rounded-2xl {{ $styles[$type]['bg'] }} px-6 py-4 text-white shadow-2xl">

        <i

            data-lucide="{{ $styles[$type]['icon'] }}"

            class="h-6 w-6">

        </i>

        <span
            id="toast-message">

            {{ $message }}

        </span>

    </div>

</div>

@push('scripts')

<script>

function showToast(message){

    const toast=document.getElementById('toast');

    document.getElementById('toast-message').innerHTML=message;

    toast.classList.remove('hidden');

    toast.classList.add('animate-bounce');

    setTimeout(()=>{

        toast.classList.add('hidden');

        toast.classList.remove('animate-bounce');

    },2500);

}

</script>

@endpush