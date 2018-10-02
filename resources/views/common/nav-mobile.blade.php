<button class="hamburger hamburger--spin" type="button">
    <span class="hamburger-box">
        <span class="hamburger-inner"></span>
    </span>
</button>

<div class="nav-mobile">
    @if (auth()->check())
        @if (auth()->user()->hasTeams() && auth()->user()->roleOnCurrentTeam() == 'owner')
            <ul>
                <li>
                    <a href="{{route('networks.index')}}">Réseaux</a>
                </li>
                <li>
                    <a href="{{route('nurseries.index')}}">Garderies</a>
                </li>
                <li>
                    <a href="{{route('users.index')}}">Employés</a>
                </li>
                <li>
                    <a href="{{route('booking-requests.index')}}">Remplacements</a>
                </li>
                <li>
                    <a href="{{route('availabilities.search')}}">Recherche</a>
                </li>
                <li>
                    <a href="{{route('settings')}}">Paramètres</a>
                </li>
            </ul>
        @elseif (auth()->user()->hasTeams())
            <ul>
                <li>
                    <a href="{{route('users.show', auth()->user())}}">Mon profil</a>
                </li>
                <li>
                    <a href="{{route('users.availabilities', auth()->user())}}">Mes disponibilités</a>
                </li>
                <li>
                    <a href="{{route('availabilities.search')}}">Recherche de remplaçant</a>
                </li>
            </ul>
        @endif
    @else
        <ul>
            <li>
                <a href="/login">Connexion</a>
            </li>
            <li>
                <a href="/register">Inscription</a>
            </li>
        </ul>
    @endif
</div>