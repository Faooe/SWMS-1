@extends('layouts.app')

@section('title','Edit Office')

@section('content')

<div class="mx-auto max-w-7xl space-y-8">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-4xl font-bold">

                Edit Office

            </h1>

            <p class="mt-2 text-slate-500">

                Update office information and location.

            </p>

        </div>

        <a
            href="{{ route('offices.index') }}"
            class="rounded-xl border px-6 py-3">

            ← Back

        </a>

    </div>

    <form

        action="{{ route('offices.update',$office) }}"

        method="POST">

        @csrf

        @method('PUT')

        @include('office.partials.form')

        @include('office.partials.map')

        @include('office.partials.status')

        @include('office.partials.action')

    </form>

</div>

@endsection