@extends('layouts.app')

@section('title', 'Tambah Employee')
@section('page-title', 'Employee')

@section('content')
<form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-6xl space-y-5">
    @csrf
    <x-employee.forms.header />
    <x-employee.forms.personal-information />
    <x-employee.forms.employment-information :departments="$departments" :positions="$positions" :teams="$teams" :offices="$offices" :employees="$employees" />
    <x-employee.forms.account-information />
    <x-employee.forms.action-buttons />
</form>
@endsection
