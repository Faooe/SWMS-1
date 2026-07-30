@props([
    'label' => '',
    'name' => null,
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

        type="file"

        @if($required)
            required
        @endif

        {{ $attributes->merge([

            'class' => '

                block
                w-full

                rounded-2xl
                border
                border-slate-300

                bg-white

                px-4
                py-3

                text-slate-700

                shadow-sm

                transition

                file:mr-4
                file:rounded-xl
                file:border-0
                file:bg-blue-600
                file:px-4
                file:py-2
                file:text-sm
                file:font-medium
                file:text-white
                hover:file:bg-blue-700

                focus:border-blue-500
                focus:ring-4
                focus:ring-blue-100

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