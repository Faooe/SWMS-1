@props([
    'label' => '',
    'name' => null,
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'required' => false,
])

<div class="space-y-2">

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

    <input

        @if($name)
            id="{{ $name }}"
            name="{{ $name }}"
        @endif

        type="{{ $type }}"

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

                '.(
                    $name && $errors->has($name)
                        ? 'border-red-500 focus:border-red-500 focus:ring-red-100'
                        : ''
                )

        ]) }}

    >

    @if($name)

        @error($name)

            <p class="flex items-center gap-1 text-sm text-red-500">

                <i
                    data-lucide="circle-alert"
                    class="h-4 w-4">
                </i>

                {{ $message }}

            </p>

        @enderror

    @endif

</div>