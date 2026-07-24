@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

@php
    $displayName = $user->employee?->full_name ?? $user->username;
@endphp

<div class="space-y-8">

    <x-ui.page-header
        title="My Profile"
        description="Kelola informasi akun dan keamanan password kamu.">
    </x-ui.page-header>

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

                <x-ui.avatar
                    :employee="$user->employee"
                    size="24"
                />

                <h2 class="mt-5 text-2xl font-bold text-slate-800">

                    {{ $displayName }}

                </h2>

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

@endsection
