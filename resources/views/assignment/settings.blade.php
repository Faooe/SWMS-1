@extends('layouts.app')

@section('title','Assignment Settings')

@section('page-title','Pengaturan Assignment')

@section('content')

<div class="mx-auto max-w-2xl space-y-6">

    <form method="POST" action="{{ route('assignment-settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ================= Auto Approve ================= --}}
        <x-ui.card>

            <div class="flex items-start justify-between gap-4">

                <div>
                    <h2 class="text-lg font-bold text-slate-800">Auto Approve</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Hasil kerja employee otomatis disetujui tanpa perlu direview manual.
                    </p>
                </div>

                <label class="relative inline-flex cursor-pointer items-center">
                    <input
                        type="checkbox"
                        name="assignment_auto_approve"
                        value="1"
                        {{ $company->assignment_auto_approve ? 'checked' : '' }}
                        class="peer sr-only">
                    <div class="h-6 w-11 rounded-full bg-slate-200 peer-checked:bg-blue-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full"></div>
                </label>

            </div>

        </x-ui.card>

        {{-- ================= Durasi Revisi ================= --}}
        <x-ui.card>

            <h2 class="text-lg font-bold text-slate-800">Durasi Revisi Default</h2>
            <p class="mt-1 text-sm text-slate-500">
                Batas waktu employee submit ulang setelah hasil kerjanya di-reject, kalau tidak diisi manual
                saat reject.
            </p>

            <div class="mt-4">
                <label class="mb-1 block text-sm font-semibold text-slate-700">Durasi (menit)</label>
                <input
                    type="number"
                    name="assignment_revision_minutes"
                    value="{{ old('assignment_revision_minutes', $company->assignment_revision_minutes) }}"
                    min="5"
                    max="43200"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm">
                @error('assignment_revision_minutes')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                @foreach([['1 Jam', 60], ['6 Jam', 360], ['1 Hari', 1440], ['3 Hari', 4320]] as [$label, $minutes])
                    <button
                        type="button"
                        onclick="document.querySelector('input[name=assignment_revision_minutes]').value = {{ $minutes }}"
                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-200">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

        </x-ui.card>

        <button
            type="submit"
            class="w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
            Simpan Pengaturan
        </button>

    </form>

</div>

@endsection
