@extends('layouts.pdf')

@section('content')
    <img src="{{asset('img/logo_garderies.png')}}" width="150" alt="">
    <h1>Hello dompdf</h1>

    <p>Lorem ipsum dolor sit amet consectuer.</p>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th width="50%">Title</th>
            <th>Value</th>
        </tr>
        </thead>
        <tr>
            <td>Lorem</td>
            <td>Ipsum</td>
        </tr>
        <tr>
            <td>Dolor</td>
            <td>Sit</td>
        </tr>
        <tr>
            <td>Amet</td>
            <td>Consectuer</td>
        </tr>
    </table>
@endsection