@extends('layouts.app')

@section('title','Assignment')

@section('page-title','Assignment Management')

@section('content')

<div class="mb-4 flex justify-end">
    <a
        href="{{ route('assignment-settings.edit') }}"
        class="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
        <i data-lucide="settings" class="h-4 w-4"></i>
        Pengaturan Assignment
    </a>
</div>

<x-assignment.statistics.overview
    :statistics="$statistics"/>

<x-assignment.actions.create-button/>

<x-assignment.filters.search/>

<x-assignment.table.table
    :assignments="$assignments"/>

@endsection