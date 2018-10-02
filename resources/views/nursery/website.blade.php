@extends('layouts.website')

@section('fulltitle', $nursery->name)

@section('content')

    <div class="container">
        <div class="row justify-content-center" style="height: 100px;">
            <div class="col text-center">
                <a href="{{url('/')}}"><img src="{{asset('img/logo_garderies.png')}}" alt="" width="200" height="51"></a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 mb-4 mb-md-0">
                <div class="card mb-4">
                    <div style="max-height: 300px; overflow: hidden;">
                        <img src="{{asset('img/bg-nursery.jpg')}}" alt="" class="card-img-top">
                    </div>
                    <div class="card-header bg-primary text-white">{{$nursery->name}}</div>
                    <div class="card-body">
                        <p class="card-text">Haec dum oriens diu perferret, caeli reserato tepore Constantius consulatu suo septies et Caesaris ter egressus Arelate Valentiam petit, in Gundomadum et Vadomarium fratres Alamannorum reges arma moturus, quorum crebris excursibus vastabantur confines limitibus terrae Gallorum.</p>
                    </div>
                </div>
                @if ($nursery->ads->count())
                <div class="card">
                    <div class="card-header bg-dark text-white">Annonces</div>
                    <div class="card-body">
                        @foreach($nursery->ads as $ad)
                            <div>
                                <h4>{{$ad->title}}</h4>
                                <p>{{$ad->created_at->format('d.m.Y')}}</p>
                                {!!$ad->description!!}
                                {{--<a href="{{route('ads.show', $ad)}}" class="btn btn-primary">En savoir plus</a>--}}
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-4">
                @if ($nursery->address)
                <div class="card bg-info text-white mb-4">
                    <div class="card-header">Informations de contact</div>
                    <div class="card-body">
                        <p>{{$nursery->address}}<br>{{$nursery->post_code}}, {{$nursery->city}}</p>
                        <p><a href="mailto:{{$nursery->email}}">{{$nursery->email}}</a></p>
                        <p class="m-0"><a href="tel:{{$nursery->phone}}">{{$nursery->phone}}</a></p>
                    </div>
                </div>
                @endif
                <div class="card mb-4 d-none">
                    <div class="card-header">Horaires</div>
                    <div class="card-body">
                        <table class="table m-0">
                            <tr><td><strong>Lundi</strong></td><td>08h00 - 18h00</td></tr>
                            <tr><td><strong>Mardi</strong></td><td>08h00 - 18h00</td></tr>
                            <tr><td><strong>Mercredi</strong></td><td>08h00 - 18h00</td></tr>
                            <tr><td><strong>Jeudi</strong></td><td>08h00 - 18h00</td></tr>
                            <tr><td><strong>Vendredi</strong></td><td>08h00 - 18h00</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
