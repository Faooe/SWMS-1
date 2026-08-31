@extends('layouts.app')

@section('title', 'Premium Management')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-500">
                Kelola subscription plan seluruh company.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- Summary Cards --}}
    {{-- ========================================================= --}}

    @php
        $totalPremium = $summary['premium'];
        $totalFree = $summary['free'];
        $totalGo = $summary['go'];
        $totalPlus = $summary['plus'];
        $totalMax = $summary['max'];
    @endphp

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Total Premium</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100">
                    <i data-lucide="gem" class="h-5 w-5 text-amber-600"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">
                {{ $totalPremium }}
            </h2>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Free Plan</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                    <i data-lucide="package" class="h-5 w-5 text-slate-600"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">
                {{ $totalFree }}
            </h2>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Premium Go</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                    <i data-lucide="zap" class="h-5 w-5 text-blue-600"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">
                {{ $totalGo }}
            </h2>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Premium Plus</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100">
                    <i data-lucide="crown" class="h-5 w-5 text-purple-600"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">
                {{ $totalPlus }}
            </h2>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">Premium Max</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100">
                    <i data-lucide="sparkles" class="h-5 w-5 text-rose-600"></i>
                </div>
            </div>
            <h2 class="mt-3 text-3xl font-bold text-slate-800">
                {{ $totalMax }}
            </h2>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- Company Table --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl bg-white shadow-sm">
        <div class="border-b px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Company Subscriptions
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Company
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Plan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Employee Limit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Berakhir
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        @php
                            $isPremium = $company->subscription_plan !== 'Free';
                            $isExpiringSoon = $isPremium
                                && $company->subscription_end
                                && $company->subscription_end->diffInDays(now(), false) >= -7;
                        @endphp

                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    @if($company->logo)
                                        <img src="{{ secure_file_url($company->logo) }}" class="h-11 w-11 rounded-xl object-cover ring-1 ring-slate-200">
                                    @else
                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 font-bold text-blue-700">
                                            {{ strtoupper(substr($company->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-semibold text-slate-800">
                                            {{ $company->name }}
                                        </h3>
                                        <p class="text-sm text-slate-500">
                                            {{ $company->code }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                @if($company->subscription_plan === 'Free')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                        <i data-lucide="package" class="h-3.5 w-3.5"></i>
                                        Free
                                    </span>
                                @elseif($company->subscription_plan === 'Premium Go')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                        <i data-lucide="zap" class="h-3.5 w-3.5"></i>
                                        Premium Go
                                    </span>
                                @elseif($company->subscription_plan === 'Premium Plus')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-100 px-3 py-1.5 text-xs font-semibold text-purple-700">
                                        <i data-lucide="crown" class="h-3.5 w-3.5"></i>
                                        Premium Plus
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-700">
                                        <i data-lucide="sparkles" class="h-3.5 w-3.5"></i>
                                        Premium Max
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-sm font-semibold text-slate-800">
                                    {{ $company->max_employee }}
                                </span>
                                <span class="text-sm text-slate-500">
                                    karyawan
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                @if($company->subscription_end)
                                    <span class="text-sm {{ $isExpiringSoon ? 'font-semibold text-amber-600' : 'text-slate-600' }}">
                                        {{ $company->subscription_end->translatedFormat('d M Y') }}
                                    </span>
                                    @if($isExpiringSoon)
                                        <p class="mt-0.5 text-xs text-amber-600">
                                            Segera berakhir
                                        </p>
                                    @endif
                                @else
                                    <span class="text-sm text-slate-400">
                                        —
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <button
                                    type="button"
                                    onclick="document.getElementById('modal-plan-{{ $company->id }}').classList.remove('hidden'); document.getElementById('modal-plan-{{ $company->id }}').classList.add('flex')"
                                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                                    <i data-lucide="settings-2" class="h-4 w-4"></i>
                                    Kelola Plan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-sm text-slate-500">
                                Belum ada company.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($companies->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $companies->links() }}
            </div>
        @endif
    </div>

{{-- ========================================================= --}}
{{-- Billing Summary & Payment History --}}
{{-- ========================================================= --}}
<div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h2 class="text-lg font-bold text-slate-800">Billing Overview</h2>
        <p class="mt-1 text-sm text-slate-500">Ringkasan transaksi subscription dari Midtrans dan subscription yang perlu perhatian.</p>
    </div>
    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
        <i data-lucide="shield-check" class="h-3.5 w-3.5"></i>
        Settlement otomatis
    </span>
</div>

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Revenue Bulan Ini</p>
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i data-lucide="wallet-cards" class="h-4 w-4"></i></span>
        </div>
        <p class="mt-3 text-xl font-bold text-slate-800">Rp {{ number_format($summary['revenue_month'], 0, ',', '.') }}</p>
    </div>
    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Revenue</p>
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="badge-dollar-sign" class="h-4 w-4"></i></span>
        </div>
        <p class="mt-3 text-xl font-bold text-slate-800">Rp {{ number_format($summary['revenue_total'], 0, ',', '.') }}</p>
    </div>
    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Settled</p>
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-50 text-green-600"><i data-lucide="circle-check-big" class="h-4 w-4"></i></span>
        </div>
        <p class="mt-3 text-xl font-bold text-green-700">{{ $summary['settled_payments'] }}</p>
    </div>
    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Payment Pending</p>
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i data-lucide="hourglass" class="h-4 w-4"></i></span>
        </div>
        <p class="mt-3 text-xl font-bold text-amber-600">{{ $summary['pending_payments'] }}</p>
    </div>
    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Gagal / Expired</p>
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600"><i data-lucide="circle-x" class="h-4 w-4"></i></span>
        </div>
        <p class="mt-3 text-xl font-bold text-rose-600">{{ $summary['failed_payments'] }}</p>
    </div>
    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Berakhir ≤ 7 Hari</p>
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-50 text-purple-600"><i data-lucide="calendar-clock" class="h-4 w-4"></i></span>
        </div>
        <p class="mt-3 text-xl font-bold text-purple-700">{{ $summary['expiring_soon'] }}</p>
    </div>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm">
    <div class="border-b border-slate-100 px-6 py-5">
        <h2 class="text-lg font-semibold text-slate-800">Riwayat Pembayaran Midtrans</h2>
        <p class="mt-1 text-xs text-slate-500">Transaksi semua company. Update plan manual tidak dihitung sebagai revenue.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3">Company</th><th class="px-6 py-3">Order</th><th class="px-6 py-3">Plan</th><th class="px-6 py-3">Nominal</th><th class="px-6 py-3">Metode</th><th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($payments as $payment)
                    @php
                        $statusClass = match($payment->status) {
                            'settlement' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            default => 'bg-red-100 text-red-700',
                        };
                    @endphp
                    <tr>
                        <td class="px-6 py-4 text-sm"><strong class="text-slate-800">{{ $payment->company?->name ?? '—' }}</strong><br><span class="text-xs text-slate-500">{{ $payment->created_at?->translatedFormat('d M Y H:i') }}</span></td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $payment->order_id }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700"><strong>{{ $payment->plan }}</strong><br><span class="text-xs text-slate-500">{{ \App\Support\SubscriptionPaymentData::durationLabel($payment->duration) }}</span></td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $payment->payment_type ?: '—' }}</td>
                        <td class="px-6 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ \App\Support\SubscriptionPaymentData::statusLabel($payment->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">Belum ada transaksi subscription.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
        <div class="border-t border-slate-100 px-6 py-4">{{ $payments->appends(request()->except('payment_page'))->links() }}</div>
    @endif
</div>
</div>

{{-- ========================================================= --}}
{{-- Modals --}}
{{-- ========================================================= --}}

@foreach($companies as $company)
    <div
        id="modal-plan-{{ $company->id }}"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">
        
        <div class="w-full max-w-md rounded-3xl bg-white p-8 shadow-2xl">
            <div class="mb-6 flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Kelola Plan
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $company->name }}
                    </p>
                </div>
                <button
                    type="button"
                    onclick="document.getElementById('modal-plan-{{ $company->id }}').classList.add('hidden'); document.getElementById('modal-plan-{{ $company->id }}').classList.remove('flex')"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form
                method="POST"
                action="{{ route('platform.premium.update', $company) }}"
                class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Pilih Plan
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer rounded-2xl border-2 border-slate-200 p-4 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="plan" value="Premium Go" class="sr-only" required>
                            <i data-lucide="zap" class="h-5 w-5 text-blue-600"></i>
                            <p class="mt-2 text-sm font-semibold text-slate-800">Premium Go</p>
                            <p class="text-xs text-slate-500">{{ config('plans.Premium Go.max_employee') }} karyawan</p>
                        </label>

                        <label class="cursor-pointer rounded-2xl border-2 border-slate-200 p-4 transition has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50">
                            <input type="radio" name="plan" value="Premium Plus" class="sr-only" required>
                            <i data-lucide="crown" class="h-5 w-5 text-purple-600"></i>
                            <p class="mt-2 text-sm font-semibold text-slate-800">Premium Plus</p>
                            <p class="text-xs text-slate-500">{{ config('plans.Premium Plus.max_employee') }} karyawan</p>
                        </label>

                        <label class="cursor-pointer rounded-2xl border-2 border-slate-200 p-4 transition has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50">
                            <input type="radio" name="plan" value="Premium Max" class="sr-only" required>
                            <i data-lucide="sparkles" class="h-5 w-5 text-rose-600"></i>
                            <p class="mt-2 text-sm font-semibold text-slate-800">Premium Max</p>
                            <p class="text-xs text-slate-500">{{ config('plans.Premium Max.max_employee') }} karyawan</p>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Durasi
                    </label>
                    <select
                        name="duration"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        <option value="1_month">1 Bulan</option>
                        <option value="3_months">3 Bulan</option>
                        <option value="12_months">1 Tahun</option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Aktifkan Plan
                </button>
            </form>

            @if($company->subscription_plan !== 'Free')
                <form
                    method="POST"
                    action="{{ route('platform.premium.cancel', $company) }}"
                    class="mt-3"
                    onsubmit="return confirm('Kembalikan company ke Free plan?')">
                    @csrf
                    @method('PATCH')
                    <button
                        type="submit"
                        class="w-full rounded-xl border border-red-200 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                        Batalkan Premium
                    </button>
                </form>
            @endif
        </div>
    </div>
@endforeach

@endsection