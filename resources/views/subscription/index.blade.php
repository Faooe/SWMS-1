@extends('layouts.app')

@section('title', 'Subscription & Billing')
@section('page-title', 'Subscription & Billing')

@php
    $durationLabels = [
        '1_month' => '1 Bulan',
        '3_months' => '3 Bulan',
        '12_months' => '1 Tahun',
    ];

    $durationShort = [
        '1_month' => 'Bulanan',
        '3_months' => '3 Bulan',
        '12_months' => 'Tahunan',
    ];

    $planIcons = [
        'Premium Go' => 'zap',
        'Premium Plus' => 'crown',
        'Premium Max' => 'sparkles',
    ];

    $planAccents = [
        'Premium Go' => 'blue',
        'Premium Plus' => 'violet',
        'Premium Max' => 'rose',
    ];

    $pricing = $plans->mapWithKeys(fn ($plan, $key) => [
        $key => $plan['price'] ?? [],
    ])->all();

    $initialPlan = $company->isPremium() && $plans->has($company->subscription_plan)
        ? $company->subscription_plan
        : 'Premium Go';

    $employeePercent = $company->max_employee > 0
        ? min(100, round(($lifecycle['employee_count'] / $company->max_employee) * 100))
        : 0;
@endphp

@section('content')
<div
    x-data="subscriptionPage(@js($initialPlan), @js($pricing))"
    class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm font-medium text-blue-600">
                <i data-lucide="credit-card" class="h-4 w-4"></i>
                Billing & Subscription
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Kelola Subscription</h1>
            <p class="mt-1 text-sm text-slate-500">
                Perpanjang atau ubah paket SWMS, pantau masa aktif, dan lihat seluruh riwayat pembayaran.
            </p>
        </div>
        <div class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-500 shadow-sm">
            <span class="h-2 w-2 rounded-full {{ config('services.midtrans.is_production') ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
            Midtrans {{ config('services.midtrans.is_production') ? 'Production' : 'Sandbox' }}
        </div>
    </div>

    {{-- Current plan + lifecycle --}}
    <div class="grid gap-5 xl:grid-cols-3">
        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
            <div class="border-b border-slate-100 p-6">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $company->isPremium() ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-600' }}">
                            <i data-lucide="{{ $company->subscription_plan === 'Premium Max' ? 'sparkles' : ($company->subscription_plan === 'Premium Plus' ? 'crown' : ($company->subscription_plan === 'Premium Go' ? 'zap' : 'package')) }}" class="h-6 w-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Plan Saat Ini</p>
                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                <h2 class="text-2xl font-bold text-slate-900">{{ $company->subscription_plan }}</h2>
                                <span class="rounded-full {{ $company->isPremium() ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-600 ring-1 ring-slate-200' }} px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide">
                                    {{ $company->isPremium() ? 'Aktif' : 'Free' }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $company->isPremium() ? 'Fitur premium aktif untuk company ini.' : 'Upgrade untuk membuka fitur premium SWMS.' }}
                            </p>
                        </div>
                    </div>

                    @if($company->subscription_end)
                        <div class="rounded-2xl bg-slate-50 px-4 py-3 text-left sm:text-right">
                            <p class="text-xs text-slate-500">Berlaku sampai</p>
                            <p class="mt-1 font-bold text-slate-800">{{ $company->subscription_end->translatedFormat('d M Y') }}</p>
                            <p class="mt-0.5 text-xs {{ $lifecycle['is_expiring_soon'] ? 'font-semibold text-amber-600' : 'text-slate-500' }}">
                                {{ $lifecycle['days_remaining'] !== null ? $lifecycle['days_remaining'].' hari tersisa' : '—' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid gap-px bg-slate-100 sm:grid-cols-3">
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                        <i data-lucide="users" class="h-4 w-4"></i>
                        Penggunaan Karyawan
                    </div>
                    <p class="mt-2 text-xl font-bold text-slate-900">{{ $lifecycle['employee_count'] }} <span class="text-sm font-medium text-slate-400">/ {{ $company->max_employee }}</span></p>
                    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full {{ $lifecycle['over_employee_limit'] ? 'bg-red-500' : ($employeePercent >= 80 ? 'bg-amber-500' : 'bg-blue-600') }}" style="width: {{ $employeePercent }}%"></div>
                    </div>
                </div>
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                        <i data-lucide="calendar-days" class="h-4 w-4"></i>
                        Tanggal Berakhir
                    </div>
                    <p class="mt-2 text-base font-bold text-slate-900">{{ $company->subscription_end?->translatedFormat('d M Y') ?? 'Tidak terbatas' }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ $company->isPremium() ? 'Perpanjangan plan yang sama menambah masa aktif.' : 'Free tidak memiliki masa kedaluwarsa.' }}</p>
                </div>
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                        <i data-lucide="shield-check" class="h-4 w-4"></i>
                        Status Lifecycle
                    </div>
                    <p class="mt-2 text-base font-bold {{ $lifecycle['is_expiring_soon'] ? 'text-amber-600' : 'text-emerald-600' }}">
                        {{ $lifecycle['is_expiring_soon'] ? 'Segera Berakhir' : ($company->isPremium() ? 'Aktif & Aman' : 'Free Plan') }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Reminder otomatis H-7, H-3, dan H-1.</p>
                </div>
            </div>

            @if($lifecycle['is_expiring_soon'])
                <div class="m-5 flex gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                    <i data-lucide="triangle-alert" class="mt-0.5 h-5 w-5 shrink-0"></i>
                    <div><strong>Subscription segera berakhir.</strong> Perpanjang sebelum tanggal berakhir agar fitur premium tetap aktif.</div>
                </div>
            @endif

            @if($lifecycle['over_employee_limit'])
                <div class="m-5 flex gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <i data-lucide="users-round" class="mt-0.5 h-5 w-5 shrink-0"></i>
                    <div>Jumlah karyawan melebihi limit plan. Data tetap aman, tetapi penambahan karyawan baru diblokir sampai limit mencukupi.</div>
                </div>
            @endif
        </section>

        <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                <i data-lucide="refresh-cw" class="h-5 w-5"></i>
            </div>
            <h3 class="mt-4 font-bold text-slate-900">Lifecycle Otomatis</h3>
            <p class="mt-1 text-sm leading-6 text-slate-500">SWMS menjaga status subscription tanpa approval manual Platform Admin.</p>

            <div class="mt-5 space-y-4">
                @foreach([['H-7','Reminder pertama'],['H-3','Reminder kedua'],['H-1','Reminder terakhir']] as [$day, $label])
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xs font-bold text-blue-600">{{ $day }}</span>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">{{ $label }}</p>
                            <p class="text-xs text-slate-400">Sebelum masa aktif berakhir</p>
                        </div>
                    </div>
                @endforeach
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600"><i data-lucide="arrow-down-circle" class="h-4 w-4"></i></span>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Expired → Free</p>
                        <p class="text-xs text-slate-400">Data company dan employee tidak dihapus</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    {{-- Plan selection --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-blue-600">Pilih Paket</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900">Perpanjang atau ubah subscription</h2>
                <p class="mt-1 text-sm text-slate-500">Plan yang sama akan memperpanjang sisa masa aktif. Plan berbeda aktif setelah pembayaran settlement.</p>
            </div>
            <div class="rounded-xl bg-slate-50 px-3 py-2 text-xs text-slate-500">
                Maksimum sesuai limit plan
            </div>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-3">
            @foreach($plans as $planKey => $planData)
                @php
                    $accent = $planAccents[$planKey] ?? 'blue';
                    $isCurrent = $company->subscription_plan === $planKey;
                @endphp
                <label
                    class="relative cursor-pointer overflow-hidden rounded-2xl border-2 p-5 transition duration-200 hover:-translate-y-0.5 hover:shadow-md"
                    :class="selectedPlan === @js($planKey) ? 'border-blue-500 bg-blue-50/40 shadow-sm' : 'border-slate-200 bg-white'">
                    <input type="radio" name="plan" value="{{ $planKey }}" x-model="selectedPlan" class="sr-only">

                    <div class="flex items-start justify-between gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $accent === 'rose' ? 'bg-rose-50 text-rose-600' : ($accent === 'violet' ? 'bg-violet-50 text-violet-600' : 'bg-blue-50 text-blue-600') }}">
                            <i data-lucide="{{ $planIcons[$planKey] ?? 'gem' }}" class="h-5 w-5"></i>
                        </div>
                        <div class="flex flex-wrap justify-end gap-1.5">
                            @if($isCurrent)
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-emerald-700 ring-1 ring-emerald-200">Plan Aktif</span>
                            @endif
                            <span x-show="selectedPlan === @js($planKey)" x-cloak class="rounded-full bg-blue-600 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white">Dipilih</span>
                        </div>
                    </div>

                    <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $planData['label'] }}</h3>
                    <p class="mt-1 text-sm text-slate-500">Hingga {{ number_format($planData['max_employee'], 0, ',', '.') }} karyawan</p>
                    <div class="mt-5 border-t border-slate-100 pt-4">
                        <p class="text-xs text-slate-400">Mulai dari</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">Rp {{ number_format($planData['price']['1_month'], 0, ',', '.') }} <span class="text-xs font-medium text-slate-400">/ bulan</span></p>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="mt-6 grid gap-5 xl:grid-cols-[1fr_360px]">
            <div>
                <label class="mb-3 block text-sm font-semibold text-slate-700">Durasi Subscription</label>
                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach($durationLabels as $value => $label)
                        <label
                            class="cursor-pointer rounded-2xl border-2 p-4 transition"
                            :class="selectedDuration === @js($value) ? 'border-blue-500 bg-blue-50/40' : 'border-slate-200 bg-white hover:border-slate-300'">
                            <input type="radio" name="duration" value="{{ $value }}" x-model="selectedDuration" class="sr-only">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $label }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $durationShort[$value] }}</p>
                                </div>
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border-2" :class="selectedDuration === @js($value) ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300'">
                                    <i x-show="selectedDuration === @js($value)" data-lucide="check" class="h-3 w-3"></i>
                                </span>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="mt-4 flex gap-3 rounded-2xl border border-blue-100 bg-blue-50/60 p-4 text-sm text-blue-800">
                    <i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0"></i>
                    <p x-text="renewalMessage"></p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Ringkasan Pembayaran</p>
                        <p class="mt-1 text-xs text-slate-400">Periksa kembali pilihan sebelum melanjutkan.</p>
                    </div>
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="receipt" class="h-5 w-5"></i>
                    </div>
                </div>

                <div class="mt-5 space-y-3 border-b border-slate-100 pb-4 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500">Plan</span>
                        <strong class="text-right font-semibold text-slate-900" x-text="selectedPlan"></strong>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500">Durasi</span>
                        <strong class="text-right font-semibold text-slate-900" x-text="durationLabel"></strong>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500">Pembayaran</span>
                        <span class="inline-flex items-center gap-1.5 font-medium text-slate-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Midtrans
                        </span>
                    </div>
                </div>

                <div class="mt-4 flex items-end justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3.5">
                    <span class="text-sm font-medium text-slate-500">Total</span>
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900" x-text="formatRupiah(totalPrice)"></span>
                </div>

                <p x-show="errorMessage" x-cloak x-text="errorMessage" class="mt-4 rounded-xl border border-red-100 bg-red-50 p-3 text-xs font-medium text-red-700"></p>

                <button
                    type="button"
                    @click="checkout()"
                    :disabled="loading || !selectedPlan"
                    class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100 disabled:cursor-not-allowed disabled:opacity-60">
                    <i x-show="!loading" data-lucide="credit-card" class="h-4 w-4"></i>
                    <svg x-show="loading" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    <span x-text="loading ? 'Membuka Midtrans...' : 'Lanjutkan Pembayaran'"></span>
                </button>

                <div class="mt-3 flex items-start justify-center gap-1.5 text-center text-[11px] leading-5 text-slate-400">
                    <i data-lucide="shield-check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-emerald-500"></i>
                    <p>Plan diperbarui otomatis setelah transaksi dikonfirmasi Midtrans.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Billing history --}}
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600"><i data-lucide="receipt-text" class="h-4 w-4"></i></div>
                    <div>
                        <h2 class="font-bold text-slate-900">Riwayat Pembayaran</h2>
                        <p class="mt-0.5 text-xs text-slate-500">Transaksi Midtrans company ini.</p>
                    </div>
                </div>
            </div>
            <span class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-500">{{ $payments->total() }} transaksi</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/80 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-6 py-3.5">Order ID</th>
                        <th class="px-6 py-3.5">Plan</th>
                        <th class="px-6 py-3.5">Metode</th>
                        <th class="px-6 py-3.5">Nominal</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        @php
                            $statusLabel = \App\Support\SubscriptionPaymentData::statusLabel($payment->status);
                            $durationLabel = \App\Support\SubscriptionPaymentData::durationLabel($payment->duration);
                            $statusClass = match($payment->status) {
                                'settlement' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                default => 'bg-red-50 text-red-700 ring-red-200',
                            };
                        @endphp
                        <tr class="transition hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                <strong class="block font-semibold text-slate-700">{{ $payment->created_at?->translatedFormat('d M Y') }}</strong>
                                <span class="text-xs text-slate-400">{{ $payment->created_at?->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $payment->order_id }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700"><strong>{{ $payment->plan }}</strong><br><span class="text-xs text-slate-400">{{ $durationLabel }}</span></td>
                            <td class="px-6 py-4 text-sm capitalize text-slate-600">{{ str_replace('_', ' ', $payment->payment_type ?: '—') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-slate-900">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold ring-1 {{ $statusClass }}">{{ $statusLabel }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><i data-lucide="receipt" class="h-5 w-5"></i></div>
                                <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada riwayat pembayaran</p>
                                <p class="mt-1 text-xs text-slate-400">Transaksi pertama akan tampil di sini setelah checkout dibuat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            @php
                $payments->appends(request()->except('payment_page'));
            @endphp
            <div class="flex flex-col gap-3 border-t border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-slate-500">
                    Menampilkan <strong class="text-slate-700">{{ $payments->firstItem() }}–{{ $payments->lastItem() }}</strong>
                    dari <strong class="text-slate-700">{{ $payments->total() }}</strong> transaksi
                </p>
                <div class="flex items-center gap-2">
                    @if($payments->onFirstPage())
                        <span class="inline-flex cursor-not-allowed items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-300">
                            <i data-lucide="chevron-left" class="h-3.5 w-3.5"></i> Sebelumnya
                        </span>
                    @else
                        <a href="{{ $payments->previousPageUrl() }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                            <i data-lucide="chevron-left" class="h-3.5 w-3.5"></i> Sebelumnya
                        </a>
                    @endif
                    <span class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600">Halaman {{ $payments->currentPage() }} / {{ $payments->lastPage() }}</span>
                    @if($payments->hasMorePages())
                        <a href="{{ $payments->nextPageUrl() }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                            Selanjutnya <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                        </a>
                    @else
                        <span class="inline-flex cursor-not-allowed items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-300">
                            Selanjutnya <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script
    src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
<script>
    function subscriptionPage(initialPlan, pricing) {
        return {
            selectedPlan: initialPlan,
            selectedDuration: '1_month',
            pricing,
            loading: false,
            errorMessage: null,
            currentPlan: @js($company->subscription_plan),

            get totalPrice() {
                return Number(this.pricing?.[this.selectedPlan]?.[this.selectedDuration] ?? 0);
            },

            get durationLabel() {
                return {
                    '1_month': '1 Bulan',
                    '3_months': '3 Bulan',
                    '12_months': '1 Tahun',
                }[this.selectedDuration] ?? '-';
            },

            get renewalMessage() {
                if (this.selectedPlan === this.currentPlan && this.currentPlan !== 'Free') {
                    return 'Kamu memilih plan yang sama. Masa aktif baru akan ditambahkan setelah masa aktif yang sekarang.';
                }

                if (this.currentPlan === 'Free') {
                    return 'Plan premium akan aktif otomatis setelah pembayaran dikonfirmasi Midtrans.';
                }

                return 'Kamu memilih plan yang berbeda. Plan baru akan berlaku setelah pembayaran dikonfirmasi Midtrans.';
            },

            formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0,
                }).format(value || 0);
            },

            checkout() {
                if (!this.selectedPlan || this.loading) return;

                this.loading = true;
                this.errorMessage = null;

                fetch(@js(route('subscription.checkout')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        plan: this.selectedPlan,
                        duration: this.selectedDuration,
                    }),
                })
                .then(async (response) => {
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal membuat transaksi.');
                    }

                    return data;
                })
                .then((data) => {
                    this.loading = false;

                    if (!data.snap_token) {
                        this.errorMessage = 'Snap token tidak diterima dari server.';
                        return;
                    }

                    if (!window.snap) {
                        this.errorMessage = 'Midtrans Snap belum siap. Muat ulang halaman lalu coba lagi.';
                        return;
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: () => window.location = @js(route('subscription.finish')),
                        onPending: () => window.location = @js(route('subscription.finish')),
                        onError: () => {
                            this.errorMessage = 'Pembayaran gagal. Silakan coba lagi.';
                        },
                        onClose: () => {},
                    });
                })
                .catch((error) => {
                    this.loading = false;
                    this.errorMessage = error.message || 'Terjadi kesalahan saat membuat transaksi.';
                });
            },
        };
    }
</script>
@endpush
