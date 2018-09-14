@extends('layouts.two-columns')

@section('title', 'Remplacements')

@section('content')
    
    <vue-table title="Remplacements" api-url="/api/bookings" :statuses="{
        pending: {{ \App\Booking::STATUS_PENDING }},
        approved: {{ \App\Booking::STATUS_APPROVED }},
        denied: {{ \App\Booking::STATUS_DENIED }},
        archived: {{ \App\Booking::STATUS_ARCHIVED }},
    }" :fields="[{
              name: '__slot:bookinglink',
              sortField: 'bookings.start',
              title: 'Date'
            }, {
              name: '__slot:userbookinglink',
              sortField: 'users.name',
              title: 'Employé'
            }, {
              name: '__slot:substitutelink',
              sortField: 'substitutes.name',
              title: 'Remplaçant'
            }, {
              name: '__slot:nurserylinkrelation',
              sortField: 'nurseries.name',
              title: 'Garderie'
            }, {
              name: 'start',
              sortField: 'start',
              title: 'Début',
              callback: 'formatTime'
            }, {
              name: 'end',
              sortField: 'end',
              title: 'Fin',
              callback: 'formatTime'
            }, {
              name: 'status',
              sortField: 'status',
              title: 'Status',
              callback: 'statusLabel'
            }, {
              name: '__slot:bookingShowlink',
              title: ''
            }]"></vue-table>


    <vue-table title="Remplacements archivés" api-url="/api/bookings?status=3" card-bg-class="bg-secondary" :statuses="{
        pending: {{ \App\Booking::STATUS_PENDING }},
        approved: {{ \App\Booking::STATUS_APPROVED }},
        denied: {{ \App\Booking::STATUS_DENIED }},
        archived: {{ \App\Booking::STATUS_ARCHIVED }},
    }" :fields="[{
              name: '__slot:bookinglink',
              sortField: 'bookings.start',
              title: 'Date'
            }, {
              name: '__slot:userbookinglink',
              sortField: 'users.name',
              title: 'Employé'
            }, {
              name: '__slot:substitutelink',
              sortField: 'substitutes.name',
              title: 'Remplaçant'
            }, {
              name: '__slot:nurserylinkrelation',
              sortField: 'nurseries.name',
              title: 'Garderie'
            }, {
              name: 'start',
              sortField: 'start',
              title: 'Début',
              callback: 'formatTime'
            }, {
              name: 'end',
              sortField: 'end',
              title: 'Fin',
              callback: 'formatTime'
            }, {
              name: 'status',
              sortField: 'status',
              title: 'Status',
              callback: 'statusLabel'
            }]"></vue-table>
@endsection

@section('nav-lateral')
    @include('booking.nav')
@endsection
