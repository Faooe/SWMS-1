@props([
    'label' => '',
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select...',
    'required' => false,
])

<div class="space-y-2">

    @if($label)

        <label
            for="{{ $name }}"
            class="flex items-center gap-1 text-sm font-semibold text-slate-700">

            {{ $label }}

            @if($required)

                <span class="text-red-500">*</span>

            @endif

        </label>

    @endif

    <select

        id="{{ $name }}"

        name="{{ $name }}"

        @if($required)
            required
        @endif

        {{ $attributes->merge([

            'class' => '
                w-full
                rounded-2xl
                border
                border-slate-300
                bg-white
                px-4
                py-3
                shadow-sm
                outline-none

                focus:border-blue-500
                focus:ring-4
                focus:ring-blue-100

                '.(
                    $errors->has($name)

                    ? 'border-red-500'

                    : ''

                )

        ]) }}

    >

        <option value="">

            {{ $placeholder }}

        </option>

        @foreach($options as $key => $option)

            @php

                /*
                |--------------------------------------------------------------------------
                | Eloquent Model / Object
                |--------------------------------------------------------------------------
                */

                if (is_object($option)) {

                    $value = $option->id;

                    $text = $option->name;

                }

                /*
                |--------------------------------------------------------------------------
                | Associative Array
                |--------------------------------------------------------------------------
                */

                elseif (!is_numeric($key)) {

                    $value = $key;

                    $text = $option;

                }

                /*
                |--------------------------------------------------------------------------
                | Simple Array
                |--------------------------------------------------------------------------
                */

                else {

                    $value = $option;

                    $text = $option;

                }

            @endphp

            <option

                value="{{ $value }}"

                @selected(old($name, $selected) == $value)

            >

                {{ $text }}

            </option>

        @endforeach

    </select>

    @error($name)

        <p class="text-sm text-red-500">

            {{ $message }}

        </p>

    @enderror

</div>