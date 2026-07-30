<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Workforce Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons untuk estetika list fitur -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-screen bg-white font-[Inter] antialiased">

<div class="grid min-h-screen lg:grid-cols-2">

    {{-- ========================================================= --}}
    {{-- LEFT SIDE: Form Login --}}
    {{-- ========================================================= --}}
    <div class="flex flex-col justify-between p-8 sm:p-12 lg:p-20 bg-white">
        
        <!-- Header Kecil / Logo Brand -->
        <div class="flex items-center gap-2.5">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 font-black text-white text-xl shadow-md shadow-blue-200">
                S
            </div>
            <span class="text-lg font-bold tracking-tight text-slate-800">SWMS</span>
        </div>

        <!-- Kontainer Utama Form (Centered) -->
        <div class="mx-auto my-auto w-full max-w-md py-12">
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">
                    Selamat Datang
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Silakan masuk untuk mengelola dashboard kerja Anda.
                </p>
            </div>

            {{-- Error Flash Message --}}
            @if ($errors->any())
                <div class="mb-6 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm animate-pulse">
                    <i data-lucide="alert-circle" class="h-5 w-5 shrink-0 text-red-600"></i>
                    <p class="font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login.authenticate') }}" class="space-y-5">
                @csrf

                <div class="space-y-1">
                    <x-ui.input
                        label="Username / Email"
                        name="login"
                        placeholder="Masukkan Username atau Email"
                        class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                    />
                </div>

                <div class="space-y-1">
                    <x-ui.input
                        label="Password"
                        name="password"
                        type="password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                    />
                </div>

                {{-- Remember Me & Lupa Password --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex cursor-pointer items-center gap-2.5 text-sm select-none text-slate-600">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded-md border-slate-300 text-blue-600 transition focus:ring-blue-500/30">
                        <span>Ingat Saya</span>
                    </label>

                    <a href="#" class="text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                        Lupa Password?
                    </a>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <x-ui.button
                        type="submit"
                        class="w-full justify-center rounded-xl bg-blue-600 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 hover:shadow-none">
                        Masuk ke Platform
                    </x-ui.button>
                </div>
            </form>
        </div>

        <!-- Footer Hak Cipta Ringan -->
        <div class="text-xs text-slate-400">
            &copy; 2026 Smart Workforce Management System. All rights reserved.
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- RIGHT SIDE: Visual Showcase (Hidden on Mobile) --}}
    {{-- ========================================================= --}}
    <div class="relative hidden overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-900 lg:flex lg:flex-col lg:justify-center px-20">
        
        <!-- Ornamen Dekoratif Glow Efek -->
        <div class="absolute -left-16 -top-16 h-96 w-96 rounded-full bg-blue-600/20 blur-[120px]"></div>
        <div class="absolute bottom-10 right-10 h-80 w-80 rounded-full bg-indigo-500/20 blur-[100px]"></div>

        <div class="relative z-10 max-w-lg text-white">
            <span class="inline-flex items-center gap-1.5 mb-6 rounded-full bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-blue-200 backdrop-blur-md">
                <i data-lucide="shield-check" class="h-3.5 w-3.5 text-blue-400"></i>
                Enterprise Human Resource Platform
            </span>

            <h2 class="text-4xl lg:text-5xl font-extrabold leading-[1.15] tracking-tight">
                Kelola Produktivitas Karyawan Secara Real-Time.
            </h2>

            <p class="mt-6 text-base lg:text-lg leading-relaxed text-slate-300/90">
                Pantau seluruh aktivitas perusahaan mulai dari absensi berbasis geofencing polygon, penugasan tim, penjadwalan dinamis, hingga otomasi laporan performa dalam satu ekosistem terpusat.
            </p>

            {{-- Feature List dengan Komponen Visual Lebih Hidup --}}
            <div class="mt-12 grid grid-cols-2 gap-6 border-t border-white/10 pt-8">
                
                <div class="flex items-start gap-3">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-emerald-500/20 text-emerald-400">
                        <i data-lucide="check" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white text-sm">Employee Database</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Struktur organisasi ringkas.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-emerald-500/20 text-emerald-400">
                        <i data-lucide="check" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white text-sm">Geofencing Attendance</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Presisi koordinat polygon.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-emerald-500/20 text-emerald-400">
                        <i data-lucide="check" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white text-sm">Assignment Tracker</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Delegasi tugas real-time.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-emerald-500/20 text-emerald-400">
                        <i data-lucide="check" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white text-sm">Automated Analytics</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Ekspor laporan instan.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- Inisialisasi Lucide Icons -->
<script>
    lucide.createIcons();
</script>
</body>

</html>