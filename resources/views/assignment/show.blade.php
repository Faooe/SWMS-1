@extends('layouts.app')

@section('title','Assignment Detail')

@section('page-title','Assignment Detail')

@section('content')

<div class="space-y-8">

    <x-assignment.show.header
        :assignment="$assignment"/>

    <x-assignment.show.information
        :assignment="$assignment"/>

    <x-assignment.show.location
        :assignment="$assignment"/>

    @livewire('assignment.employee-manager', ['assignment' => $assignment], 'assignment-employees-'.$assignment->id)

    <x-assignment.show.timeline
        :assignment="$assignment"/>

</div>

@endsection