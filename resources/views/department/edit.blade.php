@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')

<div class="mx-auto max-w-4xl space-y-8">

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">Edit Department</h1>

            <p class="mt-2 text-slate-500">Kelola informasi, Team, dan Position — semua dari satu halaman ini tanpa reload.</p>

        </div>

        <a
            href="{{ route('departments.index') }}"
            class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 shadow-sm transition hover:bg-slate-100">
            &larr; Back
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
