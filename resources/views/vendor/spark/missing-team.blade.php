@extends('layouts.app')

@section('title', 'Ajouter une équipe')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="intro mb-5 text-center">
                    <div class="intro-img">
                        <img src="{{asset('/img/create-team.svg')}}" class="h-90">
                    </div>
                    <h4>
                        Prêt à démarrer ?
                    </h4>
                    <p class="intro-copy">
                        Il semble que vous ne fassiez pas partie d'une équipe ! <br>Suivez les étapes ci-dessous selon votre situation.
                    </p>
                </div>
            </div>
            <div class="col-12 text-center mb-4">
                <h2>Je suis :</h2>
            </div>
            <div class="col-md-6">
                <h3 class="mb-4 p-2"><i class="far fa-user mr-3"></i> Un employé</h3>
                <div class="intro-point mb-4 card">
                    <div class="card-body">
                        <h4 class="title"><span class="number">1</span>Etre invité dans une équipe</h4>
                        <div class="intro-point__description">
                            <p>Vous devez attendre qu'un réseau vous invite à rejoindre un équipe afin de pouvoir utiliser les services de Garderies.ch.</p>
                            <p class="mb-0">Vous recevrez par e-mail une invitation lorsqu'un réseau ou une garderie désirera vous ajouter à son réseau de ressources humaines.</p>
                        </div>
                    </div>
                </div>
                <div class="intro-point mb-4 card">
                    <div class="card-body">
                        <h4 class="title"><span class="number">2</span>Configurer votre profil</h4>
                        <div class="intro-point__description">
                            <p>Renseignez vos informations personnelles et vos disponibilités afin de pouvoir être contacté en cas de besoin.</p>
                            <p class="mb-0">Surveillez ensuite vos notifications car les besoins en remplacement peuvent être soudains.</p>
                        </div>
                    </div>
                </div>
                <p class="text-center">Lorsque vous ferez partie d'un réseau vous ne verrez plus cet écran.</p>
            </div>
            <div class="col-md-6 border-left">
                <h3 class="mb-4 p-2"><i class="far fa-building mr-3"></i> Une garderie / un réseau de garderies</h3>

                <div class="intro-point mb-4 card">
                    <div class="card-body">
                        <h4 class="title"><span class="number">1</span>Créer une équipe</h4>
                        <div class="intro-point__description">
                            <p class="mb-0"><strong>Une équipe vous permet d'ajouter des employés / remplaçants à votre réseau de garderies</strong>, les rendant ainsi actifs au sein de votre réseau.</p>
                        </div>
                    </div>
                </div>
                <div class="intro-point mb-4 card">
                    <div class="card-body">
                        <h4 class="title"><span class="number">2</span>Inviter des membres</h4>
                        <div class="intro-point__description">
                            <p class="mb-0">Les employés / remplaçants invités pourront ainsi renseigner leurs disponibilités et gérer leurs remplacements.</p>
                        </div>
                    </div>
                </div>
                <div class="intro-point mb-4 card">
                    <div class="card-body">
                        <h4 class="title"><span class="number">3</span>Créer votre réseau de garderies</h4>
                        <div class="intro-point__description">
                            <p class="mb-0">Organisez ensuite vos structures d'accueil en réseau et assignez vos employés / remplaçants à celles-ci.</p>
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
            margin-left: 35px;
        }
    </style>
@endsection
