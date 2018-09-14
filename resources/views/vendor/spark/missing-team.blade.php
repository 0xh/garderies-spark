@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h1>Prêt à démarrer</h1>

            <div class="intro-point card">
                <div class="card-body">
                    <h3 class="title"><span class="number">1</span>Créer une équipe</h3>
                    <div class="intro-point__description">
                        <p>Lorem ipsum dolor sit amet.</p>
                    </div>
                </div>
            </div>
            <div class="intro-point">
                <h3 class="title"><span class="number">2</span>Créer une équipe</h3>
                <div class="intro-point__description">
                    <p>Lorem ipsum dolor sit amet.</p>
                </div>
            </div>


            <div class="intro mt-5 text-center">
                <div class="intro-img">
                    <img src="{{asset('/img/create-team.svg')}}" class="h-90">
                </div>
                <h4>
                    {{__('teams.wheres_your_team')}}
                </h4>
                <p class="intro-copy">
                    {{__('teams.looks_like_you_are_not_part_of_team')}}
                </p>

                <div class="mt-4 mb-4 row text-left border p-4">
                    <div class="col-12">
                        <h4>A quoi sert une équipe ?</h4>
                    </div>
                    <div class="col-md-6">
                        <strong>Pour les réseaux</strong>
                        <p class="m-0">Grâce aux équipes vous pouvez choisir quels utilisateurs ont accès à vos informations, cela permet aux membres de votre équipe d'effectuer des demandes de remplacements par exemple.</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Pour les employés / remplaçants</strong>
                        <p class="m-0">Faire partie d'une équipe vous permet de rechercher des remplaçant et de trouver des remplacements à effectuer.</p>
                    </div>
                </div>

                <div class="border p-4 bg-primary text-white">
                    <h5>Seules les garderies et réseaux de garderies ont besoin de créer une équipe.</h5>

                    <div class="intro-btn">
                        <a href="/settings#/{{ Spark::teamsPrefix() }}" class="btn btn-light">
                            {{__('teams.create_team')}}
                        </a>
                    </div>
                </div>
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
