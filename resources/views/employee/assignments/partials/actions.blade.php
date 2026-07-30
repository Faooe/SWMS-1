@php

$pivot = $assignment
    ->employees
    ->firstWhere('id', auth()->user()->employee->id)
    ?->pivot;

$status = $pivot?->status;

@endphp

<div class="rounded-3xl bg-white p-8 shadow">

    {{-- Header --}}
    <div class="mb-8 flex items-center gap-3">

        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100">

            <i data-lucide="play-circle" class="h-6 w-6 text-blue-600"></i>

        </div>

        <div>

            <h2 class="text-xl font-bold">

                Assignment Action

            </h2>

            <p class="text-sm text-slate-500">

                Available action for this assignment.

            </p>

        </div>

    </div>

    @switch($status)

        {{-- ========================================= --}}
        {{-- Assigned --}}
        {{-- ========================================= --}}

        @case('Assigned')

            <div class="grid gap-4 md:grid-cols-2">

                <form method="POST" action="{{ route('employee.assignments.reject', $assignment->uuid) }}">

                    @csrf

                    <button class="w-full rounded-2xl border border-red-300 py-3 font-semibold text-red-600 transition hover:bg-red-50">

                        Reject Assignment

                    </button>

                </form>

                <form method="POST" action="{{ route('employee.assignments.accept', $assignment->uuid) }}">

                    @csrf

                    <button class="w-full rounded-2xl bg-blue-600 py-3 font-semibold text-white transition hover:bg-blue-700">

                        Accept Assignment

                    </button>

                </form>

            </div>

        @break

        {{-- ========================================= --}}
        {{-- Accepted --}}
        {{-- ========================================= --}}

        @case('Accepted')

        <div class="space-y-4">

            <a target="_blank" href="https://www.google.com/maps?q={{ $assignment->latitude }},{{ $assignment->longitude }}" class="flex items-center justify-center rounded-2xl border py-3 font-semibold">

                Open Navigation

            </a>

            <form id="assignment-check-in-form" method="POST" action="{{ route('employee.assignments.check-in', $assignment->uuid) }}">

                @csrf

                <input type="hidden" name="latitude" class="js-assignment-lat">

                <input type="hidden" name="longitude" class="js-assignment-lng">

                <button type="submit" id="assignment-check-in-btn" class="w-full rounded-2xl bg-green-600 py-3 font-semibold text-white hover:bg-green-700">

                    Check In

                </button>

            </form>

        </div>

    @break

        {{-- ========================================= --}}
        {{-- In Progress --}}
        {{-- ========================================= --}}

        @case('In Progress')

        <div class="grid gap-4 md:grid-cols-2">

            <form id="assignment-check-out-form" method="POST" action="{{ route('employee.assignments.check-out', $assignment->uuid) }}">

                @csrf

                <input type="hidden" name="latitude" class="js-assignment-lat">

                <input type="hidden" name="longitude" class="js-assignment-lng">

                <button type="submit" id="assignment-check-out-btn" class="w-full rounded-2xl border py-3 font-semibold">

                    Check Out

                </button>

            </form>

            <form method="POST" action="{{ route('employee.assignments.complete', $assignment->uuid) }}" enctype="multipart/form-data">

                @csrf

                <label for="completion_photo" class="mb-2 block cursor-pointer rounded-2xl border border-dashed border-slate-300 p-4 text-center text-sm text-slate-500 hover:bg-slate-50">

                    <span id="completion-photo-label">Klik untuk upload foto bukti selesai</span>

                    <input type="file" id="completion_photo" name="completion_photo" accept="image/*" capture="environment" class="hidden" required>

                </label>

                @error('completion_photo')

                    <p class="mb-2 text-xs text-red-600">{{ $message }}</p>

                @enderror

                <button type="submit" class="w-full rounded-2xl bg-emerald-600 py-3 font-semibold text-white hover:bg-emerald-700">

                    Complete Assignment

                </button>

            </form>

        </div>

    @break

        {{-- ========================================= --}}
        {{-- Completed --}}
        {{-- ========================================= --}}

        @case('Completed')

            <div class="rounded-2xl bg-green-50 p-8 text-center">

                <i data-lucide="badge-check" class="mx-auto h-12 w-12 text-green-600"></i>

                <h3 class="mt-4 text-lg font-bold text-green-700">

                    Assignment Completed

                </h3>

                <p class="mt-2 text-sm text-green-600">

                    Thank you for completing this assignment.

                </p>

                @if($pivot?->completion_photo)

                    <div class="mt-6">

                        <p class="mb-2 text-xs font-medium uppercase tracking-wide text-green-700">

                            Foto Bukti Selesai

                        </p>

                        <img src="{{ Storage::url($pivot->completion_photo) }}" alt="Foto bukti selesai" class="mx-auto max-h-80 rounded-2xl border border-green-200 object-cover">

                    </div>

                @endif

            </div>

        @break

        {{-- ========================================= --}}
        {{-- Rejected --}}
        {{-- ========================================= --}}

        @case('Rejected')

            <div class="rounded-2xl bg-red-50 p-8 text-center">

                <i data-lucide="circle-x" class="mx-auto h-12 w-12 text-red-600"></i>

                <h3 class="mt-4 text-lg font-bold text-red-700">

                    Assignment Rejected

                </h3>

            </div>

        @break

        {{-- ========================================= --}}
        {{-- Default --}}
        {{-- ========================================= --}}

        @default

            <div class="rounded-2xl bg-slate-50 p-8 text-center">

                <i data-lucide="clock3" class="mx-auto h-12 w-12 text-slate-400"></i>

                <h3 class="mt-4 font-semibold">

                    Waiting...

                </h3>

            </div>

    @endswitch
    @push('scripts')
<script>

document.addEventListener('DOMContentLoaded', () => {

    function attachGeoSubmit(formId, buttonId) {

        const form = document.getElementById(formId);

        const button = document.getElementById(buttonId);

        if (!form || !button) return;

        form.addEventListener('submit', function (e) {

            if (form.dataset.locationCaptured === 'true') {

                return;

            }

            e.preventDefault();

            if (!navigator.geolocation) {

                alert('Browser tidak mendukung GPS.');

                return;

            }

            button.disabled = true;

            const originalText = button.innerHTML;

            button.innerHTML = 'Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(

                function (position) {

                    form.querySelector('.js-assignment-lat').value = position.coords.latitude;

                    form.querySelector('.js-assignment-lng').value = position.coords.longitude;

                    form.dataset.locationCaptured = 'true';

                    form.submit();

                },

                function () {

                    alert('Gagal mengambil lokasi. Pastikan izin GPS diaktifkan.');

                    button.disabled = false;

                    button.innerHTML = originalText;

                },

                { enableHighAccuracy: true }

            );

        });

    }

    attachGeoSubmit('assignment-check-in-form', 'assignment-check-in-btn');

    attachGeoSubmit('assignment-check-out-form', 'assignment-check-out-btn');

    const photoInput = document.getElementById('completion_photo');

    const photoLabel = document.getElementById('completion-photo-label');

    if (photoInput && photoLabel) {

        photoInput.addEventListener('change', function () {

            if (this.files && this.files[0]) {

                photoLabel.textContent = this.files[0].name;

            }

        });

    }

});

</script>
@endpush

</div>