@props([
    'label' => '',
    'name' => null,
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'required' => false,
])

@php
    // Toggle mata (show/hide) otomatis aktif untuk field bertipe password
    $isPassword = $type === 'password';
@endphp

<div class="space-y-2" @if($isPassword) x-data="{ showPassword: false }" @endif>

    @if($label)

        <label
            @if($name)
                for="{{ $name }}"
            @endif
            class="flex items-center gap-1 text-sm font-semibold text-slate-700">

            {{ $label }}

            @if($required)
                <span class="text-red-500">*</span>
            @endif

        </label>

    @endif

    <div class="relative">

        <input

            @if($name)
                id="{{ $name }}"
                name="{{ $name }}"
            @endif

            @if($isPassword)
                :type="showPassword ? 'text' : 'password'"
            @else
                type="{{ $type }}"
            @endif

            value="{{ old($name ?? '', $value) }}"

            placeholder="{{ $placeholder }}"

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
                    text-slate-800
                    shadow-sm
                    outline-none
                    transition
                    duration-200

                    placeholder:text-slate-400

                    focus:border-blue-500
                    focus:ring-4
                    focus:ring-blue-100

                    disabled:bg-slate-100
                    disabled:text-slate-500
                    disabled:cursor-not-allowed

                    '.($isPassword ? 'pr-12' : '').'

                    '.(
                        $name && $errors->has($name)
                            ? 'border-red-500 focus:border-red-500 focus:ring-red-100'
                            : ''
                    )

            ]) }}

        >

        @if($isPassword)

            <button
                type="button"
                @click="showPassword = !showPassword"
                tabindex="-1"
                class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition hover:text-slate-600">

                <span x-show="!showPassword"><i data-lucide="eye" class="h-5 w-5"></i></span>
                <span x-show="showPassword" style="display: none;"><i data-lucide="eye-off" class="h-5 w-5"></i></span>

            </button>

        @endif

    </div>

    @if($name)
        @error($name)
            <p class="text-sm text-red-500">{{ $message }}</p>
        @enderror
    @endif

</div>