@extends('layouts.app')

@section('title', 'Office Management')

@section('content')

<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Office Management

            </h1>

            <p class="mt-2 text-slate-500">

                Manage office locations, GPS coordinates, attendance radius, and office information.

            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="{{ route('offices.create') }}"
                class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">

                + Add Office

            </a>

            <a
                href="#"
                class="rounded-xl bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-700">

                Export PDF

            </a>

        </div>

    </div>

    {{-- Statistics --}}
    @include('office.partials.statistics')

    {{-- Filter --}}
    @include('office.partials.filters')

    {{-- Table --}}
    @include('office.partials.table')

</div>

@endsection