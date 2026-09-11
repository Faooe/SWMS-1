@extends('layouts.app')

@section('title', 'Edit Department')
@section('page-title', 'Department')

@section('content')

<div class="mx-auto max-w-4xl space-y-5">

    <div class="flex flex-col gap-4 px-1 sm:flex-row sm:items-end sm:justify-between">

        <div>

            <p class="text-sm font-bold text-blue-600">Company Workspace</p>
            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Edit Department</h1>

            <p class="mt-2 text-slate-500">Kelola informasi, Team, dan Position — semua dari satu halaman ini tanpa reload.</p>

        </div>

        <a
            href="{{ route('departments.index') }}"
            class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>Kembali
        </a>

    </div>

    {{-- Department Info --}}
    @livewire('department.edit-form', ['department' => $department])

    {{-- Team milik Department ini --}}
    @livewire('department.team-manager', ['department' => $department])

    {{-- Position (company-wide) --}}
    @livewire('position-manager')

</div>

@endsection
