@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="space-y-8">

    {{-- Header --}}
    <div>

        <h1 class="text-3xl font-bold text-slate-800">

            My Profile

        </h1>

        <p class="mt-2 text-slate-500">

            Kelola informasi akun Platform dan ubah password Anda.

        </p>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div
            class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-700">

            {{ session('success') }}

        </div>

    @endif

    <div class="grid gap-8 xl:grid-cols-2">

        {{-- ===================================================== --}}
        {{-- PROFILE INFORMATION --}}
        {{-- ===================================================== --}}

        <x-ui.card>

            <div class="mb-8">

                <h2 class="text-xl font-bold">

                    Account Information

                </h2>

                <p class="mt-2 text-sm text-slate-500">

                    Informasi akun Platform Smart Workforce Management System.

                </p>

            </div>

            <div class="flex flex-col items-center">

                <div
                    class="flex h-24 w-24 items-center justify-center rounded-full bg-blue-600 text-4xl font-bold text-white">

                    {{ strtoupper(substr($user->username,0,1)) }}

                </div>

                <h2 class="mt-5 text-2xl font-bold">

                    {{ $user->username }}

                </h2>

                <p class="mt-1 text-slate-500">

                    {{ $user->email }}

                </p>

            </div>

            <div class="mt-10 space-y-5">

                <x-ui.detail-item

                    label="Username"

                    :value="$user->username"

                />

                <x-ui.detail-item

                    label="Email"

                    :value="$user->email"

                />

                <x-ui.detail-item

                    label="Role"

                    :value="$user->role->name"

                />

                <x-ui.detail-item

                    label="Status"

                    :value="$user->is_active ? 'Active' : 'Inactive'"

                />

                <x-ui.detail-item

                    label="Last Login"

                    :value="$user->last_login_at
                        ? $user->last_login_at->format('d M Y H:i')
                        : '-'"

                />

                <x-ui.detail-item

                    label="Created At"

                    :value="$user->created_at->format('d M Y H:i')"

                />

            </div>

        </x-ui.card>

        {{-- ===================================================== --}}
        {{-- CHANGE PASSWORD --}}
        {{-- ===================================================== --}}

        <x-ui.card id="account-settings">

            <div class="mb-8">

                <h2 class="text-xl font-bold">

                    Change Password

                </h2>

                <p class="mt-2 text-sm text-slate-500">

                    Gunakan password yang kuat agar akun Platform tetap aman.

                </p>

            </div>

            <form

                method="POST"

                action="{{ route('platform.profile.update') }}"

                class="space-y-6">

                @csrf

                @method('PUT')

                <x-ui.input

                    label="Current Password"

                    name="current_password"

                    type="password"

                    required

                />

                <x-ui.input

                    label="New Password"

                    name="password"

                    type="password"

                    required

                />

                <x-ui.input

                    label="Confirm Password"

                    name="password_confirmation"

                    type="password"

                    required

                />

                <div class="pt-2">

                    <x-ui.button type="submit">

                        <i
                            data-lucide="lock-keyhole"
                            class="h-5 w-5">
                        </i>

                        Update Password

                    </x-ui.button>

                </div>

            </form>

        </x-ui.card>

    </div>

</div>

@endsection