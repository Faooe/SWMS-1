@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')
@php
    $displayName = $user->username ?: 'Platform Administrator';
    $initial = strtoupper(substr($displayName, 0, 1));
    $roleName = $user->role?->name ?? 'Platform Administrator';
@endphp

<div class="mx-auto max-w-6xl space-y-5">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Account & Security</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Profile Platform Administrator</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola identitas akun, foto profile, dan keamanan password dari satu tempat.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            {{ $user->is_active ? 'Akun Aktif' : 'Akun Nonaktif' }}
        </span>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            <i data-lucide="check-circle-2" class="h-5 w-5 shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="relative overflow-hidden border-b border-slate-100 bg-gradient-to-br from-blue-50 via-white to-slate-50 px-5 py-6 sm:px-7">
            <div class="absolute -right-12 -top-12 h-44 w-44 rounded-full bg-blue-100/50 blur-2xl"></div>
            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex min-w-0 items-center gap-4">
                    @if($user->profile_photo)
                        <img src="{{ secure_file_url($user->profile_photo) }}" alt="Foto profile" class="h-20 w-20 shrink-0 rounded-2xl border-4 border-white object-cover shadow-sm">
                    @else
                        <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl border-4 border-white bg-blue-600 text-2xl font-bold text-white shadow-sm">
                            {{ $initial }}
                        </div>
                    @endif

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="truncate text-xl font-bold text-slate-900 sm:text-2xl">{{ $displayName }}</h2>
                            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700 ring-1 ring-blue-200">{{ $roleName }}</span>
                        </div>
                        <p class="mt-1 truncate text-sm text-slate-500">{{ $user->email }}</p>
                        <p class="mt-2 text-xs font-medium text-slate-400">Member sejak {{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <form action="{{ route('platform.profile.photo') }}" method="POST" enctype="multipart/form-data" class="shrink-0">
                    @csrf
                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-300 hover:text-blue-700">
                        <i data-lucide="camera" class="h-4 w-4"></i>
                        Ganti Foto
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="hidden" data-compress-image data-auto-submit="true">
                    </label>
                </form>
            </div>
        </div>

        <div class="grid divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
            <div class="px-5 py-4 sm:px-6">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</p>
                <div class="mt-2 flex items-center gap-2 text-sm font-bold text-slate-800">
                    <span class="h-2.5 w-2.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                </div>
            </div>
            <div class="px-5 py-4 sm:px-6">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Login Terakhir</p>
                <p class="mt-2 text-sm font-bold text-slate-800">{{ $user->last_login_at ? $user->last_login_at->format('d M Y, H:i') : 'Belum tersedia' }}</p>
            </div>
            <div class="px-5 py-4 sm:px-6">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Role</p>
                <p class="mt-2 text-sm font-bold text-slate-800">{{ $roleName }}</p>
            </div>
        </div>
    </section>

    <div class="grid items-start gap-5 lg:grid-cols-[minmax(0,1.05fr)_minmax(340px,.95fr)]">
        <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-start gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="user-round" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-900">Informasi Akun</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Identitas login dan akses Platform Administrator.</p>
                </div>
            </div>

            <div class="divide-y divide-slate-100 px-5 sm:px-6">
                <div class="grid gap-1 py-4 sm:grid-cols-[150px_1fr] sm:items-center">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Username</p>
                    <p class="break-all text-sm font-semibold text-slate-800 sm:text-right">{{ $user->username }}</p>
                </div>
                <div class="grid gap-1 py-4 sm:grid-cols-[150px_1fr] sm:items-center">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</p>
                    <p class="break-all text-sm font-semibold text-slate-800 sm:text-right">{{ $user->email }}</p>
                </div>
                <div class="grid gap-1 py-4 sm:grid-cols-[150px_1fr] sm:items-center">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Role</p>
                    <p class="text-sm font-semibold text-slate-800 sm:text-right">{{ $roleName }}</p>
                </div>
                <div class="grid gap-1 py-4 sm:grid-cols-[150px_1fr] sm:items-center">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Dibuat</p>
                    <p class="text-sm font-semibold text-slate-800 sm:text-right">{{ $user->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="border-t border-slate-100 bg-slate-50/70 px-5 py-4 text-xs leading-5 text-slate-500 sm:px-6">
                Username dan email dikelola sebagai identitas akun platform. Perubahan data akun utama dilakukan melalui proses administrasi yang berwenang.
            </div>
        </section>

        <section id="account-settings" class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-start gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="shield-check" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-900">Keamanan Akun</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Perbarui password secara berkala untuk menjaga akun tetap aman.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('platform.profile.update') }}" class="space-y-4 px-5 py-5 sm:px-6 sm:py-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                        placeholder="Masukkan password saat ini">
                    @error('current_password')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password Baru</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                        placeholder="Minimal 8 karakter">
                    @error('password')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                        placeholder="Ulangi password baru">
                </div>

                <div class="rounded-2xl bg-blue-50/70 px-4 py-3 text-xs leading-5 text-blue-700">
                    <div class="flex gap-2">
                        <i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0"></i>
                        <span>Gunakan kombinasi karakter yang kuat dan jangan gunakan password yang sama dengan layanan lain.</span>
                    </div>
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
                    <i data-lucide="lock-keyhole" class="h-4 w-4"></i>
                    Simpan Password Baru
                </button>
            </form>
        </section>
    </div>
</div>
@endsection
