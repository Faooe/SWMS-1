@extends('layouts.app')

@section('title', 'Tambah Department')
@section('page-title', 'Department')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Company Workspace</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Tambah Department</h1>
            <p class="mt-1 text-sm text-slate-500">Tambahkan unit organisasi baru agar penempatan employee dan struktur perusahaan lebih teratur.</p>
        </div>

        <a
            href="{{ route('departments.index') }}"
            class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke Department
        </a>
    </div>

    <form action="{{ route('departments.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_320px]">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <i data-lucide="building-2" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Informasi Department</h2>
                        <p class="mt-1 text-sm leading-5 text-slate-500">Isi identitas utama department yang akan digunakan sebagai master data perusahaan.</p>
                    </div>
                </div>

                <div class="space-y-5 p-5 sm:p-6">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="code" class="mb-2 flex items-center gap-1 text-sm font-semibold text-slate-700">
                                Kode Department <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="hash" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                                <input
                                    id="code"
                                    name="code"
                                    type="text"
                                    maxlength="20"
                                    required
                                    autocomplete="off"
                                    value="{{ old('code') }}"
                                    placeholder="Contoh: HRD"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/70 py-3 pl-10 pr-4 text-sm font-medium text-slate-800 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50 @error('code') border-rose-300 focus:border-rose-400 focus:ring-rose-50 @enderror">
                            </div>
                            <p class="mt-2 text-xs leading-5 text-slate-400">Gunakan kode singkat dan mudah dikenali, maksimal 20 karakter.</p>
                            @error('code')
                                <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-rose-600">
                                    <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="mb-2 flex items-center gap-1 text-sm font-semibold text-slate-700">
                                Nama Department <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="badge" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    maxlength="100"
                                    required
                                    value="{{ old('name') }}"
                                    placeholder="Contoh: Human Resources"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/70 py-3 pl-10 pr-4 text-sm font-medium text-slate-800 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50 @error('name') border-rose-300 focus:border-rose-400 focus:ring-rose-50 @enderror">
                            </div>
                            <p class="mt-2 text-xs leading-5 text-slate-400">Nama ini akan tampil pada employee, team, dan filter organisasi.</p>
                            @error('name')
                                <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-rose-600">
                                    <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
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
                            placeholder="Tambahkan fungsi atau tanggung jawab utama department..."
                            class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3 text-sm leading-6 text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50 @error('description') border-rose-300 focus:border-rose-400 focus:ring-rose-50 @enderror">{{ old('description') }}</textarea>
                        <p class="mt-2 text-xs leading-5 text-slate-400">Deskripsi membantu administrator memahami fungsi department tanpa membuka data employee.</p>
                        @error('description')
                            <p class="mt-2 flex items-center gap-1.5 text-xs font-medium text-rose-600">
                                <i data-lucide="circle-alert" class="h-3.5 w-3.5"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="space-y-5">
                <section
                    x-data="{ active: {{ old('is_active', '1') == '1' ? 'true' : 'false' }} }"
                    class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <i data-lucide="circle-check-big" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Status Department</h2>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Atur apakah department langsung tersedia untuk penempatan employee.</p>
                        </div>
                    </div>

                    <input type="hidden" name="is_active" value="0">
                    <label class="mt-5 flex cursor-pointer items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3.5 transition hover:border-slate-300">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800">Department aktif</p>
                            <p class="mt-0.5 text-xs text-slate-500" x-text="active ? 'Bisa dipilih pada penempatan employee.' : 'Disimpan tetapi tidak tersedia untuk penempatan.'"></p>
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
                </section>

                <aside class="rounded-3xl border border-blue-100 bg-blue-50/60 p-5">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">
                            <i data-lucide="lightbulb" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Tips Master Data</h3>
                            <p class="mt-1 text-xs leading-5 text-slate-600">Gunakan nama department yang stabil. Team dan employee nantinya dapat dikelompokkan di bawah department ini.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        <div class="sticky bottom-4 z-20 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-lg backdrop-blur sm:flex-row sm:items-center sm:justify-between">
            <div class="hidden items-center gap-2 text-sm text-slate-500 lg:flex">
                <i data-lucide="info" class="h-4 w-4 text-slate-400"></i>
                Pastikan kode, nama, dan status department sudah benar sebelum disimpan.
            </div>

            <div class="flex gap-2 sm:ml-auto">
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
    </form>
</div>
@endsection
