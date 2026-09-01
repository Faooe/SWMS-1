@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="space-y-8">

    <x-ui.page-header
        title="My Profile"
        description="Kelola informasi akun Platform dan keamanan password kamu.">
    </x-ui.page-header>

    @if(session('success'))

        <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">

            <i data-lucide="check-circle-2" class="h-5 w-5 shrink-0"></i>

            {{ session('success') }}

        </div>

    @endif

    <div class="mb-6 flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5">
    @if($user->profile_photo)
        <img src="{{ secure_file_url($user->profile_photo) }}" class="h-20 w-20 rounded-full object-cover ring-4 ring-blue-50">
    @else
        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-600">{{ strtoupper(substr($user->username, 0, 1)) }}</div>
    @endif
    <div>
        <p class="font-bold text-slate-800">Foto Profile</p>
        <p class="mb-3 text-sm text-slate-500">Foto ini mewakili akun Platform Admin kamu.</p>
        <form action="{{ route('platform.profile.photo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                <i data-lucide="camera" class="h-4 w-4"></i> Ganti Foto
                <input type="file" name="photo" accept="image/*" class="hidden" data-compress-image data-auto-submit="true">
            </label>
        </form>
    </div>
</div>

<div class="grid gap-8 xl:grid-cols-3">

        {{-- ===================================================== --}}
        {{-- PROFILE SUMMARY --}}
        {{-- ===================================================== --}}

        <x-ui.card class="h-fit xl:col-span-1">

            <div class="flex flex-col items-center text-center">

                @if($user->profile_photo)
                    <img src="{{ secure_file_url($user->profile_photo) }}" class="h-24 w-24 rounded-full object-cover ring-4 ring-blue-50">
                @else
                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-blue-600 text-3xl font-bold text-white">
                        {{ strtoupper(substr($user->username, 0, 1)) }}
                    </div>
                @endif

                <h2 class="mt-5 text-2xl font-bold text-slate-800">

                    {{ $user->username }}

                </h2>

                <p class="mt-1 break-all text-slate-500">

                    {{ $user->email }}

                </p>

                <div class="mt-4">

                    <x-ui.badge color="blue">

                        {{ $user->role->name }}

                    </x-ui.badge>

                </div>

            </div>

            <div class="mt-8 space-y-5 border-t border-slate-100 pt-8">

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
        {{-- ACCOUNT & PASSWORD --}}
        {{-- ===================================================== --}}

        <div class="space-y-8 xl:col-span-2">

            {{-- Account Information (read-only) --}}
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

                            Informasi akun Platform Smart Workforce Management System.

                        </p>

                    </div>

                </div>

                <div class="grid gap-5 md:grid-cols-2">

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
                        label="Created At"
                        :value="$user->created_at->format('d M Y H:i')"
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

                            Gunakan password yang kuat agar akun Platform tetap aman.

                        </p>

                    </div>

                </div>

                <form
                    method="POST"
                    action="{{ route('platform.profile.update') }}"
                    class="space-y-5">

                    @csrf
                    @method('PUT')

                    <x-ui.input
                        label="Current Password"
                        name="current_password"
                        type="password"
                        required
                    />

                    <div class="grid gap-5 md:grid-cols-2">

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

                    </div>

                    <div class="flex justify-end pt-2">

                        <x-ui.button type="submit">

                            <i data-lucide="lock-keyhole" class="h-5 w-5"></i>

                            Update Password

                        </x-ui.button>

                    </div>

                </form>

            </x-ui.card>

        </div>

    </div>

</div>

@endsection
