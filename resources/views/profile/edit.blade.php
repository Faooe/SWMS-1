@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

@php
    $displayName = $user->employee?->full_name ?? $user->username;
@endphp

@if($user->role?->code === 'SUPER_ADMIN')
<div class="mx-auto max-w-6xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Account & Company</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Profile Company Administrator</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Kelola identitas akun, foto profile, dan keamanan login untuk administrasi {{ $user->company?->name ?? 'company' }}.</p>
        </div>
        <div class="inline-flex w-fit items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            {{ $user->is_active ? 'Akun Aktif' : 'Akun Nonaktif' }}
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
            <i data-lucide="check-circle-2" class="h-5 w-5 shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
        <aside class="space-y-5">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm shadow-slate-200/40">
                <div class="bg-gradient-to-br from-blue-50 via-white to-slate-50 p-6">
                    <div class="flex items-start gap-4">
                        <div class="relative shrink-0">
                            @if($user->profile_photo || $user->employee?->photo || $user->company?->logo)
                                <img src="{{ secure_file_url($user->profile_photo ?? $user->employee?->photo ?? $user->company?->logo) }}" class="h-20 w-20 rounded-2xl object-cover ring-4 ring-white shadow-sm">
                            @else
                                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-blue-100 text-2xl font-bold text-blue-600 ring-4 ring-white shadow-sm">
                                    {{ strtoupper(mb_substr($displayName, 0, 1)) }}
                                </div>
                            @endif
                            <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" class="absolute -bottom-2 -right-2">
                                @csrf
                                <label class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-blue-200 hover:text-blue-600" title="Ganti Foto">
                                    <i data-lucide="camera" class="h-4 w-4"></i>
                                    <input type="file" name="photo" accept="image/*" class="hidden" data-compress-image data-auto-submit="true">
                                </label>
                            </form>
                        </div>
                        <div class="min-w-0 pt-1">
                            <h2 class="truncate text-xl font-bold text-slate-900">{{ $displayName }}</h2>
                            <p class="mt-1 truncate text-sm text-slate-500">{{ $user->email }}</p>
                            <span class="mt-3 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-bold text-blue-700">{{ $user->role?->name ?? 'Company Administrator' }}</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 border-t border-slate-100">
                    <div class="p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Company</p>
                        <p class="mt-1 truncate text-sm font-bold text-slate-800">{{ $user->company?->name ?? '-' }}</p>
                    </div>
                    <div class="border-l border-slate-100 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Kode</p>
                        <p class="mt-1 text-sm font-bold text-slate-800">{{ $user->company?->code ?? '-' }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm shadow-slate-200/30">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600"><i data-lucide="info" class="h-4 w-4"></i></div>
                    <div><h3 class="text-sm font-bold text-slate-900">Ringkasan Akun</h3><p class="text-xs text-slate-500">Informasi akses administrator</p></div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-4"><span class="text-sm text-slate-500">Username</span><span class="max-w-[180px] truncate text-sm font-semibold text-slate-800">{{ $user->username }}</span></div>
                    <div class="h-px bg-slate-100"></div>
                    <div class="flex items-center justify-between gap-4"><span class="text-sm text-slate-500">Login Terakhir</span><span class="text-right text-sm font-semibold text-slate-800">{{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '-' }}</span></div>
                    <div class="h-px bg-slate-100"></div>
                    <div class="flex items-center justify-between gap-4"><span class="text-sm text-slate-500">Bergabung</span><span class="text-sm font-semibold text-slate-800">{{ $user->created_at->format('d M Y') }}</span></div>
                </div>
            </section>
        </aside>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm shadow-slate-200/30">
                <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="user-round" class="h-5 w-5"></i></div>
                    <div><h2 class="text-base font-bold text-slate-900">Informasi Akun</h2><p class="mt-0.5 text-xs text-slate-500">Data utama yang digunakan untuk masuk ke SWMS.</p></div>
                </div>
                <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-6">
                    <div>
                        <label class="mb-2 block text-xs font-bold text-slate-600">Username</label>
                        <input name="username" value="{{ old('username', $user->username) }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
                        @error('username')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-bold text-slate-600">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
                        @error('email')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <section id="account-settings" class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm shadow-slate-200/30">
                <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600"><i data-lucide="shield-check" class="h-5 w-5"></i></div>
                    <div><h2 class="text-base font-bold text-slate-900">Keamanan Akun</h2><p class="mt-0.5 text-xs text-slate-500">Ubah password hanya jika diperlukan.</p></div>
                </div>
                <div class="space-y-5 p-5 sm:p-6">
                    <div>
                        <label class="mb-2 block text-xs font-bold text-slate-600">Password Saat Ini</label>
                        <input type="password" name="current_password" placeholder="Masukkan password saat ini" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">
                        @error('current_password')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div><label class="mb-2 block text-xs font-bold text-slate-600">Password Baru</label><input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50">@error('password')<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="mb-2 block text-xs font-bold text-slate-600">Konfirmasi Password</label><input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50"></div>
                    </div>
                    <div class="flex gap-3 rounded-2xl bg-blue-50 p-4 text-xs leading-5 text-blue-700"><i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0"></i><p>Kosongkan seluruh field password jika kamu hanya ingin memperbarui username atau email.</p></div>
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs leading-5 text-slate-500">Perubahan informasi akun akan digunakan pada login berikutnya.</p>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
                    <i data-lucide="save" class="h-4 w-4"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@else
<div class="space-y-8">

    <p class="text-slate-500">Kelola informasi akun dan keamanan password kamu.</p>

    @if(session('success'))

        <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">

            <i data-lucide="check-circle-2" class="h-5 w-5 shrink-0"></i>

            {{ session('success') }}

        </div>

    @endif

    <div class="grid gap-8 xl:grid-cols-3">

        {{-- ===================================================== --}}
        {{-- PROFILE SUMMARY --}}
        {{-- ===================================================== --}}

        <x-ui.card class="h-fit xl:col-span-1">

            <div class="flex flex-col items-center text-center">

                <div class="relative">
                    @if($user->profile_photo || $user->employee?->photo || $user->company?->logo)
                        <img src="{{ secure_file_url($user->profile_photo ?? $user->employee?->photo ?? $user->company?->logo) }}" class="h-24 w-24 rounded-full object-cover ring-4 ring-blue-50">
                    @else
                        <x-ui.avatar :employee="$user->employee" size="24" />
                    @endif
                </div>
                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        <i data-lucide="camera" class="h-4 w-4"></i> Ganti Foto
                        <input type="file" name="photo" accept="image/*" class="hidden" data-compress-image data-auto-submit="true">
                    </label>
                </form>

                <h2 class="mt-5 text-2xl font-bold text-slate-800">

                    {{ $displayName }}

                </h2>

                @if($displayName !== $user->username)

                    <p class="mt-0.5 text-sm font-medium text-slate-400">

                        &commat;{{ $user->username }}

                    </p>

                @endif

                <p class="mt-1 break-all text-slate-500">

                    {{ $user->email }}

                </p>

                <div class="mt-4">

                    <x-ui.badge color="blue">

                        {{ $user->role?->name }}

                    </x-ui.badge>

                </div>

            </div>

            <div class="mt-8 space-y-5 border-t border-slate-100 pt-8">

                <x-ui.detail-item
                    label="Username"
                    :value="$user->username"
                />

                @if($user->company)

                    <x-ui.detail-item
                        label="Company"
                        :value="$user->company->name"
                    />

                @endif

                <x-ui.detail-item
                    label="Status"
                    :value="$user->is_active ? 'Active' : 'Inactive'"
                />

                <x-ui.detail-item
                    label="Last Login"
                    :value="$user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '-'"
                />

                <x-ui.detail-item
                    label="Member Since"
                    :value="$user->created_at->format('d M Y')"
                />

            </div>

        </x-ui.card>

        {{-- ===================================================== --}}
        {{-- ACCOUNT & PASSWORD FORM --}}
        {{-- ===================================================== --}}

        <form
            action="{{ route('profile.update') }}"
            method="POST"
            class="space-y-8 xl:col-span-2">

            @csrf
            @method('PUT')

            {{-- Account Information --}}
            <x-ui.card>

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <i data-lucide="user" class="h-5 w-5"></i>
                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">

                            Account Information

                        </h2>

                        <p class="mt-0.5 text-sm text-slate-500">

                            Informasi akun login kamu.

                        </p>

                    </div>

                </div>

                <div class="grid gap-5 md:grid-cols-2">

                    <x-ui.input
                        label="Username"
                        name="username"
                        :value="old('username', $user->username)"
                        required
                    />

                    <x-ui.input
                        label="Email"
                        name="email"
                        type="email"
                        :value="old('email', $user->email)"
                        required
                    />

                </div>

            </x-ui.card>

            {{-- Change Password --}}
            <x-ui.card id="account-settings">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <i data-lucide="lock-keyhole" class="h-5 w-5"></i>
                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">

                            Change Password

                        </h2>

                        <p class="mt-0.5 text-sm text-slate-500">

                            Kosongkan jika tidak ingin mengubah password.

                        </p>

                    </div>

                </div>

                <div class="space-y-5">

                    <x-ui.input
                        label="Current Password"
                        name="current_password"
                        type="password"
                        placeholder="Masukkan password saat ini"
                    />

                    <div class="grid gap-5 md:grid-cols-2">

                        <x-ui.input
                            label="New Password"
                            name="password"
                            type="password"
                            placeholder="Minimal 6 karakter"
                        />

                        <x-ui.input
                            label="Confirm New Password"
                            name="password_confirmation"
                            type="password"
                            placeholder="Ulangi password baru"
                        />

                    </div>

                </div>

            </x-ui.card>

            <div class="flex justify-end">

                <x-ui.button type="submit">

                    <i data-lucide="save" class="h-5 w-5"></i>

                    Save Changes

                </x-ui.button>

            </div>

        </form>

    </div>

</div>
@endif

@endsection
