@extends('layouts.app')

@section('title','Edit Assignment')

@section('page-title','Edit Assignment')

@section('content')

<form
    action="{{ route('assignments.update', $assignment) }}"
    method="POST"
    class="space-y-8">

    @csrf
    @method('PUT')

    <x-assignment.forms.header
        :assignment="$assignment"
    />

    <x-assignment.forms.assignment-information
        :assignment="$assignment"
        :offices="$offices"
        :priorities="$priorities"
        :types="$types"
        :statuses="$statuses"
    />

    <x-assignment.forms.location-information
        :assignment="$assignment"
    />

    <x-assignment.forms.employee-information
        :assignment="$assignment"
        :employees="$employees"
    />

    <x-assignment.forms.action-buttons
        :assignment="$assignment"
    />

</form>

@endsection