@props([

    'title',

    'description' => null,

])

<div
    {{ $attributes->merge([
        'class' => 'mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between'
    ]) }}>

    <div>

        <h1
            class="text-3xl font-bold tracking-tight text-slate-800">

            {{ $title }}

        </h1>

        @if($description)

            <p
                class="mt-2 text-slate-500">

                {{ $description }}

            </p>

        @endif

    </div>

    @if(trim($slot))

        <div
            class="flex items-center gap-3">

            {{ $slot }}

        </div>

    @endif

</div>