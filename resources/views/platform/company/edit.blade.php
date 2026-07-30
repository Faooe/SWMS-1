@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- Page Header --}}
    {{-- ========================================================= --}}

    <x-ui.page-header
        title="Edit Company"
        description="Perbarui informasi perusahaan.">

        <a
            href="{{ route('platform.companies.show',$company) }}">

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

        action="{{ route('platform.companies.update',$company) }}"

        method="POST"

        enctype="multipart/form-data"

        class="space-y-6">

        @csrf

        @method('PUT')

        @include('platform.company._form')

    </form>

</div>

@endsection