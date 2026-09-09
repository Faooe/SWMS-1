@extends('layouts.app')
@section('title', 'Edit Company')
@section('content')
<div class="space-y-5">
    <div class="rounded-3xl border border-slate-200 bg-white px-5 py-5 shadow-sm sm:px-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-start gap-3">
                <a href="{{ route('platform.companies.show', $company) }}" class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-slate-200"><i data-lucide="arrow-left" class="h-5 w-5"></i></a>
                <div><p class="text-xs font-bold uppercase tracking-[0.14em] text-blue-600">Edit Company</p><h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">{{ $company->name }}</h1><p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">Perbarui identitas, kontak, Head Office, alamat, dan akun administrator.</p></div>
            </div>
            <div class="flex flex-wrap items-center gap-2"><span class="inline-flex items-center gap-1.5 rounded-full border {{ $company->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }} px-3 py-1.5 text-xs font-bold"><span class="h-1.5 w-1.5 rounded-full {{ $company->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $company->is_active ? 'Aktif' : 'Nonaktif' }}</span><span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">{{ $company->subscription_plan }}</span></div>
        </div>
    </div>
    <form action="{{ route('platform.companies.update', $company) }}" method="POST" enctype="multipart/form-data" class="platform-company-form space-y-5">@csrf @method('PUT') @include('platform.company._form')</form>
</div>
@endsection