@props([
    'title',
    'description' => null,
    'icon' => null,
])

<div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">

    <div class="mb-8 flex items-start gap-4">

        @if($icon)

            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">

                <i
                    data-lucide="{{ $icon }}"
                    class="h-6 w-6">
                </i>

            </div>

        @endif

        <div>

            <h2 class="text-xl font-bold text-slate-800">

                {{ $title }}

            </h2>

            @if($description)

                <p class="mt-1 text-slate-500">

                    {{ $description }}

                </p>

            @endif

        </div>

    </div>

    {{ $slot }}

</div>