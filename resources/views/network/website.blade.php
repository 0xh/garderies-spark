@extends('layouts.naked')

@section('title', 'Site web')

@section('content')

    <div class="row" style="margin-bottom: 40px;">
        <div class="col align-items-center text-center">
            <img src="{{asset('img/logo_garderies.png')}}" alt="" width="200">
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div style="max-height: 300px; overflow: hidden;">
                    <img src="{{asset('img/bg-nursery.jpg')}}" alt="" class="card-img-top">
                </div>
                <div class="card-header bg-primary text-white">Page de présentation</div>
                <div class="card-body">
                    <p class="card-text">Haec dum oriens diu perferret, caeli reserato tepore Constantius consulatu suo septies et Caesaris ter egressus Arelate Valentiam petit, in Gundomadum et Vadomarium fratres Alamannorum reges arma moturus, quorum crebris excursibus vastabantur confines limitibus terrae Gallorum.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-dark text-white">Blog</div>
                <div class="card-body">
                    <div>
                        <h4>Lorem ipsum dolor sit amet</h4>
                        <p>22.08.2018</p>
                        <p class="card-text">Haec dum oriens diu perferret, caeli reserato tepore Constantius consulatu suo septies
                            et Caesaris ter egressus Arelate Valentiam petit, in Gundomadum et Vadomarium.</p>
                    </div>
                    <hr>
                    <div>
                        <h4>Lorem ipsum dolor sit amet</h4>
                        <p>21.08.2018</p>
                        <p class="card-text">Haec dum oriens diu perferret, caeli reserato tepore Constantius consulatu suo septies
                            et Caesaris ter egressus Arelate Valentiam petit, in Gundomadum et Vadomarium.</p>
                    </div>
                    <hr>
                    <div>
                        <h4>Lorem ipsum dolor sit amet</h4>
                        <p>20.08.2018</p>
                        <p class="card-text">Haec dum oriens diu perferret, caeli reserato tepore Constantius consulatu suo septies
                            et Caesaris ter egressus Arelate Valentiam petit, in Gundomadum et Vadomarium.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4">
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
@endsection
