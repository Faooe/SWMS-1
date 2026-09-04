@props(['employee' => null])

<div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
    <div>
        <p class="text-sm font-semibold text-blue-600">Employee Management</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
            {{ $employee ? 'Edit Employee' : 'Tambah Employee' }}
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            {{ $employee ? 'Perbarui identitas, penempatan, dan akun login employee.' : 'Tambahkan employee baru beserta penempatan dan akun login.' }}
        </p>
    </div>
    @if($employee)
        <span class="inline-flex w-fit items-center gap-2 rounded-full border {{ $employee->is_active ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' }} px-3 py-1.5 text-xs font-semibold">
            <span class="h-2 w-2 rounded-full {{ $employee->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
        </span>
    @endif
</div>
