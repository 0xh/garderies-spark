@extends('layouts.two-columns')

@section('title', 'Créer une garderie')

@section('content')
    <div class="card card-default">
        <div class="card-header">Ajouter une garderie</div>
        <div class="card-body">
            <form action="{{route('nurseries.store')}}" method="post">
                {{csrf_field()}}
                {{method_field('POST')}}
                <div class="form-group">
                    <label for="name">Nom :</label>
                    <input type="text" class="form-control {{ ($errors->has('name')) ? 'is-invalid' : '' }}" name="name">
                    @foreach ($errors->get('name') as $message)
                        <div class="invalid-feedback" style="display: block;">
                            Veuillez entrer un nom de garderie.
                        </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <label for="name">Adresse :</label>
                    <input type="text" class="form-control" name="address">
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="name">Code postal :</label>
                        <input type="text" class="form-control" name="post_code" v-mask="'####'">
                    </div>
                    <div class="form-group col-md-9">
                        <label for="name">Ville :</label>
                        <input type="text" class="form-control" name="city">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">E-mail :</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="name">Téléphone :</label>
                        <input type="text" class="form-control" name="phone" v-mask="'+41 ## ### ## ##'">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label for="network">Réseau :</label>
                        <select name="network" class="form-control selectpicker" title="Sélectionner..." data-style="btn-link border text-secondary">
                            @foreach($networks as $network)
                                <option value="{{$network->id}}">{{$network->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <a href="{{ route('nurseries.index') }}" class="btn btn-outline-primary btn-back">&larr; Retour</a>
                <button class="btn btn-primary float-right" type="submit">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection

@section('nav-lateral')
    @include('nursery.nav')
@endsection
