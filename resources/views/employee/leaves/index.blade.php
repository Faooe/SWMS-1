@extends('layouts.app')
@section('title', 'Leave / Permission')
@section('page-title', 'Leave / Permission')
@section('content')
<div class="mb-6">
    <p class="text-sm text-slate-500">Ajukan cuti atau izin dan pantau proses review company dalam satu halaman.</p>
</div>
@livewire('employee.leave-manager')
@endsection
