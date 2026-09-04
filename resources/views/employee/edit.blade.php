@extends('layouts.app')

@section('title', 'Edit Employee')
@section('page-title', 'Employee')

@section('content')
<form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-6xl space-y-5">
    @csrf
    @method('PUT')
    <x-employee.forms.header :employee="$employee" />
    <x-employee.forms.personal-information :employee="$employee" />
    <x-employee.forms.employment-information :employee="$employee" :departments="$departments" :positions="$positions" :teams="$teams" :offices="$offices" :employees="$employees" />
    <x-employee.forms.account-information :employee="$employee" />
    <x-employee.forms.action-buttons :employee="$employee" />
</form>
@endsection
