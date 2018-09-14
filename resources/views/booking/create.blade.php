@extends('layouts.two-columns')

@section('title', 'Créer')

@section('content')
    <booking-create inline-template>
        <div class="card card-default">
            <div class="card-header">Créer remplacement</div>
            <div class="card-body">
                <form action="{{route('bookings.store')}}" method="post">
                    {{csrf_field()}}
                    {{method_field('POST')}}
                    <div class="row">
                        <div class="form-group col">
                            <label for="user">Employé :</label>
                            <select name="user" class="form-control selectpicker" title="Sélectionner..." data-live-search="true" data-style="btn-link border text-secondary {{ ($errors->has('user')) ? 'is-invalid' : '' }}">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" {{ old('user') && old('user') == $user->id ? 'selected' : '' }}>{{$user->name}}</option>
                                @endforeach
                            </select>
                            @foreach ($errors->get('user') as $message)
                                <div class="invalid-feedback" style="display: block;">
                                    {{ $message }}
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group col">
                            <label for="substitute">Remplaçant :</label>
                            <select name="substitute" class="form-control selectpicker" title="Sélectionner..." data-live-search="true" data-style="btn-link border text-secondary {{ ($errors->has('substitute')) ? 'is-invalid' : '' }}">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" {{ old('substitute') && old('substitute') == $user->id ? 'selected' : '' }}>{{$user->name}}</option>
                                @endforeach
                            </select>
                            @foreach ($errors->get('substitute') as $message)
                                <div class="invalid-feedback" style="display: block;">
                                    {{ $message }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="nursery">Garderie</label>
                            <select name="nursery" class="form-control selectpicker" title="Sélectionner..." data-live-search="true" data-style="btn-link border text-secondary {{ ($errors->has('nursery')) ? 'is-invalid' : '' }}">
                                @foreach($nurseries as $nursery)
                                    <option value="{{$nursery->id}}" {{ old('nursery') && old('nursery') == $nursery->id ? 'selected' : '' }}>{{$nursery->name}}</option>
                                @endforeach
                            </select>
                            @foreach ($errors->get('nursery') as $message)
                                <div class="invalid-feedback" style="display: block;">
                                    {{ $message }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="name">Début :</label>
                            <flat-pickr
                                    :config="flatPickrConfig"
                                    value="{{now()->addHours(2)->format('d.m.Y H:00')}}"
                                    class="form-control {{ ($errors->has('date_start')) ? 'is-invalid' : '' }}"
                                    placeholder="Select a date"
                                    name="date_start">
                            </flat-pickr>
                            @foreach ($errors->get('date_start') as $message)
                                <div class="invalid-feedback" style="display: block;">
                                    {{ $message }}
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group col">
                            <label for="name">Fin :</label>
                            <flat-pickr
                                    :config="flatPickrConfig"
                                    value="{{now()->addHours(6)->format('d.m.Y H:00')}}"
                                    class="form-control {{ ($errors->has('date_end')) ? 'is-invalid' : '' }}"
                                    placeholder="Select a date"
                                    name="date_end">
                            </flat-pickr>
                            @foreach ($errors->get('date_end') as $message)
                                <div class="invalid-feedback" style="display: block;">
                                    {{ $message }}
                                </div>
                            @endforeach
                        </div>
                    </div>
    
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary btn-back">&larr; Retour</a>
                    <button class="btn btn-primary float-right" type="submit">Enregistrer</button>
                </form>
            </div>
        </div>
    </booking-create>
@endsection

@section('nav-lateral')
    @include('booking.nav')
@endsection
