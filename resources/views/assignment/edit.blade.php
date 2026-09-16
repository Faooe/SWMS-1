@extends('layouts.app')
@section('title','Ubah Assignment')
@section('page-title','Ubah Assignment')
@section('content')
<form enctype="multipart/form-data" action="{{ route('assignments.update', $assignment) }}" method="POST" class="mx-auto max-w-7xl space-y-5">
    @csrf @method('PUT')
    <x-assignment.forms.header :assignment="$assignment" />
    <x-assignment.forms.assignment-information :assignment="$assignment" :offices="$offices" :priorities="$priorities" :types="$types" :statuses="$statuses" />
    <x-assignment.forms.location-information :assignment="$assignment" />
    <x-assignment.forms.employee-information :assignment="$assignment" :employees="$employees" />
    <x-assignment.forms.action-buttons :assignment="$assignment" />
</form>
@endsection
