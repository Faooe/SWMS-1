@extends('layouts.app')

@section('title', 'Edit Employee')

@section('page-title', 'Edit Employee')

@section('content')

<form

    action="{{ route('employees.update', $employee) }}"

    method="POST"

    enctype="multipart/form-data"

    class="space-y-8">

    @csrf

    @method('PUT')

    {{-- Header --}}
    <x-employee.forms.header
        :employee="$employee"
    />

    {{-- Personal Information --}}
    <x-employee.forms.personal-information
        :employee="$employee"
    />

    {{-- Employment Information --}}
    <x-employee.forms.employment-information
        :employee="$employee"
        :offices="$offices"
        :departments="$departments"
        :positions="$positions"
        :teams="$teams"
        :employees="$employees"
    />

    {{-- Account Information --}}
    <x-employee.forms.account-information
        :employee="$employee"
    />

    {{-- Photo --}}
    <x-employee.forms.photo-upload
        :employee="$employee"
    />

    {{-- Action Buttons --}}
    <x-employee.forms.action-buttons
        :employee="$employee"
    />

</form>

@endsection