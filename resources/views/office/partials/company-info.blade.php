@php
    $company = $office->company;
@endphp

<x-ui.card>

    {{-- Header --}}
    <div class="flex items-start justify-between">

        <div class="flex items-center gap-4">

            @if($company?->logo)
                <img
                    src="{{ asset('storage/'.$company->logo) }}"
                    class="h-14 w-14 rounded-2xl object-cover">
            @else
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-lg font-bold text-blue-700">
                    {{ strtoupper(substr($company?->name ?? '-', 0, 1)) }}
                </div>
            @endif

            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    {{ $company?->name ?? '-' }}
                </h2>
                <p class="text-sm text-slate-500">
                    {{ $company?->code ?? '-' }}
                </p>
            </div>

        </div>

        @if($company?->is_active)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">
                Active
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700">
                Inactive
            </span>
        @endif

    </div>

    <p class="mt-4 text-xs text-slate-400">
        This information is managed by the Platform Administrator and shown here for reference. Editing office data below will stay linked to this company record.
    </p>

    {{-- Details --}}
    <div class="mt-6 space-y-4 border-t border-slate-100 pt-6">

        <div class="flex items-start gap-3">
            <i data-lucide="mail" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
            <span class="text-sm text-slate-600">{{ $company?->email ?? '-' }}</span>
        </div>

        <div class="flex items-start gap-3">
            <i data-lucide="phone" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
            <span class="text-sm text-slate-600">{{ $company?->phone ?? '-' }}</span>
        </div>

        @if($company?->website)
            <div class="flex items-start gap-3">
                <i data-lucide="globe" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
                <span class="text-sm text-slate-600">{{ $company->website }}</span>
            </div>
        @endif

        <div class="flex items-start gap-3">
            <i data-lucide="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400"></i>
            <span class="text-sm text-slate-600">
                {{ collect([$company?->address, $company?->city, $company?->province, $company?->postal_code])->filter()->implode(', ') ?: '-' }}
            </span>
        </div>

    </div>

    {{-- Plan & Employees --}}
    <div class="mt-6 grid grid-cols-2 gap-4 border-t border-slate-100 pt-6">

        <div>
            <p class="text-xs text-slate-400">Subscription Plan</p>
            <p class="mt-1 text-sm font-semibold text-slate-700">
                {{ $company?->subscription_plan ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-xs text-slate-400">Employees</p>
            <p class="mt-1 text-sm font-semibold text-slate-700">
                {{ $company?->employees_count ?? 0 }} / {{ $company?->max_employee ?? '-' }}
            </p>
        </div>

    </div>

</x-ui.card>