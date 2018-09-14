@extends('layouts.two-columns')

@section('title', 'Availability')

@section('content')
    <div class="card card-default">
        <div class="card-header">{{$availability->id}}
            <div class="actions float-right d-print-none">
                <a href="{{route('availabilities.edit', [$availability->id])}}" class="btn btn-info btn-sm mr-2"><i class="fas fa-edit"></i> Edit</a>

                <div class="float-right">
                    <form action="{{route('availabilities.destroy', $availability->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body">
            <p><strong>ID:</strong> {{$availability->id}}</p>
            <p><strong>User:</strong> {{$availability->user->id}} - {{$availability->user->name}}</p>
            <p><strong>Created at:</strong> {{$availability->created_at->format('d.m.Y - H\hi')}}</p>
            <p><strong>Start:</strong> {{$availability->start->format('d.m.Y H\hi')}}</p>
            <p><strong>End:</strong> {{$availability->end->format('d.m.Y H\hi')}}</p>

            <strong>Bookings</strong>
            <ul>
            @foreach ($bookings as $booking)
                <li>(bID) {{$booking->id}} : (brID) {{$booking->request_id}} : {{$booking->start}} - {{$booking->end}}</li>
            @endforeach
            </ul>

            <p><strong>Available slots</strong></p>
            @php
                echo '<pre>' . print_r($slots, true) . '</pre>';
            @endphp

            <p><strong>Free time</strong></p>
            @php
                echo '<pre>' . print_r($freetime, true) . '</pre>';
            @endphp

        </div>
    </div>
@endsection

