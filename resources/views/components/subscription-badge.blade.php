@php
    $company = auth()->user()->company;

    $planStyle = [
        'Free' => ['label' => 'Free Plan', 'classes' => 'bg-slate-800 text-white', 'icon' => 'package'],
        'Premium Go' => ['label' => 'Premium Go', 'classes' => 'bg-blue-600 text-white', 'icon' => 'zap'],
        'Premium Plus' => ['label' => 'Premium Plus', 'classes' => 'bg-purple-600 text-white', 'icon' => 'crown'],
        'Premium Max' => ['label' => 'Premium Max', 'classes' => 'bg-rose-600 text-white', 'icon' => 'sparkles'],
    ][$company?->subscription_plan ?? 'Free'];

    $plans = collect(config('plans'))->except('Free');
@endphp

@if($company)
<div
    x-data="subscriptionBadge()"
    class="fixed bottom-5 right-5 z-40">

    {{-- Floating Badge --}}
    <button
        type="button"
        @click="open = true"
        class="flex items-center gap-2 rounded-full px-4 py-2.5 text-sm font-semibold shadow-lg transition hover:opacity-90 {{ $planStyle['classes'] }}">
        <i data-lucide="{{ $planStyle['icon'] }}" class="h-4 w-4"></i>
        {{ $planStyle['label'] }}
    </button>

    {{-- Modal --}}
    <div
        x-show="open"
        x-cloak
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4"
        @click.self="open = false">

        <div class="w-full max-w-3xl rounded-3xl bg-white p-8 shadow-2xl">

            <div class="mb-6 flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Upgrade Subscription</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Plan kamu saat ini:
                        <span class="font-semibold text-slate-700">{{ $planStyle['label'] }}</span>
                        &middot; Maks {{ $company->max_employee }} karyawan
                    </p>
                </div>
                <button
                    type="button"
                    @click="open = false"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            {{-- Plan Cards --}}
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach($plans as $planKey => $planData)
                    <label
                        class="cursor-pointer rounded-2xl border-2 border-slate-200 p-4 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                        <input
                            type="radio"
                            name="plan"
                            value="{{ $planKey }}"
                            x-model="selectedPlan"
                            class="sr-only">
                        <p class="text-sm font-bold text-slate-800">{{ $planData['label'] }}</p>
                        <p class="text-xs text-slate-500">{{ $planData['max_employee'] }} karyawan</p>
                        <p class="mt-2 text-xs text-slate-400">mulai dari</p>
                        <p class="text-sm font-semibold text-blue-600">
                            Rp {{ number_format($planData['price']['1_month'], 0, ',', '.') }} / bulan
                        </p>
                    </label>
                @endforeach
            </div>

            {{-- Duration --}}
            <div class="mt-5">
                <label class="mb-2 block text-sm font-semibold text-slate-700">Durasi</label>
                <select
                    x-model="selectedDuration"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    <option value="1_month">1 Bulan</option>
                    <option value="3_months">3 Bulan</option>
                    <option value="12_months">1 Tahun</option>
                </select>
            </div>

            <p x-show="errorMessage" x-text="errorMessage" class="mt-4 text-sm font-medium text-red-600"></p>

            <button
                type="button"
                @click="checkout()"
                :disabled="loading || !selectedPlan"
                class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60">
                <span x-show="!loading">Beli Sekarang</span>
                <span x-show="loading">Memproses...</span>
            </button>

            <p class="mt-3 text-center text-xs text-slate-400">
                Pembayaran diproses lewat Midtrans (mode Sandbox).
            </p>

        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    function subscriptionBadge() {
        return {
            open: false,
            selectedPlan: null,
            selectedDuration: '1_month',
            loading: false,
            errorMessage: null,

            checkout() {
                if (!this.selectedPlan) return;

                this.loading = true;
                this.errorMessage = null;

                fetch('{{ route('subscription.checkout') }}', {
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

                    window.snap.pay(data.snap_token, {
                        onSuccess: () => window.location = '{{ route('subscription.finish') }}',
                        onPending: () => window.location = '{{ route('subscription.finish') }}',
                        onError: () => { this.errorMessage = 'Pembayaran gagal, silakan coba lagi.'; },
                        onClose: () => {},
                    });
                })
                .catch((error) => {
                    this.loading = false;
                    this.errorMessage = error.message;
                });
            },
        };
    }
</script>
@endif
