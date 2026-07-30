@extends('layouts.app')

@section('title', 'Subscription')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Subscription</h1>
        <p class="mt-1 text-sm text-slate-500">
            Kelola paket langganan company kamu.
        </p>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <p class="text-sm text-slate-500">Plan Saat Ini</p>
        <h2 class="mt-1 text-2xl font-bold text-slate-800">
            {{ $company->subscription_plan }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Maks {{ $company->max_employee }} karyawan
            @if($company->subscription_end)
                &middot; berlaku sampai {{ $company->subscription_end->translatedFormat('d M Y') }}
            @endif
        </p>

        <p class="mt-4 text-sm text-slate-500">
            Untuk upgrade plan, gunakan tombol plan yang ada di pojok kanan bawah halaman ini.
        </p>
    </div>

</div>

@endsection
