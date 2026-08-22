@php

$pivot = $assignment
    ->employees
    ->firstWhere('id', auth()->user()->employee->id)
    ?->pivot;

$status = $pivot?->status;
$reviewStatus = $pivot?->review_status;

// Absensi hari ini (Office atau assignment lain) sudah tercatat?
// Kalau sudah, tombol "Check In" di assignment ini dilewati -- langsung
// upload foto bukti untuk menyelesaikan, karena absensi memang cuma
// boleh 1x per hari.
$skipCheckIn = $status === 'Accepted' && ($hasAttendanceToday ?? false);

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

        @if($skipCheckIn)

            {{-- Absensi hari ini sudah tercatat (Office / assignment lain),
                 jadi langsung tampilkan upload foto bukti selesai. --}}

            @include('employee.assignments.partials.completion-form', ['assignment' => $assignment, 'isResubmission' => false])

        @else

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

        @endif

    @break

        {{-- ========================================= --}}
        {{-- In Progress --}}
        {{-- ========================================= --}}

        @case('In Progress')

        <div class="space-y-4">

            <form id="assignment-check-out-form" method="POST" action="{{ route('employee.assignments.check-out', $assignment->uuid) }}">

                @csrf

                <input type="hidden" name="latitude" class="js-assignment-lat">

                <input type="hidden" name="longitude" class="js-assignment-lng">

                <button type="submit" id="assignment-check-out-btn" class="w-full rounded-2xl border py-3 font-semibold">

                    Check Out

                </button>

            </form>

            @include('employee.assignments.partials.completion-form', ['assignment' => $assignment, 'isResubmission' => false])

        </div>

    @break

        {{-- ========================================= --}}
        {{-- Completed -- hasil kerja sudah pernah di-submit. Tampilan
        selanjutnya tergantung review_status (BUKAN $status yang tetap
        'Completed' selamanya begitu submit pertama kali) --}}
        {{-- ========================================= --}}

        @case('Completed')

            @if($reviewStatus === 'Approved')

                <div class="rounded-2xl bg-green-50 p-8 text-center">

                    <i data-lucide="badge-check" class="mx-auto h-12 w-12 text-green-600"></i>

                    <h3 class="mt-4 text-lg font-bold text-green-700">

                        Hasil Kerja Disetujui

                    </h3>

                    <p class="mt-2 text-sm text-green-600">

                        Terima kasih, hasil kerja kamu sudah disetujui company.

                    </p>

                </div>

            @elseif($reviewStatus === 'Needs Revision')

                <div class="space-y-4">

                    <div class="rounded-2xl bg-red-50 p-6">

                        <div class="flex items-center gap-2">
                            <i data-lucide="alert-triangle" class="h-5 w-5 text-red-600"></i>
                            <h3 class="font-bold text-red-700">Perlu Revisi</h3>
                        </div>

                        <p class="mt-2 text-sm text-red-600">
                            {{ $pivot->review_notes }}
                        </p>

                        @if($pivot->revision_deadline_at)
                            <p class="mt-3 text-xs font-semibold text-red-500">
                                Batas waktu revisi: {{ $pivot->revision_deadline_at->format('d/m/Y H:i') }}
                            </p>
                        @endif

                    </div>

                    @include('employee.assignments.partials.completion-form', ['assignment' => $assignment, 'isResubmission' => true])

                </div>

            @elseif($reviewStatus === 'Expired')

                <div class="rounded-2xl bg-slate-100 p-8 text-center">

                    <i data-lucide="clock-x" class="mx-auto h-12 w-12 text-slate-400"></i>

                    <h3 class="mt-4 text-lg font-bold text-slate-600">

                        Batas Waktu Revisi Sudah Lewat

                    </h3>

                    <p class="mt-2 text-sm text-slate-500">

                        Assignment ini sudah tidak bisa direvisi lagi.

                    </p>

                </div>

            @else {{-- Pending Review (default) --}}

                <div class="rounded-2xl bg-amber-50 p-8 text-center">

                    <i data-lucide="hourglass" class="mx-auto h-12 w-12 text-amber-600"></i>

                    <h3 class="mt-4 text-lg font-bold text-amber-700">

                        Menunggu Review

                    </h3>

                    <p class="mt-2 text-sm text-amber-600">

                        Hasil kerja kamu sedang direview company.

                    </p>

                </div>

            @endif

            @if($pivot?->completion_photo)

                <div class="mt-6">

                    <p class="mb-2 text-xs font-medium uppercase tracking-wide text-slate-500">

                        Foto Bukti

                    </p>

                    <div class="flex gap-3">

                        <img src="{{ secure_file_url($pivot->completion_photo) }}" alt="Foto bukti selesai" class="max-h-60 flex-1 rounded-2xl border border-slate-200 object-cover">

                        @if($pivot->completion_photo_2)
                            <img src="{{ secure_file_url($pivot->completion_photo_2) }}" alt="Foto bukti selesai 2" class="max-h-60 flex-1 rounded-2xl border border-slate-200 object-cover">
                        @endif

                    </div>

                    @if($pivot->completion_notes)
                        <p class="mt-4 text-sm text-slate-600">
                            {{ $pivot->completion_notes }}
                        </p>
                    @endif

                </div>

            @endif

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

    // Pakai class (bukan id) di completion-form.blade.php karena form
    // ini bisa dirender di lebih dari satu tempat -- querySelectorAll
    // supaya semua instance kepasang event listener-nya.
    document.querySelectorAll('.js-completion-photo').forEach((input) => {

        const label = input.closest('label')?.querySelector('.js-completion-photo-label');

        if (!label) return;

        input.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                label.textContent = this.files[0].name;
            }
        });

    });

    document.querySelectorAll('.js-completion-photo-2').forEach((input) => {

        const label = input.closest('label')?.querySelector('.js-completion-photo-2-label');

        if (!label) return;

        input.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                label.textContent = this.files[0].name;
            }
        });

    });

});

</script>
@endpush

</div>
