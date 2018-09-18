@extends('layouts.app')

@section('title', 'Logiciels de gestion de garderie')

@section('content')
    <div class="alert alert-primary alert-guided-tour" style="display: none;">
        Nouvel utilisateur ? Suivez la visite guidée pour découvrir en quoi <em>Garderies</em> peut vous simplifier la gestion de vos structures d'accueil.
        <a href="{{config('app.url')}}/?{{str_random(5)}}#tour">Démarrer la visite</a>
    </div>

    <div class="card mb-4 dashboard-summary">
        <div class="card-body">
            <div class="row mb-0">
                <div class="col-md-4">
                    <div class="dashboard-summary__count v-step-0 dashboard-summary--nurseries">
                        <div class="icon"><i class="fas fa-building"></i></div>
                        <div class="number"><a href="/nurseries" class="text-secondary">{{$count_nursery}}</a></div>
                        <h3 class="text-muted"><a href="/nurseries" class="text-secondary">Garderies dans vos réseaux</a></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-summary__count v-step-1 dashboard-summary--employees">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <div class="number"><a href="/users" class="text-secondary">{{$count_user}}</a></div>
                        <h3 class="text-muted"><a href="/users" class="text-secondary">Nombre d'employés</a></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-summary__count v-step-2 dashboard-summary--substitutes">
                        <div class="icon"><i class="fas fa-user-clock"></i></div>
                        <div class="number"><a href="/booking-requests" class="text-secondary">{{$count_booking}}</a></div>
                        <h3 class="text-muted"><a href="/booking-requests" class="text-secondary">Remplacements ce mois</a></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card card-default">
                <div class="card-header">Remplacements / disponibilités</div>
                <div class="card-body">
                    <div class="v-step-3" style="height: 400px;">{!! $chartBookings->container() !!}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">Garderies</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($nurseries as $nursery)
                        <li class="list-group-item">
                            <a href="{{route('nurseries.show', $nursery)}}">{{$nursery->name}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('hook-vue')
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" charset="utf-8"></script>
    {!! $chartBookings->script() !!}
@endsection
