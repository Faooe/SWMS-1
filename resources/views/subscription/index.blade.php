@extends('layouts.app')

@section('title', 'Subscription')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Subscription & Billing</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola paket langganan dan lihat riwayat pembayaran company kamu.</p>
    </div>

    <div class="grid gap-5 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm lg:col-span-2">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Plan Saat Ini</p>
                    <h2 class="mt-1 text-2xl font-bold text-slate-800">{{ $company->subscription_plan }}</h2>
                </div>
                <span class="rounded-full {{ $company->isPremium() ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-bold">
                    {{ $company->isPremium() ? 'PREMIUM' : 'FREE' }}
                </span>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs text-slate-500">Limit Karyawan</p>
                    <p class="mt-1 font-bold text-slate-800">{{ $lifecycle['employee_count'] }} / {{ $company->max_employee }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs text-slate-500">Berlaku Sampai</p>
                    <p class="mt-1 font-bold text-slate-800">{{ $company->subscription_end?->translatedFormat('d M Y') ?? 'Tidak terbatas' }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs text-slate-500">Sisa Masa Aktif</p>
                    <p class="mt-1 font-bold {{ $lifecycle['is_expiring_soon'] ? 'text-amber-600' : 'text-slate-800' }}">
                        {{ $lifecycle['days_remaining'] !== null ? $lifecycle['days_remaining'].' hari' : '—' }}
                    </p>
                </div>
            </div>

            @if($lifecycle['is_expiring_soon'])
                <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                    <strong>Subscription segera berakhir.</strong> Perpanjang sebelum tanggal berakhir agar fitur premium tetap aktif.
                </div>
            @endif

            @if($lifecycle['over_employee_limit'])
                <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    Jumlah karyawan saat ini melebihi limit plan. Data karyawan tetap aman, tetapi penambahan karyawan baru diblokir sampai limit mencukupi.
                </div>
            @endif

            <p class="mt-5 text-sm text-slate-500">Upgrade/perpanjang plan melalui tombol subscription di pojok kanan bawah. Pembayaran yang berhasil akan aktif otomatis setelah status Midtrans terkonfirmasi.</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-700">Lifecycle Otomatis</p>
            <div class="mt-4 space-y-3 text-sm text-slate-600">
                <div class="flex gap-3"><span class="font-bold text-blue-600">H-7</span><span>Reminder masa aktif.</span></div>
                <div class="flex gap-3"><span class="font-bold text-blue-600">H-3</span><span>Reminder kedua.</span></div>
                <div class="flex gap-3"><span class="font-bold text-blue-600">H-1</span><span>Reminder terakhir.</span></div>
                <div class="flex gap-3"><span class="font-bold text-slate-700">Expired</span><span>Otomatis kembali ke Free. Data tidak dihapus.</span></div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="font-bold text-slate-800">Riwayat Pembayaran Midtrans</h2>
            <p class="mt-1 text-xs text-slate-500">Perubahan plan manual oleh Platform Admin tidak termasuk transaksi pembayaran.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Order ID</th>
                        <th class="px-6 py-3">Plan</th>
                        <th class="px-6 py-3">Metode</th>
                        <th class="px-6 py-3">Nominal</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        @php
                            $statusLabel = \App\Support\SubscriptionPaymentData::statusLabel($payment->status);
                            $durationLabel = \App\Support\SubscriptionPaymentData::durationLabel($payment->duration);
                            $statusClass = match($payment->status) {
                                'settlement' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-amber-100 text-amber-700',
                                default => 'bg-red-100 text-red-700',
                            };
                        @endphp
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $payment->created_at?->translatedFormat('d M Y H:i') }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $payment->order_id }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700"><strong>{{ $payment->plan }}</strong><br><span class="text-xs text-slate-500">{{ $durationLabel }}</span></td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $payment->payment_type ?: '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-800">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">Belum ada riwayat pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">{{ $payments->links() }}</div>
        @endif
    </div>
</div>
@endsection
