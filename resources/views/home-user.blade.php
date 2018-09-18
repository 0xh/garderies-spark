@extends('layouts.app')

@section('title', 'Logiciels de gestion de garderie')

@section('content')
    <div class="row">
        <div class="col-md-8">
            {{-- Next bookings --}}
            <div class="card mb-4">
                <div class="card-header bg-dark text-white"><i class="fas fa-calendar-alt mr-2"></i> Vos prochains remplacements</div>
                <div class="card-body">
                    @if($bookings->count())
                        <div class="table-responsive">
                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Garderie</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th width="50"></th>
                            </tr>
                            </thead>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>{{$booking->start->format('d.m.Y')}}</td>
                                    <td><a href="#">{{$booking->nursery->name ?? '-'}}</a></td>
                                    <td>{{$booking->start->format('H:i')}}</td>
                                    <td>{{$booking->end->format('H:i')}}</td>
                                    <td><a href="{{route('bookings.show', $booking)}}">Voir</a></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    @else
                        <div class="alert alert-info">Aucun remplacement prévu</div>
                    @endif
                </div>
            </div>
            {{-- Pending booking requests --}}
            <div class="card mb-4">
                <div class="card-header bg-dark text-white"><i class="fas fa-user-clock mr-2"></i> Demandes de remplacements en attente</div>
                <div class="card-body">
                    @if($bookingRequests->count())
                        <div class="table-responsive">
                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Date</th>
                                <th>Horaire</th>
                                <th>Disponibilité</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bookingRequests as $request)
                                <tr>
                                    <td>
                                        <a href="{{route('users.show', $request->user->id ?? 0)}}">
                                            {{$request->user->name ?? 'Aucun'}}
                                        </a>
                                    </td>
                                    <td>{{$request->start->format('d.m.Y')}}</td>
                                    <td>
                                    <span style="font-size: 0.9em;">
                                        {{$request->start->format('H\hi')}} <i
                                                class="fas fa-arrow-right"
                                                style="font-size: .7em;"></i> {{$request->end->format('H\hi')}}
                                    </span>
                                    </td>
                                    <td>
                                        @if ($request->availability)
                                            <span style="font-size: 0.9em;">
                                            {{$request->availability->start->format('H\hi')}}
                                                <i class="fas fa-arrow-right" style="font-size: .7em;"></i>
                                                {{$request->availability->end->format('H\hi')}}
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('booking-requests.show', $request)}}">Voir</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert alert-info">Aucune demande en attente</div>
                    @endif
                </div>
            </div>
            {{-- Favorite substitutes --}}
            <div class="card">
                <div class="card-header bg-dark text-white"><i class="fas fa-star mr-2"></i> Vos remplacants favoris</div>
                <div class="card-body">
                    @if($favorites->count())
                        <div class="table-responsive">
                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Diplôme</th>
                                <th>Garderie</th>
                                <th width="150">Réseaux</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($favorites as $favorite_user)
                                <tr>
                                    <td>{{$favorite_user->name}}</td>
                                    <td>{{$favorite_user->diploma->name ?? '-'}}</td>
                                    <td>{{$favorite_user->nursery->name ?? '-'}}</td>
                                    <td>
                                        @foreach($favorite_user->networks as $network)
                                            <span class="badge text-white" style="background: {{$network->color}};">{{$network->name}}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert alert-info">Vous n'avez pas encore de remplaçant favoris</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 order-first order-md-last">
            {{-- Next booking --}}
            @if ($bookings->count())
            <div class="card mb-4 bg-info text-white">
                <div class="card-body text-center">Prochain remplacement</div>
                <div class="card-body text-center pt-0">
                    <h1 style="font-size: 4rem;">{{$bookings->first()->start->format('d')}}</h1>
                    <div>{{$months[$bookings->first()->start->month - 1]}}</div>
                </div>
            </div>
            @endif
            {{-- User's availabilities --}}
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-user-clock mr-2"></i> Vos disponibilités</div>
                <div class="card-body">
                    @if($availabilities->count())
                        <div class="table-responsive">
                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Début</th>
                                <th>Fin</th>
                            </tr>
                            </thead>
                            @foreach($availabilities as $availability)
                                <tr>
                                    <td>{{$availability->start->format('d.m.Y')}}</td>
                                    <td>{{$availability->start->format('H:i')}}</td>
                                    <td>{{$availability->end->format('H:i')}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    @else
                        <div class="alert alert-info">Vous n'avez pas encore de disponibilités</div>
                    @endif
                    <a href="{{route('users.availabilities', $user)}}" class="btn btn-info btn-block mt-3">Gérer mes disponibilités</a>
                </div>
            </div>
        </div>
    </div>
@endsection
