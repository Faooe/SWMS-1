@extends('layouts.app')

@section('title', 'Office Detail')

@section('content')

<div class="mx-auto max-w-[1700px] space-y-8">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">{{ $office->name }}</h1>
            <p class="mt-2 text-slate-500">
                View and update office information. Add / delete office is managed by the Platform Administrator.
            </p>
        </div>

        <a href="{{ route('offices.index') }}"
           class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 shadow-sm transition hover:bg-slate-100">
            &larr; Back to Office List
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            Please check the form again, there is invalid data.
        </div>
    @endif

    <form action="{{ route('offices.update', $office) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-8 xl:grid-cols-5">

            <div class="space-y-8 xl:col-span-2">
                @include('office.partials.company-info')
                @include('office.partials.form')
                @include('office.partials.status')
            </div>

            <div class="xl:col-span-3">
                @include('office.partials.map')
            </div>

        </div>

        @include('office.partials.action')
    </form>

</div>

@endsection