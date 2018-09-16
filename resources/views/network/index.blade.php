@extends('layouts.two-columns')

@section('title', 'Réseaux')

@section('content')
    
    <vue-table title="Réseaux" api-url="/api/networks" :user="user.id" :current-team="currentTeam" :fields="[{
              name: '__slot:networklink',
              sortField: 'networks.name',
              title: 'Nom'
            }, {
              name: 'nurseries_count',
              sortField: 'nurseries_count',
              title: 'Garderies',
              titleClass: 'text-right',
              dataClass: 'text-right'
            }, {
              name: 'users_count',
              sortField: 'users_count',
              title: 'Employés',
              titleClass: 'text-right',
              dataClass: 'text-right'
            }, {
              name: 'ads_count',
              sortField: 'ads_count',
              title: 'Annonces',
              titleClass: 'text-right',
              dataClass: 'text-right'
            }, {
              name: '__slot:ownerlink',
              sortField: 'users.name',
              title: 'Administrateur',
              width: '200px'
            }]"></vue-table>

@endsection

@section('nav-lateral')
    @include('network.nav')
@endsection
