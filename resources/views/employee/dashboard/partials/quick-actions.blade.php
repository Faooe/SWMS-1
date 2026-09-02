<x-ui.card class="p-0 overflow-hidden">
    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-600">Navigasi</p>
        <h2 class="mt-1 text-lg font-bold text-slate-900">Akses Cepat</h2>
        <p class="mt-1 text-sm text-slate-500">Menu tambahan tanpa memenuhi dashboard dengan card.</p>
    </div>

    <nav class="divide-y divide-slate-100">
        <a href="{{ route('employee.attendance.history') }}" class="group flex items-center gap-3 px-5 py-4 sm:px-6">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i data-lucide="history" class="h-4 w-4"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-700">Riwayat Attendance</p>
                <p class="mt-0.5 text-xs text-slate-500">Lihat jam kerja dan histori kehadiran.</p>
            </div>
            <i data-lucide="chevron-right" class="h-4 w-4 text-slate-300 group-hover:text-blue-500"></i>
        </a>

        <a href="{{ route('employee.leaves.index') }}" class="group flex items-center gap-3 px-5 py-4 sm:px-6">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i data-lucide="file-text" class="h-4 w-4"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-700">Leave / Permission</p>
                <p class="mt-0.5 text-xs text-slate-500">Ajukan cuti, sakit, atau izin.</p>
            </div>
            <i data-lucide="chevron-right" class="h-4 w-4 text-slate-300 group-hover:text-blue-500"></i>
        </a>

        <a href="{{ route('employee.profile') }}" class="group flex items-center gap-3 px-5 py-4 sm:px-6">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i data-lucide="user-round" class="h-4 w-4"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-800 group-hover:text-blue-700">Profil</p>
                <p class="mt-0.5 text-xs text-slate-500">Kelola data akun dan profil karyawan.</p>
            </div>
            <i data-lucide="chevron-right" class="h-4 w-4 text-slate-300 group-hover:text-blue-500"></i>
        </a>
    </nav>
</x-ui.card>
