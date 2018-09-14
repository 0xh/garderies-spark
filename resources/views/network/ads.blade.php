@extends('layouts.two-columns')

@section('title', 'Annonces')

@section('content')
    <div class="card">
        <div class="card-header bg-dark text-white">Annonces</div>
        <div class="card-body">
            @forelse($ads as $ad)
                <div class="card ad mb-4">
                    <div class="card-body">
                        <span class="badge badge-dark float-right">
                            <a href="{{route('nurseries.show', $ad->nursery)}}" class="text-white">{{$ad->nursery->name}}</a>
                        </span>
                        <h3><a href="{{route('ads.show', $ad)}}">{{$ad->title}}</a></h3>
                        <p class="text-muted">{{$ad->created_at->format('d.m.Y')}}</p>
                        <div class="content mb-4">
                            {{ strip_tags(str_limit($ad->description, 200, ' ...')) }}
                        </div>
                        <a href="{{route('ads.show', $ad)}}" class="btn btn-primary">Voir l'annonce</a>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Aucune annonce dans ce réseau de garderies, les annonces sont à insérer par le biais d'une garderie.</div>
            @endforelse
        </div>
    </div>
@endsection

@section('nav-lateral')
    @include('network.nav')
@endsection
