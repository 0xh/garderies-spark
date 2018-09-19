@extends('layouts.two-columns')

@section('title', 'Demande de remplacement')

@section('content')
    <booking-request-show inline-template>
        <div class="card card-default">
            <div class="card-header">
                Demande de remplacement pour le {{$bookingRequest->start->format('d.m.Y')}}

                <div class="actions float-right d-print-none">
                    @if (
                    $bookingRequest->status == \App\BookingRequest::STATUS_PENDING &&
                    $bookingRequest->availability->status != \App\Availability::STATUS_BOOKED &&
                    !$conflicts->has_conflicts
                    )
                        <a href="#" v-on:click.prevent="validateBookingRequest({{$bookingRequest->id}})" class="btn btn-success btn-sm mr-2"><i class="fas fa-check"></i> Valider</a>
                    @endif
                    <a href="#" v-on:click.prevent="deleteBookingRequest({{$bookingRequest->id}})" class="btn btn-danger btn-sm"><i class="fas fa-edit"></i> Supprimer</a>
                </div>
            </div>

            <div class="card-body">

                @switch($bookingRequest->status)
                    @case(\App\BookingRequest::STATUS_PENDING)
                    <div class="alert alert-info">Demande en attente</div>
                    @break
                    @case(\App\BookingRequest::STATUS_APPROVED)
                    <div class="alert alert-success">Demande approuvée</div>
                    @break
                    @case(\App\BookingRequest::STATUS_DENIED)
                    <div class="alert alert-danger">Demande refusée</div>
                    @break
                @endswitch

                @if (($bookingRequest->availability->status == \App\Availability::STATUS_BOOKED || $bookingRequest->availability->status == \App\Availability::STATUS_ARCHIVED))
                        <div class="alert alert-warning">Le remplaçant n'est plus disponible pour cet horaire</div>
                @endif

                @if ($conflicts->has_conflicts && $bookingRequest->availability->status == \App\Availability::STATUS_PARTIALLY_BOOKED && $bookingRequest->status != \App\BookingRequest::STATUS_APPROVED)
                    <div class="alert alert-danger">
                        Cette demande entre en conflit avec d'autres remplacements :

                        <ul class="m-0">
                            @foreach($conflicts->conflicts as $conflict)
                                <li>Remplacement de {{$conflict->start->format('H:i')}} à {{$conflict->end->format('H:i')}} dans la garderie {{$conflict->nursery->name}}</li>
                            @endforeach
                        </ul>
                    </div>
                @elseif (!$conflicts->has_conflicts && $bookingRequest->availability->status == \App\Availability::STATUS_PARTIALLY_BOOKED)
                    <div class="alert alert-info">Le remplaçant est occupé partiellement, mais peut avoir un autre crénau horaire assigné.</div>
                @endif

                <div class="progress mt-4 mb-4 d-print-none">
                    <div class="progress-bar progress-bar-striped bg-transparent text-dark" role="progressbar" style="width: {{$start_pct}}%" aria-valuenow="{{$start_pct}}" aria-valuemin="0" aria-valuemax="100">{{$bookingRequest->start->format('H:i')}}</div>
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{$completion_pct}}%" aria-valuenow="{{$completion_pct}}" aria-valuemin="0" aria-valuemax="100">{{$bookingRequest->availability->start->format('H:i')}} - {{$bookingRequest->availability->end->format('H:i')}}</div>
                    <div class="progress-bar progress-bar-striped bg-transparent text-dark" role="progressbar" style="width: {{$end_pct}}%" aria-valuenow="{{$end_pct}}" aria-valuemin="0" aria-valuemax="100">{{$bookingRequest->end->format('H:i')}}</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4 mb-sm-0">
                        <div class="card bg-light">
                            <div class="card-header text-center">Employé</div>
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="avatar avatar--sm mb-2">
                                        {!! Avatar::create($bookingRequest->user->name)->toSvg() !!}
                                    </div>
                                    <h4>{{$bookingRequest->user->name ?? '-'}}</h4>
                                </div>
                                <hr>
                                <p>Demande de remplacement faite par l'employé.</p>
                                <p><strong>Date :</strong> {{$bookingRequest->start->format('d.m.Y')}}</p>
                                <p><strong>Début :</strong> {{$bookingRequest->start->format('H\hi')}}</p>
                                <p><strong>Fin :</strong> {{$bookingRequest->end->format('H\hi')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header text-center">Remplaçant</div>
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="avatar avatar--sm mb-2">
                                        {!! Avatar::create($bookingRequest->substitute->name)->toSvg() !!}
                                    </div>
                                    <h4>{{$bookingRequest->substitute->name ?? '-'}}</h4>
                                </div>
                                <hr>
                                @if ($bookingRequest->availability)
                                    <p>Disponibilité proposée par le remplaçant.</p>
                                    <p><strong>Date :</strong> {{$availability->start->format('d.m.Y')}}</p>
                                    <p>
                                        <strong>Dès :</strong>
                                        @if (!$conflict_start)
                                            <span class="text-success">{{$availability->start->format('H\hi')}}</span>
                                        @else
                                            <span class="text-danger" data-toggle="tooltip" title="{{$bookingRequest->substitute->name ?? 'Le remplaçant'}} n'est pas disponible dès l'heure de début">{{$availability->start->format('H\hi')}}</span>
                                        @endif
                                    </p>
                                    <p>
                                        <strong>Jusqu'à :</strong>
                                        @if (!$conflict_end)
                                            <span class="text-success">{{$availability->end->format('H\hi')}}</span>
                                        @else
                                            <span class="text-danger" data-toggle="tooltip" title="{{$bookingRequest->substitute->name ?? 'Le remplaçant'}} n'est pas disponible jusqu'à l'heure de fin">{{$availability->end->format('H\hi')}}</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Garderie :</strong>
                            <a href="{{route('nurseries.show', $bookingRequest->nursery ?? 0)}}">{{$bookingRequest->nursery->name ?? '-'}}</a>
                        </p>
                        <p><strong>Groupe de travail :</strong> {{$bookingRequest->workgroup->name ?? '-'}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Message pour le remplaçant :</strong></p>
                        <p class="mb-0">{{$bookingRequest->message}}</p>
                    </div>
                </div>
    
                <a href="{{ route('booking-requests.index') }}" class="btn btn-outline-primary btn-back">&larr; Retour</a>
            </div>
        </div>
    </booking-request-show>
@endsection

@section('nav-lateral')
    @include('booking.nav')
@endsection
