@extends('layouts.app')

@section('title', 'Planning')

@section('content')
    <nursery-scheduling inline-template :nursery="{{$nursery->id}}">
        <div>
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </nursery-scheduling>
@endsection

@section('nav-lateral')
    @include('nursery.nav')
@endsection
