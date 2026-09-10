@props(['assignments'])

@if($assignments->count())
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3.5">Assignment</th>
                        <th class="px-5 py-3.5">Office</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Team</th>
                        <th class="px-5 py-3.5">Jadwal</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($assignments as $assignment)
                        <x-assignment.table.row :assignment="$assignment" />
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($assignments->hasPages())
        <div class="mt-4 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:px-5">
            <div class="text-sm text-slate-500">
                Menampilkan
                <span class="font-semibold text-slate-700">{{ $assignments->firstItem() ?? 0 }}–{{ $assignments->lastItem() ?? 0 }}</span>
                dari
                <span class="font-semibold text-slate-700">{{ $assignments->total() }}</span>
                assignment
            </div>

            <nav class="flex items-center gap-1.5" aria-label="Pagination Assignment">
                @if($assignments->onFirstPage())
                    <span class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-300 cursor-not-allowed select-none">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M12.5 15 7.5 10l5-5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $assignments->previousPageUrl() }}" class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M12.5 15 7.5 10l5-5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </a>
                @endif

                <div class="hidden items-center gap-1 sm:flex">
                    @foreach($assignments->getUrlRange(max(1, $assignments->currentPage() - 1), min($assignments->lastPage(), $assignments->currentPage() + 1)) as $page => $url)
                        @if($page == $assignments->currentPage())
                            <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-xl bg-blue-600 px-3 text-sm font-bold text-white shadow-sm shadow-blue-200" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                </div>

                <span class="inline-flex h-9 items-center rounded-xl bg-slate-100 px-3 text-xs font-semibold text-slate-500 sm:hidden">
                    {{ $assignments->currentPage() }} / {{ $assignments->lastPage() }}
                </span>

                @if($assignments->hasMorePages())
                    <a href="{{ $assignments->nextPageUrl() }}" class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <span class="hidden sm:inline">Berikutnya</span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m7.5 5 5 5-5 5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                @else
                    <span class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-300 cursor-not-allowed select-none">
                        <span class="hidden sm:inline">Berikutnya</span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m7.5 5 5 5-5 5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                @endif
            </nav>
        </div>
    @endif
@else
    <x-assignment.table.empty />
@endif
