<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Office</h1>
            <p class="mt-2 text-slate-500">
                View your company's office information, location, and attendance radius.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @forelse($offices as $office)

        <div class="space-y-8" wire:key="office-block-{{ $office->id }}">

            {{-- Office Identity --}}
            <x-ui.card>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="text-xl font-bold text-slate-800">{{ $office->name }}</h2>

                            @if($office->is_head_office)
                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                    Head Office
                                </span>
                            @endif
                        </div>

                        <p class="mt-1 text-sm text-slate-500">Code: {{ $office->code }}</p>
                    </div>

                    @if($office->is_active)
                        <span class="inline-flex w-fit rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">
                            Active
                        </span>
                    @else
                        <span class="inline-flex w-fit rounded-full bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700">
                            Inactive
                        </span>
                    @endif

                </div>
            </x-ui.card>

            {{-- Summary : Company Info (left) + Office Location Map (right) --}}
            <div class="grid grid-cols-1 gap-8 xl:grid-cols-5">

                <div class="xl:col-span-2">
                    @include('office.partials.company-info', ['office' => $office])
                </div>

                <div class="xl:col-span-3">
                    @include('office.partials.map-preview', ['office' => $office])
                </div>

            </div>

            {{-- Action --}}
            <x-ui.card>
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Office Detail</h2>
                        <p class="mt-2 text-slate-500">
                            Open the full detail to edit the office identity, address, attendance radius, and polygon area.
                        </p>
                    </div>

                    <a
                        href="{{ route('offices.edit', $office) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow transition hover:bg-blue-700">
                        <i data-lucide="eye" class="h-5 w-5"></i>
                        View / Edit
                    </a>

                </div>
            </x-ui.card>

        </div>

    @empty

        <x-ui.card>
            <div class="px-6 py-16 text-center text-slate-500">
                No office data found.
            </div>
        </x-ui.card>

    @endforelse

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        lucide.createIcons();
    }
});
</script>
@endpush