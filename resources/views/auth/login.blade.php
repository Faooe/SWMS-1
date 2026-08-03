<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <form
                method="POST"
                action="{{ route('login.authenticate') }}"
                class="space-y-5"
                id="login-form"
            >
                @csrf
                <input type="hidden" name="login_mode" id="login-mode-field" value="{{ old('login_mode', 'email') }}">

                {{-- Toggle Mode Login --}}
                {{--
                    CATATAN: sengaja pakai vanilla JS (bukan Alpine x-data/x-if)
                    karena Alpine di project ini cuma di-boot lewat Livewire
                    (@livewireScripts) -- lihat resources/js/app.js. Halaman
                    login ini standalone (bukan Livewire component), jadi
                    Alpine tidak pernah jalan di sini kalau dipaksa dipakai.
                --}}
                <div class="grid grid-cols-2 gap-1 rounded-xl bg-slate-100 p-1">
                    <button
                        type="button"
                        id="login-tab-email"
                        onclick="swmsSwitchLoginMode('email')"
                        class="rounded-lg py-2 text-xs font-semibold transition bg-white text-blue-600 shadow-sm"
                    >
                        Email
                    </button>
                    <button
                        type="button"
                        id="login-tab-employee"
                        onclick="swmsSwitchLoginMode('employee')"
                        class="rounded-lg py-2 text-xs font-semibold transition text-slate-500"
                    >
                        NIP + Kode Company
                    </button>
                </div>

                {{-- Mode: Email (default) -- dipakai Platform Admin, Company
                     Admin, dan employee yang punya email terdaftar. --}}
                <div id="login-fields-email" class="space-y-1">
                    <x-ui.input
                        label="Email"
                        name="login"
                        type="email"
                        value="{{ old('login') }}"
                        placeholder="Masukkan Email"
                        class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                    />
                </div>

                {{-- Mode: NIP + Kode Company -- alternatif untuk employee
                     yang tidak/belum punya email (lihat
                     App\Services\AuthService::loginEmployeeWeb). --}}
                <div id="login-fields-employee" class="hidden space-y-4">
                    <div class="space-y-1">
                        <x-ui.input
                            label="Kode Company"
                            name="company_code"
                            type="text"
                            value="{{ old('company_code') }}"
                            placeholder="Contoh: ABC"
                            class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm uppercase transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        />
                    </div>
                    <div class="space-y-1">
                        <x-ui.input
                            label="NIP"
                            name="employee_number"
                            type="text"
                            value="{{ old('employee_number') }}"
                            placeholder="Masukkan NIP"
                            class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50"
                        />
                    </div>
                    <p class="text-xs text-slate-400">
                        Kode Company & NIP bisa ditanyakan ke HR/Admin perusahaan kamu.
                    </p>
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

            @if(config('services.firebase.web_api_key'))

                {{-- Divider --}}
                <div class="mt-6 flex items-center gap-3">
                    <div class="h-px flex-1 bg-slate-200"></div>
                    <span class="text-xs font-medium text-slate-400">ATAU</span>
                    <div class="h-px flex-1 bg-slate-200"></div>
                </div>

                {{-- Login dengan Google (Firebase SSO) --}}
                <button
                    type="button"
                    id="google-login-btn"
                    class="mt-6 flex w-full items-center justify-center gap-3 rounded-xl border border-slate-200 bg-white py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">

                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="" class="h-5 w-5">

                    <span id="google-login-btn-label">Login dengan Google</span>

                </button>

                <p id="google-login-error" class="mt-3 hidden text-center text-sm text-red-600"></p>

            @endif

            <script type="module">
                import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.1/firebase-app.js";
                import {
                    getAuth,
                    GoogleAuthProvider,
                    signInWithPopup,
                } from "https://www.gstatic.com/firebasejs/10.13.1/firebase-auth.js";

                // Sengaja pakai <script type="module"> langsung (bukan
                // lewat bundler Vite) -- konsisten dengan gaya halaman
                // login ini yang berdiri sendiri tanpa Livewire/Alpine
                // (lihat catatan soal toggle password & tab Email/NIP di
                // atas). Browser modern menjalankan ES module native
                // tanpa perlu bundler.
                @if(config('services.firebase.web_api_key'))
                    const firebaseConfig = {
                        apiKey: "{{ config('services.firebase.web_api_key') }}",
                        authDomain: "{{ config('services.firebase.auth_domain') }}",
                        projectId: "{{ config('services.firebase.project_id') }}",
                    };

                    const firebaseApp = initializeApp(firebaseConfig);
                    const firebaseAuthClient = getAuth(firebaseApp);
                    const googleProvider = new GoogleAuthProvider();

                    const googleBtn = document.getElementById('google-login-btn');
                    const googleBtnLabel = document.getElementById('google-login-btn-label');
                    const googleError = document.getElementById('google-login-error');

                    googleBtn.addEventListener('click', async () => {
                        googleError.classList.add('hidden');
                        googleBtn.disabled = true;
                        googleBtnLabel.textContent = 'Memproses...';

                        try {
                            const result = await signInWithPopup(firebaseAuthClient, googleProvider);
                            const idToken = await result.user.getIdToken();

                            const csrfToken = document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content');

                            const response = await fetch("{{ route('auth.firebase.login') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                body: JSON.stringify({ id_token: idToken }),
                            });

                            const data = await response.json();

                            if (response.ok && data.redirect_url) {
                                window.location.href = data.redirect_url;
                                return;
                            }

                            googleError.textContent = data.message || 'Login dengan Google gagal.';
                            googleError.classList.remove('hidden');

                        } catch (error) {
                            console.error('[Firebase Login]', error);
                            googleError.textContent = 'Login dengan Google gagal atau dibatalkan.';
                            googleError.classList.remove('hidden');
                        } finally {
                            googleBtn.disabled = false;
                            googleBtnLabel.textContent = 'Login dengan Google';
                        }
                    });
                @endif
            </script>

            <script>
                function swmsSwitchLoginMode(mode) {
                    document.getElementById('login-mode-field').value = mode;

                    const isEmail = mode === 'email';

                    document.getElementById('login-fields-email').classList.toggle('hidden', !isEmail);
                    document.getElementById('login-fields-employee').classList.toggle('hidden', isEmail);

                    const emailTab = document.getElementById('login-tab-email');
                    const employeeTab = document.getElementById('login-tab-employee');

                    emailTab.className = isEmail
                        ? 'rounded-lg py-2 text-xs font-semibold transition bg-white text-blue-600 shadow-sm'
                        : 'rounded-lg py-2 text-xs font-semibold transition text-slate-500';

                    employeeTab.className = !isEmail
                        ? 'rounded-lg py-2 text-xs font-semibold transition bg-white text-blue-600 shadow-sm'
                        : 'rounded-lg py-2 text-xs font-semibold transition text-slate-500';
                }

                // Kalau request sebelumnya (setelah validasi gagal, "old()")
                // mode-nya "employee", pastikan tab yang aktif ikut sesuai --
                // supaya user gak kebingungan tab Email yang aktif padahal
                // dia baru saja submit dari tab NIP.
                document.addEventListener('DOMContentLoaded', () => {
                    const savedMode = document.getElementById('login-mode-field').value;
                    if (savedMode === 'employee') {
                        swmsSwitchLoginMode('employee');
                    }
                });

                // Toggle Show/Hide Password.
                //
                // x-ui.input punya toggle mata bawaan pakai Alpine
                // (x-data/klik/x-show), tapi Alpine di project ini cuma
                // ikut ke-boot lewat Livewire -- lihat catatan di atas
                // & resources/js/app.js. Halaman ini bukan Livewire
                // component, jadi Alpine-nya gak pernah nyala dan
                // toggle mata jadi gak ngefek. Solusinya sama kayak tab
                // Email/NIP di atas: pasang listener vanilla JS sendiri di
                // sini, gak sentuh Alpine binding di komponennya (biar
                // toggle mata di halaman lain yang emang jalan lewat
                // Livewire tetap aman/gak kesenggol).
                document.addEventListener('DOMContentLoaded', () => {
                    const passwordInput = document.getElementById('password');
                    const toggleButton = document.getElementById('password-toggle');
                    const iconShow = document.getElementById('password-icon-show');
                    const iconHide = document.getElementById('password-icon-hide');

                    if (!passwordInput || !toggleButton) return;

                    toggleButton.addEventListener('click', () => {
                        const isHidden = passwordInput.type === 'password';

                        passwordInput.type = isHidden ? 'text' : 'password';

                        if (iconShow) iconShow.style.display = isHidden ? 'none' : '';
                        if (iconHide) iconHide.style.display = isHidden ? '' : 'none';
                    });
                });
            </script>
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