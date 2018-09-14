@extends('layouts.two-columns')

@section('title', 'Edition réseau')

@section('content')
    <network-edit inline-template current-color="{{$network->color}}" :current-team="currentTeam">
        <div class="card card-default">
            <div class="card-header">Edition réseau</div>
            <div class="card-body">
                <form action="{{route('networks.update', [$network])}}" method="post">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input type="text" class="form-control {{ ($errors->has('name')) ? 'is-invalid' : '' }}" name="name" value="{{$network->name}}">
                        @foreach ($errors->get('name') as $message)
                            <div class="invalid-feedback" style="display: block;">
                                Veuillez entrer un nom de réseau.
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label for="color">Couleur :</label>
                        <input type="hidden" name="color" :value="color">
                        <swatches v-model="color" colors="material-basic" shapes="circles" value="{{$network->color}}" popover-to="right" row-length="6" inline></swatches>
                    </div>

                    <a href="{{ route('networks.show', $network) }}" class="btn btn-outline-primary btn-back">&larr; Retour</a>
                    <button class="btn btn-primary float-right" type="submit">Enregistrer</button>
                </form>
            </div>
        </div>
    </network-edit>
@endsection

@section('nav-lateral')
    @include('network.nav')
@endsection
