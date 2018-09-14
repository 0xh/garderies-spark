@extends('layouts.app')

@section('title', "Recherche de disponibilités")

@section('content')
    <search-substitute :current-team="currentTeam"></search-substitute>
@endsection