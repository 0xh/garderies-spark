@extends('layouts.two-columns')

@section('title', 'Edition Garderie')

@section('content')
    <nursery-edit inline-template>
        <div class="card card-default">
            <div class="card-header">Edition garderie</div>
            <div class="card-body">
                <form action="{{route('nurseries.update', [$nursery])}}" method="post">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input type="text" class="form-control {{ ($errors->has('name')) ? 'is-invalid' : '' }}" name="name" value="{{$nursery->name}}">
                        @foreach ($errors->get('name') as $message)
                            <div class="invalid-feedback" style="display: block;">
                                Veuillez entrer un nom de garderie.
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label for="name">Adresse :</label>
                        <input type="text" class="form-control" name="address" value="{{$nursery->address}}">
                    </div>
                    <div class="row">
                        <div class="form-group col-3">
                            <label for="name">Code postal :</label>
                            <input type="text" class="form-control" name="post_code" value="{{$nursery->post_code}}" v-mask="'####'">
                        </div>
                        <div class="form-group col-9">
                            <label for="name">Ville :</label>
                            <input type="text" class="form-control" name="city" value="{{$nursery->city}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="name">E-mail :</label>
                            <input type="email" class="form-control" name="email" value="{{$nursery->email}}">
                        </div>
                        <div class="form-group col">
                            <label for="name">Téléphone :</label>
                            <input type="text" class="form-control" name="phone" value="{{$nursery->phone}}" v-mask="'+41 ## ### ## ##'">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="network">Réseau :</label>
                            <select name="network" class="form-control selectpicker" title="Sélectionner..." data-style="btn-link border text-secondary">
                                @foreach($networks as $network)
                                    <option value="{{$network->id}}" {{(optional($nursery->network)->id == $network->id) ? 'selected' : ''}}>{{$network->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
    
                    <a href="{{ route('nurseries.show', $nursery) }}" class="btn btn-outline-primary btn-back">&larr; Retour</a>
                    <button class="btn btn-primary float-right" type="submit">Enregistrer</button>
                </form>
            </div>
        </div>
    </nursery-edit>
@endsection

@section('nav-lateral')
    @include('nursery.nav')
@endsection
