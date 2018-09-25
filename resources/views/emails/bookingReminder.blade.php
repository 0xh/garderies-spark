@component('mail::message')
# Rappel de remplacement

Vous recevez ce mail pour vous rappeler de votre prochain remplacement, dont vous trouverez le détail ci-dessous :

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
