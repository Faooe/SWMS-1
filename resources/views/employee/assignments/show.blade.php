@extends('layouts.app')

@section('title','Assignment Detail')

@section('page-title','Assignment Detail')

@section('content')

<div class="space-y-6">

    <a
        href="{{ route('employee.assignments.index') }}"
        class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700">

        <i
            data-lucide="arrow-left"
            class="h-4 w-4">
        </i>

        Back to Assignment

    </a>

    @include('employee.assignments.partials.header')

    @include('employee.assignments.partials.description')

    @include('employee.assignments.partials.location')

    @include('employee.assignments.partials.team')

    @include('employee.assignments.partials.timeline')

    @include('employee.assignments.partials.actions')

</div>

@endsection