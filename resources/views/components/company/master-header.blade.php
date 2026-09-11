@props([
    'title',
    'subtitle',
    'icon' => 'database',
    'addRoute' => null,
    'addLabel' => null,
])

<section class="flex flex-col gap-4 px-1 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <div class="flex items-center gap-2 text-sm font-bold text-blue-600">
            <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
            <span>Company Workspace</span>
        </div>
        <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">{{ $title }}</h1>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">{{ $subtitle }}</p>
    </div>

    @if($addRoute)
        <a href="{{ $addRoute }}"
           class="inline-flex w-fit items-center justify-center gap-2 rounded-2xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
            <i data-lucide="plus" class="h-4 w-4"></i>
            {{ $addLabel }}
        </a>
    @endif
</section>
