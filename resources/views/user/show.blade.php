@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <user-show inline-template :is-favorite="{{boolval($isFavorite) ? 'true' : 'false'}}" :user="user">
        <div>
            <div class="row d-print-none">
                <div class="col mb-2">
                    @if (auth()->user()->roleOnCurrentTeam() == 'owner')
                        <a href="{{route('users.index')}}" class="btn btn-info btn-sm">&larr; Retour aux utilisateurs</a>
                    @else
                        <a href="/" class="btn btn-info btn-sm">&larr; Retour à l'accueil</a>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-5">

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
                                        <a href="{{route('users.edit', [$user->id])}}" class="btn btn-info btn-sm mr-2"><i class="fas fa-edit"></i> Editer</a>
                                    @endcan
                                    @can('delete', $user)
                                        <a href="#" v-on:click.prevent="deleteUser({{$user->id}})" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Supprimer</a>
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
                                        <span class="badge text-white" style="background: {{$network->color}};">{{$network->name}}</span>
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
                                        <span v-if="favorite">
                                            Ajouté aux favoris
                                        </span>
                                        <span v-else>
                                            Ajouter aux favoris
                                        </span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <ul class="list-group mb-4">
                        <li class="list-group-item"><a href="{{route('users.bookings', $user)}}">Remplacements archivés</a></li>
                    </ul>

                </div>
                <div class="col-xl-8 col-lg-7">
                    {{-- Bookings --}}
                    <div class="card card-default mb-4">
                        <div class="card-header">Prochains remplacements</div>
                        <div class="card-body">
                            @if (!$bookings->count())
                                <div class="alert alert-info">Aucun remplacement prévu.</div>
                            @else
                                <table class="table table-borderless table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th>Jour</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Garderie</th>
                                        <th width="120">Status</th>
                                        <th width="50"></th>
                                    </tr>
                                    </thead>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>{{$booking->start->format('d.m.Y')}}</td>
                                            <td>{{$booking->start->format('H\hi')}}</td>
                                            <td>{{$booking->end->format('H\hi')}}</td>
                                            <td><a href="{{route('nurseries.show', $booking->nursery ?? 0)}}">{{$booking->nursery->name ?? '-'}}</a></td>
                                            <td>
                                                @switch($booking->status)
                                                    @case(\App\Booking::STATUS_PENDING)
                                                    <span class="badge badge-info">En attente</span>
                                                    @break
                                                    @case(\App\Booking::STATUS_APPROVED)
                                                    <span class="badge badge-success">Validé</span>
                                                    @break
                                                    @case(\App\Booking::STATUS_ARCHIVED)
                                                    <span class="badge badge-dark">Archivé</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td><a href="{{route('bookings.show', $booking)}}">Voir</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                        </div>
                    </div>
                    {{-- Booking request --}}
                    <div class="card card-default mb-4">
                        <div class="card-header">Demandes en attente</div>
                        <div class="card-body">
                            @if (!$bookingRequests->count())
                                <div class="alert alert-info">Aucune demande en attente.</div>
                            @else
                                <table class="table table-borderless table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th>Jour</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Garderie</th>
                                        <th width="120">Status</th>
                                        <th width="50"></th>
                                    </tr>
                                    </thead>
                                    @foreach($bookingRequests as $request)
                                        <tr>
                                            <td>{{$request->start->format('d.m.Y')}}</td>
                                            <td>{{$request->start->format('H\hi')}}</td>
                                            <td>{{$request->end->format('H\hi')}}</td>
                                            <td><a href="{{route('nurseries.show', $request->nursery ?? 0)}}">{{$request->nursery->name ?? '-'}}</a></td>
                                            <td>
                                                @switch($request->status)
                                                    @case(\App\Booking::STATUS_PENDING)
                                                    <span class="badge badge-info">{{\App\Booking::STATUS_PENDING_LABEL}}</span>
                                                    @break
                                                    @case(\App\Booking::STATUS_APPROVED)
                                                    <span class="badge badge-success">{{\App\Booking::STATUS_APPROVED_LABEL}}</span>
                                                    @break
                                                    @case(\App\Booking::STATUS_DENIED)
                                                    <span class="badge badge-danger">{{\App\Booking::STATUS_DENIED_LABEL}}</span>
                                                    @break
                                                    @case(\App\Booking::STATUS_ARCHIVED)
                                                    <span class="badge badge-dark">{{\App\Booking::STATUS_ARCHIVED_LABEL}}</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td><a href="{{route('booking-requests.show', $request)}}">Voir</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                        </div>
                    </div>
                    {{-- Availabilities --}}
                    <div class="card card-default mb-4">
                        <div class="card-header">
                            Prochaines disponibilités
                            <div class="actions float-right">
                                <a href="{{route('users.availabilities', $user->id)}}" class="btn btn-info btn-sm"><i class="fas fa-calendar"></i> Gérer les disponibilités</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (!$availabilities->count())
                                <div class="alert alert-info">Aucune disponibilité renseignée pour le moment.</div>
                                <a href="{{route('users.availabilities', $user->id)}}" class="btn btn-info"><i class="fas fa-calendar"></i> Gérer les disponibilités</a>
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
                    {{-- Own Booking request --}}
                    <div class="card card-default mb-4">
                        <div class="card-header">Demandes de remplacement pour cet utilisateur</div>
                        <div class="card-body">
                            @if (!$userBookingRequests->count())
                                <div class="alert alert-info">Aucune demande en attente.</div>
                            @else
                                <table class="table table-borderless table-striped table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th>Jour</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Garderie</th>
                                        <th>Remplaçant</th>
                                        <th width="120">Status</th>
                                        <th width="50"></th>
                                    </tr>
                                    </thead>
                                    @foreach($userBookingRequests as $request)
                                        <tr>
                                            <td>{{$request->start->format('d.m.Y')}}</td>
                                            <td>{{$request->start->format('H\hi')}}</td>
                                            <td>{{$request->end->format('H\hi')}}</td>
                                            <td><a href="{{route('nurseries.show', $request->nursery ?? 0)}}">{{$request->nursery->name ?? '-'}}</a></td>
                                            <td><a href="{{route('users.show', $request->substitute ?? 0)}}">{{$request->substitute->name ?? '-'}}</a></td>
                                            <td>
                                                @switch($request->status)
                                                    @case(\App\Booking::STATUS_PENDING)
                                                    <span class="badge badge-info">{{\App\Booking::STATUS_PENDING_LABEL}}</span>
                                                    @break
                                                    @case(\App\Booking::STATUS_APPROVED)
                                                    <span class="badge badge-success">{{\App\Booking::STATUS_APPROVED_LABEL}}</span>
                                                    @break
                                                    @case(\App\Booking::STATUS_DENIED)
                                                    <span class="badge badge-danger">{{\App\Booking::STATUS_DENIED_LABEL}}</span>
                                                    @break
                                                    @case(\App\Booking::STATUS_ARCHIVED)
                                                    <span class="badge badge-dark">{{\App\Booking::STATUS_ARCHIVED_LABEL}}</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td><a href="{{route('booking-requests.show', $request)}}">Voir</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            {!! $chart->container() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </user-show>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" charset="utf-8"></script>
    {!! $chart->script() !!}
@endsection
