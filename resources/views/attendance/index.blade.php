@extends('layouts.app')

@section('title', 'Attendance Management')

@section('content')

<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Attendance Management

            </h1>

            <p class="mt-2 text-slate-500">

                Monitor employee attendance, check-in, check-out, GPS validation, and attendance history.

            </p>

        </div>

        <div class="flex items-center gap-3">

            <a
                href="{{ route('attendance.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100">

                Refresh

            </a>

            <a
                href="{{ route('attendance.export.pdf', request()->query()) }}"
                class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">

                Export PDF

            </a>

        </div>

    </div>

    {{-- Statistics --}}
    @include('attendance.partials.statistics')

    {{-- Filters --}}
    @include('attendance.partials.filters')

    {{-- Attendance Table --}}
    @include('attendance.partials.table')

</div>

@endsection