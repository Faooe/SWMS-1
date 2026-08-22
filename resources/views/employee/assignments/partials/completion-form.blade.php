{{--
    Form upload 2 foto + catatan -- dipakai submit PERTAMA kali maupun
    RESUBMIT setelah reject (endpoint & payload-nya sama persis, cuma
    teks tombol yang beda). Props:
    - $assignment (wajib)
    - $isResubmission (opsional, default false)
--}}
@php($isResubmission ??= false)

<form method="POST" action="{{ route('employee.assignments.complete', $assignment->uuid) }}" enctype="multipart/form-data" class="space-y-4">

    @csrf

    <div class="grid grid-cols-2 gap-3">

        <label class="block cursor-pointer rounded-2xl border border-dashed border-slate-300 p-4 text-center text-xs text-slate-500 hover:bg-slate-50">
            <span class="js-completion-photo-label">Foto 1 (wajib)</span>
            <input type="file" name="completion_photo" accept="image/*" capture="environment" class="js-completion-photo hidden" required>
        </label>

        <label class="block cursor-pointer rounded-2xl border border-dashed border-slate-300 p-4 text-center text-xs text-slate-500 hover:bg-slate-50">
            <span class="js-completion-photo-2-label">Foto 2 (opsional)</span>
            <input type="file" name="completion_photo_2" accept="image/*" capture="environment" class="js-completion-photo-2 hidden">
        </label>

    </div>

    <p class="-mt-2 text-[11px] text-slate-400">
        Foto otomatis dikompres ke bawah 300KB, tidak perlu pilih foto berukuran kecil secara manual.
    </p>

    @error('completion_photo')
        <p class="text-xs text-red-600">{{ $message }}</p>
    @enderror

    @error('completion_photo_2')
        <p class="text-xs text-red-600">{{ $message }}</p>
    @enderror

    <div>
        <textarea
            name="completion_notes"
            rows="3"
            placeholder="Jelaskan detail pekerjaan yang dilakukan/diperbaiki (min. 10 karakter)..."
            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">{{ old('completion_notes') }}</textarea>
        @error('completion_notes')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="js-completion-submit w-full rounded-2xl bg-emerald-600 py-3 font-semibold text-white hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60">
        {{ $isResubmission ? 'Kirim Revisi' : 'Complete Assignment' }}
    </button>

</form>
