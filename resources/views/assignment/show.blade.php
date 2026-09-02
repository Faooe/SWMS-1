@extends('layouts.app')

@section('title','Assignment Detail')
@section('page-title','Assignment Detail')

@section('content')
<div class="space-y-5">
    <x-assignment.show.header :assignment="$assignment" />

    <div class="grid items-start gap-5 xl:grid-cols-[minmax(0,.85fr)_minmax(0,1.15fr)]">
        <x-assignment.show.information :assignment="$assignment" />
        <x-assignment.show.location :assignment="$assignment" />
    </div>

    @livewire('assignment.employee-manager', ['assignment' => $assignment], 'assignment-employees-'.$assignment->id)

    @include('assignment.partials.attachments')

    <x-assignment.show.timeline :assignment="$assignment" />
</div>
@endsection
