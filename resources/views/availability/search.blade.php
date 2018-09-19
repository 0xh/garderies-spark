@extends('layouts.app')

@section('title', "Recherche de disponibilités")

@section('content')
    <search-substitute :user="user" :current-team="currentTeam" role="{{auth()->user()->roleOnCurrentTeam()}}"></search-substitute>
@endsection