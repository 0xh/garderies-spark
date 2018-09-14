@extends('layouts.two-columns')

@section('title', 'Feedbacks')

@section('content')
    
    <div class="card card-default mb-4">
        <div class="card-header bg-dark text-white">Retours de remplacements</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Nom</th>
                        <th>Contenu</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($feedbacks as $feedback)
                        <tr>
                            <td>{{$feedback->created_at->format('d.m.Y')}}</td>
                            <td>{{$feedback->name}}</td>
                            <td>Lorem ipsum dolor sit amet...</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('nav-lateral')
    @include('feedback.nav')
@endsection
