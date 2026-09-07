@extends('layouts.app')

@section('title', 'Attendance Detail')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm font-medium text-slate-500">
                <a href="{{ route('attendance.index') }}" class="transition hover:text-blue-600">Attendance</a>
                <span>/</span>
                <span>Detail</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Attendance Detail</h1>
            <p class="mt-1 text-sm text-slate-500">Informasi kehadiran, validasi lokasi, dokumentasi, dan aktivitas employee.</p>
        </div>

        <a href="{{ route('attendance.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke Attendance
        </a>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xl:col-span-6">
            @include('attendance.partials.employee-card')
        </div>

        <div class="col-span-12 xl:col-span-6">
            @include('attendance.partials.attendance-card')
        </div>

        <div class="col-span-12 xl:col-span-6">
            @include('attendance.partials.gps-card')
        </div>

        <div class="col-span-12 xl:col-span-6">
            @include('attendance.partials.photos-card')
        </div>

        <div class="col-span-12">
            @include('attendance.partials.timeline-card')
        </div>
    </div>
</div>

<div id="photoModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/85 p-4 backdrop-blur-sm" onclick="closePhoto()">
    <div class="relative max-h-[92vh] max-w-6xl" onclick="event.stopPropagation()">
        <button onclick="closePhoto()"
                class="absolute -right-2 -top-12 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-slate-700 shadow-lg transition hover:bg-slate-100"
                aria-label="Close photo preview">
            <i data-lucide="x" class="h-5 w-5"></i>
        </button>
        <img id="photoPreview" alt="Attendance photo preview" class="max-h-[88vh] max-w-full rounded-2xl bg-white object-contain shadow-2xl">
    </div>
</div>

@push('scripts')
<script>
function openPhoto(url) {
    const modal = document.getElementById('photoModal');
    document.getElementById('photoPreview').src = url;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closePhoto() {
    const modal = document.getElementById('photoModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closePhoto();
});
</script>
@endpush
@endsection
