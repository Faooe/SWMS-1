@props(['employee' => null])

<x-employee.section-card
    title="Informasi Pribadi"
    description="Identitas dasar, kontak, dan foto profil employee."
    icon="user">

    <div class="grid gap-6 xl:grid-cols-[200px_minmax(0,1fr)]">
        <div x-data="{
                preview: '{{ $employee?->photo ? secure_file_url($employee->photo) : '' }}',
                updatePreview(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    this.preview = URL.createObjectURL(file);
                }
             }" class="xl:border-r xl:border-slate-100 xl:pr-6">
            <p class="text-sm font-semibold text-slate-700">Foto Employee</p>
            <div class="mt-4 flex flex-col items-center xl:items-start">
                <template x-if="preview">
                    <img :src="preview" class="h-28 w-28 rounded-full border border-slate-200 object-cover shadow-sm">
                </template>
                <template x-if="!preview">
                    <div class="flex h-28 w-28 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <i data-lucide="user-round" class="h-10 w-10"></i>
                    </div>
                </template>

                <label class="mt-4 inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-700">
                    <i data-lucide="camera" class="h-4 w-4"></i>
                    {{ $employee ? 'Ganti Foto' : 'Pilih Foto' }}
                    <input type="file" name="photo" data-compress-image accept="image/jpeg,image/png,image/webp" class="hidden" @change="updatePreview">
                </label>
                <p class="mt-2 text-xs leading-5 text-slate-400">JPG, JPEG, PNG, WEBP. Maks. 1MB.</p>
                @error('photo')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <div class="grid gap-5 md:grid-cols-2">
                <x-ui.input name="employee_number" label="Employee Number (NIP)" :value="$employee?->employee_number" required />
                <x-ui.input name="full_name" label="Nama Lengkap" :value="$employee?->full_name" required />
                <x-ui.input name="email" type="email" label="Email Pribadi" :value="$employee?->email" required />
                <x-ui.input name="phone" label="Nomor Telepon" :value="$employee?->phone" />

                <div>
                    <label for="gender" class="mb-2 flex items-center gap-1 text-sm font-semibold text-slate-700">Gender <span class="text-red-500">*</span></label>
                    <select id="gender" name="gender" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        <option value="">Pilih Gender</option>
                        <option value="Male" @selected(old('gender', $employee?->gender) === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender', $employee?->gender) === 'Female')>Female</option>
                    </select>
                    @error('gender')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="marital_status" class="mb-2 block text-sm font-semibold text-slate-700">Status Pernikahan</label>
                    <select id="marital_status" name="marital_status" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        <option value="">Pilih Status</option>
                        <option value="Single" @selected(old('marital_status', $employee?->marital_status) === 'Single')>Single</option>
                        <option value="Married" @selected(old('marital_status', $employee?->marital_status) === 'Married')>Married</option>
                        <option value="Divorced" @selected(old('marital_status', $employee?->marital_status) === 'Divorced')>Divorced</option>
                    </select>
                </div>

                <x-ui.input name="birth_place" label="Tempat Lahir" :value="$employee?->birth_place" />
                <x-ui.input name="birth_date" type="date" label="Tanggal Lahir" :value="$employee?->birth_date?->format('Y-m-d')" />
                <x-ui.input name="emergency_contact_name" label="Kontak Darurat" :value="$employee?->emergency_contact_name" />
                <x-ui.input name="emergency_contact_phone" label="Telepon Darurat" :value="$employee?->emergency_contact_phone" />
            </div>

            <div class="mt-5">
                <label for="address" class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
                <textarea id="address" name="address" rows="3" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('address', $employee?->address) }}</textarea>
                @error('address')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
</x-employee.section-card>
