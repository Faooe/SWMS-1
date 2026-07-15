@extends('layouts.app')

@section('title', 'Attendance Detail')

@section('content')

<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                Attendance Detail

            </h1>

            <p class="mt-2 text-slate-500">

                Complete attendance information, GPS validation, attendance photos, and activity timeline.

            </p>

        </div>

        <div class="flex items-center gap-3">

            <a
                href="{{ route('attendance.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 hover:bg-slate-100">

                ← Back

            </a>

        </div>

    </div>

    {{-- Content --}}
    <div class="grid grid-cols-12 gap-6">

        {{-- Employee --}}
        <div class="col-span-12 lg:col-span-6">

            @include('attendance.partials.employee-card')

        </div>

        {{-- Attendance --}}
        <div class="col-span-12 lg:col-span-6">

            @include('attendance.partials.attendance-card')

        </div>

        {{-- GPS --}}
        <div class="col-span-12 lg:col-span-5">

            @include('attendance.partials.gps-card')

        </div>

        {{-- Photos --}}
        <div class="col-span-12 lg:col-span-7">

            @include('attendance.partials.photos-card')

        </div>

        {{-- Timeline --}}
        <div class="col-span-12">

            @include('attendance.partials.timeline-card')

        </div>

    </div>

</div>
<div
    id="photoModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80">

    <div class="relative max-w-6xl">

        <button

            onclick="closePhoto()"

            class="absolute -top-12 right-0 rounded-lg bg-white px-4 py-2">

            ✕

        </button>

        <img

            id="photoPreview"

            class="max-h-[90vh] rounded-2xl shadow-2xl">

    </div>

</div>
@push('scripts')

<script>

function openPhoto(url){

    document
        .getElementById('photoPreview')
        .src=url;

    document
        .getElementById('photoModal')
        .classList
        .remove('hidden');

    document
        .getElementById('photoModal')
        .classList
        .add('flex');

}

function closePhoto(){

    document
        .getElementById('photoModal')
        .classList
        .remove('flex');

    document
        .getElementById('photoModal')
        .classList
        .add('hidden');

}

</script>

@endpush

@endsection