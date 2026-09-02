@if($assignment->relationLoaded('attachments') && $assignment->attachments->isNotEmpty())
<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <div class="mb-5 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i data-lucide="paperclip" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-900">Lampiran Instruksi</h2>
                <p class="text-xs text-slate-500">Dokumen dan referensi yang diberikan untuk assignment ini.</p>
            </div>
        </div>
        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">{{ $assignment->attachments->count() }}/5 file</span>
    </div>

    <div class="grid gap-3 sm:grid-cols-2">
        @foreach($assignment->attachments as $file)
            @php
                $isPdf = str_contains(strtolower($file->mime_type ?? ''), 'pdf');
                $isImage = str_contains(strtolower($file->mime_type ?? ''), 'image');
                $sizeKb = max(1, (int)round(($file->size ?? 0) / 1024));
            @endphp
            <a href="{{ secure_file_url($file->file_path) }}" target="_blank" rel="noopener" class="group flex min-w-0 items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-blue-300 hover:bg-blue-50/60">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">
                    <i data-lucide="{{ $isPdf ? 'file-text' : ($isImage ? 'image' : 'file') }}" class="h-5 w-5"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-slate-800 group-hover:text-blue-700">{{ $file->original_name ?? 'Lampiran' }}</p>
                    <p class="mt-0.5 text-xs text-slate-400">{{ strtoupper($isPdf ? 'PDF' : ($isImage ? 'Gambar' : 'File')) }} • {{ number_format($sizeKb) }} KB</p>
                </div>
                <i data-lucide="external-link" class="h-4 w-4 shrink-0 text-slate-400 transition group-hover:text-blue-600"></i>
            </a>
        @endforeach
    </div>
</div>
@endif
