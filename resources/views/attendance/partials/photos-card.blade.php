<x-ui.card>
    <div class="mb-5 flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
            <i data-lucide="images" class="h-5 w-5"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-900">Attendance Photos</h2>
            <p class="mt-1 text-sm text-slate-500">Dokumentasi check in dan check out employee.</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach([
            ['label' => 'Check In', 'photo' => $attendance->check_in_photo, 'icon' => 'log-in'],
            ['label' => 'Check Out', 'photo' => $attendance->check_out_photo, 'icon' => 'log-out'],
        ] as $photoItem)
            <div class="rounded-2xl border border-slate-200 bg-slate-50/40 p-3">
                <div class="mb-3 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                        <i data-lucide="{{ $photoItem['icon'] }}" class="h-4 w-4 text-slate-500"></i>
                        {{ $photoItem['label'] }}
                    </div>
                    <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $photoItem['photo'] ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $photoItem['photo'] ? 'Tersedia' : 'Tidak ada foto' }}
                    </span>
                </div>

                @if($photoItem['photo'])
                    <button type="button" onclick="openPhoto('{{ secure_file_url($photoItem['photo']) }}')" class="group block w-full text-left">
                        <div class="relative h-52 overflow-hidden rounded-xl bg-slate-100">
                            <img src="{{ secure_file_url($photoItem['photo']) }}"
                                 alt="{{ $photoItem['label'] }} attendance photo"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 flex items-center justify-center bg-slate-950/0 transition group-hover:bg-slate-950/25">
                                <span class="translate-y-2 rounded-lg bg-white/95 px-3 py-2 text-xs font-semibold text-slate-800 opacity-0 shadow-sm transition group-hover:translate-y-0 group-hover:opacity-100">
                                    Lihat Foto
                                </span>
                            </div>
                        </div>
                    </button>
                @else
                    <div class="flex h-52 items-center justify-center rounded-xl border border-dashed border-slate-300 bg-white">
                        <div class="text-center">
                            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100">
                                <i data-lucide="camera-off" class="h-5 w-5 text-slate-400"></i>
                            </div>
                            <p class="mt-3 text-sm font-medium text-slate-500">Belum ada foto {{ strtolower($photoItem['label']) }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-ui.card>
