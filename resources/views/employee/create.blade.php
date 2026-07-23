@extends('layouts.app')

@section('title', 'Add Employee')

@section('page-title', 'Add Employee')

@section('content')

<form

    action="{{ route('employees.store') }}"

    method="POST"

    enctype="multipart/form-data"

    class="space-y-8">

    @csrf

    {{-- Header --}}
    <x-employee.forms.header />

    {{-- Personal Information --}}
    <x-employee.forms.personal-information />

    {{-- Employment Information --}}
    <x-employee.forms.employment-information

        :departments="$departments"

        :positions="$positions"

        :teams="$teams"

        :employees="$employees"

    />

    {{-- Account Information --}}
    <x-employee.forms.account-information />

    {{-- Photo --}}
    <x-employee.forms.photo-upload />

    {{-- Action Buttons --}}
    <x-employee.forms.action-buttons />

</form>

@endsection