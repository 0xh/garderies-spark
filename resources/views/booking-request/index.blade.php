@extends('layouts.two-columns')

@section('title', 'Demandes de remplacement')

@section('content')

    <div class="card card-default mb-4">
        <div class="card-header bg-dark text-white">Demandes de remplacements en attente</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <thead>
                    <tr>
                        <th>Employé</th>
                        <th>Date</th>
                        <th>Horaire</th>
                        <th>Remplaçant</th>
                        <th>Disponibilité</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $last_timestamp = '';
                    @endphp

                    @foreach($pendingBookingRequests as $request)

                        @if ($last_timestamp != $request->request_group)
                            <tr class="bg-dark text-white small">
                                <td colspan="7" class="p-2">
                                    <strong>Groupe : {{$request->request_group}}</strong>
                                </td>
                            </tr>
                        @endif

                        @php
                            $last_timestamp = $request->request_group;
                        @endphp
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
                                <a href="{{route('users.show', $request->substitute->id ?? 0)}}" target="_blank">
                                    {{$request->substitute->name ?? 'Aucun'}}
                                </a>
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
                                @switch($request->status)
                                    @case(\App\BookingRequest::STATUS_PENDING)
                                    <span class="badge badge-info">En attente</span>
                                    @break
                                    @case(\App\BookingRequest::STATUS_APPROVED)
                                    <span class="badge badge-success">Validé</span>
                                    @break
                                    @case(\App\BookingRequest::STATUS_DENIED)
                                    <span class="badge badge-danger">Refusé</span>
                                    @break
                                @endswitch
                            </td>
                            <td>
                                <a href="{{route('booking-requests.show', $request)}}">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if(!$pendingBookingRequests->count())
                <div class="alert alert-info">Aucune demande</div>
            @endif
        </div>
    </div>
    <div class="card card-default mb-4">
        <div class="card-header bg-secondary text-white">Demandes de remplacements traitées</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <thead>
                    <tr>
                        <th>Employé</th>
                        <th>Date</th>
                        <th>Horaire</th>
                        <th>Remplaçant</th>
                        <th>Disponibilité</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $last_timestamp = '';
                    @endphp

                    @foreach($bookingRequests as $request)

                        @if ($last_timestamp != $request->request_group)
                            <tr class="bg-secondary text-white small">
                                <td colspan="7" class="p-2">
                                    <strong>Groupe : {{$request->request_group}}</strong>
                                </td>
                            </tr>
                        @endif

                        @php
                            $last_timestamp = $request->request_group;
                        @endphp
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
                                <a href="{{route('users.show', $request->substitute->id ?? 0)}}" target="_blank">
                                    {{$request->substitute->name ?? 'Aucun'}}
                                </a>
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
                                @switch($request->status)
                                    @case(\App\BookingRequest::STATUS_PENDING)
                                    <span class="badge badge-info">En attente</span>
                                    @break
                                    @case(\App\BookingRequest::STATUS_APPROVED)
                                    <span class="badge badge-success">Validé</span>
                                    @break
                                    @case(\App\BookingRequest::STATUS_DENIED)
                                    <span class="badge badge-danger">Refusé</span>
                                    @break
                                @endswitch
                            </td>
                            <td>
                                <a href="{{route('booking-requests.show', $request)}}">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if(!$bookingRequests->count())
                <div class="alert alert-info">Aucune demande en attente</div>
            @endif
        </div>
    </div>
@endsection

@section('nav-lateral')
    @include('booking.nav')
@endsection
