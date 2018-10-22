@extends('layouts.app')

@section('title', 'Logiciels de gestion de garderie')

@section('content')

    @if ($count_nursery == 0 || $count_user == 0)
    <div class="alert alert-primary alert-guided-tour">
        Nouveau sur <em>Garderies</em> ? Découvrez comment nous pouvons vous simplifier la gestion de vos structures d'accueil.
        <a href="{{config('app.url')}}/?{{str_random(5)}}#tour">Démarrer la visite</a>
    </div>
    @endif

    <div class="card mb-4 dashboard-summary">
        <div class="card-body">
            <div class="row mb-0">
                <div class="col-md-4">
                    <div class="dashboard-summary__count v-step-0 dashboard-summary--employees border-right">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <div class="number"><a href="{{route('users.index')}}" class="text-secondary">{{$count_user}}</a></div>
                        <h3 class="text-muted"><a href="{{route('users.index')}}" class="text-secondary">Employés</a></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-summary__count v-step-1 dashboard-summary--bookings border-right">
                        <div class="icon"><i class="fas fa-user-clock"></i></div>
                        <div class="number"><a href="{{route('bookings.index')}}" class="text-secondary">{{$count_booking}}</a></div>
                        <h3 class="text-muted"><a href="{{route('bookings.index')}}" class="text-secondary">Remplacements ce mois</a></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-summary__count v-step-2 dashboard-summary--booking-requests">
                        <div class="icon"><i class="fas fa-user-check"></i></div>
                        <div class="number"><a href="{{route('booking-requests.index')}}" class="text-secondary">{{$count_booking_req}}</a></div>
                        <h3 class="text-muted"><a href="{{route('booking-requests.index')}}" class="text-secondary">Demandes de remplacements</a></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            {{-- Next bookings --}}
            <div class="card mb-4 planned-bookings">
                <div class="card-header bg-dark text-white"><i class="fas fa-calendar-alt mr-2"></i> Remplacements planifiés</div>
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
                        <div class="alert alert-info m-0">Aucun remplacement prévu</div>
                    @endif
                </div>
            </div>
            {{-- Pending booking requests --}}
            <div class="card pending-bookings">
                <div class="card-header bg-dark text-white"><i class="fas fa-user-clock mr-2"></i> Demandes de remplacements en attente</div>
                <div class="card-body">
                    @if($bookingRequests->count())
                        <div class="table-responsive">
                            <table class="table table-borderless table-striped">
                                <thead>
                                <tr>
                                    <th>Employé</th>
                                    <th>Remplaçant</th>
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
                                        <td>
                                            <a href="{{route('users.show', $request->substitute->id ?? 0)}}">
                                                {{$request->substitute->name ?? 'Aucun'}}
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
                        <div class="alert alert-info m-0">Aucune demande en attente</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card birthdays">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-birthday-cake mr-2"></i> Anniversaires à venir
                </div>
                <div class="card-body">
                    @if ($birthdays->count())
                    <table class="table table-borderless table-striped table-sm m-0">
                        @foreach($birthdays as $birthday)
                        <tr>
                            <td>{{$birthday->birthdate->format('d.m.') . now()->format('Y')}}</td>
                            <td><a href="{{route('users.show', $birthday)}}">{{$birthday->name}}</a></td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                        <div class="alert alert-info m-0">Aucun anniversaire à venir</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card card-default">
                <div class="card-header">Remplacements / disponibilités</div>
                <div class="card-body">
                    <div class="v-step-3" style="height: 400px;">{!! $chartBookings->container() !!}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('hook-vue')
    <director-tour></director-tour>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" charset="utf-8"></script>
    {!! $chartBookings->script() !!}
@endsection
