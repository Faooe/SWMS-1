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

                <form method="POST" action="{{ route('employee.assignments.reject', $assignment->uuid) }}" class="rounded-2xl border border-red-200 bg-red-50/40 p-3">
                    @csrf
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Alasan penolakan *</label>
                    <textarea name="reason" required minlength="5" rows="3" placeholder="Jelaskan kenapa assignment tidak dapat kamu terima..." class="mb-3 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('reason') }}</textarea>
                    @error('reason')<p class="mb-2 text-xs text-red-600">{{ $message }}</p>@enderror
                    <button class="w-full rounded-xl border border-red-300 py-2.5 font-semibold text-red-600 transition hover:bg-red-50">Reject Assignment</button>
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

            {{-- Check Out sengaja TIDAK ditaruh di sini -- urutannya
                 sekarang wajib submit foto & catatan hasil kerja DULU
                 (di bawah), baru tombol Check Out muncul setelah itu
                 (lihat blok status 'Completed' + Pending Review/Needs
                 Revision di bawah). Backend juga sudah menolak
                 permintaan check-out kalau completion_photo masih
                 kosong -- lihat AttendanceService::checkOutAssignment(). --}}

            <div class="rounded-2xl bg-blue-50 p-4 text-sm text-blue-700">
                Upload foto bukti & catatan hasil kerja dulu di bawah ini sebelum bisa Check Out.
            </div>

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

            @unless($assignmentCheckedOut ?? false)

                {{-- Check Out baru muncul DI SINI -- setelah foto bukti
                     berhasil disubmit (status sudah 'Completed'), apa pun
                     hasil review-nya. Menunggu approve/reject company
                     bisa lama, jadi employee tidak perlu nunggu itu dulu
                     buat check-out & pulang. --}}

                <form id="assignment-check-out-form" method="POST" action="{{ route('employee.assignments.check-out', $assignment->uuid) }}" class="mt-4">

                    @csrf

                    <input type="hidden" name="latitude" class="js-assignment-lat">

                    <input type="hidden" name="longitude" class="js-assignment-lng">

                    <button type="submit" id="assignment-check-out-btn" class="w-full rounded-2xl border py-3 font-semibold">

                        Check Out

                    </button>

                </form>

            @endunless

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
    //
    // Kompresi otomatis (browser-image-compression, lihat resources/js/
    // assignment-photo-compress.js) -- begitu user pilih foto, kalau
    // ukurannya sudah di atas 300KB langsung dikompres di background
    // SEBELUM form disubmit, supaya user tidak perlu pilih foto lain
    // manual. File hasil kompresi di-inject balik ke <input> yang sama
    // pakai DataTransfer (browser tidak izinkan set input.files
    // langsung dari array biasa).
    async function attachAutoCompress(inputClass, labelClass) {

        document.querySelectorAll(`.${inputClass}`).forEach((input) => {

            const label = input.closest('label')?.querySelector(`.${labelClass}`);

            if (!label) return;

            const form = input.closest('form');
            const submitButton = form?.querySelector('.js-completion-submit');

            const originalLabelText = label.textContent;

            input.addEventListener('change', async function () {

                const file = this.files?.[0];

                if (!file) {
                    label.textContent = originalLabelText;
                    return;
                }

                if (!window.compressAssignmentPhoto) {
                    // Skrip kompresi belum termuat (jarang terjadi) --
                    // biarkan file asli lewat, validasi backend max
                    // 300KB tetap jadi safety-net.
                    label.textContent = file.name;
                    return;
                }

                label.textContent = 'Mengompres foto...';
                input.disabled = true;

                // Cegah user submit form SEBELUM kompresi selesai --
                // tanpa ini, klik cepat bisa kirim foto asli yang masih
                // di atas 300KB dan ditolak backend.
                if (submitButton) submitButton.disabled = true;

                try {

                    const compressed = await window.compressAssignmentPhoto(file);

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressed);
                    this.files = dataTransfer.files;

                    const sizeLabel = window.formatFileSize
                        ? window.formatFileSize(compressed.size)
                        : '';

                    label.textContent = sizeLabel
                        ? `${compressed.name} (${sizeLabel})`
                        : compressed.name;

                } catch (error) {

                    console.error('Gagal mengompres foto:', error);
                    label.textContent = file.name;

                } finally {

                    input.disabled = false;
                    if (submitButton) submitButton.disabled = false;

                }

            });

        });

    }

    attachAutoCompress('js-completion-photo', 'js-completion-photo-label');

    attachAutoCompress('js-completion-photo-2', 'js-completion-photo-2-label');

});

</script>
@endpush

</div>
