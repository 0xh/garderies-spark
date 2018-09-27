@extends('layouts.two-columns')

@section('title', $network->name)

@section('content')
    <network-show inline-template>
        <div class="card card-default mb-4">
            <div class="card-header">
                {{$network->name}}
                <div class="actions float-right d-print-none">
                    <a href="{{route('networks.edit', [$network])}}" class="btn btn-info btn-sm mr-2"><i class="fas fa-edit"></i> Editer</a>
                    <a href="#" v-on:click.prevent="deleteNetwork('{{$network->slug}}')" class="btn btn-danger btn-sm"><i class="fas fa-edit"></i> Supprimer</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-4 mb-md-0 text-center">
                        <div class="mb-2"><i class="fas fa-sitemap text-secondary" style="font-size: 5em;"></i></div>
                        <div><strong>{{$network->name}}</strong></div>
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tr>
                                <td><strong>Nom :</strong></td>
                                <td>{{$network->name}}</td>
                            </tr>
                            <tr>
                                <td><strong>Administrateur :</strong></td>
                                <td>{{$network->team->owner->name ?? '-'}}</td>
                            </tr>
                            <tr>
                                <td><strong>Couleur :</strong></td>
                                <td><span class="badge badge-pill" style="background-color: {{$network->color}};">&nbsp;</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </network-show>

    <vue-table title="Garderies" api-url="/api/nurseries?network={{$network->id}}" :user="user.id" :current-team="currentTeam" :fields="[{
            name: '__slot:nurserylink',
            sortField: 'nurseries.name',
            title: 'Nom'
        }, {
            name: 'users_count',
            sortField: 'users_count',
            title: 'Employés'
        }, {
            name: '__slot:networklinkrelation',
            sortField: 'networks.name',
            title: 'Réseau',
            width: '150px'
        }]">
    </vue-table>

    <vue-table title="Employés" api-url="/api/users?nursery=0&network={{$network->id}}" :user="user.id" :current-team="currentTeam" :fields="[
            {
              name: '__slot:photo',
            }, {
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
@endsection

@section('nav-lateral')
    @include('network.nav')
@endsection
