@props([

'title',

'value',

'icon',

'color'=>'blue',

'change'=>'+12%'

])

<x-ui.card>

<div class="flex items-start justify-between">

    <div>

        <p class="text-sm text-slate-500">

            {{ $title }}

        </p>

        <h2 class="mt-3 text-4xl font-bold">

            {{ number_format($value) }}

        </h2>

        <p class="mt-4 text-sm text-green-600">

            {{ $change }}

            dibanding bulan lalu

        </p>

    </div>

    <div

class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100">

<i

data-lucide="{{ $icon }}"

class="h-7 w-7 text-blue-600">

</i>

    </div>

</div>

</x-ui.card>