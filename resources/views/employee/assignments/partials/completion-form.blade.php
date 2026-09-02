@php
    $isResubmission ??= false;
    $submitLabel ??= $isResubmission ? 'Kirim Revisi' : 'Kirim Hasil Assignment';
@endphp

<form method="POST" action="{{ route('employee.assignments.complete', $assignment->uuid) }}" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div class="grid grid-cols-2 gap-3">
        <label class="group block cursor-pointer rounded-2xl border border-dashed border-slate-300 bg-white p-4 text-center transition hover:border-blue-300 hover:bg-blue-50/40">
            <i data-lucide="camera" class="mx-auto mb-2 h-5 w-5 text-slate-400 transition group-hover:text-blue-500"></i>
            <span class="js-completion-photo-label block text-xs font-semibold text-slate-600">Foto 1 (wajib)</span>
            <input type="file" name="completion_photo" accept="image/*" capture="environment" class="js-completion-photo hidden" required>
        </label>

        <label class="group block cursor-pointer rounded-2xl border border-dashed border-slate-300 bg-white p-4 text-center transition hover:border-blue-300 hover:bg-blue-50/40">
            <i data-lucide="images" class="mx-auto mb-2 h-5 w-5 text-slate-400 transition group-hover:text-blue-500"></i>
            <span class="js-completion-photo-2-label block text-xs font-semibold text-slate-600">Foto 2 (opsional)</span>
            <input type="file" name="completion_photo_2" accept="image/*" capture="environment" class="js-completion-photo-2 hidden">
        </label>
    </div>

    <div class="flex items-start gap-2 rounded-xl bg-blue-50 px-3 py-2 text-[11px] leading-relaxed text-blue-700">
        <i data-lucide="image-down" class="mt-0.5 h-3.5 w-3.5 shrink-0"></i>
        <span>Foto otomatis dikompres sebelum upload untuk menghemat data dan storage.</span>
    </div>

    @error('completion_photo')
        <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
    @enderror
    @error('completion_photo_2')
        <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
    @enderror
    @error('assignment')
        <p class="rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-600">{{ $message }}</p>
    @enderror

    <div>
        <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Catatan hasil kerja</label>
        <textarea
            name="completion_notes"
            rows="4"
            required
            minlength="10"
            placeholder="Jelaskan pekerjaan yang dilakukan, hasil, kendala, atau perubahan yang dibuat..."
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50">{{ old('completion_notes') }}</textarea>
        @error('completion_notes')
            <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="js-completion-submit inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-4 py-3 font-bold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60">
        <i data-lucide="send" class="h-4 w-4"></i>
        {{ $submitLabel }}
    </button>
</form>
