@extends('layouts.app')

@section('title', 'Office Detail')
@section('page-title', 'Office')

@section('content')

<div class="mx-auto max-w-[1700px] space-y-5">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-bold text-blue-600">Company Workspace</p>
            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">{{ $office->name }}</h1>
            <p class="mt-2 text-slate-500">
                Lihat dan perbarui informasi office. Penambahan atau penghapusan dikelola oleh platform admin.
            </p>
        </div>

        <a href="{{ route('offices.index') }}"
           class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>Kembali ke Daftar Office
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            Periksa kembali formulir karena masih ada data yang belum valid.
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
