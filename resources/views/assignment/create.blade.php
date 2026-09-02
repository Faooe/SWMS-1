@extends('layouts.app')
@section('title','Buat Assignment')
@section('page-title','Buat Assignment')
@section('content')
<form enctype="multipart/form-data" action="{{ route('assignments.store') }}" method="POST" class="mx-auto max-w-7xl space-y-5">
    @csrf
    <x-assignment.forms.header />
    <x-assignment.forms.assignment-information :offices="$offices" :priorities="$priorities" :types="$types" :statuses="$statuses" />
    <x-assignment.forms.location-information />
    <x-assignment.forms.employee-information :employees="$employees" />
    <x-assignment.forms.action-buttons />
</form>
@endsection
