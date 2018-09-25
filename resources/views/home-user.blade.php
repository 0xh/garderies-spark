@extends('layouts.app')

@section('title', 'Logiciels de gestion de garderie')

@section('content')
    <div class="row">
        <div class="col-md-8">
            {{-- Next bookings --}}
            <div class="card mb-4">
                <div class="card-header bg-dark text-white"><i class="fas fa-calendar-alt mr-2"></i> Vos prochains
                    remplacements
                </div>
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
                <div class="card-header bg-dark text-white"><i class="fas fa-user-clock mr-2"></i> Demandes de
                    remplacements en attente
                </div>
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
            {{-- Availabilities --}}
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-clock mr-2"></i> Prochaines disponibilités
                    <div class="actions float-right">
                        <a href="{{route('users.availabilities', $user->id)}}" class="btn btn-info btn-sm"><i
                                    class="fas fa-calendar"></i> Gérer mes disponibilités</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (!$availabilities->count())
                        <div class="alert alert-info">Aucune disponibilité renseignée pour le moment.</div>
                        <a href="{{route('users.availabilities', $user->id)}}" class="btn btn-info"><i
                                    class="fas fa-calendar"></i> Gérer mes disponibilités</a>
                    @else
                        <table class="table table-borderless table-striped table-responsive-lg">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th width="30">Status</th>
                            </tr>
                            </thead>
                            @foreach($availabilities as $availability)
                                <tr>
                                    <td>{{$availability->start->format('d.m.Y')}}</td>
                                    <td>{{$availability->start->format('H\hi')}}</td>
                                    <td>{{$availability->end->format('H\hi')}}</td>
                                    <td>
                                        @switch($availability->status)
                                            @case(\App\Availability::STATUS_UNTOUCHED)
                                            <span class="badge badge-success">{{\App\Availability::STATUS_UNTOUCHED_LABEL}}</span>
                                            @break
                                            @case(\App\Availability::STATUS_PARTIALLY_BOOKED)
                                            <span class="badge badge-warning">{{\App\Availability::STATUS_PARTIALLY_BOOKED_LABEL}}</span>
                                            @break
                                            @case(\App\Availability::STATUS_BOOKED)
                                            <span class="badge badge-danger">{{\App\Availability::STATUS_BOOKED_LABEL}}</span>
                                            @break
                                            @case(\App\Availability::STATUS_ARCHIVED)
                                            <span class="badge badge-dark">{{\App\Availability::STATUS_ARCHIVED_LABEL}}</span>
                                            @break
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
            {{-- Favorite substitutes --}}
            <div class="card">
                <div class="card-header bg-dark text-white"><i class="fas fa-star mr-2"></i> Vos remplacants favoris
                </div>
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
                                                <span class="badge text-white"
                                                      style="background: {{$network->color}};">{{$network->name}}</span>
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

            {{-- User's infos --}}
            <div class="card mb-4">
                <div class="profile-card text-center">
                    <div class="card-body">

                        <div class="avatar mb-2 center-block">
                            {!! Avatar::create($user->name)->setDimension(140, 140)->setFontSize(44)->toSvg() !!}
                        </div>
                        <div>
                            <h5>{{$user->name}}</h5>
                        </div>
                        <div class="actions pt-2 d-print-none">
                            @can('update', $user)
                                <a href="{{route('users.edit', [$user->id])}}" class="btn btn-info btn-sm mr-2"><i
                                            class="fas fa-edit"></i> Editer mon profil</a>
                            @endcan
                            @can('delete', $user)
                                <a href="#" v-on:click.prevent="deleteUser({{$user->id}})"
                                   class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Supprimer</a>
                            @endcan
                        </div>
                    </div>
                    <ul class="list-group list-group-flush text-left">
                        <li class="list-group-item">
                            <strong>Téléphone :</strong>
                            <span class="text-muted">
                                <a href="tel:{{$user->phone}}">{{$user->phone}}</a>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <strong>E-mail :</strong>
                            <span class="text-muted">
                                <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <strong>Garderie :</strong>
                            <span class="text-muted">
                                @if ($user->nursery)
                                    <a href="{{route('nurseries.show', $user->nursery)}}">{{$user->nursery->name ?? '-'}}</a>
                                @else
                                    -
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <strong>Diplôme :</strong> <span class="text-muted">{{$user->diploma->name ?? '-'}}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Préférences de contact :</strong>
                            @foreach($contactPreferences as $preference)
                            <span class="text-muted p-1" data-toggle="tooltip" title="{{$contactPreferencesLabels[$preference]['label']}}"><i class="{{$contactPreferencesLabels[$preference]['icon']}}"></i></span>
                            @endforeach
                        </li>
                        <li class="list-group-item">
                            <strong>Groupes de travail :</strong>
                            @forelse($user->workgroups as $workgroup)
                                <span class="badge badge-warning">{{$workgroup->name}}</span>
                            @empty
                                -
                            @endforelse
                        </li>
                        <li class="list-group-item">
                            <strong>Réseaux :</strong>
                            @forelse ($user->networks as $network)
                                <span class="badge text-white"
                                      style="background: {{$network->color}};">{{$network->name}}</span>
                            @empty
                                -
                            @endforelse
                        </li>
                        <li class="list-group-item">
                            <strong>Equipes :</strong>
                            @forelse ($user->teams as $team)
                                <span class="badge badge-dark">{{$team->name}}</span>
                            @empty
                                -
                            @endforelse
                        </li>
                        @if (auth()->user()->id != $user->id)
                            <li class="list-group-item">
                                <a href="#" v-on:click.prevent="addToFavorite({{$user->id}})">
                                    <i class="text-warning" :class="[favorite ? 'fas fa-star' : 'far fa-star']"></i>
                                    Ajouter aux favoris
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>
@endsection
