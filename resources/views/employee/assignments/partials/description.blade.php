<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <div class="mb-5 flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-600">
            <i data-lucide="file-text" class="h-5 w-5"></i>
        </div>
        <div>
            <h2 class="font-bold text-slate-900">Deskripsi Pekerjaan</h2>
            <p class="text-xs text-slate-500">Instruksi dan konteks pekerjaan dari company.</p>
        </div>
    </div>

    @if($assignment->description)
        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-5 text-sm leading-7 text-slate-700">
            {!! nl2br(e($assignment->description)) !!}
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-5 py-8 text-center">
            <i data-lucide="file-x-2" class="mx-auto h-8 w-8 text-slate-300"></i>
            <p class="mt-3 font-semibold text-slate-600">Tidak ada deskripsi tambahan</p>
        </div>
    @endif
</div>
