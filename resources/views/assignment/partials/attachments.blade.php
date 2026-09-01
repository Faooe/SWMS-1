@if($assignment->relationLoaded('attachments') && $assignment->attachments->isNotEmpty())
<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center gap-3">
        <div class="rounded-xl bg-blue-50 p-2 text-blue-600"><i data-lucide="paperclip" class="h-5 w-5"></i></div>
        <div><h3 class="font-semibold text-slate-900">Lampiran Instruksi</h3><p class="text-xs text-slate-500">Dokumen dan foto referensi untuk assignment ini.</p></div>
    </div>
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($assignment->attachments as $file)
            <a href="{{ secure_file_url($file->file_path) }}" target="_blank" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 transition hover:border-blue-300 hover:bg-blue-50/50">
                <div class="rounded-lg bg-white p-2 text-blue-600">
                    <i data-lucide="{{ str_contains($file->mime_type ?? '', 'pdf') ? 'file-text' : 'image' }}" class="h-4 w-4"></i>
                </div>
                <div class="min-w-0 flex-1"><p class="truncate text-sm font-medium text-slate-800">{{ $file->original_name ?? 'Lampiran' }}</p><p class="text-xs text-slate-500">{{ number_format(($file->size ?? 0)/1024, 0) }} KB</p></div>
                <i data-lucide="external-link" class="h-4 w-4 text-slate-400"></i>
            </a>
        @endforeach
    </div>
</div>
@endif
