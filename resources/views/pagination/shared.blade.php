@if ($paginator->hasPages())
    <nav aria-label="Navigasi halaman data" class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-[0_8px_24px_rgba(15,23,42,0.06)] sm:gap-4 sm:rounded-3xl sm:p-4 xl:flex-row xl:items-center xl:justify-between">
        <div class="flex min-w-0 items-center gap-3">
            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 8h12M6 12h12M6 16h7" />
                </svg>
            </span>
            <div class="min-w-0 text-left">
                <p class="text-sm font-bold text-slate-900">Navigasi halaman</p>
                <p class="truncate text-xs text-slate-500">
                    Menampilkan <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
                    dari <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span> data
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 xl:justify-end">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="inline-flex min-h-10 items-center justify-center gap-1.5 rounded-xl border border-slate-100 bg-slate-50 px-2.5 py-2 text-xs font-semibold text-slate-400 sm:px-3 sm:text-sm">
                    <span aria-hidden="true">←</span> Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" @if($livewire ?? false) wire:click.prevent="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="aria-disabled" @endif class="inline-flex min-h-10 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100 sm:px-3 sm:text-sm">
                    <span aria-hidden="true">←</span> Sebelumnya
                </a>
            @endif

            <div class="hidden items-center gap-1.5 md:flex">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-2 text-slate-400" aria-hidden="true">{{ $element }}</span>
                    @elseif (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" aria-label="Halaman {{ $page }}, halaman saat ini" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl bg-blue-600 px-2.5 text-sm font-bold text-white shadow-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" @if($livewire ?? false) wire:click.prevent="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" @endif aria-label="Ke halaman {{ $page }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-2.5 text-sm font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" @if($livewire ?? false) wire:click.prevent="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="aria-disabled" @endif class="inline-flex min-h-10 items-center justify-center gap-1.5 rounded-xl border border-blue-100 bg-blue-50 px-2.5 py-2 text-xs font-semibold text-blue-700 transition hover:border-blue-200 hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-100 sm:px-3 sm:text-sm">
                    Berikutnya <span aria-hidden="true">→</span>
                </a>
            @else
                <span aria-disabled="true" class="inline-flex min-h-10 items-center justify-center gap-1.5 rounded-xl border border-slate-100 bg-slate-50 px-2.5 py-2 text-xs font-semibold text-slate-400 sm:px-3 sm:text-sm">
                    Berikutnya <span aria-hidden="true">→</span>
                </span>
            @endif
            <p class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600 md:hidden">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</p>
        </div>
    </nav>
@endif
