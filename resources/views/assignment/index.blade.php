@extends('layouts.app')

@section('title','Assignment')

@section('page-title','Assignment Management')

@section('content')

<x-assignment.statistics.overview
    :statistics="$statistics"/>

<x-assignment.actions.create-button/>

<x-assignment.filters.search/>

<x-assignment.table.table
    :assignments="$assignments"/>

@endsection