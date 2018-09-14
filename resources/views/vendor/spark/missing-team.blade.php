@extends('layouts.app')

@section('title', 'Ajouter une équipe')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="intro mt-5 mb-5 text-center">
                    <div class="intro-img">
                        <img src="{{asset('/img/create-team.svg')}}" class="h-90">
                    </div>
                    <h4>
                        Prêt à démarrer ?
                    </h4>
                    <p class="intro-copy">
                        {{__('teams.looks_like_you_are_not_part_of_team')}}
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="mb-4"><i class="far fa-user mr-3"></i> Je suis un employé</h3>
                <div class="intro-point card">
                    <div class="card-body">
                        <h4 class="title"><span class="number">1</span>Etre invité dans une équipe</h4>
                        <div class="intro-point__description">
                            <p class="mb-0">Vous devez attendre qu'un réseau vous invite à rejoindre un équipe afin de pouvoir utiliser les services de Garderies.ch.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="mb-4"><i class="far fa-building mr-3"></i> Garderie / un réseau de garderies</h3>

                <div class="intro-point mb-4 card">
                    <div class="card-body">
                        <h4 class="title"><span class="number">1</span>Créer une équipe</h4>
                        <div class="intro-point__description">
                            <p class="mb-0">Vous devez attendre qu'un réseau vous invite à rejoindre un équipe afin de pouvoir utiliser les services de Garderies.ch.</p>
                        </div>
                    </div>
                </div>
                <div class="intro-point mb-4 card">
                    <div class="card-body">
                        <h4 class="title"><span class="number">2</span>Inviter des membres</h4>
                        <div class="intro-point__description">
                            <p class="mb-0">Vous devez attendre qu'un réseau vous invite à rejoindre un équipe afin de pouvoir utiliser les services de Garderies.ch.</p>
                        </div>
                    </div>
                </div>
                <div class="intro-point mb-4 card">
                    <div class="card-body">
                        <h4 class="title"><span class="number">3</span>Créer votre réseau de garderies</h4>
                        <div class="intro-point__description">
                            <p class="mb-0">Vous devez attendre qu'un réseau vous invite à rejoindre un équipe afin de pouvoir utiliser les services de Garderies.ch.</p>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="/settings#/{{ Spark::teamsPrefix() }}" class="btn btn-primary btn-lg">
                        {{__('teams.create_team')}}
                    </a>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('styles')
    <style>
        .intro-point {
            margin-bottom: 30px;

        }

        .intro-point .number {
            margin-right: 20px;
            font-weight: bold;
        }

        .intro-point .title {

        }

        .intro-point .intro-point__description {
            margin-left: 37px;
        }
    </style>
@endsection
