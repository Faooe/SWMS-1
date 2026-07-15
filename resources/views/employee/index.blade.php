@extends('layouts.app')

@section('title', 'Employee')

@section('page-title', 'Employee Management')

@section('content')

<div class="space-y-6">

    <x-employee.stats
        :statistics="$statistics" />

    <x-employee.toolbar />

    <x-employee.table
        :employees="$employees" />
    
    <x-employee.filters
    :departments="$departments"/>

</div>

@endsection