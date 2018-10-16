@extends('layouts.pdf')

@section('content')
    <div style="padding: 0 30px; font-size: 13px; line-height: 13px;">
        <table class="table table-borderless table-sm">
            <tr>
                <td width="33%">
                    Simon Rapin<br>
                    Ch. des Primevères 2<br>
                    1305 Penthalaz
                </td>
                <td width="33%"></td>
                <td width="33%"></td>
            </tr>
            <tr>
                <td colspan="3" height="60"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>
                    {{$user->name}}<br>
                    {{$user->billing_address}}<br>
                    {{$user->billing_zip}} {{$user->billing_city}}<br>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="120"></td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>Utilisation de la plateforme Garderies.ch</strong>
                </td>
            </tr>
            <tr><td colspan="3" height="10"></td></tr>
            <tr>
                <td colspan="3">
                    <p>Madame, Monsieur,</p>
                    <p>Vous êtes invité à rejoindre l'équipe <em>DevWeb</em> sur l'application en ligne Garderies.ch, cette application permet entre autre la gestion automatisée des remplacements de personnes.</p>
                    <p>En restant à votre disposition, nous vous adressons, Madame, Monsieur, nos salutations distinguées.</p>
                </td>
            </tr>
            <tr><td colspan="3" height="40"></td></tr>
            <tr>
                <td></td>
                <td></td>
                <td>Simon Rapin</td>
            </tr>
        </table>
    </div>
@endsection