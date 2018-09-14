@extends('layouts.two-columns')

@section('title', 'Vos garderies')

@section('content')
    <vue-table title="Garderies" api-url="/api/nurseries" :user="user.id" :current-team="currentTeam" :fields="[{
            name: '__slot:nurserylink',
            sortField: 'nurseries.name',
            title: 'Nom',
            width: '200'
        }, {
            name: 'ads_count',
            sortField: 'ads_count',
            title: 'Annonces',
            width: '150px'
        },  {
            name: 'users_count',
            sortField: 'users_count',
            title: 'Employés',
            width: '150px'
        }, {
            name: '__slot:networklinkrelation',
            sortField: 'networks.name',
            title: 'Réseau',
            width: '150px'
        }]">
    </vue-table>
@endsection

@section('nav-lateral')
    @include('nursery.nav')
@endsection
