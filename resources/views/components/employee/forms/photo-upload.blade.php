@props([
    'employee' => null,
])

<x-employee.section-card
    title="Employee Photo"
    description="Upload employee profile photo."
    icon="image">

    <div
        x-data="{

            preview: '{{ $employee?->photo ? asset('storage/'.$employee->photo) : '' }}',

            updatePreview(event){

                const file = event.target.files[0];

                if(!file) return;

                this.preview = URL.createObjectURL(file);

            }

        }"

        class="space-y-6">

        {{-- Preview --}}
        <div class="flex justify-center">

            <template x-if="preview">

                <img
                    :src="preview"
                    class="h-44 w-44 rounded-full border-4 border-slate-200 object-cover shadow">

            </template>

            <template x-if="!preview">

                <div
                    class="flex h-44 w-44 items-center justify-center rounded-full border-2 border-dashed border-slate-300 bg-slate-50">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-14 w-14 text-slate-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 16l5-5 4 4 7-7 2 2v8H3z"/>

                    </svg>

                </div>

            </template>

        </div>

        {{-- Upload Area --}}
        <label
            class="flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-10 transition hover:border-blue-500 hover:bg-blue-50">

            <div class="text-center">

                <h3 class="font-semibold">

                    Click to Upload

                </h3>

                <p class="mt-2 text-sm text-slate-500">

                    JPG, PNG, JPEG

                </p>

                <p class="text-sm text-slate-500">

                    Maximum 2 MB

                </p>

            </div>

            <input
                type="file"
                name="photo"
                class="hidden"
                accept="image/*"
                @change="updatePreview">

        </label>

        @error('photo')

            <p class="text-sm text-red-500">

                {{ $message }}

            </p>

        @enderror

    </div>

</x-employee.section-card>