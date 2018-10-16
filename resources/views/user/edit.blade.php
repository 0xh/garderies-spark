@extends('layouts.app')

@section('title', 'Edition employé')

@section('content')
    <user-edit inline-template>
        <div class="card card-default">
            <div class="card-header">Edition employé</div>
            <div class="card-body">
                <form action="{{route('users.update', [$user])}}" method="post">
                    {{csrf_field()}}
                    {{method_field('PUT')}}

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="name">Nom :</label>
                                <input type="text" class="form-control {{ ($errors->has('name')) ? 'is-invalid' : '' }}" name="name" value="{{$user->name}}">
                                @foreach ($errors->get('name') as $message)
                                    <div class="invalid-feedback" style="display: block;">
                                        Veuillez entrer le nom de l'employé.
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail :</label>
                                <input type="text" class="form-control {{ ($errors->has('email')) ? 'is-invalid' : '' }}" name="email" value="{{$user->email}}">
                                @foreach ($errors->get('email') as $message)
                                    <div class="invalid-feedback" style="display: block;">
                                        Veuillez entrer l'email de l'employé.
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="phone">Téléphone :</label>
                                <input type="text" class="form-control" name="phone" value="{{$user->phone}}" v-mask="'+41 ## ### ## ##'">
                            </div>
                            <div class="form-group">
                                <label for="birthdate">Date de naissance :</label>
                                <flat-pickr
                                        :config="flatPickrConfig"
                                        value="{{($user->birthdate) ? $user->birthdate->format('d.m.Y') : now()->subYears(16)->format('d.m.Y')}}"
                                        class="form-control"
                                        placeholder="Select a date"
                                        name="birthdate">
                                </flat-pickr>
                            </div>
                            @if ($user->roleOnCurrentTeam() == 'substitute')
                            <div class="form-group">
                                <label for="diploma">Diplôme :</label>
                                <select name="diploma" class="form-control selectpicker" title="Sélectionner..." data-style="btn-link border text-secondary">
                                    @foreach($diplomas as $diploma)
                                        <option value="{{$diploma->id}}" {{($diploma->id == optional($user->diploma)->id) ? 'selected' : ''}}>{{$diploma->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="billing_address">Adresse :</label>
                                <input type="text" name="billing_address" class="form-control" value="{{$user->billing_address}}">
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="billing_zip">NPA :</label>
                                        <input type="text" name="billing_zip" class="form-control" value="{{$user->billing_zip}}">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="billing_city">Adresse :</label>
                                        <input type="text" name="billing_city" class="form-control" value="{{$user->billing_city}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="billing_state">Canton :</label>
                                <select name="billing_state" class="form-control">
                                    <option value="">Sélectionner...</option>
                                    <option value="Vaud" {{($user->billing_state == 'Vaud') ? 'selected' : ''}}>Vaud</option>
                                </select>
                            </div>
                        </div>
                        @if ($user->roleOnCurrentTeam() == 'substitute')
                        <div class="col-md-5">
                            <div class="mb-4">
                                <h5>Réseaux de travail</h5>
                                <ul class="pl-4 mb-0">
                                    @foreach($user->networks()->orderBy('name')->get() as $network)
                                        <li>{{$network->name}}</li>
                                    @endforeach
                                </ul>
                                @if (!$user->networks->count())
                                    Aucun
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Groupes de travail :</label>
                                @foreach($workgroups as $workgroup)
                                    <div class="form-check">
                                        <input type="checkbox" value="{{$workgroup->id}}" class="form-check-input" id="workgroup-{{$workgroup->id}}" name="workgroups[]" {{(in_array($workgroup->id, $currentWorkgroups) ? 'checked' : '')}}>
                                        <label for="workgroup-{{$workgroup->id}}" class="form-check-label">{{$workgroup->name}}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label>Préférences de contact</label>
                                <div class="form-check">
                                    <input type="checkbox" value="sms" class="form-check-input" id="contact_preference_sms" name="contact_preferences[]" {{(in_array('sms', $contactPreferences) ? 'checked' : '')}}>
                                    <label for="contact_preference_sms" class="form-check-label">SMS</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" value="email" class="form-check-input" id="contact_preference_email" name="contact_preferences[]" {{(in_array('email', $contactPreferences) ? 'checked' : '')}}>
                                    <label for="contact_preference_email" class="form-check-label">E-mail</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" value="phone" class="form-check-input" id="contact_preference_phone" name="contact_preferences[]" {{(in_array('phone', $contactPreferences) ? 'checked' : '')}}>
                                    <label for="contact_preference_phone" class="form-check-label">Téléphone</label>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
    
                    <button class="btn btn-primary float-right" type="submit">Enregistrer</button>
                </form>
            </div>
        </div>
    </user-edit>
@endsection

@section('nav-lateral')
    @include('user.nav')
@endsection
