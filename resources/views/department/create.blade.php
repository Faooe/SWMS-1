@extends('layouts.app')

@section('title', 'Tambah Department')
@section('page-title', 'Department')

@section('content')
<div class="mx-auto max-w-4xl space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Company Workspace</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Tambah Department</h1>
            <p class="mt-1 text-sm leading-6 text-slate-500">Tambahkan unit organisasi baru untuk mengelompokkan employee, team, dan struktur perusahaan.</p>
        </div>

        <a
            href="{{ route('departments.index') }}"
            class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali
        </a>
    </div>

    <form action="{{ route('departments.store') }}" method="POST">
        @csrf

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-start gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="building-2" class="h-5 w-5"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-lg font-bold text-slate-900">Informasi Department</h2>
                        <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-500">Master Data</span>
                    </div>
                    <p class="mt-1 text-sm leading-5 text-slate-500">Isi identitas dasar department yang akan digunakan di employee, team, assignment, dan filter organisasi.</p>
                </div>
            </div>

            @if($errors->any())
                <div class="mx-5 mt-5 flex items-start gap-3 rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700 sm:mx-6">
                    <i data-lucide="circle-alert" class="mt-0.5 h-4 w-4 shrink-0"></i>
                    <p>Masih ada data yang perlu diperbaiki. Periksa field yang ditandai di bawah.</p>
                </div>
            @endif

            <div class="space-y-6 px-5 py-5 sm:px-6 sm:py-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <x-ui.input
                        name="code"
                        label="Kode Department"
                        placeholder="Contoh: HRD"
                        hint="Kode singkat dan mudah dikenali. Maksimal 20 karakter."
                        maxlength="20"
                        autocomplete="off"
                        required />

                    <x-ui.input
                        name="name"
                        label="Nama Department"
                        placeholder="Contoh: Human Resources"
                        hint="Nama ini akan tampil pada employee, team, dan filter organisasi."
                        maxlength="100"
                        required />
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <label for="description" class="text-sm font-semibold text-slate-700">Deskripsi</label>
                        <span class="text-xs font-medium text-slate-400">Opsional</span>
                    </div>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Jelaskan fungsi atau tanggung jawab utama department..."
                        class="w-full resize-none rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm leading-6 text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @error('description') border-rose-400 focus:border-rose-500 focus:ring-rose-100 @enderror">{{ old('description') }}</textarea>
                    <p class="mt-2 text-xs leading-5 text-slate-400">Deskripsi membantu administrator memahami fungsi department tanpa perlu membuka data employee.</p>
                    @error('description')
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-rose-600">
                            <i data-lucide="circle-alert" class="h-4 w-4"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div
                x-data="{ active: {{ old('is_active', '1') == '1' ? 'true' : 'false' }} }"
                class="border-t border-slate-100 bg-slate-50/50 px-5 py-4 sm:px-6">
                <input type="hidden" name="is_active" value="0">

                <label class="flex cursor-pointer items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white px-4 py-3.5 transition hover:border-blue-200">
                    <div class="flex min-w-0 items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                            <i data-lucide="circle-check-big" class="h-4 w-4"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-semibold text-slate-800">Department aktif</p>
                                <span
                                    class="rounded-full px-2 py-0.5 text-[11px] font-semibold"
                                    :class="active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                                    x-text="active ? 'Aktif' : 'Nonaktif'"></span>
                            </div>
                            <p class="mt-1 text-xs leading-5 text-slate-500" x-text="active ? 'Department langsung tersedia untuk penempatan employee.' : 'Department disimpan tetapi tidak tersedia untuk penempatan employee.'"></p>
                        </div>
                    </div>

                    <span class="relative inline-flex shrink-0">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            x-model="active"
                            class="peer sr-only"
                            @checked(old('is_active', '1') == '1')>
                        <span class="h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-blue-600"></span>
                        <span class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white shadow-sm transition peer-checked:translate-x-5"></span>
                    </span>
                </label>

                @error('is_active')
                    <p class="mt-2 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="flex items-start gap-2 text-xs leading-5 text-slate-500 sm:max-w-md">
                    <i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
                    <span>Pastikan kode dan nama department sudah benar sebelum disimpan.</span>
                </div>

                <div class="flex gap-2 sm:shrink-0">
                    <a
                        href="{{ route('departments.index') }}"
                        class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50 sm:flex-none">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100 sm:flex-none">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        Simpan Department
                    </button>
                </div>
            </div>
        </section>
    </form>
</div>
@endsection
