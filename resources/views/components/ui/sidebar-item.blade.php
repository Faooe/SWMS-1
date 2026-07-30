@props([
    'href' => '#',
    'icon',
    'title',
    'active' => false,
])

<a

    href="{{ $href }}"

    title="{{ $title }}"

    {{ $attributes->merge([

        'class' => '

        sidebar-item

        group

        relative

        flex

        items-center

        gap-3

        rounded-2xl

        px-4

        py-3

        transition-all

        duration-200

        overflow-hidden

        '.

        (

            $active

                ? 'bg-blue-600 text-white shadow-md shadow-blue-200'

                : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'

        )

    ]) }}

>

    @if($active)

        <span

            class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-white">

        </span>

    @endif

    <div

        class="sidebar-icon flex h-10 w-10 shrink-0 items-center justify-center rounded-xl

        {{

            $active

                ? 'bg-white/20'

                : 'bg-slate-100 group-hover:bg-white'

        }}">

        <i

            data-lucide="{{ $icon }}"

            class="h-5 w-5">

        </i>

    </div>

    <span

        class="sidebar-label truncate font-medium">

        {{ $title }}

    </span>

</a>