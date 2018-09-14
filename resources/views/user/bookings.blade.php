@extends('layouts.two-columns')

@section('title', $user->name)

@section('content')
    {{-- Archived Bookings --}}
    <div class="card card-default">
        <div class="card-header">Anciens remplacements</div>
        <div class="card-body">
            @if (!$archivedBookings->count())
                <div class="alert alert-info">Aucun remplacement.</div>
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
                    @foreach($archivedBookings as $booking)
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

            <a href="{{ route('users.show', $user) }}" class="btn btn-outline-primary btn-back">&larr; Retour</a>
        </div>
    </div>
@endsection

@section('nav-lateral')
    @include('user.nav')
@endsection
