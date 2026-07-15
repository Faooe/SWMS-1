<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Smart Workforce Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="min-h-screen bg-slate-100 font-[Inter]">

<div class="grid min-h-screen lg:grid-cols-2">

    {{-- ========================= --}}
    {{-- LEFT SIDE --}}
    {{-- ========================= --}}

    <div class="flex items-center justify-center p-10 lg:p-16">

        <div class="w-full max-w-md">

            {{-- Logo --}}
            <div class="mb-10">

                <h1 class="text-4xl font-extrabold tracking-tight text-slate-800">

                    SWMS

                </h1>

                <p class="mt-3 text-slate-500">

                    Smart Workforce Management System

                </p>

            </div>

            {{-- Error --}}
            @if ($errors->any())

                <div
                    class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-600">

                    {{ $errors->first() }}

                </div>

            @endif

            {{-- Login Form --}}
            <form
                method="POST"
                action="{{ route('login.authenticate') }}"
                class="space-y-6"
            >

                @csrf

                <x-ui.input
                    label="Username / Email"
                    name="login"
                    placeholder="Masukkan Username atau Email"
                />

                <x-ui.input
                    label="Password"
                    name="password"
                    type="password"
                />

                {{-- Remember --}}
                <div class="flex items-center justify-between">

                    <label
                        class="flex items-center gap-2 text-sm text-slate-600">

                        <input
                            type="checkbox"
                            name="remember"
                            class="rounded border-slate-300">

                        Remember Me

                    </label>

                    <a
                        href="#"
                        class="text-sm font-medium text-blue-600 hover:text-blue-700">

                        Lupa Password?

                    </a>

                </div>

                <x-ui.button
                    type="submit"
                    class="w-full">

                    Sign In

                </x-ui.button>

            </form>

        </div>

    </div>

    {{-- ========================= --}}
    {{-- RIGHT SIDE --}}
    {{-- ========================= --}}

    <div
        class="relative hidden overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-slate-900 lg:flex">

        {{-- Background Blur --}}
        <div
            class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-cyan-400/20 blur-3xl">

        </div>

        <div
            class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-blue-500/20 blur-3xl">

        </div>

        <div
            class="relative z-10 flex flex-col justify-center px-20 text-white">

            <span
                class="mb-4 w-fit rounded-full bg-white/20 px-4 py-2 text-sm">

                Enterprise Human Resource Platform

            </span>

            <h2
                class="text-5xl font-extrabold leading-tight">

                Welcome Back 👋

            </h2>

            <p
                class="mt-8 text-lg leading-8 text-blue-100">

                Kelola seluruh aktivitas perusahaan mulai dari
                absensi, data karyawan, penugasan, shift,
                hingga laporan secara real-time
                dalam satu platform terintegrasi.

            </p>

            {{-- Feature List --}}
            <div class="mt-14 space-y-5">

                <div class="flex items-center gap-3">

                    <div
                        class="h-3 w-3 rounded-full bg-green-400">

                    </div>

                    <span>Employee Management</span>

                </div>

                <div class="flex items-center gap-3">

                    <div
                        class="h-3 w-3 rounded-full bg-green-400">

                    </div>

                    <span>Attendance Monitoring</span>

                </div>

                <div class="flex items-center gap-3">

                    <div
                        class="h-3 w-3 rounded-full bg-green-400">

                    </div>

                    <span>Assignment Tracking</span>

                </div>

                <div class="flex items-center gap-3">

                    <div
                        class="h-3 w-3 rounded-full bg-green-400">

                    </div>

                    <span>Analytics & Reporting</span>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>