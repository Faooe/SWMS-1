@extends('layouts.app')

@section('title', 'Create Company')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- Page Header --}}
    {{-- ========================================================= --}}

    <x-ui.page-header
        title="Create Company"
        description="Tambahkan perusahaan baru ke dalam Smart Workforce Management System.">

        <a
            href="{{ route('platform.companies.index') }}">

            <x-ui.button variant="secondary">

                <i
                    data-lucide="arrow-left"
                    class="h-5 w-5">
                </i>

                Back

            </x-ui.button>

        </a>

    </x-ui.page-header>

    {{-- ========================================================= --}}
    {{-- Form --}}
    {{-- ========================================================= --}}

    <form

        action="{{ route('platform.companies.store') }}"

        method="POST"

        enctype="multipart/form-data"

        class="space-y-6">

        @csrf

        @include('platform.company._form')

    </form>

</div>

@endsection