@props([

'type'=>'button',

'variant'=>'primary'

])

@php

$style=[

'primary'=>'bg-blue-600 hover:bg-blue-700 text-white',

'secondary'=>'bg-slate-100 hover:bg-slate-200 text-slate-700',

'danger'=>'bg-red-600 hover:bg-red-700 text-white',

][$variant];

@endphp

<button

type="{{ $type }}"

{{ $attributes->merge([

'class'=>"inline-flex items-center gap-2 rounded-xl px-5 py-3 font-medium transition {$style}"

]) }}

>

{{ $slot }}

</button>