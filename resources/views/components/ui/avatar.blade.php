@props([
    'employee' => null,
    'size' => '12',
])

@php

$sizeClass = match($size){

    '8' => 'h-8 w-8 text-xs',

    '10' => 'h-10 w-10 text-sm',

    '12' => 'h-12 w-12 text-base',

    '16' => 'h-16 w-16 text-xl',

    '20' => 'h-20 w-20 text-2xl',

    '24' => 'h-24 w-24 text-3xl',

    default => 'h-12 w-12 text-base',

};

@endphp

@if($employee && $employee->photo)

<img

    src="{{ asset('storage/'.$employee->photo) }}"

    alt="{{ $employee->full_name }}"

    class="{{ $sizeClass }} rounded-full object-cover border border-slate-200">

@else

<div

    class="{{ $sizeClass }} rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">

    {{ strtoupper(substr($employee?->full_name ?? 'U',0,1)) }}

</div>

@endif