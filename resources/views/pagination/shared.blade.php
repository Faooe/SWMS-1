@if ($paginator->hasPages())
    <nav aria-label="Navigasi halaman data" class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5 xl:flex-row xl:items-center xl:justify-between">
        <div class="text-center text-sm text-slate-500 xl:text-left">
            Menampilkan <span class="font-bold text-slate-900">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
            dari <span class="font-bold text-slate-900">{{ $paginator->total() }}</span> assignment
        </div>

        <div class="flex flex-wrap items-center justify-center gap-2">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-2xl border border-slate-100 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-400 sm:px-4">
                    <span aria-hidden="true">←</span> Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" @if($livewire ?? false) wire:click.prevent="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="aria-disabled" @endif class="inline-flex min-h-11 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100 sm:px-4">
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
                                <span aria-current="page" aria-label="Halaman {{ $page }}, halaman saat ini" class="inline-flex h-11 min-w-11 items-center justify-center rounded-2xl bg-blue-600 px-3 text-sm font-bold text-white shadow-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" @if($livewire ?? false) wire:click.prevent="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" @endif aria-label="Ke halaman {{ $page }}" class="inline-flex h-11 min-w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" @if($livewire ?? false) wire:click.prevent="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="aria-disabled" @endif class="inline-flex min-h-11 items-center justify-center gap-2 rounded-2xl border border-blue-100 bg-blue-50 px-3 py-2.5 text-sm font-semibold text-blue-700 transition hover:border-blue-200 hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-100 sm:px-4">
                    Berikutnya <span aria-hidden="true">→</span>
                </a>
            @else
                <span aria-disabled="true" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-2xl border border-slate-100 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-400 sm:px-4">
                    Berikutnya <span aria-hidden="true">→</span>
                </span>
            @endif
            <p class="w-full text-center text-xs font-medium text-slate-500 md:hidden">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</p>
        </div>
    </nav>
@endif
