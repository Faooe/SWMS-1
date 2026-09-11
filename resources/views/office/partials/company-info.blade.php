@php($company = $office->company)

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-5 py-4">
        <div class="flex items-center gap-3">
            @if($company?->logo)
                <img src="{{ secure_file_url($company->logo) }}" alt="Logo {{ $company->name }}" class="h-11 w-11 shrink-0 rounded-xl border border-slate-200 object-cover">
            @else
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-black text-blue-600">{{ strtoupper(substr($company?->name ?? '-', 0, 1)) }}</div>
            @endif
            <div class="min-w-0 flex-1"><h2 class="truncate text-sm font-black text-slate-900">{{ $company?->name ?? '-' }}</h2><p class="mt-0.5 truncate text-xs font-medium text-slate-400">{{ $company?->code ?? '-' }}</p></div>
            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $company?->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}"><span class="h-1.5 w-1.5 rounded-full {{ $company?->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>{{ $company?->is_active ? 'Aktif' : 'Nonaktif' }}</span>
        </div>
        <p class="mt-3 text-xs leading-5 text-slate-500">Informasi company dikelola oleh platform admin dan ditampilkan sebagai referensi.</p>
    </div>

    <div class="space-y-4 p-5">
        <x-ui.detail-item icon="mail" label="Email" :value="$company?->email" />
        <x-ui.detail-item icon="phone" label="Telepon" :value="$company?->phone" />
        @if($company?->website)<x-ui.detail-item icon="globe" label="Situs Web" :value="$company->website" />@endif
        <x-ui.detail-item icon="map-pin" label="Alamat" :value="collect([$company?->address, $company?->city, $company?->province, $company?->postal_code])->filter()->implode(', ')" />
    </div>

    <div class="grid grid-cols-2 divide-x divide-slate-100 border-t border-slate-100">
        <div class="px-5 py-4"><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Paket</p><p class="mt-1 truncate text-sm font-black text-slate-800">{{ $company?->subscription_plan ?? '-' }}</p></div>
        <div class="px-5 py-4"><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Employee</p><p class="mt-1 text-sm font-black text-slate-800">{{ $company?->employees_count ?? 0 }} / {{ $company?->max_employee ?? '∞' }}</p></div>
    </div>
</section>
