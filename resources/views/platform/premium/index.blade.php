@extends('layouts.app')

@section('title', 'Premium Management')

@section('content')
@php
    $planCards = [
        'Premium Go' => ['icon' => 'zap', 'wrap' => 'hover:border-blue-200 hover:bg-blue-50/40 has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50', 'iconClass' => 'bg-blue-50 text-blue-600'],
        'Premium Plus' => ['icon' => 'crown', 'wrap' => 'hover:border-violet-200 hover:bg-violet-50/40 has-[:checked]:border-violet-400 has-[:checked]:bg-violet-50', 'iconClass' => 'bg-violet-50 text-violet-600'],
        'Premium Max' => ['icon' => 'sparkles', 'wrap' => 'hover:border-rose-200 hover:bg-rose-50/40 has-[:checked]:border-rose-400 has-[:checked]:bg-rose-50', 'iconClass' => 'bg-rose-50 text-rose-600'],
    ];
    $planMeta = [
        'Free' => ['icon' => 'package', 'badge' => 'bg-slate-100 text-slate-600', 'iconBg' => 'bg-slate-100 text-slate-600'],
        'Premium Go' => ['icon' => 'zap', 'badge' => 'bg-blue-50 text-blue-700', 'iconBg' => 'bg-blue-50 text-blue-600'],
        'Premium Plus' => ['icon' => 'crown', 'badge' => 'bg-violet-50 text-violet-700', 'iconBg' => 'bg-violet-50 text-violet-600'],
        'Premium Max' => ['icon' => 'sparkles', 'badge' => 'bg-rose-50 text-rose-700', 'iconBg' => 'bg-rose-50 text-rose-600'],
    ];
@endphp

<div class="space-y-6">
    <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-blue-600">
                <i data-lucide="gem" class="h-4 w-4"></i>
                <span>Platform Workspace</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-950">Premium Management</h1>
            <p class="mt-1 max-w-2xl text-sm text-slate-500">Pantau distribusi plan, lifecycle subscription, dan transaksi seluruh company dalam satu area kerja.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            Lifecycle aktif
        </span>
    </section>

    @if(session('success'))
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800">
            <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-white/80 text-emerald-600"><i data-lucide="circle-check" class="h-4 w-4"></i></span>
            <p class="pt-1 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="chart-no-axes-combined" class="h-5 w-5"></i></span>
                <div>
                    <h2 class="font-bold text-slate-900">Ringkasan Plan</h2>
                    <p class="text-xs text-slate-500">Distribusi subscription seluruh company.</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 divide-x divide-y divide-slate-100 sm:grid-cols-5 sm:divide-y-0">
            @foreach([
                ['Total Premium', $summary['premium'], 'gem', 'text-amber-600', 'bg-amber-50'],
                ['Free', $summary['free'], 'package', 'text-slate-600', 'bg-slate-100'],
                ['Premium Go', $summary['go'], 'zap', 'text-blue-600', 'bg-blue-50'],
                ['Premium Plus', $summary['plus'], 'crown', 'text-violet-600', 'bg-violet-50'],
                ['Premium Max', $summary['max'], 'sparkles', 'text-rose-600', 'bg-rose-50'],
            ] as [$label, $value, $icon, $textColor, $iconBg])
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-semibold text-slate-500">{{ $label }}</p>
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $iconBg }} {{ $textColor }}"><i data-lucide="{{ $icon }}" class="h-4 w-4"></i></span>
                    </div>
                    <p class="mt-3 text-2xl font-bold tracking-tight text-slate-950">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
        <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h2 class="font-bold text-slate-900">Subscription Company</h2>
                <p class="mt-1 text-xs text-slate-500">Kelola plan dan masa aktif setiap company.</p>
            </div>
            <div class="relative w-full sm:w-80">
                <i data-lucide="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input id="premium-company-search" type="search" placeholder="Cari nama atau kode company" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-50">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-3.5">Company</th>
                        <th class="px-6 py-3.5">Plan</th>
                        <th class="px-6 py-3.5">Kapasitas</th>
                        <th class="px-6 py-3.5">Masa Aktif</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="premium-company-table" class="divide-y divide-slate-100">
                    @forelse($companies as $company)
                        @php
                            $meta = $planMeta[$company->subscription_plan] ?? $planMeta['Free'];
                            $isPremium = $company->subscription_plan !== 'Free';
                            $daysRemaining = $company->subscription_end ? now()->startOfDay()->diffInDays($company->subscription_end->copy()->startOfDay(), false) : null;
                            $isExpiringSoon = $isPremium && $daysRemaining !== null && $daysRemaining >= 0 && $daysRemaining <= 7;
                        @endphp
                        <tr class="premium-company-row transition hover:bg-slate-50/60" data-search="{{ strtolower($company->name.' '.$company->code) }}">
                            <td class="px-6 py-4">
                                <div class="flex min-w-56 items-center gap-3">
                                    @if($company->logo)
                                        <img src="{{ secure_file_url($company->logo) }}" alt="{{ $company->name }}" class="h-10 w-10 rounded-xl border border-slate-200 object-cover">
                                    @else
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-900">{{ $company->name }}</p>
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $company->code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1.5 text-xs font-semibold {{ $meta['badge'] }}">
                                    <i data-lucide="{{ $meta['icon'] }}" class="h-3.5 w-3.5"></i>{{ $company->subscription_plan }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <i data-lucide="users" class="h-4 w-4 text-slate-400"></i>
                                    <span><strong class="font-bold text-slate-800">{{ $company->max_employee }}</strong> employee</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($company->subscription_end)
                                    <p class="text-sm font-semibold {{ $isExpiringSoon ? 'text-amber-600' : 'text-slate-700' }}">{{ $company->subscription_end->translatedFormat('d M Y') }}</p>
                                    <p class="mt-0.5 text-xs {{ $isExpiringSoon ? 'font-medium text-amber-600' : 'text-slate-400' }}">
                                        @if($daysRemaining !== null && $daysRemaining < 0)
                                            Sudah berakhir
                                        @elseif($isExpiringSoon)
                                            {{ $daysRemaining }} hari lagi
                                        @else
                                            Subscription aktif
                                        @endif
                                    </p>
                                @else
                                    <p class="text-sm text-slate-400">Tanpa masa aktif</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button type="button" onclick="openPlanModal({{ $company->id }})" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                    <i data-lucide="sliders-horizontal" class="h-3.5 w-3.5"></i> Kelola Plan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-14 text-center text-sm text-slate-500">Belum ada company terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div id="premium-search-empty" class="hidden px-6 py-14 text-center">
            <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-400"><i data-lucide="search-x" class="h-5 w-5"></i></span>
            <p class="mt-3 text-sm font-semibold text-slate-700">Company tidak ditemukan</p>
            <p class="mt-1 text-xs text-slate-400">Coba gunakan nama atau kode company lain.</p>
        </div>
        @if($companies->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">{{ $companies->links() }}</div>
        @endif
    </section>

    <section class="space-y-4">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-bold text-slate-900">Billing & Lifecycle</h2>
                <p class="mt-1 text-xs text-slate-500">Ringkasan settlement Midtrans dan subscription yang membutuhkan perhatian.</p>
            </div>
            <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700"><i data-lucide="shield-check" class="h-3.5 w-3.5"></i> Settlement otomatis</span>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
            <div class="grid gap-0 sm:grid-cols-2 xl:grid-cols-6">
                @foreach([
                    ['Revenue Bulan Ini', 'Rp '.number_format($summary['revenue_month'], 0, ',', '.'), 'wallet-cards', 'text-emerald-600', 'bg-emerald-50'],
                    ['Total Revenue', 'Rp '.number_format($summary['revenue_total'], 0, ',', '.'), 'landmark', 'text-blue-600', 'bg-blue-50'],
                    ['Settlement', $summary['settled_payments'], 'circle-check-big', 'text-emerald-600', 'bg-emerald-50'],
                    ['Payment Pending', $summary['pending_payments'], 'hourglass', 'text-amber-600', 'bg-amber-50'],
                    ['Gagal / Expired', $summary['failed_payments'], 'circle-x', 'text-rose-600', 'bg-rose-50'],
                    ['Berakhir ≤ 7 Hari', $summary['expiring_soon'], 'calendar-clock', 'text-violet-600', 'bg-violet-50'],
                ] as [$label, $value, $icon, $textColor, $iconBg])
                    <div class="border-b border-slate-100 p-4 sm:border-r xl:border-b-0 last:border-r-0">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-[11px] font-semibold leading-4 text-slate-500">{{ $label }}</p>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $iconBg }} {{ $textColor }}"><i data-lucide="{{ $icon }}" class="h-4 w-4"></i></span>
                        </div>
                        <p class="mt-3 truncate text-lg font-bold tracking-tight text-slate-900">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <h3 class="font-bold text-slate-900">Riwayat Pembayaran Midtrans</h3>
                <p class="mt-1 text-xs text-slate-500">Update plan manual tidak dihitung sebagai revenue.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50/70 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <tr><th class="px-6 py-3.5">Company</th><th class="px-6 py-3.5">Order</th><th class="px-6 py-3.5">Plan</th><th class="px-6 py-3.5">Nominal</th><th class="px-6 py-3.5">Metode</th><th class="px-6 py-3.5">Status</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            @php
                                $statusClass = match($payment->status) {
                                    'settlement' => 'bg-emerald-50 text-emerald-700',
                                    'pending' => 'bg-amber-50 text-amber-700',
                                    default => 'bg-rose-50 text-rose-700',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-6 py-4"><p class="text-sm font-bold text-slate-800">{{ $payment->company?->name ?? '—' }}</p><p class="mt-0.5 text-xs text-slate-400">{{ $payment->created_at?->translatedFormat('d M Y H:i') }}</p></td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $payment->order_id }}</td>
                                <td class="px-6 py-4"><p class="text-sm font-semibold text-slate-700">{{ $payment->plan }}</p><p class="text-xs text-slate-400">{{ \App\Support\SubscriptionPaymentData::durationLabel($payment->duration) }}</p></td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-slate-800">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $payment->payment_type ?: '—' }}</td>
                                <td class="px-6 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ \App\Support\SubscriptionPaymentData::statusLabel($payment->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">Belum ada transaksi subscription.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $payments->appends(request()->except('payment_page'))->onEachSide(1)->links('pagination.shared') }}
        </div>
    </section>
</div>

@foreach($companies as $company)
    @php $currentMeta = $planMeta[$company->subscription_plan] ?? $planMeta['Free']; @endphp
    <div id="modal-plan-{{ $company->id }}" class="fixed inset-0 z-50 hidden items-end justify-center bg-slate-950/45 p-0 backdrop-blur-[2px] sm:items-center sm:p-4" onclick="if(event.target===this) closePlanModal({{ $company->id }})">
        <div class="max-h-[92vh] w-full overflow-y-auto rounded-t-3xl bg-white shadow-2xl sm:max-w-lg sm:rounded-3xl">
            <div class="sticky top-0 z-10 border-b border-slate-100 bg-white/95 px-5 py-4 backdrop-blur sm:px-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $currentMeta['iconBg'] }}"><i data-lucide="{{ $currentMeta['icon'] }}" class="h-5 w-5"></i></span>
                        <div class="min-w-0"><h3 class="font-bold text-slate-900">Kelola Plan</h3><p class="truncate text-xs text-slate-500">{{ $company->name }} · {{ $company->subscription_plan }}</p></div>
                    </div>
                    <button type="button" onclick="closePlanModal({{ $company->id }})" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"><i data-lucide="x" class="h-4 w-4"></i></button>
                </div>
            </div>

            <div class="p-5 sm:p-6">
                <form method="POST" action="{{ route('platform.premium.update', $company) }}" class="space-y-5">
                    @csrf
                    @method('PATCH')
                    <div>
                        <div class="mb-3"><p class="text-sm font-bold text-slate-800">Pilih plan baru</p><p class="mt-0.5 text-xs text-slate-500">Plan menentukan batas maksimum employee company.</p></div>
                        <div class="space-y-2.5">
                            @foreach($planCards as $planName => $planCard)
                                <label class="group flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 p-3.5 transition {{ $planCard['wrap'] }}">
                                    <input type="radio" name="plan" value="{{ $planName }}" class="peer sr-only" required {{ $company->subscription_plan === $planName ? 'checked' : '' }}>
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $planCard['iconClass'] }}"><i data-lucide="{{ $planCard['icon'] }}" class="h-5 w-5"></i></span>
                                    <span class="min-w-0 flex-1"><span class="block text-sm font-bold text-slate-800">{{ $planName }}</span><span class="text-xs text-slate-500">Hingga {{ config('plans.'.$planName.'.max_employee') }} employee</span></span>
                                    <span class="h-5 w-5 rounded-full border-2 border-slate-300 peer-checked:border-[6px] peer-checked:border-blue-600"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-800">Durasi subscription</label>
                        <select name="duration" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-50">
                            <option value="1_month">1 Bulan</option>
                            <option value="3_months">3 Bulan</option>
                            <option value="12_months">1 Tahun</option>
                        </select>
                        <p class="mt-2 text-xs text-slate-400">Jika plan sama, durasi akan memperpanjang masa aktif sesuai aturan subscription.</p>
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-700"><i data-lucide="check" class="h-4 w-4"></i> Simpan Plan</button>
                </form>

                @if($company->subscription_plan !== 'Free')
                    <div class="mt-5 border-t border-slate-100 pt-5">
                        <div class="rounded-2xl bg-rose-50/70 p-4">
                            <p class="text-sm font-bold text-rose-800">Kembalikan ke Free</p>
                            <p class="mt-1 text-xs leading-5 text-rose-600">Subscription premium akan dibatalkan dan limit company mengikuti plan Free.</p>
                            <form method="POST" action="{{ route('platform.premium.cancel', $company) }}" class="mt-3" onsubmit="return confirm('Kembalikan company ke Free plan?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-white px-3.5 py-2.5 text-xs font-bold text-rose-700 hover:bg-rose-50"><i data-lucide="undo-2" class="h-3.5 w-3.5"></i> Batalkan Premium</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach

@push('scripts')
<script>
    function openPlanModal(id) {
        const modal = document.getElementById(`modal-plan-${id}`);
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }
    function closePlanModal(id) {
        const modal = document.getElementById(`modal-plan-${id}`);
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }
    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        document.querySelectorAll('[id^="modal-plan-"]').forEach((modal) => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
        document.body.classList.remove('overflow-hidden');
    });
    const premiumSearch = document.getElementById('premium-company-search');
    if (premiumSearch) {
        premiumSearch.addEventListener('input', (event) => {
            const query = event.target.value.trim().toLowerCase();
            const rows = [...document.querySelectorAll('.premium-company-row')];
            let visible = 0;
            rows.forEach((row) => {
                const show = !query || (row.dataset.search || '').includes(query);
                row.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            document.getElementById('premium-search-empty')?.classList.toggle('hidden', visible !== 0 || rows.length === 0);
        });
    }
</script>
@endpush
@endsection
