@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="mx-auto max-w-2xl space-y-6">

    <x-ui.page-header
        title="My Profile"
        description="Kelola informasi akun dan password kamu.">
    </x-ui.page-header>

    @if(session('success'))

        <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">

            {{ session('success') }}

        </div>

    @endif

    <form

        action="{{ route('profile.update') }}"

        method="POST"

        class="space-y-6">

        @csrf

        @method('PUT')

        {{-- Account Information --}}
        <x-ui.card>

            <div class="mb-6">

                <h2 class="text-xl font-bold text-slate-800">

                    Account Information

                </h2>

                <p class="mt-1 text-sm text-slate-500">

                    Informasi akun login kamu.

                </p>

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

            <div class="mb-6">

                <h2 class="text-xl font-bold text-slate-800">

                    Change Password

                </h2>

                <p class="mt-1 text-sm text-slate-500">

                    Kosongkan jika tidak ingin mengubah password.

                </p>

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

                Save Changes

            </x-ui.button>

        </div>

    </form>

</div>

@endsection