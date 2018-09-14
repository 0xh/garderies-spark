@extends('layouts.two-columns')

@section('title', 'Retour de remplacement')

@section('content')
    <div class="card card-default">
        <div class="card-header">Retour de remplacement</div>
        <div class="card-body">
            <p><strong>Titre :</strong> {{$feedback->name}}</p>
            <p><strong>Description :</strong> <blockquote class="blockquote">{{$feedback->description}}</blockquote></p>
            <p><strong>Date :</strong> {{$feedback->created_at->format('d.m.Y')}}</p>
        </div>
    </div>
@endsection

@section('nav-lateral')
    @include('feedback.nav')
@endsection
