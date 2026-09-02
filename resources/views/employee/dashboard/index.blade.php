@extends('layouts.app')

@section('title', 'Employee Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="mx-auto max-w-7xl space-y-5">

    @include('employee.dashboard.partials.greeting')

    <div class="grid grid-cols-1 items-start gap-5 xl:grid-cols-[minmax(0,1.6fr)_minmax(340px,.8fr)]">
        @include('employee.dashboard.partials.today-overview')
        @include('employee.dashboard.partials.statistics')
    </div>

    @include('employee.dashboard.partials.activities')

</div>
@endsection
