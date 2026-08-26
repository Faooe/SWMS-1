@extends('layouts.app')

@section('title', 'Attendance History')

@section('page-title', 'Attendance History')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>

            <p class="text-sm text-slate-500">

                Riwayat absensi kamu.

            </p>

        </div>

        <a href="{{ route('employee.attendance.index') }}">

            <x-ui.button variant="secondary">

                <i data-lucide="arrow-left" class="h-5 w-5"></i>

                Back

            </x-ui.button>

        </a>

    </div>

    @include('employee.attendance.partials.attendance-history')

</div>

@endsection