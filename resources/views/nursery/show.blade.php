@extends('layouts.two-columns')

@section('title', $nursery->name)

@section('content')
    <nursery-show inline-template>
        <div>
            <div class="card card-default mb-4">
                <div class="card-header">{{$nursery->name}}
                    <div class="actions float-right d-print-none">
                        <a href="{{route('nurseries.edit', [$nursery])}}" class="btn btn-info btn-sm mr-2"><i class="fas fa-edit"></i> Editer</a>
                        <a href="#" v-on:click.prevent="deleteNursery('{{$nursery->slug}}')" class="btn btn-danger btn-sm"><i class="fas fa-edit"></i> Supprimer</a>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row align-items-center">
                        <div class="col-md-4 mb-4 mb-md-0 text-center">
                            <div class="mb-2"><i class="fas fa-building text-secondary" style="font-size: 5em;"></i></div>
                            <div><strong>{{$nursery->name}}</strong></div>
                        </div>
                        <div class="col-md-8">
                            <table class="table">
                                <tr>
                                    <td><strong>Adresse :</strong></td>
                                    <td>{{$nursery->address}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Localité :</strong></td>
                                    <td>{{$nursery->post_code . ', ' ?? ''}}{{$nursery->city}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Téléphone :</strong></td>
                                    <td>{{$nursery->phone}}</td>
                                </tr>
                                <tr>
                                    <td><strong>E-mail :</strong></td>
                                    <td>{{$nursery->email}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Réseau :</strong></td>
                                    <td>
                                        @if ($nursery->network)
                                            <a href="{{route('networks.show', $nursery->network)}}">
                                                <span class="badge text-white" style="background-color: {{$nursery->network->color ?? '#ccc'}};">{{$nursery->network->name}}</span>
                                            </a>
                                        @else
                                            <span class="badge text-white" style="background-color: {{$nursery->network->color ?? '#ccc'}};">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <vue-table title="Employés" api-url="/api/users?nursery={{$nursery->id}}&network=0" :user="spark.userId" :fields="[{
              name: '__slot:userlink',
              sortField: 'users.name',
              title: 'Nom et prénom'
            }, {
              name: 'phone',
              sortField: 'phone',
              title: 'Téléphone'
            }, {
              name: 'email',
              sortField: 'email',
              title: 'E-mail'
            }, {
              name: '__slot:nurserylinkrelation',
              sortField: 'nurseries.name',
              title: 'Garderie'
            }]"></vue-table>
    
            <vue-table title="Remplacements à venir" api-url="/api/bookings?nursery={{$nursery->id}}" :user="spark.userId" :fields="[{
              name: '__slot:userbookinglink',
              sortField: 'users.name',
              title: 'Employé'
            }, {
              name: '__slot:substitutelink',
              sortField: 'substitutes.name',
              title: 'Remplaçant'
            }, {
              name: '__slot:bookinglink',
              sortField: 'bookings.start',
              title: 'Date'
            }, {
              name: 'start',
              sortField: 'start',
              title: 'Début',
              callback: 'formatTime'
            }, {
              name: 'end',
              sortField: 'end',
              title: 'Fin',
              callback: 'formatTime'
            }, {
              name: '__slot:bookingShowlink',
              title: ''
            }]"></vue-table>

            <div class="row">
                <div class="col mb-4 mb-md-0">
                    <div class="card">
                        <div class="card-header">Absences par type</div>
                        <div class="card-body">
                            {!! $bookings_chart->container() !!}
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-header">Répartition des diplômes</div>
                        <div class="card-body">
                            {!! $diplomas_chart->container() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </nursery-show>
@endsection

@section('nav-lateral')
    @include('nursery.nav')
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" charset="utf-8"></script>
    {!! $bookings_chart->script() !!}
    {!! $diplomas_chart->script() !!}
@endsection