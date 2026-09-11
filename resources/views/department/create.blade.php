@extends('layouts.app')

@section('title', 'Tambah Department')
@section('page-title', 'Department')

@section('content')

<div class="mx-auto max-w-3xl space-y-5">

    <div class="flex flex-col gap-4 px-1 sm:flex-row sm:items-end sm:justify-between">

        <div>

            <p class="text-sm font-bold text-blue-600">Company Workspace</p>
            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Tambah Department</h1>

            <p class="mt-2 text-slate-500">Tambahkan department baru sebagai master data.</p>

        </div>

        <a
            href="{{ route('departments.index') }}"
            class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>Kembali
        </a>

    </div>

    <form
        action="{{ route('departments.store') }}"
        method="POST"
        class="space-y-6">

        @csrf

        <x-ui.card class="space-y-6">

            <x-ui.input
                label="Kode Department"
                name="code"
                placeholder="e.g. HRD"
                required
            />

            <x-ui.input
                label="Nama Department"
                name="name"
                placeholder="e.g. Human Resources"
                required
            />

            <x-ui.textarea
                label="Deskripsi"
                name="description"
                placeholder="Deskripsi singkat department (opsional)"
            />

            <label class="flex items-center gap-3">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    checked
                    class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                <span class="text-sm font-medium text-slate-700">Department aktif</span>

            </label>

        </x-ui.card>

        <div class="flex justify-end gap-3">

            <a
                href="{{ route('departments.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 shadow-sm transition hover:bg-slate-100">
                Batal
            </a>

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-sm transition hover:bg-blue-700">
                Simpan Department
            </button>

        </div>

    </form>

</div>

@endsection
