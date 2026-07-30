@props([
    'title',
    'description' => '',
    'icon' => 'folder'
])

<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="border-b border-slate-100 bg-slate-50 px-8 py-6">

        <div class="flex items-center gap-4">

            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100">

                <i
                    data-lucide="{{ $icon }}"
                    class="h-6 w-6 text-blue-600">
                </i>

            </div>

            <div>

                <h2 class="text-xl font-bold text-slate-800">

                    {{ $title }}

                </h2>

                @if($description)

                    <p class="mt-1 text-sm text-slate-500">

                        {{ $description }}

                    </p>

                @endif

            </div>

        </div>

    </div>

    {{-- Body --}}
    <div class="p-8">

        {{ $slot }}

    </div>

</div>