@props(['employee' => null])

<x-employee.section-card
    title="Akun & Login"
    description="Kredensial yang dipakai employee untuk mengakses SWMS."
    icon="shield-check">

    <div class="grid gap-5 md:grid-cols-2">
        <x-ui.input name="username" label="Username" :value="$employee?->user?->username" hint="Identitas internal employee." required />
        <x-ui.input name="user_email" type="email" label="Login Email" :value="$employee?->user?->email" hint="Email yang digunakan untuk login." required />
        <x-ui.input name="password" type="password" label="{{ $employee ? 'Password Baru (opsional)' : 'Password' }}" :required="!$employee" placeholder="{{ $employee ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}" />

        <div>
            <label for="user_is_active" class="mb-2 block text-sm font-semibold text-slate-700">Status Akun</label>
            <select id="user_is_active" name="user_is_active" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="1" @selected((string) old('user_is_active', $employee?->user?->is_active ?? true) === '1')>Aktif</option>
                <option value="0" @selected((string) old('user_is_active', $employee?->user?->is_active ?? true) === '0')>Nonaktif</option>
            </select>
            <p class="mt-2 text-xs text-slate-400">Jika akun nonaktif, employee tidak dapat login.</p>
        </div>
    </div>

    @if($employee)
        <input type="hidden" name="is_active" value="{{ $employee->is_active ? 1 : 0 }}">
    @else
        <input type="hidden" name="is_active" value="1">
    @endif
</x-employee.section-card>
