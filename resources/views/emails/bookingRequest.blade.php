@component('mail::message')
# Demande de remplacement

Vous avez reçu une nouvelle demande de remplacement, vous trouverez les informations relatives à celle-ci, ci-dessous :

@component('mail::table')
| Informations         |                                             |
|:-------------------- | ------------------------------------------- |
| **Date**             | {{$bookingRequest->start->format('d.m.Y')}} |
| **Heure de début**   | {{$bookingRequest->start->format('H:i')}}   |
| **Heure de fin**     | {{$bookingRequest->end->format('H:i')}}     |
| **Garderie**         | {{$bookingRequest->nursery->name}}          |
| **Employé**          | {{$bookingRequest->user->name}}             |
@endcomponent

@component('mail::button', ['url' => route('booking-requests.show', $bookingRequest)])
Voir la demande
@endcomponent

Meilleures salutations,<br>
{{ config('app.name') }}
@endcomponent
