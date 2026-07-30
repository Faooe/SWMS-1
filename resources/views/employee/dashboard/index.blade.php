@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')

<div class="space-y-6">

    @include('employee.dashboard.partials.greeting')

    @include('employee.dashboard.partials.statistics')

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        @include('employee.dashboard.partials.attendance')

        @include('employee.dashboard.partials.assignment')

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        @include('employee.dashboard.partials.activities')

        @include('employee.dashboard.partials.quick-actions')

    </div>

</div>

@endsection