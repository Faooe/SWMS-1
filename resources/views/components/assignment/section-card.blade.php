@props(['title','description' => null,'icon' => null])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6']) }}>
    <div class="mb-5 flex items-start gap-3">
        @if($icon)
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
            </div>
        @endif
        <div class="min-w-0">
            <h2 class="text-lg font-bold text-slate-900">{{ $title }}</h2>
            @if($description)<p class="mt-1 text-sm text-slate-500">{{ $description }}</p>@endif
        </div>
    </div>
    {{ $slot }}
</div>
