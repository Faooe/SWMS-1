@extends('layouts.app')

@section('title', 'Edit Position')

@section('content')

<div class="mx-auto max-w-3xl space-y-8">

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">Edit Position</h1>

            <p class="mt-2 text-slate-500">Perbarui informasi position {{ $position->name }}.</p>

        </div>

        <a
            href="{{ route('positions.index') }}"
            class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 shadow-sm transition hover:bg-slate-100">
            ← Back
        </a>

    </div>

    <form
        action="{{ route('positions.update', $position) }}"
        method="POST"
        class="space-y-6">

        @csrf
        @method('PUT')

        <x-ui.card class="space-y-6">

            <x-ui.input
                label="Position Code"
                name="code"
                value="{{ $position->code }}"
                required
            />

            <x-ui.input
                label="Position Name"
                name="name"
                value="{{ $position->name }}"
                required
            />

            <x-ui.textarea
                label="Description"
                name="description"
                value="{{ $position->description }}"
            />

            <label class="flex items-center gap-3">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked($position->is_active)
                    class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                <span class="text-sm font-medium text-slate-700">Active</span>

            </label>

        </x-ui.card>

        <div class="flex justify-end gap-3">

            <a
                href="{{ route('positions.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-6 py-3 font-medium text-slate-700 shadow-sm transition hover:bg-slate-100">
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-sm transition hover:bg-blue-700">
                Update Position
            </button>

        </div>

    </form>

</div>

@endsection
