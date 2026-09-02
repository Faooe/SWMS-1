@props(['assignment' => null,'offices','priorities','types','statuses'])

@php
    $existingAttachmentCount = $assignment?->relationLoaded('attachments') ? $assignment->attachments->count() : ($assignment?->attachments()->count() ?? 0);
    $remainingAttachmentSlots = max(0, 5 - $existingAttachmentCount);
@endphp

<x-assignment.section-card title="Informasi Assignment" description="Atur identitas pekerjaan, jadwal, workflow, dan attachment instruksi." icon="clipboard-list">
    <div class="grid gap-5 md:grid-cols-2">
        <x-ui.input name="title" label="Judul Assignment" :value="$assignment?->title" required />
        <x-ui.select name="office_id" label="Office" :options="$offices" :selected="$assignment?->office_id" placeholder="Pilih Office" required />

        <div>
            <label for="priority" class="mb-2 block text-sm font-semibold text-slate-700">Prioritas <span class="text-red-500">*</span></label>
            <select id="priority" name="priority" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">Pilih Prioritas</option>
                @foreach($priorities as $priority)<option value="{{ $priority }}" @selected(old('priority',$assignment?->priority)===$priority)>{{ $priority }}</option>@endforeach
            </select>
        </div>

        <div>
            <label for="assignment_type" class="mb-2 block text-sm font-semibold text-slate-700">Jenis Assignment <span class="text-red-500">*</span></label>
            <select id="assignment_type" name="assignment_type" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">Pilih Jenis</option>
                @foreach($types as $type)<option value="{{ $type }}" @selected(old('assignment_type',$assignment?->assignment_type)===$type)>{{ $type }}</option>@endforeach
            </select>
        </div>

        <div>
            <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
            @if($assignment && in_array($assignment->status, ['In Progress', 'Completed']))
                <div class="flex min-h-[50px] items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $assignment->status }}</span>
                    <span class="text-xs text-slate-500">Status otomatis mengikuti workflow employee.</span>
                </div>
                <input type="hidden" name="status" value="{{ $assignment->status }}">
            @else
                <select id="status" name="status" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    @foreach($statuses as $status)<option value="{{ $status }}" @selected(old('status',$assignment?->status ?? 'Draft')===$status)>{{ $status }}</option>@endforeach
                </select>
                <p class="mt-1.5 text-xs text-slate-500">Gunakan Draft untuk assignment terjadwal; Assigned akan langsung tersedia untuk employee.</p>
            @endif
        </div>

        <div></div>

        <x-ui.input name="start_datetime" type="datetime-local" label="Mulai" :value="$assignment?->start_datetime?->format('Y-m-d\TH:i')" required />
        <x-ui.input name="end_datetime" type="datetime-local" label="Deadline" :value="$assignment?->end_datetime?->format('Y-m-d\TH:i')" required />

        <div class="md:col-span-2 rounded-2xl border border-blue-100 bg-blue-50/60 p-4">
            <label class="flex cursor-pointer items-start gap-3">
                <input type="hidden" name="daily_attendance_enabled" value="0">
                <input id="daily-attendance-enabled" type="checkbox" name="daily_attendance_enabled" value="1" class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500" @checked(old('daily_attendance_enabled', $assignment?->daily_attendance_enabled ?? false))>
                <span class="min-w-0 flex-1">
                    <strong class="block text-sm text-slate-800">Aktifkan Attendance Harian</strong>
                    <span class="mt-1 block text-xs leading-5 text-slate-500">Employee melakukan Check In dan Check Out terpisah pada setiap hari wajib selama rentang assignment.</span>
                </span>
            </label>
            <div class="mt-3 border-t border-blue-100 pt-3">
                <label for="attendance_day_rule" class="mb-1.5 block text-xs font-semibold text-slate-600">Hari yang wajib attendance</label>
                <select id="attendance_day_rule" name="attendance_day_rule" class="w-full rounded-xl border-slate-300 bg-white text-sm">
                    <option value="WORK_CALENDAR" @selected(old('attendance_day_rule', $assignment?->attendance_day_rule ?? 'WORK_CALENDAR') === 'WORK_CALENDAR')>Hari kerja Company — mengikuti Work Calendar</option>
                    <option value="EVERY_DAY" @selected(old('attendance_day_rule', $assignment?->attendance_day_rule) === 'EVERY_DAY')>Setiap hari kalender — termasuk weekend/libur</option>
                </select>
            </div>
        </div>

        <div class="md:col-span-2">
            <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi / Instruksi</label>
            <textarea id="description" name="description" rows="4" placeholder="Jelaskan pekerjaan, target, atau catatan yang perlu diketahui employee..." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('description',$assignment?->description) }}</textarea>
        </div>

        <div class="md:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm"><i data-lucide="paperclip" class="h-5 w-5"></i></div>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-800">Lampiran Instruksi</label>
                            <p class="mt-1 text-xs text-slate-500">Foto dikompres otomatis. PDF tetap dikirim apa adanya. Maksimal 5 file total.</p>
                        </div>
                        <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-slate-500">{{ $existingAttachmentCount }}/5 tersimpan</span>
                    </div>

                    @if($assignment && $existingAttachmentCount > 0)
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($assignment->attachments as $file)
                                <a href="{{ secure_file_url($file->file_path) }}" target="_blank" rel="noopener" class="inline-flex max-w-[220px] items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-600 hover:border-blue-200 hover:text-blue-700"><i data-lucide="file" class="h-3.5 w-3.5 shrink-0"></i><span class="truncate">{{ $file->original_name }}</span></a>
                            @endforeach
                        </div>
                    @endif

                    @if($remainingAttachmentSlots > 0)
                        <input id="assignment-attachments" type="file" name="attachments[]" multiple accept="image/jpeg,image/png,image/webp,application/pdf" data-max-files="{{ $remainingAttachmentSlots }}" class="mt-3 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
                        <p id="assignment-attachments-status" class="mt-2 text-xs text-slate-500">Masih dapat menambahkan {{ $remainingAttachmentSlots }} file.</p>
                    @else
                        <p class="mt-3 text-xs font-semibold text-slate-500">Batas 5 lampiran sudah tercapai.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-assignment.section-card>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('assignment-attachments');
  const status = document.getElementById('assignment-attachments-status');
  if (!input) return;
  input.addEventListener('change', async () => {
    const maxFiles = Number(input.dataset.maxFiles || 5);
    const files = Array.from(input.files || []).slice(0, maxFiles);
    const dt = new DataTransfer();
    if (status) status.textContent = 'Menyiapkan lampiran...';
    for (const file of files) {
      if (file.type.startsWith('image/') && window.compressAssignmentPhoto) {
        try { dt.items.add(await window.compressAssignmentPhoto(file)); } catch (_) { dt.items.add(file); }
      } else { dt.items.add(file); }
    }
    input.files = dt.files;
    if (status) status.textContent = `${dt.files.length} file baru siap diupload.`;
  });
});
</script>
