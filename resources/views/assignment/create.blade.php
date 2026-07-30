@extends('layouts.app')

@section('title','Create Assignment')

@section('page-title','Create Assignment')

@section('content')

<form
    action="{{ route('assignments.store') }}"
    method="POST"
    class="space-y-8">

    @csrf

    <x-assignment.forms.header />

    <x-assignment.forms.assignment-information
        :offices="$offices"
        :priorities="$priorities"
        :types="$types"
        :statuses="$statuses"
    />

    <x-assignment.forms.location-information />

    <x-assignment.forms.employee-information
        :employees="$employees"
    />

    <x-assignment.forms.action-buttons />

</form>

@endsection