@php
    $company = auth()->user()->company;

    $planStyle = [
        'Free' => ['label' => 'Free Plan', 'classes' => 'bg-slate-800 text-white', 'icon' => 'package'],
        'Premium Go' => ['label' => 'Premium Go', 'classes' => 'bg-blue-600 text-white', 'icon' => 'zap'],
        'Premium Plus' => ['label' => 'Premium Plus', 'classes' => 'bg-violet-600 text-white', 'icon' => 'crown'],
        'Premium Max' => ['label' => 'Premium Max', 'classes' => 'bg-rose-600 text-white', 'icon' => 'sparkles'],
    ][$company?->subscription_plan ?? 'Free'];
@endphp

@if($company)
    <div class="fixed bottom-5 right-5 z-40">
        <a
            href="{{ route('subscription.index') }}"
            class="group flex items-center gap-2 rounded-full px-4 py-2.5 text-sm font-semibold shadow-lg shadow-slate-900/10 transition hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-100 {{ $planStyle['classes'] }}"
            title="Kelola Subscription & Billing">
            <i data-lucide="{{ $planStyle['icon'] }}" class="h-4 w-4"></i>
            <span>{{ $planStyle['label'] }}</span>
            <i data-lucide="chevron-right" class="h-4 w-4 opacity-70 transition group-hover:translate-x-0.5"></i>
        </a>
    </div>
@endif
