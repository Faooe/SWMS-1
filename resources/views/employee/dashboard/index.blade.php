@extends('layouts.app')

@section('title', 'Employee Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    @include('employee.dashboard.partials.greeting')

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(320px,.65fr)]">
        @include('employee.dashboard.partials.today-overview')
        @include('employee.dashboard.partials.statistics')
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.25fr)_minmax(320px,.75fr)]">
        @include('employee.dashboard.partials.activities')
        @include('employee.dashboard.partials.quick-actions')
    </div>

</div>
@endsection
