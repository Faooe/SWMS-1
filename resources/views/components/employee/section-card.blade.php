@props([
    'title',
    'description' => '',
    'icon' => 'folder'
])

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-start gap-3 border-b border-slate-100 px-6 py-5">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
            <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-900">{{ $title }}</h2>
            @if($description)
                <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
            @endif
        </div>
    </div>
    <div class="p-6">{{ $slot }}</div>
</section>
