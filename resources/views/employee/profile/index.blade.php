@extends('layouts.app')

@section('title', 'Profile')

@section('content')
@php
    $displayName = $user->employee?->full_name ?? $user->username;
    $employee = $user->employee;
    $company = $user->company;
    $employment = $employee?->currentEmployment;
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Employee Workspace</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Profile</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola identitas akun, foto profile, dan keamanan akses SWMS.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $user->is_active ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700' }}">
            <span class="h-1.5 w-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
            {{ $user->is_active ? 'Akun Aktif' : 'Akun Nonaktif' }}
        </span>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            <i data-lucide="check-circle-2" class="h-5 w-5 shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
                <div class="p-6 sm:p-7">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                        <div class="relative shrink-0">
                            @if($user->profile_photo || $employee?->photo)
                                <img src="{{ secure_file_url($user->profile_photo ?? $employee?->photo) }}" class="h-20 w-20 rounded-2xl object-cover ring-1 ring-slate-200">
                            @else
                                <x-ui.avatar :employee="$employee" size="20" />
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="truncate text-xl font-bold text-slate-900">{{ $displayName }}</h2>
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700">{{ $user->role?->name ?? 'Employee' }}</span>
                            </div>
                            <p class="mt-1 truncate text-sm text-slate-500">{{ $employment?->position?->name ?? 'Employee' }}{{ $employment?->department?->name ? ' • '.$employment->department->name : '' }}</p>
                            <p class="mt-1 break-all text-sm text-slate-400">{{ $user->email }}</p>
                            <form action="{{ route('employee.profile.photo') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                @csrf
                                <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                    <i data-lucide="camera" class="h-4 w-4 text-blue-600"></i>
                                    Ganti Foto
                                    <input type="file" name="photo" accept="image/*" class="hidden" data-compress-image data-auto-submit="true">
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 border-t border-slate-100 sm:grid-cols-4">
                    <div class="p-4 sm:px-5"><p class="text-[11px] font-medium text-slate-400">NIP</p><p class="mt-1 truncate text-sm font-bold text-slate-800">{{ $employee?->employee_number ?? '-' }}</p></div>
                    <div class="border-l border-slate-100 p-4 sm:px-5"><p class="text-[11px] font-medium text-slate-400">Company</p><p class="mt-1 truncate text-sm font-bold text-slate-800">{{ $company?->name ?? '-' }}</p></div>
                    <div class="border-t border-slate-100 p-4 sm:border-l sm:border-t-0 sm:px-5"><p class="text-[11px] font-medium text-slate-400">Office</p><p class="mt-1 truncate text-sm font-bold text-slate-800">{{ $employment?->office?->name ?? '-' }}</p></div>
                    <div class="border-l border-t border-slate-100 p-4 sm:border-t-0 sm:px-5"><p class="text-[11px] font-medium text-slate-400">Login Terakhir</p><p class="mt-1 truncate text-sm font-bold text-slate-800">{{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '-' }}</p></div>
                </div>
            </section>

            <form action="{{ route('employee.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
                    <div class="flex items-start gap-3 border-b border-slate-100 px-6 py-5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="user-round" class="h-5 w-5"></i></div>
                        <div><h3 class="font-bold text-slate-900">Identitas Login</h3><p class="mt-0.5 text-sm text-slate-500">Informasi akun yang digunakan untuk masuk ke SWMS.</p></div>
                    </div>
                    <div class="grid gap-5 p-6 md:grid-cols-2">
                        <x-ui.input label="Username" name="username" :value="old('username', $user->username)" required />
                        <x-ui.input label="Email" name="email" type="email" :value="old('email', $user->email)" required />
                    </div>
                </section>

                <section id="account-settings" class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
                    <div class="flex items-start gap-3 border-b border-slate-100 px-6 py-5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="shield-check" class="h-5 w-5"></i></div>
                        <div><h3 class="font-bold text-slate-900">Keamanan Akun</h3><p class="mt-0.5 text-sm text-slate-500">Kosongkan semua field password jika tidak ingin mengubah password.</p></div>
                    </div>
                    <div class="space-y-5 p-6">
                        <x-ui.input label="Password Saat Ini" name="current_password" type="password" placeholder="Masukkan password saat ini" />
                        <div class="grid gap-5 md:grid-cols-2">
                            <x-ui.input label="Password Baru" name="password" type="password" placeholder="Minimal 6 karakter" />
                            <x-ui.input label="Konfirmasi Password Baru" name="password_confirmation" type="password" placeholder="Ulangi password baru" />
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <x-ui.button type="submit"><i data-lucide="save" class="h-4 w-4"></i>Simpan Perubahan</x-ui.button>
                </div>
            </form>
        </div>

        <aside class="space-y-6 xl:sticky xl:top-24 xl:self-start">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
                <div class="border-b border-slate-100 px-5 py-4"><h3 class="text-sm font-bold text-slate-900">Informasi Employee</h3><p class="mt-0.5 text-xs text-slate-500">Penempatan kerja yang dikelola company.</p></div>
                <div class="divide-y divide-slate-100">
                    <div class="px-5 py-4"><p class="text-xs text-slate-400">Department</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ $employment?->department?->name ?? '-' }}</p></div>
                    <div class="px-5 py-4"><p class="text-xs text-slate-400">Position</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ $employment?->position?->name ?? '-' }}</p></div>
                    <div class="px-5 py-4"><p class="text-xs text-slate-400">Office</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ $employment?->office?->name ?? '-' }}</p></div>
                    @if($employment?->shift)
                        <div class="px-5 py-4"><p class="text-xs text-slate-400">Shift</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ $employment->shift->name ?? '-' }}</p></div>
                    @endif
                </div>
            </section>

            @if($employee && $company)
                <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 px-5 py-4"><h3 class="text-sm font-bold text-slate-900">Cara Login</h3><p class="mt-0.5 text-xs text-slate-500">Selain email, gunakan NIP dan kode company.</p></div>
                    <div class="divide-y divide-slate-100">
                        <div class="flex items-center gap-3 px-5 py-4"><div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="badge-check" class="h-4 w-4"></i></div><div class="min-w-0"><p class="text-xs text-slate-400">Employee Number (NIP)</p><p class="truncate text-sm font-semibold text-slate-800">{{ $employee->employee_number }}</p></div></div>
                        <div class="flex items-center gap-3 px-5 py-4"><div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="building-2" class="h-4 w-4"></i></div><div class="min-w-0"><p class="text-xs text-slate-400">Kode Company</p><p class="truncate text-sm font-semibold text-slate-800">{{ $company->code }}</p></div></div>
                    </div>
                </section>
            @endif
        </aside>
    </div>
</div>
@endsection
