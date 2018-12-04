@extends('layouts.app')

@section('title', 'Planning')

@section('styles')
    <link rel="stylesheet" media="print" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.print.css">
    <style media="print">
        @page {
            /*size: landscape;*/
        }
        #calendar,
        .fc-toolbar,
        .fc-view-container {
            max-width: 1000px;
        }
    </style>
@endsection

@section('content')
    @if($nursery->users()->count() <= 0)
        <div class="alert alert-info">Vous n'avez pas encore d'employés dans cette garderie.</div>
    @endif

    <nursery-scheduling inline-template :nursery="{{$nursery->id}}">
        <div>
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                    <div class="mt-4 text-right">
                        <a href="#" class="btn btn-info btn-sm" v-on:click="createEventModal"><i class="fas fa-plus mr-2"></i> Ajouter un élément</a>
                    </div>
                </div>
            </div>
            <div class="modal modal-event" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter un élément</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label for="resource">Ressource</label>
                                    <select class="form-control" name="resource" id="resource" v-model.number="newEvent.resource">
                                        <option disabled value="">Sélectionner...</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="date_start">Date</label>
                                    <flat-pickr
                                            v-model="newEvent.date"
                                            :config="flatPickrDateConfig"
                                            @on-change="flatpickrchange"
                                            class="form-control"
                                            placeholder="Date"
                                            name="date">
                                    </flat-pickr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="date_start">Heure de début</label>
                                    <flat-pickr
                                            v-model="newEvent.hour_start"
                                            :config="flatPickrConfig"
                                            @on-change="flatpickrchange"
                                            class="form-control"
                                            placeholder="Heure de début"
                                            name="hour_start">
                                    </flat-pickr>
                                </div>
                                <div class="form-group col">
                                    <label for="date_end">Heure de fin</label>
                                    <flat-pickr
                                            v-model="newEvent.hour_end"
                                            :config="flatPickrConfig"
                                            @on-change="flatpickrchange"
                                            class="form-control"
                                            placeholder="Heure de fin"
                                            name="hour_end">
                                    </flat-pickr>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-primary" v-on:click="saveEvent"
                            :disabled="!newEvent.resource || !newEvent.hour_start || !newEvent.hour_end">Sauvegarder</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal modal-edit-event" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editer un élément</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label for="resource">Ressource</label>
                                    <select class="form-control" name="resource" id="resource" v-model.number="editEvent.resource">
                                        <option disabled value="">Sélectionner...</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="date_start">Heure de début</label>
                                    <flat-pickr
                                            v-model="editEvent.hour_start"
                                            :config="flatPickrConfig"
                                            @on-change="flatpickrchange"
                                            class="form-control"
                                            placeholder="Heure de début"
                                            name="hour_start">
                                    </flat-pickr>
                                </div>
                                <div class="form-group col">
                                    <label for="date_end">Heure de fin</label>
                                    <flat-pickr
                                            v-model="editEvent.hour_end"
                                            :config="flatPickrConfig"
                                            @on-change="flatpickrchange"
                                            class="form-control"
                                            placeholder="Heure de fin"
                                            name="hour_end">
                                    </flat-pickr>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-danger btn-sm" v-on:click="deleteEvent">Supprimer cet élément</button>
                            <button type="button" class="btn btn-primary" v-on:click="updateEvent"
                            :disabled="!editEvent.resource || !editEvent.hour_start || !editEvent.hour_end">Sauvegarder</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nursery-scheduling>
@endsection

@section('nav-lateral')
    @include('nursery.nav')
@endsection
