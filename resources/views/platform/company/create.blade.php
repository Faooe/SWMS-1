@extends('layouts.app')

@section('title', 'Tambah Company')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- Page Header --}}
    {{-- ========================================================= --}}

    <x-ui.page-header
        title="Tambah Company"
        description="Daftarkan company baru beserta lokasi dan akun Super Administrator.">

        <a
            href="{{ route('platform.companies.index') }}">

            <x-ui.button variant="secondary">

                <i
                    data-lucide="arrow-left"
                    class="h-5 w-5">
                </i>

                Kembali

            </x-ui.button>

        </a>

    </x-ui.page-header>

    <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4 sm:p-5">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                <i data-lucide="building-2" class="h-5 w-5"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-800">Persiapan Company Baru</p>
                <p class="mt-1 text-sm leading-6 text-slate-600">
                    Isi identitas company, tentukan lokasi Head Office, lalu buat akun Super Administrator.
                    Setelah tersimpan, sistem otomatis menyiapkan Head Office dan paket awal Free.
                </p>
            </div>
        </div>
    </div>

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