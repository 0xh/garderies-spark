@extends('layouts.two-columns')

@section('title', 'Création réseau')

@section('content')
    @if (!$can_create)
        <div class="alert alert-info">
            <strong>Oops ! </strong> Durant votre période d'essai la quantité de contenu que vous pouvez créer est limitée. <a href="/settings#/subscription">Consultez la page Souscription</a> pour choisir le plan le plus adapté à vos besoins.
        </div>
    @endif
    <network-create inline-template>
        <div class="card {{($can_create) ?: 'blur'}}">
            <div class="card-header">Création réseau</div>
            <div class="card-body">
                <form action="{{route('networks.store')}}" method="post">
                    {{csrf_field()}}
                    {{method_field('POST')}}
                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input type="text" class="form-control {{ ($errors->has('name')) ? 'is-invalid' : '' }}" name="name">
                        @foreach ($errors->get('name') as $message)
                            <div class="invalid-feedback" style="display: block;">
                                Veuillez entrer un nom de réseau.
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label for="color">Couleur :</label>
                        <input type="hidden" name="color" :value="color">
                        <swatches v-model="color" colors="material-basic" shapes="circles" popover-to="right" row-length="6" inline></swatches>
                    </div>
    
                    <a href="{{ route('networks.index') }}" class="btn btn-outline-primary btn-back">&larr; Retour</a>
                    <button class="btn btn-primary float-right" type="submit">Enregistrer</button>
                </form>
            </div>
        </div>
    </network-create>
@endsection

@section('nav-lateral')
    @include('network.nav')
@endsection
