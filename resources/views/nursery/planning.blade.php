@extends('layouts.app')

@section('title', 'Nursery')

@section('content')
    <div class="print-header d-none d-print-block">
        <h3 class="text-center">Planning du {{$first_day_month}} au {{$last_day_month}}</h3>
        <hr class="mb-4">
    </div>
    <nursery-planning inline-template
                      :nursery="{{$nursery->id}}"
                      first-day="{{$first_day_month}}"
                      last-day="{{$last_day_month}}">
        <div class="wrapper">
            <div class="filter-bar p-4 mb-4 bg-white d-print-none">
                <div class="row">
                    <div class="col-md-5">
                        <a href="{{route('nurseries.show', $nursery)}}" class="btn btn-info">&larr; Retour à la garderie</a>
                    </div>
                    <div class="col-md-3">
                        <flat-pickr
                            v-model="search.date_start"
                            :config="flatPickrConfig"
                            class="form-control"
                            placeholder="Select a date"
                            name="date_start"
                            v-on:on-change="dateChange">
                        </flat-pickr>
                    </div>
                    <div class="col-md-3">
                        <flat-pickr
                            v-model="search.date_end"
                            :config="flatPickrConfig"
                            class="form-control"
                            placeholder="Select a date"
                            name="date_end"
                            v-on:on-change="dateChange">
                        </flat-pickr>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary" v-on:click.prevent="applyFilter">Filtrer</button>
                    </div>
                </div>
            </div>
            <div class="card card-default">
                <div class="card-header d-print-none">
                    Remplacements du <em><span v-html="search.date_start"></span></em> au <em><span v-html="search.date_end"></span></em>
                    <div class="actions float-right d-print-none">
                        <button class="btn btn-link" onclick="javascript:window.print();" title="Imprimer"><i class="fas fa-print"></i> Imprimer</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-borderedd table-striped m-0">
                        <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Employé</th>
                            <th>Remplaçant</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Groupe</th>
                            <th>Tél. remplaçant</th>
                        </tr>
                        </thead>
                            <tr v-for="booking in bookings">
                                <td><strong>@{{booking.start_date}}</strong></td>
                                <td>@{{booking.user.name}}</td>
                                <td>@{{booking.substitute.name}}</td>
                                <td>@{{booking.start_hour}}</td>
                                <td>@{{booking.end_hour}}</td>
                                <td>@{{booking.workgroup}}</td>
                                <td>@{{booking.substitute.phone}}</td>
                            </tr>
                            <tr v-if="!bookings.length">
                                <td colspan="7" class="text-center">Pas de remplacement</td>
                            </tr>
                    </table>
                </div>
            </div>
        </div>
    </nursery-planning>
@endsection

@section('nav-lateral')
    @include('nursery.nav')
@endsection
