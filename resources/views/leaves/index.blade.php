@extends('layouts.app')
@section('title', 'Leave / Permission Management')
@section('page-title', 'Leave / Permission')
@section('content')
<div class="mb-6">
    <p class="text-sm text-slate-500">Prioritaskan pengajuan Pending, review detail periode, lalu setujui atau tolak dengan alasan yang jelas.</p>
</div>
@livewire('leave.manager')
@endsection
