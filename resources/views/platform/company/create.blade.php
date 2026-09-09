@extends('layouts.app')

@section('title', 'Tambah Company')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Company Management</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Tambah Company</h1>
            <p class="mt-1 text-sm text-slate-500">Daftarkan tenant baru, tentukan Head Office, dan siapkan akun Super Administrator.</p>
        </div>
        <a href="{{ route('platform.companies.index') }}" class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke Companies
        </a>
    </div>

    <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4 sm:p-5">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                <i data-lucide="sparkles" class="h-5 w-5"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-800">Persiapan Company Baru</p>
                <p class="mt-1 text-sm leading-6 text-slate-600">Lengkapi data dari atas ke bawah. Setelah tersimpan, sistem menyiapkan Head Office, paket Free, serta password awal Super Administrator secara otomatis.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('platform.companies.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @include('platform.company._form')
    </form>
</div>
@endsection
