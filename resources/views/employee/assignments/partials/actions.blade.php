@php
    $state = $assignmentState ?? [];
    $actions = $myActions ?? ($state['my_actions'] ?? []);
    $myStatus = $state['my_status'] ?? null;
    $reviewStatus = $state['my_review_status'] ?? null;
    $reviewNotes = $state['my_review_notes'] ?? null;
    $revisionDeadline = $state['my_revision_deadline_at'] ?? null;
    $daily = (bool) $assignment->daily_attendance_enabled;
    $todayAttendance = collect($dailyAttendance ?? [])->firstWhere('date', today()->toDateString());

    $canAccept = (bool)($actions['can_accept'] ?? false);
    $canReject = (bool)($actions['can_reject'] ?? false);
    $canCheckIn = (bool)($actions['can_check_in'] ?? false);
    $canCheckOut = (bool)($actions['can_check_out'] ?? false);
    $canComplete = (bool)($actions['can_complete'] ?? false);
    $canResubmit = (bool)($actions['can_resubmit'] ?? false);

    $displayStatus = match($reviewStatus) {
        'Approved' => 'Completed',
        'Pending Review' => 'Pending Review',
        'Needs Revision' => 'Needs Revision',
        'Not Worked', 'Expired' => 'Not Worked',
        default => $myStatus,
    };
@endphp

<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-6 py-5">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                <i data-lucide="mouse-pointer-click" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-900">Aksi Assignment</h2>
                <p class="text-xs text-slate-500">Aksi yang tersedia mengikuti status dan jadwal saat ini.</p>
            </div>
        </div>
    </div>

    <div class="space-y-4 p-6">
        {{-- Assigned: employee harus memberi respons terlebih dahulu. --}}
        @if($canAccept || $canReject)
            <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                <div class="flex gap-3">
                    <i data-lucide="clipboard-check" class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"></i>
                    <div>
                        <p class="font-bold text-blue-900">Assignment baru</p>
                        <p class="mt-1 text-sm leading-relaxed text-blue-700">Terima assignment untuk mulai mengikuti jadwal kerja, atau tolak dengan alasan yang jelas.</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('employee.assignments.accept', $assignment->uuid) }}">
                @csrf
                <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 px-4 py-3 font-bold text-white transition hover:bg-blue-700">
                    <i data-lucide="check" class="h-4 w-4"></i>
                    Terima Assignment
                </button>
            </form>

            <details class="rounded-2xl border border-slate-200 bg-slate-50">
                <summary class="cursor-pointer list-none px-4 py-3 text-center text-sm font-semibold text-rose-600">Tidak bisa mengerjakan?</summary>
                <form method="POST" action="{{ route('employee.assignments.reject', $assignment->uuid) }}" class="space-y-3 border-t border-slate-200 p-4">
                    @csrf
                    <textarea name="reason" required minlength="5" rows="3" placeholder="Tuliskan alasan penolakan..." class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm focus:border-rose-400 focus:ring-4 focus:ring-rose-50">{{ old('reason') }}</textarea>
                    @error('reason')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                    <button class="w-full rounded-xl border border-rose-200 bg-white py-2.5 text-sm font-bold text-rose-600 transition hover:bg-rose-50">Tolak Assignment</button>
                </form>
            </details>
        @else
            {{-- Review/result state selalu ditampilkan sebelum action operasional. --}}
            @if($displayStatus === 'Completed')
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 text-center">
                    <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                        <i data-lucide="badge-check" class="h-6 w-6"></i>
                    </div>
                    <p class="mt-3 font-bold text-emerald-800">Hasil kerja disetujui</p>
                    <p class="mt-1 text-sm text-emerald-700">Assignment kamu telah selesai dan disetujui company.</p>
                </div>
            @elseif($displayStatus === 'Pending Review')
                <div class="rounded-2xl border border-violet-100 bg-violet-50 p-5 text-center">
                    <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-violet-100 text-violet-600">
                        <i data-lucide="scan-search" class="h-6 w-6"></i>
                    </div>
                    <p class="mt-3 font-bold text-violet-800">Menunggu review</p>
                    <p class="mt-1 text-sm text-violet-700">Bukti pekerjaan sudah dikirim dan sedang direview company.</p>
                </div>
            @elseif($displayStatus === 'Needs Revision')
                <div class="rounded-2xl border border-rose-100 bg-rose-50 p-5">
                    <div class="flex items-start gap-3">
                        <i data-lucide="rotate-ccw" class="mt-0.5 h-5 w-5 shrink-0 text-rose-600"></i>
                        <div>
                            <p class="font-bold text-rose-800">Perlu revisi</p>
                            <p class="mt-1 text-sm leading-relaxed text-rose-700">{{ $reviewNotes ?: 'Company meminta perbaikan pada hasil pekerjaan.' }}</p>
                            @if($revisionDeadline)
                                <p class="mt-2 text-xs font-bold text-rose-600">Batas revisi: {{ \Carbon\Carbon::parse($revisionDeadline)->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($canResubmit)
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <p class="mb-3 text-sm font-bold text-slate-800">Kirim hasil revisi</p>
                        @include('employee.assignments.partials.completion-form', [
                            'assignment' => $assignment,
                            'isResubmission' => true,
                            'submitLabel' => 'Kirim Revisi',
                        ])
                    </div>
                @else
                    <div class="rounded-xl bg-slate-100 px-4 py-3 text-sm text-slate-600">Batas pengiriman revisi sudah tidak tersedia.</div>
                @endif


                @if($canCheckOut)
                    <form id="assignment-check-out-form" method="POST" action="{{ route('employee.assignments.check-out', $assignment->uuid) }}">
                        @csrf
                        <input type="hidden" name="latitude" class="js-assignment-lat">
                        <input type="hidden" name="longitude" class="js-assignment-lng">
                        <button type="submit" id="assignment-check-out-btn" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 py-3 font-bold text-white transition hover:bg-blue-700">
                            <i data-lucide="log-out" class="h-4 w-4"></i>
                            Check Out
                        </button>
                    </form>
                @endif
            @elseif($displayStatus === 'Not Worked')
                <div class="rounded-2xl border border-slate-200 bg-slate-100 p-5 text-center">
                    <i data-lucide="clock-x" class="mx-auto h-8 w-8 text-slate-500"></i>
                    <p class="mt-3 font-bold text-slate-700">Assignment tidak dikerjakan</p>
                    <p class="mt-1 text-sm text-slate-500">Deadline pekerjaan atau revisi sudah terlewat.</p>
                </div>
            @elseif($myStatus === 'Rejected')
                <div class="rounded-2xl border border-rose-100 bg-rose-50 p-5 text-center">
                    <i data-lucide="circle-x" class="mx-auto h-8 w-8 text-rose-600"></i>
                    <p class="mt-3 font-bold text-rose-800">Assignment ditolak</p>
                    <p class="mt-1 text-sm text-rose-700">Kamu telah menolak assignment ini.</p>
                </div>
            @elseif($assignment->status === 'Cancelled')
                <div class="rounded-2xl border border-rose-100 bg-rose-50 p-5 text-center">
                    <i data-lucide="ban" class="mx-auto h-8 w-8 text-rose-600"></i>
                    <p class="mt-3 font-bold text-rose-800">Assignment dibatalkan</p>
                    <p class="mt-1 text-sm text-rose-700">Assignment ini sudah dibatalkan oleh company.</p>
                </div>
            @endif

            {{-- DAILY ATTENDANCE: aksi Check In/Out ditampilkan langsung di card kalender
                 agar employee tidak perlu mencari tombol di sidebar. Sidebar ini fokus
                 pada workflow assignment/review. --}}
            @if($daily && !in_array($displayStatus, ['Needs Revision', 'Not Worked'], true) && $myStatus !== 'Rejected' && $assignment->status !== 'Cancelled')
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex gap-3">
                        <i data-lucide="calendar-check-2" class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"></i>
                        <div>
                            <p class="font-bold text-slate-800">Attendance harian aktif</p>
                            <p class="mt-1 text-sm leading-relaxed text-slate-500">Check In dan Check Out harian tersedia langsung pada bagian <strong>Attendance Harian Assignment</strong>.</p>
                        </div>
                    </div>
                </div>

                @if($canComplete)
                    <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-4">
                        <div class="mb-4 flex gap-3">
                            <i data-lucide="flag" class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"></i>
                            <div>
                                <p class="font-bold text-slate-800">Kirim hasil akhir assignment</p>
                                <p class="mt-1 text-sm text-slate-600">Attendance hari terakhir sudah ditutup. Kirim bukti dan catatan hasil kerja untuk masuk ke tahap review.</p>
                            </div>
                        </div>
                        @include('employee.assignments.partials.completion-form', [
                            'assignment' => $assignment,
                            'isResubmission' => false,
                            'submitLabel' => 'Kirim Hasil Assignment',
                        ])
                    </div>
                @endif
            @endif

            {{-- NON-DAILY: workflow lama, tetapi tombol mengikuti rule backend/API. --}}
            @if(!$daily && !in_array($displayStatus, ['Needs Revision', 'Not Worked'], true) && $myStatus !== 'Rejected' && $assignment->status !== 'Cancelled')
                @if($canCheckIn)
                    <a target="_blank" rel="noopener" href="https://www.google.com/maps?q={{ $assignment->latitude }},{{ $assignment->longitude }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                        <i data-lucide="navigation" class="h-4 w-4"></i>
                        Buka Navigasi
                    </a>

                    <form id="assignment-check-in-form" method="POST" action="{{ route('employee.assignments.check-in', $assignment->uuid) }}">
                        @csrf
                        <input type="hidden" name="latitude" class="js-assignment-lat">
                        <input type="hidden" name="longitude" class="js-assignment-lng">
                        <button type="submit" id="assignment-check-in-btn" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-600 py-3 font-bold text-white transition hover:bg-emerald-700">
                            <i data-lucide="log-in" class="h-4 w-4"></i>
                            Check In
                        </button>
                    </form>
                @endif

                @if($canComplete)
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <p class="mb-1 font-bold text-slate-800">Laporan hasil kerja</p>
                        <p class="mb-4 text-xs text-slate-500">Upload bukti pekerjaan sebelum melakukan Check Out.</p>
                        @include('employee.assignments.partials.completion-form', [
                            'assignment' => $assignment,
                            'isResubmission' => false,
                            'submitLabel' => 'Kirim Hasil Assignment',
                        ])
                    </div>
                @endif

                @if($canCheckOut)
                    <form id="assignment-check-out-form" method="POST" action="{{ route('employee.assignments.check-out', $assignment->uuid) }}">
                        @csrf
                        <input type="hidden" name="latitude" class="js-assignment-lat">
                        <input type="hidden" name="longitude" class="js-assignment-lng">
                        <button type="submit" id="assignment-check-out-btn" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 py-3 font-bold text-white transition hover:bg-blue-700">
                            <i data-lucide="log-out" class="h-4 w-4"></i>
                            Check Out
                        </button>
                    </form>
                @endif
            @endif

            @if(!($canAccept || $canReject || $canCheckIn || $canCheckOut || $canComplete || $canResubmit)
                && !in_array($displayStatus, ['Completed', 'Pending Review', 'Needs Revision', 'Not Worked'], true)
                && $myStatus !== 'Rejected'
                && $assignment->status !== 'Cancelled')
                <div class="rounded-2xl bg-slate-50 p-4 text-center text-sm text-slate-500">
                    Tidak ada aksi yang perlu dilakukan saat ini.
                </div>
            @endif
        @endif

        @if(($state['my_completion_photo_url'] ?? null) || ($state['my_completion_photo_2_url'] ?? null))
            <div class="border-t border-slate-100 pt-4">
                <p class="mb-3 text-xs font-bold uppercase tracking-wide text-slate-400">Bukti Terakhir</p>
                <div class="grid grid-cols-2 gap-2">
                    @if($state['my_completion_photo_url'] ?? null)
                        <a href="{{ $state['my_completion_photo_url'] }}" target="_blank" class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                            <img src="{{ $state['my_completion_photo_url'] }}" alt="Foto bukti" class="h-28 w-full object-cover transition hover:scale-105">
                        </a>
                    @endif
                    @if($state['my_completion_photo_2_url'] ?? null)
                        <a href="{{ $state['my_completion_photo_2_url'] }}" target="_blank" class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                            <img src="{{ $state['my_completion_photo_2_url'] }}" alt="Foto bukti kedua" class="h-28 w-full object-cover transition hover:scale-105">
                        </a>
                    @endif
                </div>
                @if($state['my_completion_notes'] ?? null)
                    <p class="mt-3 rounded-xl bg-slate-50 p-3 text-sm leading-relaxed text-slate-600">{{ $state['my_completion_notes'] }}</p>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    function attachGeoSubmit(formId, buttonId) {
        const form = document.getElementById(formId);
        const button = document.getElementById(buttonId);
        if (!form || !button) return;

        form.addEventListener('submit', function (event) {
            if (form.dataset.locationCaptured === 'true') return;
            event.preventDefault();

            if (!navigator.geolocation) {
                alert('Browser tidak mendukung GPS.');
                return;
            }

            button.disabled = true;
            const originalHtml = button.innerHTML;
            button.innerHTML = 'Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    form.querySelector('.js-assignment-lat').value = position.coords.latitude;
                    form.querySelector('.js-assignment-lng').value = position.coords.longitude;
                    form.dataset.locationCaptured = 'true';
                    form.submit();
                },
                () => {
                    alert('Gagal mengambil lokasi. Pastikan izin lokasi/GPS browser diaktifkan.');
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        });
    }

    attachGeoSubmit('assignment-check-in-form', 'assignment-check-in-btn');
    attachGeoSubmit('assignment-check-out-form', 'assignment-check-out-btn');
    attachGeoSubmit('daily-assignment-check-in-form', 'daily-assignment-check-in-btn');
    attachGeoSubmit('daily-assignment-check-out-form', 'daily-assignment-check-out-btn');

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
                    label.textContent = file.name;
                    return;
                }

                label.textContent = 'Mengompres foto...';
                input.disabled = true;
                if (submitButton) submitButton.disabled = true;

                try {
                    const compressed = await window.compressAssignmentPhoto(file);
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressed);
                    this.files = dataTransfer.files;

                    const sizeLabel = window.formatFileSize ? window.formatFileSize(compressed.size) : '';
                    label.textContent = sizeLabel ? `${compressed.name} (${sizeLabel})` : compressed.name;
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

    // Detail web juga perlu mengikuti pergantian hari/jam mulai seperti mobile.
    // Timer ini hanya mengecek waktu di browser; request baru terjadi ketika
    // boundary benar-benar terlewati, jadi tidak melakukan polling API.
    const dailyAttendanceEnabled = @json($daily);
    if (dailyAttendanceEnabled) {
        const openedDay = new Date().toDateString();
        const scheduleStart = @json($assignment->start_datetime->format('H:i:s'));
        let boundaryReloaded = false;

        const maybeReloadForBoundary = () => {
            if (boundaryReloaded) return;
            const now = new Date();

            if (now.toDateString() !== openedDay) {
                boundaryReloaded = true;
                window.location.reload();
                return;
            }

            const [hour, minute, second] = scheduleStart.split(':').map(Number);
            const startToday = new Date(now);
            startToday.setHours(hour || 0, minute || 0, second || 0, 0);

            if (now >= startToday && document.body.dataset.phase3StartRefreshed !== 'true') {
                // Hanya reload otomatis bila halaman dibuka sebelum jam mulai.
                const openedAt = new Date(@json(now()->format('Y-m-d\TH:i:s')));
                if (openedAt < startToday) {
                    document.body.dataset.phase3StartRefreshed = 'true';
                    boundaryReloaded = true;
                    window.location.reload();
                }
            }
        };

        window.setInterval(maybeReloadForBoundary, 30000);
    }
});
</script>
@endpush
