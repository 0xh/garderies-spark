@extends('layouts.two-columns')

@section('title', 'Annonces')

@section('content')
    <div class="card">
        <div class="card-header bg-dark text-white">
            Annonces
            <div class="float-right actions d-print-none">
                <a href="{{route('ads.create', $nursery)}}" class="btn btn-success btn-sm">Ajouter une annonce</a>
            </div>
        </div>
        <div class="card-body">
            @forelse($ads as $ad)
                <div class="card ad mb-4">
                    <div class="card-body">
                        <h3><a href="{{route('ads.show', $ad)}}">{{$ad->title}}</a></h3>
                        <p class="text-muted">{{$ad->created_at->format('d.m.Y')}}</p>
                        <div class="content mb-4">
                            {{ strip_tags(str_limit($ad->description, 200, ' ...')) }}
                        </div>
                        <a href="{{route('ads.show', $ad)}}" class="btn btn-primary">Voir l'annonce</a>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Aucune annonce pour le moment.</div>
            @endforelse
        </div>
    </div>
@endsection

@section('nav-lateral')
    @include('nursery.nav')
@endsection
