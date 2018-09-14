@extends('layouts.two-columns')

@section('title', 'Retour de remplacement')

@section('content')
    <div class="card card-default">
        <div class="card-header">Retour de remplacement</div>
        <div class="card-body">
            <form action="{{route('feedbacks.store')}}" method="post">
                @method('POST')
                @csrf

                <div class="form-group">
                    <label for="name">Titre</label>
                    <input type="text" class="form-control" name="name">
                </div>

                <div class="form-group">
                    <label for="description">Contenu</label>
                    <textarea name="description" class="form-control" cols="30" rows="5"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user">Employé</label>
                            <select name="user" class="form-control selectpicker" title="Sélectionner..." data-live-search="true" data-style="btn-link border text-secondary">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="substitute">Remplacant</label>
                            <select name="substitute" class="form-control selectpicker" title="Sélectionner..." data-live-search="true" data-style="btn-link border text-secondary">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="booking">Remplacement</label>
                            <select name="booking" class="form-control selectpicker" title="Sélectionner..." data-style="btn-link border text-secondary">
                                @foreach($bookings as $booking)
                                    <option value="{{$booking->id}}">{{$booking->start->format('d.m.Y')}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


            </form>
        </div>
    </div>
@endsection

@section('nav-lateral')
    @include('feedback.nav')
@endsection
