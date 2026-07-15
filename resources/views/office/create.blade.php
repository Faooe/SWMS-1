@extends('layouts.app')

@section('title', 'Create Office')

@section('content')

<div class="mx-auto max-w-7xl space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-4xl font-bold text-slate-800">

                Create Office

            </h1>

            <p class="mt-2 max-w-3xl text-slate-500">

                Register a new office that will be used as an attendance
                location, assignment destination, and GPS validation point.

            </p>

        </div>

        <a
            href="{{ route('offices.index') }}"
            class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 shadow-sm transition hover:bg-slate-100">

            ← Back

        </a>

    </div>

    <form
        action="{{ route('offices.store') }}"
        method="POST"
        class="space-y-8">

        @csrf

        {{-- Office Information --}}
        @include('office.partials.form')

        {{-- Map --}}
        @include('office.partials.map')

        {{-- Status --}}
        @include('office.partials.status')

        {{-- Action --}}
        @include('office.partials.action')

    </form>

</div>

@endsection