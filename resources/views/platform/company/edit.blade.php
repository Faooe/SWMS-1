@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Company Management</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Edit Company</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui identitas, lokasi, dan informasi {{ $company->name }}.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-2 rounded-full border {{ $company->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }} px-3 py-1.5 text-xs font-semibold">
                <span class="h-2 w-2 rounded-full {{ $company->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                {{ $company->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
            <a href="{{ route('platform.companies.show', $company) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali ke Detail
            </a>
        </div>
    </div>

    <form action="{{ route('platform.companies.update', $company) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        @include('platform.company._form')
    </form>
</div>
@endsection
